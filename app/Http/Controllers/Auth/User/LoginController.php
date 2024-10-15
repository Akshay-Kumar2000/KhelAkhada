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

use Illuminate\Support\Str; // To generate a new session ID
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

    // this is the previous login function in which user can be able to login via multiple devices and browsers at the same time

    // public function doLoginUser(Request $request){
    //     $this->validate($request, [
    //         'mobile' => 'required|numeric',
    //     ]);

    //     $isResend = $request->has('resend_otp') && $request->resend_otp == true;

    //     try {
    //         $mobile = $request->mobile;
    //         $otp = $request->otp;
    //         $newOtp = rand(100000,999999);
    //         $userData = User::where('mobile', $mobile)->first();

    //         if (isset($userData)) {
    //             if ($userData->status == 0) {
    //                 return response([
    //                     'status' => 0,
    //                     'message' => "This User is Blocked"
    //                 ], 400);
    //             }

    //             if ($otp) {
    //                 if ($userData->otp != $otp) {
    //                     return response()->json([
    //                         'status' => 0,
    //                         'message' => 'Invalid OTP!'
    //                     ], 400);
    //                 }
    //                 Auth::login($userData);
    //                 $userData->otp = 0;
    //                 $userData->save();

    //                 // Check if user_settings record exists and update if necessary
    //                 $user_setting = UserSetting::where('user_id', $userData->id)->first();
    //                 if (!$user_setting) {
    //                     $user_setting = new UserSetting;
    //                     $user_setting->user_id = $userData->id;
    //                     $user_setting->status = 1;
    //                     $user_setting->referral = bin2hex(random_bytes(4));
    //                     $user_setting->save();
    //                 } else {
    //                     // Update existing record if necessary
    //                     $user_setting->status = 1;
    //                     $user_setting->save();
    //                 }

    //                 return response()->json([
    //                     'status' => 2,
    //                     'url' => url('/')
    //                 ], 200);
    //             }

    //             if (!$isResend) {
    //                 $userData->otp = $newOtp;
    //                 $userData->save();
    //                 $this->sendOtp($mobile, $newOtp);

    //                 return response()->json([
    //                     'status' => 1,
    //                     'message' => 'OTP sent successfully!'
    //                 ], 200);
    //             }

    //             $this->sendOtp($mobile, $newOtp);

    //             return response()->json([
    //                 'status' => 1,
    //                 'message' => 'OTP resent successfully!'
    //             ], 200);
    //         } else {
    //             $user = User::create([
    //                 'username' => ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)).ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)),
    //                 'mobile' => $mobile,
    //                 'otp' => $newOtp,
    //                 'ip' => $request->ip()
    //             ]);
    //             $user_id = $user->id;
    //             if ($request->referral) {
    //                 $settings = UserSetting::where('user_id', $user_id)->first();
    //                 $rf_user_data = UserSetting::where('referral', $request->referral)->first();
    //                 if (empty($settings)) {
    //                     $settings = new UserSetting;
    //                     $settings->user_id = $user_id;
    //                     $settings->status = 1;
    //                     $settings->referral = bin2hex(random_bytes(4));
    //                     $settings->save();
    //                 }
    //                 $rf_user = User::where('id', $rf_user_data->user_id)->first();
    //                 if (isset($rf_user)) {
    //                     if ($rf_user->status == 0) {
    //                         return response([
    //                             'status' => 0,
    //                             'message' => "This User is Blocked"
    //                         ], 400);
    //                     }
    //                     $rf_user->earnings += 50;
    //                     $rf_user->save();
    //                 }
    //             }
    //             $this->sendOtp($mobile, $newOtp);

    //             return response()->json([
    //                 'status' => 1,
    //                 'message' => 'OTP sent successfully!'
    //             ], 200);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 0,
    //             'message' => 'An error occurred: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }



    public function doLoginUser(Request $request)
    {
        // Validate the mobile number input
        $this->validate($request, [
            'mobile' => 'required|numeric',
        ]);

        $isResend = $request->has('resend_otp') && $request->resend_otp == true;

        try {
            $mobile = $request->mobile;
            $otp = $request->otp;
            $newOtp = rand(100000, 999999);
            $userData = User::where('mobile', $mobile)->first();

            // Check if user exists
            if (isset($userData)) {
                // Check if the user is blocked
                if ($userData->status == 0) {
                    return response()->json([
                        'status' => 0,
                        'message' => "This User is Blocked"
                    ], 400);
                }

                // Handle OTP verification
                if ($otp) {
                    if ($userData->otp != $otp) {
                        return response()->json([
                            'status' => 0,
                            'message' => 'Invalid OTP!'
                        ], 400);
                    }

                    // Check if the user is already logged in from another browser or device
                    if ($userData->session_id && $userData->session_id !== session()->getId()) {
                        return response()->json([
                            'status' => 0,
                            'message' => 'You are already logged in from another device or browser.'
                        ], 400);
                    }

                    // Generate a new session ID and store it in the database
                    $sessionId = session()->getId();
                    $userData->session_id = $sessionId;
                    $userData->otp = 0; // Clear OTP after successful login
                    $userData->save();

                    // Log the user in
                    Auth::login($userData);

                    // Check if user_settings record exists and update if necessary
                    $user_setting = UserSetting::where('user_id', $userData->id)->first();
                    if (!$user_setting) {
                        $user_setting = new UserSetting;
                        $user_setting->user_id = $userData->id;
                        $user_setting->status = 1;
                        $user_setting->referral = bin2hex(random_bytes(4));
                        $user_setting->save();
                    } else {
                        $user_setting->status = 1;
                        $user_setting->save();
                    }

                    return response()->json([
                        'status' => 2,
                        'url' => url('/')
                    ], 200);
                }

                // Handle OTP resend
                if (!$isResend) {
                    $userData->otp = $newOtp;
                    $userData->save();
                    $this->sendOtp($mobile, $newOtp);

                    return response()->json([
                        'status' => 1,
                        'message' => 'OTP sent successfully!'
                    ], 200);
                }

                $this->sendOtp($mobile, $newOtp);

                return response()->json([
                    'status' => 1,
                    'message' => 'OTP resent successfully!'
                ], 200);
            } else {
                // Handle new user registration
                $user = User::create([
                    'username' => ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)) . ucfirst(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 4)),
                    'mobile' => $mobile,
                    'otp' => $newOtp,
                    'ip' => $request->ip(),
                    'session_id' => session()->getId(), // Store the new session ID for the user
                ]);

                // Handle referral logic
                if ($request->referral) {
                    $settings = UserSetting::where('user_id', $user->id)->first();
                    $rf_user_data = UserSetting::where('referral', $request->referral)->first();
                    if (empty($settings)) {
                        $settings = new UserSetting;
                        $settings->user_id = $user->id;
                        $settings->status = 1;
                        $settings->referral = bin2hex(random_bytes(4));
                        $settings->save();
                    }
                    $rf_user = User::where('id', $rf_user_data->user_id)->first();
                    if (isset($rf_user)) {
                        if ($rf_user->status == 0) {
                            return response()->json([
                                'status' => 0,
                                'message' => "This User is Blocked"
                            ], 400);
                        }
                        $rf_user->earnings += 50;
                        $rf_user->save();
                    }
                }

                // Send OTP to the new user
                $this->sendOtp($mobile, $newOtp);

                return response()->json([
                    'status' => 1,
                    'message' => 'OTP sent successfully!'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
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
    private function sendOtp($mobile, $otp) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://login.99smsservice.com/sms/api?action=send-sms&api_key=ckpDTUp6cnVPcnZhQUdvQ2ppTz0%3D&to='.$mobile.'&from=HARIOK&sms=Dear%20User%0AYour%20OTP%20is%20'.$otp.'%20Valid%20for%2010%20minutes.%20Please%20do%20not%20share%20this%20OTP.%0ARegards%0AAKADDA%0Ahariom&p_entity_id=1101778010000075980&temp_id=1107171109383082610',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Cookie: laravel_session=eyJpdiI6ImJDZVwvMitEekNiZFFkcUVqOTBocm53PT0iLCJ2YWx1ZSI6IlVWMUtvWEZiOHZUNit5OXQ5Y01HajVOUHlQU3J1Y1BlXC9aNkkrb0dpQ3NhUjk5RlM0c3UxcmNlZ0xBOHA0VnhUcndMS2Q3WVBMSTZBUzZtTXhFSVNsUT09IiwibWFjIjoiODYwYzEyMDQyODA4ZGExMTQ0NjNmZjg3ZjRlZWFlMGQ2NmEzZDdkYzAyZjIzYWU0NDlhNTAzZThiNjRkNDUxZSJ9'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // Log the response from the SMS gateway for debugging
        // \Log::info('SMS API Response: ' . $response);

        // Update OTP in the database
        $user = User::where('mobile', '=', $mobile)->update(['otp' => $otp]);

        return $response;
    }

    public function logout()
{
    $user = Auth::user();

    if ($user) {
        // Clear the session ID
        $user->session_id = null;
        $user->save();
    }

    // Perform logout and destroy session
    Auth::logout();
    Session::flush();

    return redirect()->route('login')->with('message', 'Logged out successfully!');
}



    // public function logout(){
    //     Auth::logout();
    //     return redirect()->route('login')->with('error','You entered Wrong credentials!');
    // }

}
