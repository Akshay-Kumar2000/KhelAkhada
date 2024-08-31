<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserSetting;
use App\Transaction;
use App\ManualPayment;
use Hash;

class UserController extends Controller
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
    {
        if($request->referral){
            $referral  =   $request->referral;
            $search  =   '';
            $users  = User::with('setting')->select('users.*','user_settings.referral','user_settings.used_referral')
                        ->leftJoin('user_settings','user_settings.user_id','=','users.id')
                        ->where('user_settings.referral','LIKE','%'.$referral.'%')
                        ->orWhere('user_settings.used_referral','LIKE','%'.$referral.'%')
                        ->orderBy('id','desc')->paginate($this->paging);
        }elseif($request->search){
            $search  =   $request->search;
            $referral  =   '';
            $users   =   User::with('setting')->where(function($query) use ($search){
                            return $query
                            ->where('users.name','LIKE','%'.$search.'%')
                            ->orWhere('users.id','LIKE','%'.$search.'%')
                            ->orWhere('users.username','LIKE','%'.$search.'%')
                            ->orWhere('users.email','LIKE','%'.$search.'%')
                            ->orWhere('users.mobile','LIKE','%'.$search.'%');
                        })->orderBy('id','desc')->paginate($this->paging);
            $users->appends(['search' => $search]);
        }else{
            $search  =   '';
            $referral  =   '';
            //$users   =   User::role('Member')->with('setting')->orderBy('id','desc')->paginate($this->paging);
            $users   =   User::with('setting')->orderBy('id','desc')->paginate($this->paging);
        }
        //echo "<pre>"; print_r($users);die;
        return view('admin/user/users',compact('users','search','referral'));
    }

    public function getUserDetails(Request $request)
    {
        $users   =   User::with('setting')->orderBy('id','desc')->paginate(500);
        return view('user/user-details',compact('users'));
    }

    public function create(){
        return view('admin/user/create');
    }

    protected function store(Request $request)
    { //echo "<pre>";print_r($request->all());die;
		$this->validate($request, [
            'mobile' => [
                'required','digits:10',
                'unique:users,mobile',
            ],
            'referral' => [
                'nullable','exists:user_settings,referral',
            ]
        ]);

        $mobile		=	$request->mobile;

		$user	=	User::create([
            'username' => ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)).ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)),
            'mobile' => $mobile,
			'ip'		=>	$request->ip()
        ]);

		if($user){
			$user->syncRoles('Member');

			$used_referral		=	0;
            $rf_user_id         =   0;
			if(isset($request->referral)){
                $rf_user_data    =   UserSetting::where('referral',$request->referral)->first();
                $rf_user_id      =   $rf_user_data->user_id;
				$used_referral	 =	 $request->referral;
			}

			UserSetting::create([
				'user_id'		=>	$user->id,
				'used_referral'	=>	$used_referral,
				'rf_user_id'	=>	$rf_user_id,
				'referral'		=>	bin2hex(random_bytes(4)),
			]);

		}

        \Log::info("Processing order ID: " . $request);

        return redirect()->back()->with('success', 'User added successfully!');
    }

    public function edit($user_id){
        $user   =   User::with('setting')->find($user_id);
        return view('admin/user/edit',compact('user'));
    }

    public function update(Request $request)
    {
        // update user info
        $validator = Validator::make($request->all(), [
            'id'            => 'required',
            'mobile'        => 'required | digits:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try{
            $user       = User::find($request->id);
            $setting    = UserSetting::where('user_id',$request->id)->first();

            $update = $user->update([
                'username'          => $request->name,
                'mobile'            => $request->mobile,
                'email'             => $request->email,
            ]);


			if(isset($request->referral)){
                $rf_user_data    =   UserSetting::where('referral',$request->referral)->first();
                if(isset($rf_user_data)){
                    $rf_user_id      =   $rf_user_data->user_id;

                    $setting->update([
                        'used_referral'     => $request->referral,
                        'rf_user_id'        => $rf_user_id,
                    ]);
                }
			}

            return redirect('admin/users')->with('success', 'User information updated succesfully!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function wallet($user_id){
        $user   =   User::find($user_id);
        //echo "<pre>";print_r($user_id);die;ManualPayment
        return view('admin/user/wallet',compact('user'));
    }

    public function updateWallet(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $user   =   User::find($request->user_id);

        $payment	=	ManualPayment::create([
            'user_id' => $request->user_id,
            'old_balance' => $user->wallet,
            'balance' => $request->wallet,
			'ip'		=>	$request->ip()
        ]);
        $transaction = Transaction::create([
            'user_id'           => $request->user_id,
            'source_id'         => 'Admin',
            'amount'            => $request->wallet,
            'status'            => 'Admin_add',
            'remark'            => 'Success',
            'ip'                =>  $request->ip(),
            'closing_balance' =>  $request->wallet,
        ]);
        if($payment){
            $user->wallet   =   $request->wallet;
            $user->save();

            return redirect('admin/users')->with('success', 'Wallet balance updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = User::find($uid);
        if($data){
            $data->update(['status' => $status]);
            return redirect()->back()->with('success', 'Status updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found for this user in the user_setting table');
        }
    }

    public function delete($id)
    {
        $user   = User::find($id);
        if($user){
            $user->delete();
            return redirect('admin\users')->with('success', 'User removed!');
        }else{
            return redirect('admin\users')->with('error', 'User not found');
        }
    }

    public function statement($id)
    {
        $user   =   User::withSum('recharges','amount')->withSum('wonamount','amount')->withSum('withdrawamt','amount')
                    ->withSum('referralamt','amount')->withSum('prizeamt','amount')->find($id);

        $txns   =   Transaction::where('user_id',$id)->latest()->paginate($this->paging);
        //echo "<pre>";print_r($user);die;
        return view('admin/user/statement',compact('user','txns'));
    }

}
