<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Event;
use App\EventJoin;
use App\Transaction;
use Auth;

class EventJoinController extends Controller
{
    
    public function create(Request $request)
    { 
        $validator              = Validator::make($request->all(), [
            'event_id'          => 'required',
        ]);

        if($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        $event                  =   Event::find($request->event_id);
        $user                   =   Auth::user();

        if($event->status   == 0 || $event->status   == 3 || $event->status   == 4){
            return response([
                'message'       => "Event has been ended!",
                'success'       => 0
            ],400);
        }

        $eventJoin              =   EventJoin::where('user_id',$user->id)->where('status',1)
                                    ->where('event_id',$request->event_id)->first();
        
        if(isset($eventJoin)){
            $eventJoin->update([
                'status'        =>  0,
            ]);
            return response([
                'message'       => "Booked event joined succesfully!",
                'success'       => 1
            ],200);
        }

        if($event->price    >   $user->wallet_bal){
            return response([
                'message'       => "You wallet balance is low to join this event, please recharge first.",
                'success'       => 0
            ],402);
        }

        $join   = EventJoin::create([
                    'event_id'  => $request->event_id,
                    'user_id'   => $user->id,
                    'amount'    => $event->price,
                    'status'    => 0,
                    'ip'        => $request->ip(),
                    'booking_id'=> time().''.$user->id,
                ]);
          
        if($join){
            $user->decrement('wallet_bal',$event->price);

            Transaction::create([
                'source_id' => $request->event_id,
                'user_id'   => $user->id,
                'amount'    => $event->price,
                'agc_amount'=> $event->price,
                'status'    => "JOIN",
                'pdate'     =>  date('Y-m-d'),
                'ip'        => $request->ip(),
            ]);

            return response([
                'message'       => 'Event joined succesfully!',
                'success'       => 1
            ],200);
        }

        return response([
                'message'       => 'Sorry! Failed to join event!',
                'success'       => 0
            ],400);
    }

    public function book(Request $request)
    { 
        $validator              = Validator::make($request->all(), [
            'event_id'          => 'required',
        ]);

        if($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        $event                  =   Event::find($request->event_id);
        $user                   =   Auth::user();

        if($event->status   != 1 ){
            return response([
                'message'       => "You cannot book this event!",
                'success'       => 0
            ],400);
        }

        $eventJoin              =   EventJoin::where('user_id',$user->id)->where('status',1)
                                    ->where('event_id',$request->event_id)->count();
        
        if($eventJoin == 1){
            return response([
                'message'       => "You have already booked this event!",
                'success'       => 0
            ],400);
        }

        if($event->price    >   $user->wallet_bal){
            return response([
                'message'       => "You wallet balance is low to join this event, please recharge first.",
                'success'       => 0
            ],402);
        }

        $join   = EventJoin::create([
                    'event_id'  => $request->event_id,
                    'user_id'   => $user->id,
                    'amount'    => $event->price,
                    'status'    => 1,
                    'ip'        => $request->ip(),
                    'booking_id'=> time().''.$user->id,
                ]);
          
        if($join){
            $user->decrement('wallet_bal',$event->price);

            Transaction::create([
                'source_id' => $request->event_id,
                'user_id'   => $user->id,
                'amount'    => $event->price,
                'agc_amount'=> $event->price,
                'status'    => "JOIN",
                'pdate'     =>  date('Y-m-d'),
                'ip'        => $request->ip(),
            ]);

            return response([
                'message'       => 'Event booked succesfully!',
                'success'       => 1
            ],200);
        }

        return response([
                'message'       => 'Sorry! Failed to book event!',
                'success'       => 0
            ],400);
    }
    
}
