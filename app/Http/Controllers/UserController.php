<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Profile;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @param Request $request
         * @return \Illuminate\Http\RedirectResponse
     */
    public function postSignUp(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
        $username = $request['username'];
        $email = $request['email'];
        $password = $request['password'];
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();
        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->save();
        $profile_id = $profile->id;
        $user->profile_id = $profile_id;
        $user->group_id = 1;
        $user->save();
        Auth::login($user);
        return redirect()->route('dashboard')
            ->with(['message' => 'User Created Successfully', 'success' => true]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSignIn(Request $request)
    {
        $this->validate($request, [
            'login_email' => 'required|email',
            'login_password' => 'required|min:6'
        ]);
        $credentials = [
            'email' => $request['login_email'],
            'password' => $request['login_password']
        ];
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')
                ->with(['message' => 'Successfully logged In', 'success' => true]);
        }
        return redirect()->back();
    }

    public function getLogout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('login')
            ->with(['message' => 'Logged Out Successfully', 'success' => true]);
    }

    public function getProfile()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    public function postSaveProfile(Request $request)
    {
        $this->validate($request, [
            'profile_img' => 'image|mimes:jpg,jpeg,png'
        ]);
        $user = Auth::user();
        $profile = $user->profile;
        $profile->first_name = $request['first_name'];
        $profile->last_name = $request['last_name'];
        $profile->dob = Carbon::createFromFormat('d/m/Y', $request['dob']);
        $profile->gender = $request['gender'];
        $profile->address = $request['address'];
        $profile->city = $request['city'];
        $profile->state = $request['state'];
        $profile->zipcode = $request['zipcode'];
        $profile->country = $request['country'];
        $profile->phone_no = $request['phone_no'];
        $profile->about = $request['about'];
        $file = $request->file('profile_img');

        if ($file) {
            $profile_img = md5($file->getClientOriginalName()) . '.jpg';
            $profile->profile_img = $profile_img;
            Storage::disk('uploads')->put($profile_img, File::get($file));
        }
        $profile->update();
        return redirect()->route('profile')
            ->with(['message' => 'User Profile saved Successfully', 'success' => true]);
    }

    public function getImage($filename)
    {
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function postSearchUser(Request $request)
    {
        $searchterm = $request['searchterm'];
        if ($searchterm == null) {
            $searchterm = ' ';
        }
        $users = new User();
        $users = $users->where('group_id', Auth::User()->group_id);
        $selectedusers = $users->where('username','like', '%'.$searchterm.'%')->get();
        $users = $users->where('username','not like', '%'.$searchterm.'%')->get();
        $usersession = new \App\Session();
        $loggedusers = $usersession->where('user_id', '!=' , null)->get();

        return view('inc.userlist', [
            'selectedusers' => $selectedusers,
            'users' => $users,
            'loggedusers' => $loggedusers
        ]);
    }

    public function getUserList()
    {
        $users = User::all();
        $users = $users->where('group_id', Auth::User()->group_id);
        $usersession = new \App\Session();
        $loggedusers = $usersession->where('user_id', '!=' , null)->get();
        return view('inc.userlist', [
            'users' => $users,
            'loggedusers' => $loggedusers
        ]);
    }
}
