<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\EventStage;
use App\SeatCategory;
use App\StageSeatSetting;
use Auth;

class EventStageController extends Controller
{
    public function categories(Request $request)
    { 
        $validator              = Validator::make($request->all(), [            
            'stage_id'          => 'required | numeric',
        ]);

        if($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }
        //$data                   =   EventStage::with('creator','booking','userjoin')->withCount('join')->find($request->id);
        $data                   =   StageSeatSetting::with('category')->where('stage_id',$request->stage_id)->where('status',1)->get();
        
        return response([
                'data'          => $data,
                'success'       => 1
            ],200);
    }
    
}
