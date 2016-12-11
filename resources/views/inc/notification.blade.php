<ul class="post_notif_box">
    @foreach($notifications as $n)
        @if($n->user_id != Auth::user()->id)
            <li class="clearfix"><a href="#">
                    <img class="img_icon_sm" src="{{ URL::to('uploads/' . $n->user->profile->profile_img) }}" alt="">
                    {{ $n->user->username }}
                </a>
            <span class="notif_body"> {{ ($n->action == 'like') ? 'liked' : '' }}
                {{ ($n->action == 'comment') ? 'commented on' : '' }}
                your post: "{{ $n->onpost }}"</span>
                @if(floor((time() - strtotime($n->updated_at)) / 60) >= 1440)
                    <span class="pull-right">{{ floor((time() - strtotime($n->updated_at)) / 86400) }} day ago on {{ $n->ongroup }}</span>
                @elseif(floor((time() - strtotime($n->updated_at)) / 60) >= 60 && floor((time() - strtotime($n->updated_at)) / 60) < 1440)
                    <span class="pull-right">{{ floor((time() - strtotime($n->updated_at)) / 3600) }} hr ago on {{ $n->ongroup }}</span>
                @else(floor((time() - strtotime($n->updated_at)) / 60) < 60)
                    <span class="pull-right">{{ floor((time() - strtotime($n->updated_at)) / 60) }} min ago on {{ $n->ongroup }}</span>
                @endif
            </li>
        @endif
    @endforeach
</ul>
