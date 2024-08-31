<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Event;
use App\User;
use App\StageSeatSetting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\Notification;

class EventController extends Controller
{
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }
    
    protected $paging   =   20;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {        
        if($request->search){
            $search  =  $request->search;
            $events  = Event::select('events.*','users.name')->with(['creator','joinusers'])
                        ->leftJoin('users','events.user_id','=','users.id')
                        ->where('events.title','LIKE','%'.$search.'%')
                        ->orWhere('users.name','LIKE','%'.$search.'%')
                        ->latest()->paginate($this->paging);
            $events->appends(['search' => $search]);
        }else{
            $search  =  '';
            $events  = Event::with(['creator','joinusers'])->withSum('earning','amount')->latest()->paginate($this->paging);
        }
        //echo "<pre>";print_r($events);die;
        return view('admin/event/events',compact('events','search'));
    }

    public function create(Request $request)
    {
        $creators   =   User::where('status',1)->role('Creator')->pluck('name','id');
        $stages     =   DB::table('event_stages')->where('status',1)->pluck('name','id');
        //echo "<pre>";print_r($stages);die;
        return view('admin/event/create',compact('creators','stages'));
    }

    public function store(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'creator'           => 'required',
            'event_type'        => 'required',
            'title'             => 'required | string',
            'description'       => 'required | string',
            'event_time'        => 'required',
            //'banner'            => 'required | image|mimes:jpeg,png,jpg|max:2048',
        ]);
        //echo "<pre>"; print_r($request->all());die;
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            // $banner = $request->file('banner');
            // if($banner){
            //     $imageName = 'profile/'.time().'.'.$banner->getClientOriginalExtension();
            //     //echo $imageName;die;
            //     Storage::disk('s3')->put($imageName, file_get_contents($banner));
            // }

            // store user information
            $event = Event::create([
                        'title'             => $request->title,
                        'stage_id'          => $request->stage_id,
                        'price'             => $request->price,
                        'user_id'           => $request->creator,
                        'description'       => $request->description,
                        //'banner'            => $imageName,
                        'type'              => $request->event_type,
                        'schedule'          => 1,
                        'ip'                => $request->ip(),
                        'event_time'        => date('Y-m-d h:i:s',strtotime($request->event_time)),
                    ]);

            if($event){ 
                return redirect('admin\events')->with('success', 'Event created successfully!');
            }else{
                return redirect('admin\events')->with('error', 'Failed to create event! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function details($id)
    {
        $event  = Event::with(['creator','joinusers','stage','eventbooking'])->withSum('earning','amount')->where('id',$id)->first();

        //echo "<pre>";print_r($event);die;
        return view('admin/event/details',compact('event'));
    }

    public function forceStop($eid)
    {
        $data   = Event::where([['id',$eid],['status',2]])->first();
        
        if($data){
            $creator    =   User::find($data->user_id);
            $title = '';
            $body = '';

            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
            ]);

            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $creator->fcm_token)
                ->withNotification($notification) // optional
                //->withData($data) // optional
            ;

            $message = CloudMessage::fromArray([
                'token' => $creator->fcm_token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ], 
                'data' => [
                    'type'  =>  'force_stop',
                    'id'    =>  $eid
                ], 
                'options'           =>  [
                    'priority'      =>  'high',
                    'content_available'  =>  true,
                ],
            ]);

            $response   =           $this->messaging->send($message);

            if(isset($response)){
                $data->update([
                    'status'    =>  4,
                ]);
                return redirect()->back()->with('success', 'Stream stoped successfully!');
            }
            return redirect()->back()->with('error', 'Stream already ended!');
        }else{
            return redirect()->back()->with('error', 'Stream already ended!');
        }
    }

}