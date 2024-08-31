<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\StageSeatSetting;
use App\SeatCategory;
use Auth;
use Illuminate\Support\Facades\Validator;

class StageSeatSettingController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function settings($stage_id)
    {
        $categories     =   SeatCategory::where('status',1)->pluck('name','id');
        $settings       =   StageSeatSetting::where('status',1)->where('stage_id',$stage_id)->get();
        $sArrays        =   StageSeatSetting::where('status',1)->where('stage_id',$stage_id)->pluck('category_id')->toArray();
        //$sArrays        =   array_values($settings, 'category_id');
        //echo "<pre>";print_r($sArrays); die;
        return view('admin/stage/settings',compact('categories','settings','sArrays','stage_id'));
    }

    public function update(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'categories.*'      => 'required',
            'capacity.*'        => 'required',
            'price.*'           => 'required',
        ]);

        //echo "<pre>"; print_r($request->all());die;
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            $categories         =   $request->categories;
            $settings           =   $request->settings;
            $capacities         =   $request->capacities;
            $prices             =   $request->prices;

            foreach($categories as $key => $val){
               if(isset($settings[$key])){
                    $data       =   StageSeatSetting::find($settings[$key]);
                    $data->update([
                        'capacity'             => $capacities[$key],
                        'price'                => $prices[$key],
                    ]);
               }else{
                    StageSeatSetting::create([
                        'stage_id'              =>  $request->stage_id,
                        'category_id'           =>  $val,
                        'capacity'              =>  $capacities[$key],
                        'price'                 =>  $prices[$key],
                        'ip'                    =>  $request->ip(),
                    ]);
               }
            }
            return redirect('admin\stages')->with('success', 'Stage Seat settings updated successfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = SeatCategory::find($uid);
        if($data){
            $data->update(['status' => $status]);
            return redirect()->back()->with('success', 'Staus updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

}