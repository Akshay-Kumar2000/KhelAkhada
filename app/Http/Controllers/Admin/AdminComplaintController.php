<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Complaint;

class AdminComplaintController extends Controller
{
    public function index(Request $request){
        $requests       =   Complaint::with('playername')->latest()->paginate(20);
        //echo "<pre>";print_r($requests);die;
        return view('admin/complaint/requests',compact('requests'));
    }

    public function complaintAction(Request $request , $uid){
        $data        =  Complaint::find($uid);

        $data->status   =   0;
        $data->save();

        return redirect('admin\user-complaints')->with('success', 'User complaint resolved successfully!');
    }
    
}
