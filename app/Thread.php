<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use App\RecordsActivity;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function($builder) {
            $builder->withCount('replies');
        });

//        static::addGlobalScope('creator', function($builder) {
//            $builder->withCount('creator');
//        });

        static::deleting(function($thread) {
            $thread->replies->each->delete();
        });

    }


    public function path()
    {
//        return '/threads/'.$this->id;
//        return '/threads/'.$this->channel->slug.'/'.$this->id;
       return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
       return $this->hasMany(Reply::class);
    }

    public function creator()
    {
       return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

//        foreach ($this->subscriptions as $subscription){
//            if($subscription->user_id != $reply->user_id){
//                $subscription->user->notify(new ThreadWasUpdated($this, $reply));
//            }
//        }

        $this->subscriptions
            ->filter(function($sub) use ($reply){
                return $sub->user_id != $reply->user_id;
            })
            ->each(function ($sub) use ($reply){
                $sub->user->notify(new ThreadWasUpdated($this, $reply));
            });

        return $reply;
    }

    // accepts a set of filters
    public function scopeFilter($query, $filters)
    {
        //apply those filters to the current query
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
           'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }


    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where(['user_id' => $userId ?: auth()->id()])
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
