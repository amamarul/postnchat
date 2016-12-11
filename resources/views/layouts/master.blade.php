<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::to('src/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('src/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('src/css/main.css') }}">
    </head>
<body>
@include('inc.header')
<div class="container-fluid  maincontainer">
    <div class="row">
        @yield('content')
    </div>
</div>
@include('inc.footer')
</body>
</html>
