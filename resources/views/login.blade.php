@extends('layouts.master')

@section('title')
login | PostnChat
@endsection

@section('content')
<div class="col-md-12">
    @include('inc.message-block')
</div>
<div class="col-md-4 col-md-offset-4">
    <form action="{{ route('signup') }}" method="post">
        <header><h2>Sign Up For Free</h2></header>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Your Username" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="Your Email" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Your password" class="form-control">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Re-enter Your Password" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" value="Sign Up" class="btn btn-success">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
        </div>
    </form>
</div>
@endsection