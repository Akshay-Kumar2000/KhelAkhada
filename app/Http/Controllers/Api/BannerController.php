<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Event;
use App\Banner;
use App\User;
use Auth;

class BannerController extends Controller
{
    public function search(Request $request)
    {
        $events             =   Event::with('creator')->where('status',1)->where('title','LIKE','%'.$request->search.'%')->get();   
        $creators           =   User::where('status',1)->where('name','LIKE','%'.$request->search.'%')->role('Creator')->get();   
        
        return response([
                'events'        => $events,
                'creators'      => $creators,
                'success'       => 1
            ],200);
    }

    public function list(Request $request)
    {
        $banners                 =   Banner::where('status',1)->get();      
        
        return response([
                'banners'       => $banners,
                'success'       => 1
            ],200);
    }
    
}
