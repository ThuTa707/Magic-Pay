<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(){

        $user = User::find(Auth::id()); // auth()->user() so error pya notifications mhr
        $notifications = $user->notifications()->paginate(5);
        return view('frontend.notifications', compact('notifications'));
    }


    public function show($id){
        $user = User::find(Auth::id());
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();
        return view('frontend.notifications-detail', compact('notification'));

    }
}
