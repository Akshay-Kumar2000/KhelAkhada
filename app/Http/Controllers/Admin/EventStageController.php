<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EventStage;
use App\SeatCategory;
use App\StageSeatSetting;
use Auth;

class EventStageController extends Controller
{
    protected $paging   =   20;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {        
        $stages = EventStage::latest()->paginate($this->paging);
        //echo "<pre>";print_r($stages);die;
        return view('admin/stage/stages',compact('stages'));
    }

    public function create(Request $request)
    {
        return view('admin/stage/create');
    }

    public function edit($stage)
    {
        $stage     =   EventStage::find($stage);
        //echo "<pre>";print_r($stage); die;
        return view('admin/stage/edit',compact('stage'));
    }

    public function store(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'name'              => 'required',
            'venue'             => 'required',
            'link'              => 'required | url',
        ]);
        
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            // store user information
            $event = EventStage::create([
                        'name'             => $request->name,
                        'link'             => $request->link,
                        'venue'            => $request->venue,
                        'ip'               => $request->ip(),
                    ]);

            if($event){ 
                return redirect('admin\stages')->with('success', 'Stage added successfully!');
            }else{
                return redirect('admin\stages')->with('error', 'Failed to add stage! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'stage_id'          => 'required',
            'name'              => 'required',
            'venue'             => 'required',
            'link'              => 'required | url',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            $data               =   EventStage::find($request->stage_id);
            // store user information
            $update = $data->update([
                'name'             => $request->name,
                'link'             => $request->link,
                'venue'            => $request->venue,
            ]);
            
            if($update){ 
                return redirect('admin\stages')->with('success', 'Stage updated successfully!');
            }else{
                return redirect('admin\stages')->with('error', 'Failed to update stage! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = EventStage::find($uid);
        if($data){
            $data->update(['status' => $status]);
            return redirect()->back()->with('success', 'Staus updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

    public function categories($s_id){
        $data       =   StageSeatSetting::with('category')->where('stage_id',$s_id)->where('status',1)->get();
        return $data;
    }
    
}
