@extends('layouts.app')

@section('content')
    <div>
        <h4>{{  $thread->title }}</h4>
        {{ $thread->body }}
    </div>
@endsection
