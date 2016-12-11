<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Post;
use App\Tracker;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function getTrackUpdate()
    {
        $tracker = new Tracker();
        $tracker_check = $tracker->where('user_id', Auth::user()->id)->count();
        if ($tracker_check < 1) {
            $tracker->user_id = Auth::user()->id;
            $tracker->track = 'like';
            $tracker->save();
            $tracker = new Tracker();
            $tracker->user_id = Auth::user()->id;
            $tracker->track = 'comment';
            $tracker->save();
            return false;
        }

        $current_time = time();
        $last = Carbon::createFromTimestamp($current_time);

        $chat = new Chat();
        $acn_count = $chat->where('created_at', '>=' , Carbon::now()->subDay(1))
            ->where('user_id', Auth::user()->id)
            ->orWhere('created_at', '>=' , Carbon::now()->subSecond(60))
            ->where('receiver_id', Auth::user()->id)->count();
        $acn = $chat->where('created_at', '>=' , Carbon::now()->subDay(1))
            ->where('user_id', Auth::user()->id)
            ->orWhere('created_at', '>=' , Carbon::now()->subSecond(60))
            ->where('receiver_id', Auth::user()->id)->get();
        if ($acn_count < 1) {
            return false;
        }
        return $acn;
    }
}
