<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Network;
use Mail;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        $networkCount = Network::where('parent_user_id', Auth::user()->id)->orWhere('user_id',Auth::user()->id)->count();
        $networkData= Network::with('user')->where('parent_user_id',Auth::user()->id)->get(); 
        return view('backend.dashboard',compact(['networkCount','networkData']));
    }
}