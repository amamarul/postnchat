<span class="hcp_comment_count">{{count($post->comments)}}</span>
@foreach($post->comments as $comment)
    <div class="comment clearfix">
        <img class="img-responsive img_icon_40" src="{{ URL::to('uploads/' . $comment->user->profile->profile_img) }}" alt="">
        <span>
            <p><strong>{{ $comment->user->username}}</strong> {{ $comment->comment }}</p>
            @if( Auth::user()->username == $comment->user->username)
            <a href="{{ route('comment.delete', ['comment_id' => $comment->id]) }}"
               class="pull-right delete_comment"><i class="glyphicon glyphicon-trash"></i></a>
            @endif
        </span>
    </div>
@endforeach