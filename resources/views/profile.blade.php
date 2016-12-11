@extends('layouts.master')

@section('title')
    Profile | PostnChat
@endsection

@section('content')
<div class="col-md-12">
    @include('inc.message-block')
</div>
<div class="col-sm-8 col-sm-offset-2">
    <form class="form" role="form" enctype="multipart/form-data" method="post" action="{{ route('profile.save') }}">
        <h2 class="text-center">Profile</h2>

        <div class="col-sm-12">
            <div class="form-group">
                <img src="{{ URL::to('uploads/' . $user->profile->profile_img) }}" alt="profile pic" class="col-sm-4 col-sm-offset-4 img-responsive">
                <div class="col-sm-4 col-sm-offset-4">
                    <label for="profile_img">Image (only .jpg)</label>
                    <input type="file" name="profile_img" class="btn-default btn-file" id="profile_img">
                </div>
            </div>
        </div>
        <div class="col-sm-12"><br><br></div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" value="{{ $user->username }}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}">
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" name="first_name" id="first_name"
                       placeholder="Enter Your First Name" value="{{ $user->profile->first_name }}">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" name="last_name" id="last_name"
                       value="{{ $user->profile->last_name }}" placeholder="Enter Your Last Name">
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="text" class="form-control datepicker" name="dob" id="dob"
                       placeholder="Select Date" value="{{ date('m/d/Y', strtotime($user->profile->dob)) }}">
            </div>
            <div class="form-group">
                <label for="gender">Select your Gender:</label><br>
                <input type="radio" name="gender" value="male"
                            {{ ($user->profile->gender === 'male') ? ' checked' : '' }}> Male
                <input type="radio" name="gender" value="female"
                            {{ ($user->profile->gender === 'female') ? ' checked' : '' }}> Female
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="address">Local Address:</label>
                <input type="text" class="form-control" name="address" id="address"
                       placeholder="Enter Your Address" value="{{ $user->profile->address }}">
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" name="city" id="city"
                       placeholder="Enter Your City" value="{{ $user->profile->city }}">
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" class="form-control" name="state" id="state"
                       placeholder="Enter Your State" value="{{ $user->profile->state }}">
            </div>
            <div class="form-group">
                <label for="zipcode">ZipCode:</label>
                <input type="text" class="form-control" name="zipcode" id="zipcode"
                       placeholder="Enter Your Zip Code" value="{{ $user->profile->zipcode }}">
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" class="form-control" name="country" id="country"
                       placeholder="Enter Your Country" value="{{ $user->profile->country }}">
            </div>
            <div class="form-group">
                <label for="phone_no">Phone No:</label>
                <input type="text" class="form-control" name="phone_no" id="phone_no"
                       value="{{ $user->profile->phone_no }}" placeholder="Enter Phone No">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="about">About:</label>
            <textarea name="about" id="about" class="form-control" rows="5" placeholder="About You">
                {{ $user->profile->about }}</textarea>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group text-center">
                <input type="submit" class="btn btn-primary" value="Save Profile">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </div>
        </div>
    </form>
</div>
@endsection