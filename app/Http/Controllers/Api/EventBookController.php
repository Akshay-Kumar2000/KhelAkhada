<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Event;
use App\EventBooking;
use App\StageSeatSetting;
use App\Transaction;
use Auth;

class EventBookController extends Controller
{
    
    public function create(Request $request)
    { 
        $validator              = Validator::make($request->all(), [
            'event_id'          => 'required',
            'stage_id'          => 'required',
            'category_id'       => 'required',
            'quantity'          => 'required',
        ]);

        if($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        $event                  =   Event::find($request->event_id);
        $setting                =   StageSeatSetting::select('capacity','price')->where('stage_id',$request->stage_id)
                                    ->where('category_id',$request->category_id)->where('status',1)->latest()->first();
        $user                   =   Auth::user();
        $amount                 =   $setting->price * $request->quantity;
        $booking_id             =   time().''.$user->id;

        if($event->status   != 1 || $event->event_time < date('Y-m-d H:i:s')){
            return response([
                'message'       => "We are unable to process your request at this time!",
                'success'       => 0
            ],400);
        }

        $eventBookedQty         =   EventBooking::where('event_id',$request->event_id)->sum('quantity');
        //echo $eventBookedQty;die;
        
        if($setting->capacity    < ($eventBookedQty + $request->quantity)){
            return response([
                'message'       => "Seats are not available!",
                'success'       => 0
            ],400);
        }

        if(($amount)    >   $user->wallet_bal){
            return response([
                'message'       => "You wallet balance is low to join this event, please recharge first.",
                'success'       => 0
            ],402);
        }

        $book   = EventBooking::create([
                    'event_id'  => $request->event_id,
                    'category_id'=> $request->category_id,
                    'stage_id'  => $request->stage_id,
                    'quantity'  => $request->quantity,
                    'user_id'   => $user->id,
                    'amount'    => $amount,
                    'status'    => 1,
                    'ip'        => $request->ip(),
                    'booking_id'=> $booking_id,
                ]);
          
        if($book){
            $user->decrement('wallet_bal',$amount);

            Transaction::create([
                'source_id' => $booking_id,
                'user_id'   => $user->id,
                'amount'    => $amount,
                'agc_amount'=> $amount,
                'status'    => "BOOK",
                'pdate'     =>  date('Y-m-d'),
                'ip'        => $request->ip(),
            ]);

            return response([
                'message'       => 'Event booked succesfully!',
                'success'       => 1,
                'booking_id'    =>  $booking_id
            ],200);
        }

        return response([
                'message'       => 'Sorry! Failed to book event!',
                'success'       => 0
            ],400);
    }

    public function details(Request $request){
        $validator              = Validator::make($request->all(), [
            'booking_id'        => 'required',
        ]);

        if($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        $data       =   EventBooking::with('eventdata','stage','seatcategory')->where('booking_id',$request->booking_id)->first();

        return response([
            'data'          => $data,
            'success'       => 1,
        ],200);
    }

}
