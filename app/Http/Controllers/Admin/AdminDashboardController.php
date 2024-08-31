<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
use DataTables, Auth;
use DB;
//use Illuminate\Support\Facades\Schema;
//use Config;
use App\Challenge;
use App\Setting;
use App\Transaction;
use App\PaymentOrder;
use App\User;
use Carbon\Carbon;
use Hash;

class AdminDashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return Hash::make('AASAFALI@#4020');
        $admin_id = Auth::user()->id;
        $data['today_users'] = User::where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->count();
        $data['total_users'] = User::count();
        $data['total_wallet_balance'] = User::sum('wallet');
        $data['total_challenges'] = Challenge::count();
        $data['playing_challenges'] = Challenge::where(function ($query) {
            return $query
                ->where('status', 2)
                ->orWhere('status', 3)
                ->orWhere('status', 4);
        })->count();
        $data['one_person_response'] = Challenge::leftJoin('user_results', 'challenges.id', '=', 'user_results.ch_id')->select('challenges.id')->where(function ($query) {
            return $query
                ->where('challenges.status', 2)
                ->orWhere('challenges.status', 3)
                ->orWhere('challenges.status', 4);
        })
            ->whereNull('challenges.deleted_at')
            ->groupBy('challenges.id')
            ->havingRaw('COUNT(user_results.id) = 1')
            ->count();
        $data['withdraw_log'] = Setting::where('field_name', 'withdraw_gateway_log')->first();
        $data['hold_challenges'] = Challenge::where('status', 5)->count();

        $data['total_recharge'] = Transaction::where('status', 'Wallet')->sum('amount');
        $data['today_recharge'] = Transaction::where('status', 'Wallet')->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->sum('amount');
        $data['today_withdraw'] = Transaction::where('status', 'Withdraw')->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->sum('amount');
        $data['total_withdraw'] = Transaction::where('status', 'Withdraw')->sum('amount');
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $data['month_recharge'] = Transaction::where('status', 'Wallet')->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $startDate = Carbon::now()->subWeek()->startOfDay();

        // Get the end date (today)
        $endDate = Carbon::now()->endOfDay();
        $alldaterecharge = array();
        // Get the start date for the last 1 week
        $startDate = Carbon::now()->subWeek()->startOfDay();

        // Get the end date (today)
        $endDate = Carbon::now()->endOfDay();

        // Initialize an associative array to store results
        $resultsByDate = [];

        // Loop through each day in the last 1 week
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $alldaterecharge[$currentDate->format('Y-m-d')] = $this->rechargeByDate($currentDate->format('Y-m-d'));
            // Move to the next day
            $currentDate->addDay();
        }
        // Modify the query to include the date range condition
        $data['rechargesByDay'] = $alldaterecharge;
        $data['recharges'] = Transaction::with(['playername'])->where('status', 'Wallet')->latest()->limit(10)->get();
        $data['total_month_refer_income'] = Transaction::where('status', 'Referral')->whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-d 23:59:59')])->sum('amount');
        return view('admin/dashboard/dashboard', compact('data'));
    }

    public function rechargeByDate($date)
    {
        return PaymentOrder::where('status', '1')
            ->whereRaw('DATE(created_at) = ?', [$date])
            ->sum('amount');
    }
    public function getIncomeDateWise()
    {
        $arr = DB::table('transactions')->select([DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'), DB::raw("(sum(a_amount)) as income")])
            ->whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-d H:i:s')])->groupBy('date')->get();
        //echo "<pre>";print_r($txns);die;

        // for($i=1; $i < date('d') ; $i++){
        //     $flag   =   1;
        //     for($y=0 ; $y < $txns->count(); $y++){
        //         if($i+1 == date('d',strtotime($txns[$y]->pdate))){
        //             $arr[$i]['date']    =   $txns[$y]->pdate;
        //             $arr[$i]['income']  =   $txns[$y]->revenue;
        //             $flag   =   0;
        //         }
        //     }
        //     if($flag){
        //         $arr[$i]['date']    =   date('Y-m-'.($i+1));
        //         $arr[$i]['income']  =   0;
        //     }
        // }
        //echo "<pre>";print_r($arr);die;
        return $arr;
    }

    private function getAgencyCreatorList($a_id)
    {
        return User::where('added_by', $a_id)->where('status', 1)->pluck('id')->toArray();
    }

}
