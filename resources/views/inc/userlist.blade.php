@if(isset($selectedusers))
    @foreach($selectedusers as $user)
        @if($user->id != Auth::user()->id)
            
            <li class="selected_user">
                <a href="{{ route('chat', ['receiver_id' => $user->id]) }}" data-receiverid="{{ $user->id }}" class="userchathandler">
                    {{ ucfirst($user->username) }}
                    <span class="pull-right
                    {{ ($loggedusers->where('user_id', $user->id)->first())? " online" : " offline" }}
                    "></span>
                </a>
                @if(count($user->chat->where('receiver_id', Auth::user()->id)->where('view', 0)) > 0)
                    <span class="notification">
                        {{ count($user->chat->where('receiver_id', Auth::user()->id)->where('view', 0)) }}
                    </span>
                @endif
            </li>
        @endif
    @endforeach
@endif

@foreach($users as $user)
    @if($user->id != Auth::user()->id)
        @if($loggedusers->where('user_id', $user->id)->first())
            <li><a href="{{ route('chat', ['receiver_id' => $user->id]) }}" data-receiverid="{{ $user->id }}" class="userchathandler">
                    {{ ucfirst($user->username) }}
                    <span class="pull-right online"></span>
                </a>
                @if(count($user->chat->where('receiver_id', Auth::user()->id)->where('view', 0)) > 0)
                    <span class="notification">
                        {{ count($user->chat->where('receiver_id', Auth::user()->id)->where('view', 0)) }}
                    </span>
                @endif
            </li>
        @endif
    @endif
@endforeach

@foreach($users as $user)
    @if($user->id != Auth::user()->id)
        @if(!($loggedusers->where('user_id', $user->id)->first()))
            <li><a href="{{ route('chat', ['receiver_id' => $user->id]) }}" data-receiverid="{{ $user->id }}" class="userchathandler">
                    {{ ucfirst($user->username) }}
                    <span class="pull-right offline"></span>
                </a>
                @if(count($user->chat->where('receiver_id', Auth::user()->id)->where('view', 0)) > 0)
                    <span class="notification">
                        {{ count($user->chat->where('receiver_id', Auth::user()->id)->where('view', 0)) }}
                    </span>
                @endif
            </li>
        @endif
    @endif
@endforeach
