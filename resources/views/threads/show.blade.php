@extends('layouts.app')

@section('content')
    <div>
        <a href="#" >{{ $thread->creator->name }}</a> posted:
        <h4>{{  $thread->title }}</h4>
        {{ $thread->body }}
    </div>

    <div>
        @foreach($thread->replies as $reply)
            @include('threads.reply')
        @endforeach
    </div>

    @if(auth()->check())
    <div>
        <form method="POST" action ="{{ $thread->path() . '/replies' }}">
            @csrf
            <div class="form-group">
                <textarea name="body" id="body" class="form-control" cols="30" rows="10" placeholder="Have something to say?"></textarea>
            </div>
            <button type="submit" class="btn btn-submit">Post</button>
        </form>
    </div>
    @else
        <p class="text-center ">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion .</p>
    @endif
@endsection
