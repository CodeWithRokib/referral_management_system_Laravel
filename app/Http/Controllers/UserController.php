<?php

namespace App\Http\Controllers;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Network;
use Mail;
use Auth;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    public function loadRegister(){
        return view('frontend.registrartion');
    }

    public function register(Request $request){
        $request->validate([
            'name'=> 'required|string|min:2',
            'email'=>'required|string|email|max:100|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);
        $referral_code = Str::random(10);
        $token = Str::random(50);
        if(isset($request->referral_code)){
            $userData = User::where('referral_code',$request->referral_code)->get();

            if(count($userData)>0){
                $user_id = User::insertGetId([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'password'=> Hash::make($request->password),
                    'referral_code'=> $referral_code,
                   
                ]);
                Network::insert([
                    'referral_code'=>$request->referral_code,
                    'user_id'=>$user_id,
                    'parent_user_id'=>$userData[0]['id'],
                ]);
            }else{
                return back()->with('error','Please enter valid referral code');
            }
        }else{
            User::insert([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=> Hash::make($request->password),
                'referral_code'=> $referral_code,
                'remember_token'=>$token
            ]);
        }

        $domain= URL::to('/');
        $url = $domain.'/referral-register?ref='.$referral_code;

        $data['url'] = $url;
        $data['name']=$request->name;
        $data['email']=$request->email;
        $data['password']=$request->password;
        $data['title']="Registered";

        Mail::send('frontend.registerMail',['data'=>$data],function($message) use ($data){
           $message->to($data['email'])->subject($data['title']); 
        });

        // verification mail
        $url = $domain.'/email-verification/'.$token;

        $data['url'] = $url;
        $data['name']=$request->name;
        $data['email']=$request->email;
        $data['password']=$request->password;
        $data['title']="Referral Verification Mail";
        
        Mail::send('frontend.verifyMail',['data'=>$data],function($message) use ($data){
            $message->to($data['email'])->subject($data['title']); 
         });
        
        
        return back()->with('success','Your registration successfully completed & verify your Mail');
    }

    public function loadReferralRegister(Request $request){
        if(isset($request->ref)){
            $referral = $request->ref;
            $userData = User::where('referral_code',$referral)->get();
            if(count($userData)>0){
                return view('frontend.referralRegister',compact('referral'));
            }else{
                return view('frontend.404');
            }
        }else{
            return redirect('/');
        }
    }

    public function emailVerification($token){
        $userData = User::where('remember_token',$token)->get();
        
        if(count($userData)>0){
            if($userData[0]['is_verified']==1){
                return view('frontend.verified',['message'=>'Your mail is already verified']);
            }
            User::where('id',$userData[0]['id'])->update([
               'is_verified'=>1,
               'email_verified_at'=>date('Y-m-d H:i:s'), 
            ]);
            return view('frontend.verified',['message'=>'Your '.$userData[0]['email']. ' mail verified Successfully']);
        }else{
            return view('frontend.verified',['message'=>'404 page not found']);
        }
    }

    public function loadLogin(){
        return view('frontend.login');
    }

    public function userLogin(Request $request){
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required',
        ]);
        $userData = User::where('email',$request->email)->first();
        if(!empty($userData)){
            if($userData->is_verified == 0){
                return back()->with('error','Please verified your mail!');
            }
        }
        $userCredential = $request->only('email','password');
        if(Auth::attempt($userCredential)){
            return redirect('/dashboard');
        }else{
            return back()->with('error','Email and Password is incorrect');
        }
    }
    
    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect('/login');   
    }
   
}