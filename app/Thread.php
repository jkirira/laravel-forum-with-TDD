<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function($builder) {
            $builder->withCount('replies');
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
       return $this->hasMany(Reply::class)
           ->withCount('favourites')
           ->with('owner');
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
