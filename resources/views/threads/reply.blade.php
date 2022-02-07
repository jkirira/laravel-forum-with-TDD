<div>
    <a href="">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...<br>
    {{ $reply->body }}
</div>
<hr>
