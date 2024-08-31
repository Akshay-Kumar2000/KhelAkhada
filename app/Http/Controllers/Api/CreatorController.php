<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Creator;
use Auth;

class CreatorController extends Controller
{
    public function list(Request $request)
    {
        $creators               =   Creator::with('nextevent')->where('status',1)->paginate(6); 
        if(isset($request->search)){
            $creators           =   Creator::with('nextevent')->where('status',1)
                                    ->where('name','LIKE','%'.$request->search.'%')->paginate(6);   
        }
        
        return response([
                'creators' => $creators,
                'success' => 1
            ],200);
    }

    public function details(Request $request)
    {
        $data       =   Creator::with('events')->find($request->id);
        return response([
                'data' => $data,
                'success' => 1
            ],200);
    }
    
    public function profile(Request $request)
    {
        $user = Auth::user();
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
        ]);

        if(isset($request->email)){
            $check = User::where('email', $request->email)
                     ->where('id', '!=', $user->id)
                     ->count();
            if($check > 0){
                return response([
                    'message' => 'The email address is already used!',
                    'success' => 0
                ],400);
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
                ],400);
            }
        }

        $user->update($validData);

        
        return response([
                    'message' => 'Profile updated successfully!',
                    'status'  => 1
                ]);
    }


}
