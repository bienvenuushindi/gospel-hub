<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\UserComment;
use App\Models\VisitorPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PostCommentController
 * @package App\Http\Controllers
 */
class PostCommentController extends Controller
{
    /**
     * return an  ids of all the post commented by this
     * client
     * @param $client_id
     */
    public static function clientComments($client_id)
    {
        $clientComment = UserComment::query()->where('user_id', '=', $client_id);
    }

    /**
     * @param $post_id
     */
    public static function visitorComments($post_id)
    {
        $postComment = PostComment::query()->where('post_id', '=', $post_id)
            ->where('user_type', '=', 'visitor');
    }

    /**
     * @param $post_id
     */
    public static function postComments($post_id)
    {
        $postComment = PostComment::query()->where('post_id', '=', $post_id)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id, $visitor = '')
    {
        $fetch = false;
        if ($visitor != '') {
            $fetch = true;
        }

        $post = Post::query()->find($post_id);

        //
        return view('author.comment.index', ['post' => $post, 'visitor' => $fetch]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $userType = 'visitor';
        $parent_id = null;
        $user = 0;
        $notify = 0;
        if (Auth::check()) {
            $userType = 'user';
            $user = Auth::id();
            $this->validate($request,
                [
                    'comment_body' => 'required|min:2|string',
                ]
            );
            if ($request->has('comment_id')) {
                $parent_id = $request->get('comment_id');
            }
        } else {
            $this->validate($request,
                [
                    'comment_body' => 'required|min:2|string',
                    'visitor_name' => 'required', 'string', 'max:255',
                    'visitor_email' => 'required', 'string', 'email', 'max:255'
                ]);
            if ($request->has('notify')) {
                $notify = $request->get('notify');
            }
        }
        $comment = PostComment::query()->create([
            'comment' => $request->get('comment_body'),
            'post_id' => $request->get('post_id'),
            'user_type' => $userType,
            'parent_id' => $parent_id
        ]);
        if (!Auth::check()) {
            VisitorPost::query()->create([
                'email' => $request->get('visitor_email'),
                'name' => $request->get('visitor_name'),
                'notify_via_email' => $notify,
                'comment_id' => $comment['id']
            ]);
            return back()->with('success','Thank you for your comment');
        } else {
            UserComment::query()->create([
                'user_id' => $user,
                'comment_id' => $comment['id']
            ]);
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
