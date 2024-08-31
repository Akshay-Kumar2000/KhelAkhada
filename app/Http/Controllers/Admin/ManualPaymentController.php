<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\ManualPayment;

class ManualPaymentController extends Controller
{
    protected $paging = 500;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    { $search='';
        if($request->search){
            $search         =  $request->search;
            $transactions  = ManualPayment::select('manual_payments.*','users.username')->with('user')
                        ->leftJoin('users','manual_payments.user_id','=','users.id')
                        ->where('users.username','LIKE','%'.$search.'%')
                        ->latest()->paginate($this->paging);
            $transactions->appends(['search' => $search]);
        }else{
            $transactions   =   ManualPayment::with('user')->latest()->paginate(20);
        }
        //echo "<pre>"; print_r($transactions);die;
        return view('admin/manual-payment/index',compact('transactions','search'));
    }

}
