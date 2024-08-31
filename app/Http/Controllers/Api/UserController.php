<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Transaction;
use Auth;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $creators               =   User::with('nextevent')->where('status',1)->role('Creator')->paginate(6);
        if(isset($request->search)){
            $creators           =   User::with('nextevent')->where('status',1)
                                    ->where('name','LIKE','%'.$request->search.'%')->role('Creator')->paginate(6);
        }

        return response([
                'creators' => $creators,
                'success' => 1
            ],200);
    }

    public function transactions(Request $request)
    {
        $transactions           =   Transaction::select('id','amount','status','created_at')->where('user_id',Auth::user()->id)->paginate(10);

        return response([
                'transactions' => $transactions,
                'success' => 1
            ],200);
    }

    public function details(Request $request)
    {
        $data       =   User::with('events','organisers')->find($request->id);
        return response([
                'data' => $data,
                'success' => 1
            ],200);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();
        return response([
                    'user' => $user,
                    'success' => 1
                ],200);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validData = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required | email ',
            'mobile'    => 'required | numeric ',
            'dob'       => 'required',
        ]);

        if(isset($request->email)){
            $check = User::where('email', $request->email)
                     ->where('id', '!=', $user->id)
                     ->count();
            if($check > 0){
                return response([
                    'message' => 'The email address is already used!',
                    'success' => 0
                ]);
            }
        }

        if(isset($request->mobile)){
            $checkM = User::where('mobile', $request->mobile)
                     ->where('id', '!=', $user->id)
                     ->count();
            if($checkM > 0){
                return response([
                    'message' => 'The mobile number is already used!',
                    'success' => 0
                ]);
            }
        }

        $user->update($validData);

        $user = Auth::user();
        $roles = $user->getRoleNames();

        return response([
                    'user' => $user,
                    'message' => 'Profile updated successfully!',
                    'status'  => 1
                ]);
    }

    public function updateFCMToken(Request $request)
    {
        $validData = $request->validate([
            'fcm_token' => 'required',
        ]);

        $user = Auth::user();
        $user->update($validData);

        return response([
                    'user' => $user,
                    'message' => 'FCM token updated successfully!',
                    'status'  => 1
                ]);
    }

    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_pic'      => 'required | image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        $user = Auth::user();

        //Upload image on AWS S3
        $file = $request->file('profile_pic');
        if($file){
            $imageName = 'profile/'.time().'-'. $user->id.'.'.$file->getClientOriginalExtension();
            Storage::disk('s3')->put($imageName, file_get_contents($file));
        }

        if($user->profile_pic){
            Storage::disk('s3')->delete($user->profile_pic);
        }

        $user->update([
            'profile_pic'       => $imageName,
        ]);

        $user = Auth::user();
        $roles = $user->getRoleNames();

        return response([
                    'user' => $user,
                    'message' => 'Profile pic updated successfully!',
                    'status'  => 1
                ]);
    }


}
