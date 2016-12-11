@if(count($errors) > 0)
<ul>
    @foreach($errors->all() as $error)
        <li class="alert alert-danger">{{ $error }}</li>
    @endforeach
</ul>
@endif

@if(Session::has('message'))
<ul>
    @if(Session::get('success'))
        {{--<li class="alert alert-success">{{ Session::get('message') }}</li>--}}
    @else
        <li class="alert alert-danger">{{ Session::get('message') }}</li>
    @endif
</ul>
@endif