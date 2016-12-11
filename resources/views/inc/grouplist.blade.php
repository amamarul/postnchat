@foreach($groups as $group)
    <li class="{{ (Auth::user()->group_id == $group->id)? 'active' : '' }}"><a href="{{ route('dashboard', ['group' => $group->id]) }}" class="userchathandler">{{ ucfirst($group->name) }} ({{ count($allusers->where('group_id', $group->id)) }})</a></li>
@endforeach
