<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RecordsActivity;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

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
            $thread->replies()->delete();
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
        $this->replies()->create($reply);
    }

    // accepts a set of filters
    public function scopeFilter($query, $filters)
    {
        //apply those filters to the current thread we have running
        return $filters->apply($query);
    }
}
