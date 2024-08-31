<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\RoomCode;
use App\Setting;
use Auth;
use Illuminate\Support\Facades\Validator;

class RoomCodeController extends Controller
{
    protected $paging   =   100;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {        
        $codes = RoomCode::orderBy('id','desc')->paginate($this->paging);
        //echo "<pre>";print_r($banners);die;
        return view('admin/room-code/index',compact('codes'));
    }

    public function create(Request $request)
    {
        return view('admin/room-code/create');
    }

    public function store(Request $request)
    { 
        $codes  =   $request->room_codes;
        //echo "<pre>";print_r($codes);die;
        //echo strtotime('+5 minutes');die;
        $minutes    =   Setting::find(2);
        try
        {
            foreach($codes as $key => $val){
                if($val > 0){
                    RoomCode::create([
                        'room_code'              => $val,
                        'status'                 => 1,
                        'expire_at'              => date('Y-m-d H:i:s',strtotime('+'.$minutes->field_value.' minutes')),
                    ]);
                }
            }                    
            return redirect('admin\room-codes')->with('success', 'Room codes added successfully!');            
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = RoomCode::find($uid);
        if($data){
            $data->update(['status' => $status]);
            return redirect()->back()->with('success', 'Status updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

}