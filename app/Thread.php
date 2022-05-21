<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
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
       return "/threads/{$this->channel->slug}/{$this->slug}";
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

        event(new ThreadReceivedNewReply($reply));

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

    public function hasUpdatesFor($user)
    {

        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);

    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug)
    {
       //Thread::whereTitle('Help Me')->latest('id')->value('slug');

        $max = static::whereTitle($this->title)->latest('id')->value('slug');

        if(is_numeric($max[-1])){
            return preg_replace_callback('/(\d+)$/', function($matches){
                return $matches[1] + 1;
            }, $max);
        }

        return "{$slug}-2";

    }
}
