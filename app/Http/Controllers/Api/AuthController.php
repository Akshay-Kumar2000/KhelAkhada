<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use DB;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validData      = $request->validate([
            'mobile'    => 'required|numeric',
            'fcm_token' => 'required',
        ]);

        $userData   =   User::where('mobile',$request->mobile)->first();

        if(isset($userData)){
            DB::table('oauth_access_tokens')->where('user_id',$userData->id)->where('revoked',0)->update(['revoked' => 1]);
            Auth::login($userData);
            $accessToken    =   $userData->createToken('authToken')->accessToken;
            $message        =   "User has been login successfully!";
            $code           =   200;
            $roles          =   $userData->getRoleNames();
            $userData->update(['fcm_token' => $request->fcm_token]);
        }else{
            $accessToken    =   null;
            $message        =   "User not found!";
            $code           =   404;
        }
        
        return  response([
                    'user' => $userData, 
                    'access_token' => $accessToken,
                    'message' => $message
                ], $code);
    }

    public function creatorLogin(Request $request)
    {
        $validator              = Validator::make($request->all(), [
            'email'             => 'required|email|string',
            'password'          => 'required|string',
            'fcm_token'         => 'required',
        ]);

        if($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }
        
        if(!Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            return response([
                'message' => 'Invalid credentials!',
                'success' => 0
            ],400);
        }
        
        $accessToken    = Auth::user()->createToken('authToken')->accessToken;

        $user           =   Auth::user();
        $roles          =   $user->getRoleNames();
        $user->update(['fcm_token' => $request->fcm_token]);

        return  response([
                    'user' => $user, 
                    'access_token' => $accessToken,
                    'success' => 1
                ]);
    }

    public function socialLogin(Request $request)
    { 
        $validData      = $request->validate([
            'social_id' => 'required|string',
            'fcm_token' => 'required',
        ]);

        $userData   =   User::where('social_id',$request->social_id)->first();

        if(isset($userData)){
            Auth::login($userData);
            $accessToken    =   $userData->createToken('authToken')->accessToken;
            $message        =   "User has been login successfully!";
            $code           =   200;
            $roles          =   $userData->getRoleNames();
            $userData->update(['fcm_token' => $request->fcm_token]);
        }else{
            $accessToken    =   null;
            $message        =   "User not found!";
            $code           =   404;
        }
        
        return  response([
                    'user' => $userData, 
                    'access_token' => $accessToken,
                    'message' => $message
                ], $code);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required | string ',
            'email'    => 'required | email | unique:users',
            'mobile'   => 'required | numeric | unique:users',
            'social_id'=> 'unique:users',
        ]);

        if ($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        $user = User::create([
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'mobile'        => $request->mobile,
                    'dob'           => $request->dob,
                    'ip'            => $request->ip(),
                    'social_id'     => $request->social_id,
                    'profile_pic'   => $request->profile_pic,
                    'fcm_token'     => $request->fcm_token,
                    'wallet_bal'    => 0,
                ]);
        
        if($user){
            $role = $user->syncRoles('Member');
            $accessToken    = $user->createToken('authToken')->accessToken;

            return response([
                'message'       => 'User registered succesfully!',
                'user'          => $user,
                'access_token'  => $accessToken,
                'success'       => 1
            ],200);
        }

        return response([
                'message'       => 'Sorry! Failed to register user!',
                'success'       => 0
            ],400);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        // match old password
        if (Hash::check($request->old_password, Auth::user()->password)){

            User::find(auth()->user()->id)
            ->update([
                'password'=> Hash::make($request->password)
            ]);

            return response([
                        'message' => 'Password has been changed',
                        'status'  => 1
                    ],200);
            
        }
            return response([
                        'message' => 'Password not matched!',
                        'status'  => 0
                    ],400);
    }

    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response([
                    'message' => 'Logged out succesfully!',
                    'status'  => 0
                ]);
    }

}
