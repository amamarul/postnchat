<a href="{{ route('chat', ['receiver_id' => $receiver_id]) }}" data-receiverid="{{ $receiver_id }}"></a>
@if(isset($chat))
    @foreach($chat as $c)
        @if($c->user_id == Auth::user()->id)
            <li class="send">
                <img src="{{ URL::to('uploads/' . $c->user->profile->profile_img) }}" alt="">
                <span class="send_msg">{{ $c->message }}</span>
            </li>
        @else
            <li class="receive">
                <img src="{{ URL::to('uploads/' . $c->user->profile->profile_img) }}" alt="">
                <span class="receive_msg">{{ $c->message }}</span>
            </li>
        @endif
    @endforeach
@endif

