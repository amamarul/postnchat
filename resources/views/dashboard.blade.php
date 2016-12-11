@extends('layouts.master')

@section('title')
dashboard | PostnChat
@endsection

@section('content')
<div class="col-md-10 col-md-offset-1">
    @include('inc.message-block')
</div>
<div class="col-md-1 groups">
    <div class="row">
        <div class="group-list">
            <ul>
                <li class="head clearfix">
                    <span class="pull-left"><strong>Groups</strong></span>
                </li>
                <div class="overflow-y grouplist">
                    @include('inc.grouplist')
                </div>
            </ul>

        </div>

    </div>
</div>
<div class="container-fluid">

</div>
<div class="col-md-10 col-md-offset-1 main_section">
    <div class="col-md-6">
        <form method="post" class="create-post clearfix" action="{{ route('post.create') }}" role="form" enctype="multipart/form-data">
            <div class="form-group">
                <textarea class="form-control" name="post-body" id="post-body" rows="3" placeholder="Share your Words in {{ ucfirst(Auth::User()->group->name) }} Group.."></textarea>
            </div>
            <div class="form-group">
                <input type="file" class="pull-left" name="post-image">
                <input type="submit" id="create-post" value="New Post" class="btn btn-default pull-right">
            </div>
        </form>
    </div>
    <div class="col-md-6 ad_img">
        <img  src="{{ URL::to('assets/img/ad.jpg') }}" alt="">
    </div>
    <div class="col-md-12">
        <h3>{{ ucfirst(Auth::User()->group->name) }} Feed</h3>
    </div>
    <div class="col-md-12">
        <div class="row grid ">
            @if(count($posts) < 1)
                <div class="col-md-12">Sorry there are no post to display in this group. Be the first to create post in this group.</div>
            @endif
            @foreach($posts as $post)
                <div class="col-md-6 grid-item">
                    <article class="newsfeed clearfix" data-postid = "{{ $post->id }}">
                        <div class="info">
                            <img class="img_icon_50" src="{{ URL::to('uploads/' .$post->profile->profile_img) }}" alt="">
                            <span><strong>{{ $post->user->username }}</strong><br>posted this on {{ $post->created_at }}</span>
                            <ul class="nav pull-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></i> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" class="hide_post">Hide Post</a></li>
                                        @if( Auth::user()->username == $post->user->username)
                                            <li><a href="{{ route('post.delete', ['post_id' => $post->id]) }}" class="delete_post">Delete Post</a></li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            {{ $post->body }}
                        </div>
                        @if($post->image_name != null)
                            <div class="postimg">
                                <img src="{{ URL::to('uploads/' . $post->image_name) }}"
                                     class="img-responsive" alt="">
                            </div>
                        @endif
                        <div class="interaction">
                        <span class="pull-left">
                            <a href="#" class="#">
                                <span class="like_count">
                                    {{ $likes->where('post_id', $post->id)->count() }}
                                </span>
                                <i class="glyphicon glyphicon-thumbs-up"></i>
                            </a>
                            <a href="#" class="hit_like" >
                                {{ Auth::user()->likes()->where('post_id', $post->id)->first() ?
                                (Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ?
                                'Unlike' : 'Like'): 'Like' }}
                            </a>
                        </span>
                        <span class="pull-right">
                            <a href="#">
                                <span class="comment_count">{{ $post->comments->count() }}</span>
                                <i class="glyphicon glyphicon-comment"></i>
                            </a>
                        </span>
                        </div>
                        <div class="comments">
                            <div class="comments_wrapper">
                                @include('inc.comment')
                            </div>
                            <div class="commentform">
                                <img class="img-responsive img_icon_40" src="{{ URL::to('uploads/' . Auth::user()->profile->profile_img) }}" alt="">
                                <form>
                                    <textarea name="comment-body" id="" rows="2" class="form-control comment_body" placeholder="comment.."></textarea>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>

</div>
<div class="col-md-1 userslist">
    <div class="row">
        <div class="friends-list">
            <ul>
                <li class="head clearfix">
                    <span class="pull-left"><strong>Users</strong></span>
                </li>
                <li class="end">
                    <form method="post" action="{{ route('user.search') }}">
                        <input class="form-control user-search" type="text">
                    </form>
                </li>
                <div class="overflow-y userlist">
                    @include('inc.userlist')
                </div>
            </ul>

        </div>

    </div>
</div>
<div class="chatbox">
    <div class="head clearfix">
        <h3></h3>
        <span class="control">
            <i class="glyphicon glyphicon-remove"></i>
        </span>
    </div>
    <div class="messages clearfix">
        <ul></ul>
        <form action="#" class="chatform clearfix">
            <div class="pull-left chatinputwrapper">
                <textarea name="chatmsg" rows="1" class="chatmsg form-control"></textarea>
            </div>
            {{--<button type="submit" class="pull-right btn btn-success savechatmsg"><i class="glyphicon glyphicon-send "></i></button>--}}
        </form>
    </div>
</div>
<script>
    var token = "{{ Session::token()}}";
    var urlCreatePost = '{{ route('post.create') }}';
    var urlLikePost = '{{ route('post.like') }}';
    var urlCreateComment = '{{ route('post.create.comment') }}';
    var urlSaveChatMsg = '{{ route('chat.save') }}';
    var urlGetUsersList = '{{ route('user.list') }}';
</script>
@endsection