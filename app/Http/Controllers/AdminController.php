<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\EmailSubscription;
use App\Models\User;
use App\Models\Users;

class AdminController extends Controller
{
    //

    public function index()
    {

        return view('admin.index');
    }

    public function authors($status=null){
        $authors=array();
        if($status !=null)$authors=TypeController::authors_query()->where('blocked',$status)->orderBy('name')->paginate(10);
        else $authors=TypeController::authors_query()->orderBy('name')->paginate(10);
        return view('admin.page.authors.index',compact('authors'));
    }

    public function email_subscription(){
        $subscriptions=EmailSubscription::query()->paginate(10);
        return view('admin.page.subscription.index',compact('subscriptions'));
    }
    public  function  updateSubscriptionStatus($status,$client){
        $user=EmailSubscription::query()->find($client);
        $newStatus=0;
        if($status == 0){
            $newStatus=1;
        }
        $user->subscribed=$newStatus;
        $user->save();
        return \redirect()->back()->with('success','Status Updated successfully');
    }
    public function editSubscription($id){
        $subscription=EmailSubscription::query()->find($id);
        return view('admin.page.subscription.edit_alert', compact('subscription'));
    }

    public function authorPosts($id){
        $user=User::query()->find($id);
        return view('admin.page.authors.posts', PostController::indexProcessor($user));

    }
    public function updateUserStatus($status,$user){
        $author=Users::query()->find($user);
        $newStatus=0;
        if($status == 0){
            $newStatus=1;
        }
        $author->blocked=$newStatus;
        $author->save();
        return \redirect()->back()->with('success','Status Updated successfully');
    }

    public function fetch($postType,$year,$user=null,$month=null){
        return 0;
    }
    public function edit($author){
        $user=User::query()->find($author);
        return view('admin.page.authors.edit_alert', compact('user'));
    }

    public function role(){
        $roles=TypeController::roles();
        return view('admin.page.roles.index',compact('roles'));
    }

    public function  postType(){
        $postTypes=PostTypeController::types();
        return view('admin.page.post_types.index',compact('postTypes'));
    }
    public static function  countAdmin(){
        return   Users::query()->selectRaw("COUNT(*) AS total")->where('type_id',TypeController::adminRoleId());

    }
    public static function  countAuthors(){
        return   Users::query()->selectRaw("COUNT(*) AS total")->where('type_id',TypeController::authorRoleId());

    }
    public static function countConsumers(){
        return   Users::query()->selectRaw("COUNT(*) AS total")->where('type_id',TypeController::registeredUserRoleId());
    }
    public static function countMessages(){
        return   Contact::query()->selectRaw("DATE_FORMAT(created_at, '%Y') AS year, COUNT(*) AS total")->groupBy("year")->get();

    }
    public static  function countEmailSubscription(){
        return   EmailSubscription::query()->selectRaw("DATE_FORMAT(created_at, '%Y') AS year, COUNT(*) AS total")->groupBy("year")->get();

    }
}
