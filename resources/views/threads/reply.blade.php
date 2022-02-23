<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner) }}">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }} ...
            </h5>
        </div>

        <div class="">

            <form method="POST" action="/replies/{{$reply->id}}/favourites">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-default" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                    {{ $reply->favourites_count }} {{ str_plural('Favourite', $reply->favourites_count) }}
                </button>
            </form>
        </div>

    </div>

    <div class="panel-body">
        {{ $reply->body }}
    </div>

    @can('update', $reply)
        <div class="panel-body">
            <form method="POST" action="/replies/{{$reply->id}}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-danger btn-xs">
                    delete
                </button>
            </form>
        </div>
    @endcan

</div>