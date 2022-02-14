@component('profiles.activities.activity')

    @slot('heading')
        {{ $profileUser }}
        published <a href="{{ $record->subject->thread->path() }}" {{ $activity->subject->thread->title }} </a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot

@endcomponent