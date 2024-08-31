<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Event;
use App\EventJoin;
use App\EventBooking;
use Auth;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\Notification;

class EventController extends Controller
{
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }
    
    public function list(Request $request)
    {
        if(isset(auth('api')->user()->id)){
            if(isset($request->search)){
                $events             =   Event::with('creator','userjoin')->where('status',1)->where('title','LIKE','%'.$request->search.'%')
                                        ->where('event_time', '>=' ,date('Y-m-d h:i:s'))->paginate(10);   
            }elseif(isset($request->type)){
                $events             =   Event::with('creator','userjoin')->where('status',1)->where('type',$request->type)
                                        ->where('event_time', '>=' ,date('Y-m-d h:i:s'))->paginate(10);  
            }else{
                $events             =   Event::with('creator','userjoin')->where('status',1)
                                        ->where('event_time', '>=' ,date('Y-m-d h:i:s'))->paginate(10);      
            }
        }else{
            if(isset($request->search)){
                $events             =   Event::with('creator')->where('status',1)->where('title','LIKE','%'.$request->search.'%')
                                        ->where('event_time', '>=' ,date('Y-m-d h:i:s'))->paginate(10);   
            }elseif(isset($request->type)){
                $events             =   Event::with('creator')->where('status',1)->where('type',$request->type)
                                        ->where('event_time', '>=' ,date('Y-m-d h:i:s'))->paginate(10);  
            }else{
                $events             =   Event::with('creator')->where('status',1)
                                        ->where('event_time', '>=' ,date('Y-m-d h:i:s'))->paginate(10);      
            }
        }
        
        return response([
                'events'        => $events,
                'success'       => 1
            ],200);
    }

    public function activeEvents(Request $request)
    {
        $events                 =   Event::with('creator')->where('status',2)->get();      
        
        return response([
                'events'        => $events,
                'success'       => 1
            ],200);
    }

    public function creatorActiveEvent(Request $request)
    {
        $events                 =   Event::with('creator')->where('user_id',Auth::user()->id)
                                    ->where('status',2)->first();
        
        return response([
                'event'         => $events,
                'success'       => 1
            ],200);
    }

    public function creatorEvents(Request $request)
    {
        $events                 =   Event::where('user_id',Auth::user()->id)
                                    ->where('status',1)->get();
        
        return response([
                'events'         => $events,
                'success'        => 1
            ],200);
    }

    public function userVirtualBookings(Request $request)
    {
        $events                 =   EventJoin::with('eventdata')->where('user_id',Auth::user()->id)
                                    ->where('status',1)->get();
        
        return response([
                'bookings'      => $events,
                'success'       => 1
            ],200);
    }
    
    public function userStageBookings(Request $request)
    {
        $events                 =   EventBooking::with('eventdata')->where('user_id',Auth::user()->id)
                                    ->where('status',1)->get();
        
        return response([
                'bookings'      => $events,
                'success'       => 1
            ],200);
    }

    public function details(Request $request)
    {
        if(isset(auth('api')->user()->id)){
            $data                   =   Event::with('creator','booking','userjoin','stage','eventbooking')->withCount('join')->find($request->id);
        }else{
            $data                   =   Event::with('creator','booking','stage','eventbooking')->withCount('join')->find($request->id);
        }
        return response([
                'data'          => $data,
                'success'       => 1
            ],200);
    }

    public function create(Request $request)
    {
        $validator              = Validator::make($request->all(), [
            'title'             => 'required | string ',
            'price'             => 'required | numeric',
            'event_time'        => 'required',
        ]);

        if($validator->fails()) {
            return response([
                'message'       => $validator->messages()->first(),
                'success'       => 0
            ],400);
        }

        $event = Event::create([
                    'title'         => $request->title,
                    'user_id'       => Auth::user()->id,
                    'price'         => $request->price,
                    'event_time'    => $request->event_time,
                    'schedule'      => $request->schedule,
                    'status'        => $request->status,
                    'ip'            => $request->ip(),
                ]);
          
        if($event){
            $this->eventStartEndNotification('event_start',$event->id);
            return response([
                'data'          => Event::with('creator')->find($event->id),
                'message'       => 'Event created succesfully!',
                'success'       => 1
            ],200);
        }

        return response([
                'message'       => 'Sorry! Failed to register user!',
                'success'       => 0
            ],400);
    }

    public function updateStatus(Request $request)
    {
        $user               = Auth::user();
        $validData          = $request->validate([
            'event_id'      => 'required',
            'status'        => 'required', 
        ]);

        $data = Event::with('creator')->find($request->event_id);

        if($data->status == 0 || $data->status == 3 || $data->status == 4 || $user->id != $data->user_id ){
            return response([
                'data' => $data,
                'message' => 'Sorry! we are unable to process your request.',
                'status'  => 0
            ],400);
        }

        if($request->status ==  3)
        $this->eventStartEndNotification('event_end',$request->event_id);

        $data->update([
            'status'    =>  $request->status
        ]);
        
        return response([
                    'data' => $data,
                    'message' => 'Event status changed successfully!',
                    'status'  => 1
                ],200);
    }

    private function eventStartEndNotification($type,$event_id){
        $topic                  = 'user';

        $notification           =   [];
        if($type=='event_start'){
            $title              = Auth::user()->name.' is Live!';
            $body               = 'Watch live event now!';

            $notification       = Notification::fromArray([
                'title'         => $title,
                'body'          => $body,
            ]);
    
            $notification = Notification::create($title, $body);
            // $message            =   CloudMessage::withTarget('topic', $topic)
            //                     ->withNotification($notification);
        }
     
        $message                = CloudMessage::fromArray([
            'topic'             => $topic,
            'notification'      => $notification,
            'data'              => [
                'type'          =>  $type,
                'id'            =>  $event_id,
            ], 
            'options'           =>  [
                'priority'      =>  'high',
                'content_available'  =>  true,
            ],
        ]);

        $response           =    $this->messaging->send($message);
    }
    
    public function sendFcmMessage(Request $request){
       // die('kk');

        //$deviceToken = 'cplPBin6T6aNMgrq_iXzsZ:APA91bHeDGdvgnORB0ulnEpJUaL-AxAlAWDcZAAI2F0yVdHBj_nNkRyVMioesON4xWkmnchD27SGluqSEj62UerNA9VwUuqa2XIy86S546ca8il9mn2_xwulpK_-1icmnldIHwTgC-GV';
        $deviceToken = $request->fcm_token;
        //$notification   =   Notification::create('Title', 'Body');
        //$messaging = $factory->createMessaging();
        // $data           =   ['key' => 'val'];
        // //$messaging = (new Factory)->withServiceAccount('...')->createMessaging();
        $title = '';
        $body = '';

        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
        ]);

        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token',$deviceToken)
            ->withNotification($notification) // optional
            //->withData($data) // optional
        ;

        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ], 
            'data' => [
                'type'  =>  'force_stop',
                'id'    =>  $request->event_id
            ], 
        ]);

        $response   =           $this->messaging->send($message);
