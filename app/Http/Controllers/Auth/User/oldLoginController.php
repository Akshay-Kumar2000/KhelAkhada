<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use App\UserData;
use App\UserSetting;
use Auth;
use Session;
use Http;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/challenges';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest:player')->except('logout');
    }

    public function showLoginForm(Request $request){
        if(isset(Auth::user()->id)){
            return redirect()->route('challenges');
        }
        $referral   =   0;
        if($request->referral)
        $referral   =   $request->referral;
        
		return view('auth.front.login',compact('referral'));
	}
	
	public function doLoginUser(Request $request){
        $this->validate($request, [
            'mobile' => 'required|numeric',
        ]);
        try
        {            
            
            $mobile                 =   $request->mobile;
            $otp                    =   $request->otp;
            $newOtp                 =   rand(100000,999999);
            $userData               =   User::where('mobile',$mobile)->first();
            $vplay_id = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 5).rand(111,999);
		
            if(isset($userData)){
                //echo "<pre>";print_r($userData);die;
                if($userData && $otp){
                    if($userData->otp != $otp){
                        return response([
                            'status'         => 0,
                            'message'        => "Invalid OTP!"
                        ],400);
                    }
                    Auth::login($userData);
                    $userData->otp  =   0;
                    $userData->save();
                    return response([
                        'status'         => 2,
                        'url'            => url('/')
                    ],200);
                }
                $userData->otp  =   111111; 
                $userData->save();
                $this->sendOtp($mobile,$newOtp); 
              
              	$user_id = $userData->id;
				$user_data = new UserData;
				$user_data['user_id'] = $user_id;
				$user_data['vplay_id'] = $vplay_id;
				$user_data->save();
                return response([
                    'status'         => 1,
                    'message'        => "OTP send successfully!"
                ],200);
            }

            //die("Registrations are closed. Please contact to admin!");
            $user	                =	User::create([
                'username'          => ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)).ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)),
                'mobile'            => $mobile,
                'otp'               => $newOtp,
                'ip'	            =>	$request->ip()
            ]);
            //echo "<pre>";print_r($user->id);die;
            if(isset($user->id)){
                $user->syncRoles('Member');
                //echo $user->id;die;
                $rf_user_id =   0;
                if($request->referral){
                    $rf_user_data    =   UserSetting::where('referral',$request->referral)->first();
                    $rf_user_id      =     $rf_user_data->user_id;
                }
				
				
				$user_id = $user->id;
				$user_data = new UserData;
				$user_data['user_id'] = $user_id;
				$user_data['vplay_id'] = $vplay_id;
				$user_data->save();
				
                UserSetting::create([
                    'user_id'		=>	$user->id,
                    'referral'		=>	bin2hex(random_bytes(4)),
                    'used_referral' =>	$request->referral,
                    'rf_user_id'    =>	$rf_user_id,
                ]);
    
                 $this->sendOtp($mobile,$newOtp);	
                return response([
                    'status'         => 1,
                    'message'        => "OTP send successfully!"
                ],200);
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
	}

    private function sendOtp($mobile,$otp){			    
	
	$key ="exBsvGAWjPaM0hy3iRZHg1VbTzJlucw6UqrpCd2IXkFt49no7N5qkpboLQawIVKviYmctznOyW97ue4d";	
	$route = "v3";		
	$sender_id = "FTWSMS"; 
	$message = "Your Login OTP is ".$otp.".";    
    $language = "english";      
	$flash = "0";  
	$numbers = $mobile;		
	$message = urlencode($message);	
	$data = "authorization=".$key."&route=".$route."&sender_id=".$sender_id."&message=".$message."&language=".$language."&flash=".$flash."&numbers=".$numbers;	
	$ch =   curl_init('https://www.fast2sms.com/dev/bulkV2?'.$data);		 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);				
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');				
	$response = curl_exec($ch);				
	curl_close($ch);	
	$user = User::where('mobile','=',$mobile)->update(['otp' => $otp]);  
	return response()->json([$user],200);

		return 1;
	}

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('error','You entered Wrong credentials!');
    }
	
}
