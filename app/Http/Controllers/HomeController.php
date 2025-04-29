<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserFollowNotification;
use App\Notifications\WelcomeNotification;
use App\Notifications\WelcomeSmsNotfication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    public function mailNotification(){
        $users = User::all();

        $post = [
            'title'=>'post title',
            'slug'=>'post-slug',
        ];

        try{
            foreach($users as $user){
                $user->notify(new WelcomeNotification($post));
            }
            
            return "Done";
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function databaseNotification(){

        if(auth()->user()){
            $user = User::find(2);

            auth()->user()->notify(new UserFollowNotification($user));
            dd("done");
        }
       
    }

    public function markAsRead($id){
        if($id){
            auth()->user()->notifications->where('id',$id)->markAsRead();
        }

        return back();
    }

    public function smsNotification(){
        $user = User::first();
        $user->notify(new WelcomeSmsNotfication);

        return view('welcome');
    }
}
