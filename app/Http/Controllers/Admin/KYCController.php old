<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\UserData;
use App\UserSetting;

class KYCController extends Controller
{
   
    public function kyc_pending()
    {
// 		$pending = UserData::where('verify_status','2')->where('DOCUMENT_NAME','!=',null)->where('DOCUMENT_NUMBER','!=',null)->where('DOCUMENT_FRONT_IMAGE','!=',null)->where('DOCUMENT_BACK_IMAGE','!=',null)->get();
		$pending = UserData::where('verify_status','2')->get();
		 return view('admin.kycs.pending',compact('pending'));
    }
	
	  public function kyc_view($id)
    {
		  $user_id = $id;
	     $user_kyc_details = UserData::where('user_id',$user_id)->first();
		 $user_details = User::where('id',$user_id)->first();
		 $user_setting = UserSetting::where('user_id',$user_id)->first();
// 		 return $user_setting;
		 return view('admin.kycs.kyc_view',compact('user_setting','user_details','user_kyc_details'));
    }

	public function kyc_approved()
    {
		$approved = UserData::leftJoin('users', 'user_data.user_id', '=', 'users.id')
    ->where('user_data.verify_status', '1')
    ->get();
    // return $approved;
        return view('admin.kycs.approved',compact('approved'));
    }
	
	
	
	public function kyc_verify($id)
    {
		$userData = UserData::find($id);
		$userData->verify_status = '1';
		$userData->save();
		
        return redirect('/admin/kyc-pending');
    }
	
	public function kyc_rejected($id)
    {
		$userData = UserData::find($id);
		$userData->DOCUMENT_NAME = null;
		$userData->DOCUMENT_NUMBER = null;
		$userData->DOCUMENT_FIRST_NAME = null;
		$userData->DOCUMENT_LAST_NAME = null;
		$userData->DOCUMENT_DOB = null;
		$userData->DOCUMENT_STATE = null;
		$userData->DOCUMENT_FRONT_IMAGE = null;
		$userData->DOCUMENT_BACK_IMAGE = null;
		$userData->verify_status = null;
		$userData->save();
		
        return redirect('/admin/kyc-pending');
    }
}
