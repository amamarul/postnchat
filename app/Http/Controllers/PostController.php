<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Group;
use App\Like;
use App\Post;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Comment;

class PostController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getIndex()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDashboard(Request $request)
    {
        $group_id = Auth::User()->group_id;
        if (isset($request['group'])) {
            $group_id = $request['group'];
        }

//        $activity = new Activity();
//        $activity->user_id = Auth::user()->id;
//        $activity->action = 'group.change';
//        $activity->save();

        $currentuser = Auth::User();
        $currentuser->group_id = $group_id;
        $currentuser->save();

        $posts = Post::orderBy('created_at', 'desc')->where('group_id', $group_id)->get();
        $likes = Like::all();
        $comments = Comment::all();
        $allusers = User::all();
        $users = User::where('group_id', $group_id)->get();
        $groups = Group::all();
        $usersession = new \App\Session();
        $loggedusers = $usersession->where('user_id', '!=' , null)->get();

        $notifications = Activity::orderBy('created_at', 'desc')->where('onuser_id', Auth::user()->id)->get();
        return view('dashboard', [
            'posts' => $posts,
            'likes' => $likes,
            'comments' => $comments,
            'allusers' => $allusers,
            'users' => $users,
            'groups' => $groups,
            'loggedusers' => $loggedusers,
            'notifications' => $notifications
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreatePost(Request $request)
    {
        $this->validate($request, [
            'post-body' => 'required',
            'post-image' => 'image|mimes:jpg,jpeg,png'
        ]);

        $post = new Post();
        $post->body = $request['post-body'];
        $post->user_id = Auth::user()->id;
        $post->profile_id = Auth::user()->profile->id;
        $post->group_id = Auth::user()->group_id;
        $file = $request->file('post-image');

        if ($file) {
            $post_img_name = md5($file->getClientOriginalName()) . '.jpg';
            $post->image_name = $post_img_name;
            Storage::disk('uploads')->put($post_img_name, File::get($file));
        }

        if ($post->save()) {
//            $activity = new Activity();
//            $activity->user_id = Auth::user()->id;
//            $activity->action = 'create';
//            $activity->onpost = str_limit($post->body, 50, '..');
//            $activity->ongroup = Auth::user()->group->name;
//            $activity->onuser_id = Auth::user()->id;
//            $activity->save();

            return redirect()->back()
                ->with(['message' => 'Post Created Successfully', 'success' => true]);
        }
        return redirect()->back()
            ->with(['message' => 'Unable to create Post', 'success' => false]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function postLikePost(Request $request)
    {
        $post_id = $request['postId'];
        $post = Post::find($post_id);
        if (!$post) {
            return 'Error: No post';
        }
        $user = Auth::user();
        $like = $user->likes()->where('post_id', $post_id)->first();

        if ($like) {
            $like->delete();
            $like = new Like();
            $like_count = $like->where('post_id', $post_id)->count();
            return response()->json(['like' => false, 'like_count' => $like_count], 200);
        } else {
            $like = new Like();
        }

        $like->like = true;
        $like->user_id = $user->id;
        $like->post_id = $post_id;
        $like->save();

        $activity = new Activity();
        $activity->user_id = $user->id;
        $activity->action = 'like';
        $activity->onpost = str_limit($post->body, 25);
        $activity->ongroup = Auth::user()->group->name;
        $activity->onuser_id = $like->post->user->id;
        $activity->save();

        $like_count = $like->where('post_id', $post_id)->count();
        return response()->json(['like' => true, 'like_count' => $like_count], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postCreateComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);
        $post_id = $request['postId'];

        $comment = new Comment();
        $comment->comment = $request['comment'];
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request['postId'];
        $comment->save();

        $activity = new Activity();
        $activity->user_id = Auth::user()->id;
        $activity->action = 'comment';
        $activity->onpost = str_limit($comment->post->body, 25);
        $activity->ongroup = Auth::user()->group->name;
        $activity->onuser_id = $comment->post->user->id;
        $activity->save();

        $post = Post::find($post_id);
        return view('inc.comment', ['post' => $post]);
    }

    /**
     * @param $post_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getDeletePost($post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if (Auth::user()->id !== $post->user->id) {
            return redirect()->back();
        }
        $post->delete();

//        $activity = new Activity();
//        $activity->user_id = Auth::user()->id;
//        $activity->action = 'post.delete';
//        $activity->onpost_id = $post_id;
//        $activity->onuser_id = $post->user->id;
//        $activity->save();

        return response('Post deleted successfully', 200);
    }

    /**
     * @param $comment_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getDeleteComment($comment_id)
    {
        $comment = Comment::where('id', $comment_id)->first();
        if (Auth::user()->id !== $comment->user->id) {
            return redirect()->back();
        }

        if ($comment->delete()) {

            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->action = 'comment.delete';
            $activity->onpost_id = $comment->post->id;
            $activity->onuser_id = $comment->post->user->id;
            $activity->save();

            $post_id = $comment->post_id;
            $comment = Comment::where('post_id', $post_id)->get();
            $comment_count = count($comment);
            return response()->json(['comment_count' => $comment_count], 200);
        }

        return response()->json(['msg' => 'Unable to Delete']);
    }

    public function getNotification()
    {
        $activity = new Activity();
        $notifications = $activity->where('onuser_id', Auth::user()->id)->get();

        return view('inc.notification', ['notifications' => $notifications]);
    }
}
