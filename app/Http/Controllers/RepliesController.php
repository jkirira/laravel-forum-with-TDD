<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread, Spam $spam)
    {
        try {

            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved at this time', 422);
        }


//        if(request()->expectsJson()){
//            return $reply->load('owner');
//        }

        return $reply->load('owner');


    }

    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        try {

            $this->validateReply();

            $reply->update(['body' => request('body')]);

        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved at this time', 422);
        }

//        if(request()->expectsJson()){
//            return response(['status' => 'Reply updated']);
//        }
//
//        return back();
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()){
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    public function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}
