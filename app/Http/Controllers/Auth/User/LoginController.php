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

            if(isset($userData)){
                if($userData->status==0){
                      return response([
                            'status'         => 0,
                            'message'        => "This User is Blocked"
                        ],400);
                }
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

                  $user_id = $userData->id;
                  $user_data = new UserData;
                  $user_data->user_id = $user_id;
                //   $user_data->vplay_id = 0;
                //   $user_data->save();

                  $user_data->vplay_id = 0;
                  if($user_data->save()){
                    $user_setting = new UserSetting;
                    \Log::info('KYC Status for User ID ' . json_encode($user_data));
                    $user_setting->user_id = $user_id;
                    $user_setting->status = 1;
                    $user_setting->referral  =  bin2hex(random_bytes(4));
                    if($user_setting->save()){
                        \Log::info('user setting log ' . json_encode($user_setting));
                        return response([
                            'status'         => 2,
                            'url'            => url('/')
                        ],200);
                    }
                  }

                    return response([
                        'status'         => 0,
                        "message" => "user not correctly passed"
                        // 'url'            => url('/')
                    ],400);
                }
                $userData->otp  =   111111;
                $userData->save();
                $this->sendOtp($mobile,$newOtp);

                return response([
                    'status'         => 1,
                    'message'        => "OTP send successfully!"
                ],200);
            }else{
                $user	                = User::create([
                    'username'          => ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)).ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)),
                    'mobile'            => $mobile,
                    // 'wallet'            => 30,
                    'otp'               => $newOtp,
                    'ip'	            =>	$request->ip()
                ]);
                $user_id = $user->id;
                if($request->referral){
                    $settings   =   UserSetting::where('user_id',$user_id)->first();
                    $rf_user_data    =   UserSetting::where('referral',$request->referral)->first();
                    if(empty($settings)){
                      $settings = new UserSetting;
                      $settings->user_id   =  $user_id;
                      $settings->referral  =  bin2hex(random_bytes(4));
                    }
                  	$settings->used_referral = $request->referral;
                    $settings->rf_user_id = $rf_user_data->user_id;
                  	$settings->save();
                }

                if(isset($user->id)){
			    	$user_id = $user->id;
			    	$user_data = new UserData;
			    	$user_data['user_id'] = $user_id;
			    	$user_data['vplay_id'] = 0;
			    	$user_data->save();
                    $this->sendOtp($mobile,$newOtp);
                    return response([
                        'status'         => 1,
                        'message'        => "OTP send successfully!"
                    ],200);
                }
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);

        }
	}

//     private function sendOtp($mobile,$otp){

// 	$key ="0dDJC19kWfubAUlTFazNX7SZBg4Gj6Ph8yKexmw5ivrOVpsHq3zn6YtWDbC8gR7amip3xXfdjsBP0qNM";
// 	$route = "otp";
// 	$sender_id = "FTWSMS";
// 	$message = "Your Login OTP is ".$otp.".";
//     $language = "english";
// 	$flash = "0";
// 	$numbers = $mobile;
// 	$message = urlencode($message);
// 	$data = "authorization=".$key."&route=".$route."&variables_values=".$otp."&language=".$language."&flash=".$flash."&numbers=".$numbers;
// 	$ch =   curl_init('https://www.fast2sms.com/dev/bulkV2?'.$data);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
// 	$response = curl_exec($ch);
// 	curl_close($ch);
// 	$user = User::where('mobile','=',$mobile)->update(['otp' => $otp]);
// 	return $response;
// 	return response()->json([$user],200);

// 		return 1;
// 	}
    private function sendOtp($mobile,$otp){
	$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://login.99smsservice.com/sms/api?action=send-sms&api_key=TkFNS0p4SUlQR3l2bkZPZXBwdHg%3D&to='.$mobile.'&from=SANGHC&sms=Dear%20User%2C%0A%0AYour%20OTP%20is%20'.$otp.'.%20Valid%20for%2010%20minutes.%20Please%20do%20not%20share%20this%20OTP.%0A%0ARegards%0AAK%20ADDA%0A%0A%0ATK%20IND.&p_entity_id=1201162643300643505&temp_id=1207169726695274252',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Cookie: laravel_session=eyJpdiI6IjBmWmZBOUhVbjVld1VCS04wRk9pdEE9PSIsInZhbHVlIjoiSmxcL2JVaHZTWWVlYWd6YzdvQXRkMEkwT0hpdG5rbUpNQld4YzlXQml5Q3RkOWYySUU1THlHTVByd1VjYkhyS0NGS2FcL0hPQzkxa3hBbDdFV3owOVdQZz09IiwibWFjIjoiZWZkYWE0ZGY3YWY0N2E3YjJjYTZjYTBjMTkwOGI0OTVlMDMxOWM5MTIxNmYzMmY3Y2E5YTFjNWQ5ODY2Yjk2MSJ9'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
	$user = User::where('mobile','=',$mobile)->update(['otp' => $otp]);
	return $response;
	return response()->json([$user],200);

		return 1;
	}

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('error','You entered Wrong credentials!');
    }

}