echo "<pre>";print_r($response);die;
        // $message = CloudMessage::fromArray([
        //     'token' => $deviceToken,
        //    // 'notification' => [/* Notification data as array */], // optional
        //     'data' => [
        //         'title' =>  'Title',
        //         'body' =>  'Body',
        //     ], // optional
        // ]);
       


        $title = 'My Notification Title';
        $body = 'My Notification Body';

        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
        ]);

        $notification = Notification::create($title, $body);

        // $changedNotification = $notification
        //     ->withTitle('Changed title')
        //     ->withBody('Changed body')
        //     ->withImageUrl('http://lorempixel.com/200/400/');

        //$message = $message->withNotification($notification);
        //$response=  $this->messaging->send($message);

        $topic = 'user';

        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification) // optional
            //->withData($data) // optional
        ;

        $message = CloudMessage::fromArray([
            'topic' => $topic,
            'notification' => $notification, // optional
            'data' => [
                'title' =>  'Test title',
                'body' =>  'Test body',
            ], // optional
        ]);

        $response   =    $this->messaging->send($message);

        echo "<pre>"; print_r($response) ; die;

        $url = "https://fcm.googleapis.com/fcm/send";
        //$token =  $this->device_id;
        $serverKey = 'AAAAoUXnSqY:APA91bETIh92uouE0_8yBkTCVyUcszlEhD4FiQpWFxDne8wzKHepe9cIsAMtg6muHtcQOC56-Its3QAJHy441MEi-UKz7_z5CSyagLKHAdVb39Ve3mL8i71FyHVXCitxXfr_wIvNMCEU';
        // if(!empty($this->message)) {
        //     if(empty($this->message['sound'])) {
        //         $this->message['sound'] = 'default';
        //     }
        //     if(empty($this->message['text']) && !empty($this->message['message'])) {
        //         $this->message['text'] = $this->message['message'];
        //     }
        //     if(!empty($this->message['text'])) {
        //         $this->message['body'] = $this->message['text'];
        //     }
        // }

        // $customMessage = ['apn' => [
        //     'data' => [
        //         'sound' =>  'default',
        //         'text' =>  'test',
        //         'body' =>  'test body',
        //     ]
        // ]];

        // if(isset($this->message['soundName'])){
        //     $customMessage['soundName'] = $this->message['soundName'];
        // }

        //$notification = $this->message;
        //$customMessage['notification'] = $notification;
        $arrayToSend = array(
            'registration_ids' => $token, 
            'data' => [
                'title' =>   'test title',
                'head' =>   'test head',
                'budy' =>   'test body',
            ], 
            //'notification' => $notification, 
            'priority' => 'high'
        );
        // if(!empty($this->message['soundName'])) {
        //     $arrayToSend['soundName'] = $this->message['soundName'];
        // }
        // if(!empty($this->message['sound'])) {
        //     $arrayToSend['sound'] = $this->message['sound'];
        // }
        
        $arrayToSend['android'] = ['priority' => 'high'];
        $json = json_encode($arrayToSend);

        // Log::debug('===========FCM json-------------------');
        // Log::debug($json);
        // Log::debug('===========FCM json-------------------');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

        //Send the request
        $response = curl_exec($ch);
        //Close request

        // Log::debug('===========FCM Result-------------------');
        // Log::debug($response);
        // Log::debug('===========FCM Result-------------------');


        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
echo "<pre>";print_r($response); die;






    }

    
}
