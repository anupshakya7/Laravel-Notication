<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

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
          
            dd("done");
        }catch(\Exception $e){
            dd($e->getMessage());
        }

        return view('welcome');
    }
}
