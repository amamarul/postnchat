<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            @if(Auth::check())
                <a class="navbar-brand" href="{{ route('dashboard') }}">PostnChat</a>
            @else
                <a class="navbar-brand" href="{{ route('login') }}">PostnChat</a>
            @endif
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if(Auth::check())
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">
                            <img class="img_icon_sm" src="{{ URL::to('uploads/' . Auth::user()->profile->profile_img) }}" alt="">
                            {{ Auth::user()->username }}</a></li>
                    <li class="post_notification">
                        <a href="#"><span class="notif_count"></span><i class="glyphicon glyphicon-bell"></i></a>
                        @include('inc.notification')
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-cog"></i> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile') }}">Profile</a></li>
                            <li><a href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            @else
                <form class="navbar-form navbar-right" action="{{ route('signin') }}" method="post">
                    <div class="form-group">
                        <input type="text" name="login_email" class="form-control" placeholder="Your Email">
                        <input type="password" name="login_password" class="form-control" placeholder="Your Password">
                    </div>
                    <button type="submit" class="btn btn-default">Log In</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                </form>
            @endif
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>