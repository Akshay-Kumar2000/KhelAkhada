<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
use DataTables, Auth;
use DB;
//use Illuminate\Support\Facades\Schema;
//use Config;
use App\Event;
use App\User;
use App\Organiser;

class DashboardController extends Controller
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
        $admin_id = Auth::user()->id;

        $data['total_users'] = User::role('Member')->count();

        $data['total_creators'] = User::role('Creator')->count();

        $data['total_events'] = Event::count();
        $data['total_organisers'] = Organiser::count();

        $data['events'] = Event::with(['creator', 'joinusers'])->withSum('earning', 'amount')->latest()->limit(10)->get();
        $data['total_recharge'] = DB::table('transactions')->where('status', 'WALLET')
            ->whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-d H:i:s')])
            ->sum('amount');
        return view('admin/dashboard/dashboard', compact('data'));
    }

    public function getIncomeDateWise()
    {
        $txns = DB::table('transactions')->select(['pdate', DB::raw("(sum(amount)) as revenue")])
            ->whereBetween('created_at', [date('Y-m-01 00:00:00'), date('Y-m-d H:i:s')])
            ->where('status', 'WALLET')->groupBy('pdate')->get();
        //echo "<pre>";print_r($txns);die;

        for ($i = 0; $i < date('d'); $i++) {
            $flag = 1;
            for ($y = 0; $y < $txns->count(); $y++) {
                if ($i + 1 == date('d', strtotime($txns[$y]->pdate))) {
                    $arr[$i]['date'] = $txns[$y]->pdate;
                    $arr[$i]['income'] = $txns[$y]->revenue;
                    $flag = 0;
                }
            }
            if ($flag) {
                $arr[$i]['date'] = date('Y-m-' . ($i + 1));
                $arr[$i]['income'] = 0;
            }
        }

        return $arr;
    }

    private function getAgencyCreatorList($a_id)
    {
        return User::where('added_by', $a_id)->where('status', 1)->pluck('id')->toArray();
    }

}
