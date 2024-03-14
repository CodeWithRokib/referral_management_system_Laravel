<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Network;
use Mail;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(){
        $networkCount = Network::where('parent_user_id', Auth::user()->id)->orWhere('user_id',Auth::user()->id)->count();
        $networkData= Network::with('user')->where('parent_user_id',Auth::user()->id)->get(); 
        return view('backend.dashboard',compact(['networkCount','networkData']));
    }
    public function referralTrack(){
        
        $dataLabels = [];
        $dateData = [];

        for($i = 30; $i>= 0; $i--){
            $dataLabels[] = Carbon::now()->subDays($i)->format('d-m-Y');
            $dateData[]= Network::whereDate('created_at',Carbon::now()->subDays($i)->format('Y-m-d'))
            ->where('parent_user_id',Auth::user()->id)->count();
        }

        $dataLabels = json_encode($dataLabels);
        $dateData = json_encode($dateData);
  
        
        return view('backend.referralTrack',compact(['dataLabels','dateData']));
    }
}