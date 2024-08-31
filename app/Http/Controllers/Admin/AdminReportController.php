<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use PDF;
use App\Transaction;
use App\User;

class AdminReportController extends Controller
{
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
     * Show the roles page
     *
     */
    public function index(Request $request)
    {
        try{
            $data['search']             =   '';
            $data['commission']         =   '';
            $data['recharge']           =   '';
            $data['from_date']          =   '';
            $data['to_date']            =   '';
            $data['referral']           =   '';
            $data['withdrawl']          =   '';
            $data['balance']            =   '';
            $data['games']              =   '';

            if($request->search){
                $data['search']         =   $request->search;
                $date                   =   explode('-',$data['search']);
                $data['commission']     =   Transaction::whereBetween('created_at',[$date[0],$date[1].' 23:59:59'])->sum('a_amount');                
                $data['recharge']       =   Transaction::where('status','Wallet')->whereBetween('created_at',[$date[0],$date[1].' 23:59:59'])->sum('amount');                
                $data['from_date']      =   $date[0];                
                $data['to_date']        =   $date[1];                
                $data['referral']       =   Transaction::where('status','Referral')->whereBetween('created_at',[$date[0],$date[1].' 23:59:59'])->sum('amount');                
                $data['withdrawl']      =   Transaction::where('status','Withdraw')->whereBetween('created_at',[$date[0],$date[1].' 23:59:59'])->sum('amount');                
                $data['games']          =   DB::table('challenge_results')->where('is_cancel',0)->whereBetween('created_at',[$date[0],$date[1].' 23:59:59'])->count();                
                $data['balance']        =   User::sum('wallet');                
            }

            return view('admin/report/index', compact('data'));
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
    }

    // public function reportDetails(Request $request , $rid)
    // {
    //     try{
    //         $rData              =   AdminReport::find($rid);
    //         $data               =   $request->all();
    //         $search             =   '';
    //         switch($rid){
    //             case(1): 
    //                     if(isset($data['search']))
    //                         $search     =   $data['search'];
    //                     $payouts    =   $this->payoutReport($data);
    //                     return view('admin/report/payout-report', compact('payouts','rData','search'));
    //         }
    //     }catch (\Exception $e) {
    //         $bug = $e->getMessage();
    //         return redirect()->back()->with('error', $bug);

    //     }
    // }

    // public function downloadPDF(Request $request, $rid)
    // {
    //     try{
    //         $rData              =   AdminReport::find($rid);
    //         $data               =   $request->all();
    //         switch($rid){
    //             case(1):
    //                     $payouts  =   $this->payoutReport($data);
    //                     view()->share(['payouts' => $payouts,'search' => $data['search'],'rData' => $rData]);
    //                     $pdf_doc = PDF::setPaper('a4', 'landscape')->loadView('admin/report/download-pdf', $payouts);

    //                     return $pdf_doc->download($rData->name.'.pdf');
    //                     //return view('admin/report/download-pdf', compact('payouts','rData'));
    //         }
    //     }catch (\Exception $e) {
    //         $bug = $e->getMessage();
    //         return redirect()->back()->with('error', $bug);

    //     }
    // }

    // private function payoutReport($data){
    //     if(!empty($data)){
    //         $search     =   $data['search'];
    //         $date       =   explode('-',$search);
    //         $payouts	=	Payout::with('user')->whereBetween('created_at', [date('Y-m-d 00:00:00',strtotime($date[0])), date('Y-m-d 23:59:59',strtotime($date[1]))])->latest()->get();
    //     }else{
    //         $payouts    =   [];
    //     }
    //     return $payouts;
    // }
    
}
