@component('profiles.activities.activity')

    @slot('heading')
        {{ $profileUser }}
        published <a href="{{ $record->subject->path() }}" {{ $activity->subject->title }} </a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot

@endcomponent