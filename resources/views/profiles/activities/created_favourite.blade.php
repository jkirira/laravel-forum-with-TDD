@component('profiles.activities.activity')

    @slot('heading')
        <a href="{{ $activity->subject->favourited->path() }}"</a>
            {{ $profileUser->name }} favourited a reply
<!--        <a href="{{ $activity->subject->thread->path() }}" >{{ $activity->subject->thread->title }}</a>-->
    @endslot

    @slot('body')
        {{ $activity->subject->favourited->body }}
    @endslot

@endcomponent
