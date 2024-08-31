<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Complaint;
use Auth;

class ComplaintController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function support(Request $request)
    {        
        $requests	    =	Complaint::where('user_id',Auth::user()->id)
						    ->whereRaw('created_at >= "'.date('Y-m-d H:i:s').'" -  interval 05 day')->latest()->get();        
        return view('user.support',compact('requests'));
    }

    public function notification(Request $request)
    {        
        $requests	    =	Complaint::where('user_id',Auth::user()->id)
						    ->whereRaw('created_at >= "'.date('Y-m-d H:i:s').'" -  interval 05 day')->latest()->get();        
        return view('user.notification',compact('requests'));
    }
    public function legal(Request $request)
    {        
        $requests	    =	Complaint::where('user_id',Auth::user()->id)
						    ->whereRaw('created_at >= "'.date('Y-m-d H:i:s').'" -  interval 05 day')->latest()->get();        
        return view('user.legal',compact('requests'));
    }

    public function create(Request $request)
	{ 
		try
        {
            $user_id    =   Auth::user()->id;            
            $request->validate([
                'message' => 'required',
            ]);
            
            $complaint         =   new Complaint();

            if($request->image){
                $request->validate([
                            'image' => 'required|image|mimes:jpeg,png,jpg,gif,JPG,JPEG|max:10240',
                        ]);
                $imageName = time().'-'.$user_id.'.'.$request->image->extension(); 
                //@chmod(public_path('uploads/'.$result->c_id), 0777);
                $request->image->move(public_path('uploads/support/'), $imageName);
                $complaint->image 	=	'uploads/support/'.$imageName;
            }

            $complaint->user_id  =   $user_id;
            $complaint->message  =   $request->message;
            $complaint->ip       =   $request->ip();
            $complaint->save();

            return response()->json(['success'=>'Complaint submitted successfully!']);

        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}