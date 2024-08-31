<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\UserData;
use Session;
use Illuminate\Support\Facades\Http;
class KycController
{

	public function step1(){
		$user_id = Auth::user()->id;
		$TokenAUth = $this->AuthDeepvue();
        $DataTokenSession = $TokenAUth->data->session_id;
        $DataTokenCaptch = $this->base64_to_jpeg($TokenAUth->data->captcha);
		return view('user.kyc_new',compact('DataTokenSession','DataTokenCaptch'));
	}
	private function AuthDeepvue(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://production.deepvue.tech/v1/ekyc/aadhaar/connect?consent=Y&purpose=For%20KYC',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'x-api-key: 0fad5b8a34504ec6938da5bc4eeccf35',
            'client-id: free_tier_khanaasaf69_ab971a2871'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    private function APIHitDeepvue($url){
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "data":"adarsh"
}',
  CURLOPT_HTTPHEADER => array(
    'x-api-key: 0fad5b8a34504ec6938da5bc4eeccf35',
    'client-id: free_tier_khanaasaf69_ab971a2871',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    private function base64_to_jpeg($base64String) {
      // Decode the Base64 string
      $decodedString = base64_decode($base64String);

      // Get the image type (e.g. image/jpeg, image/png, etc.)
      $imageType = '';
      if (strpos($base64String, 'data:') === 0) {
        $parts = explode(',', $base64String);
        if (count($parts) > 1) {
          $header = $parts[0];
          $imageType = str_replace('data:', '', $header);
        }
      }

      // Create a temporary file for the image
      $tmpFile = tempnam(sys_get_temp_dir(), 'image');
      file_put_contents($tmpFile, $decodedString);

      // Get the image data URL
      $imageDataUrl = 'data:' . $imageType . ';base64,' . $base64String;

      // Remove the temporary file
      unlink($tmpFile);

      return $imageDataUrl;
    }
	public function check_aadhar(Request $request){
        $user_id = Auth::user()->id;
	    if(isset($request->ref_id)){
	        $sessionIDDD = $request->ref_id;
	        $uSEROtp = $request->otp;
	        $url = "https://production.deepvue.tech/v1/ekyc/aadhaar/verify-otp?otp=$uSEROtp&session_id=$sessionIDDD&consent=Y&purpose=For%20KYC";
            $lastdata = $this->APIHitDeepvue($url);
            // $curl = curl_init();
            // curl_setopt_array($curl, [
            //   CURLOPT_URL => "https://mothersolution.in/api/aadhar_otp_verify",
            //   CURLOPT_RETURNTRANSFER => true,
            //   CURLOPT_ENCODING => "",
            //   CURLOPT_MAXREDIRS => 10,
            //   CURLOPT_TIMEOUT => 30,
            //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //   CURLOPT_CUSTOMREQUEST => "POST",
            //   CURLOPT_POSTFIELDS => "-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"otp\"\r\n\r\n$request->otp\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"ref_id\"\r\n\r\n$request->ref_id\r\n-----011000010111000001101001\r\nContent-Disposition: form-data; name=\"auth\"\r\n\r\n$request->auth\r\n-----011000010111000001101001--\r\n",
            //   CURLOPT_HTTPHEADER => [
            //     "Accept: */*",
            //     "User-Agent: Thunder Client (https://www.thunderclient.com)",
            //     "content-type: multipart/form-data; boundary=---011000010111000001101001"
            //   ],
            // ]);
            // $response = curl_exec($curl);
            // $err = curl_error($curl);
            // curl_close($curl);
            // $lastdata = json_decode($response);
            //     return $lastdata;
            if($lastdata->code == 200){
		        $user_data_details = UserData::where('user_id', $user_id)->first();
		        $user_data_details->DOCUMENT_FIRST_NAME =  $lastdata->data->name;
                $user_data_details->DOCUMENT_DOB =  $lastdata->data->dateOfBirth;
                $user_data_details->verify_status =  1;
                $user_data_details->DOCUMENT_STATE =
                $lastdata->data->address->street
                .', '.$lastdata->data->address->vtc
                .', '.$lastdata->data->address->postOffice
                .', '.$lastdata->data->address->subDistrict
                .', '.$lastdata->data->address->district
                .', '.$lastdata->data->address->state
                .', '.$lastdata->data->address->country
                .', '.$lastdata->data->address->pin;
             $user_data_details->save();
    	     return redirect('/complete-kyc/approve')->with('success',"Aadhar card Registered");
            }
    	    return back()->with('error',"Something wents wrong!");
	    }else{
	        $DataTokenCaptch = $request->captcha;
	        $DataTokenSession = $request->sessionid;
    	    $document_name = "UID";
    	    $document_number = $request->aadhar;
    	    $exist = UserData::where('DOCUMENT_NUMBER', $document_number)->where('verify_status', 1)->count();
    	    if($exist > 0){
    	        return back()->with('error',"Aadhar card already exist");
    	    }

		    $user_data_details = UserData::where('user_id', $user_id)->first();
		    if($user_data_details){
		    $user_data_details->DOCUMENT_NAME =  $document_name;
            $user_data_details->DOCUMENT_NUMBER =  $document_number;
		    }else{
		        $user_data_details = new UserData;
		        $user_data_details->user_id = $user_id;
		        $user_data_details->DOCUMENT_NAME =  $document_name;
                $user_data_details->DOCUMENT_NUMBER =  $document_number;
		    }
            $url = "https://production.deepvue.tech/v1/ekyc/aadhaar/generate-otp?aadhaar_number=$document_number&captcha=$DataTokenCaptch&session_id=$DataTokenSession&consent=Y&purpose=For%20KYC";
            $resposne = $this->APIHitDeepvue($url);
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //   CURLOPT_URL => 'https://mothersolution.in/api/aadhar_verify',
            //   CURLOPT_RETURNTRANSFER => true,
            //   CURLOPT_ENCODING => '',
            //   CURLOPT_MAXREDIRS => 10,
            //   CURLOPT_TIMEOUT => 0,
            //   CURLOPT_FOLLOWLOCATION => true,
            //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //   CURLOPT_CUSTOMREQUEST => 'POST',
            //   CURLOPT_POSTFIELDS =>'{
            //       "aadhaar_number":'.$document_number.',
            //       "userid":"MP15751"
            //   }',
            //   CURLOPT_HTTPHEADER => array(
            //     'Content-Type: application/json'
            //   ),
            // ));

            // $response = curl_exec($curl);
            // $err = curl_error($curl);

            // curl_close($curl);
            // $data = json_decode($response);
            // return $resposne;
            if(isset($resposne->code) && $resposne->code == 200 && $resposne->sub_code == "SUCCESS"){
               $ref_id = $DataTokenSession;
               $user_data_details->save();
               $user_data_details->ref_id =  $ref_id;
               return back()->with('otp',$ref_id);
            }
    	    return back()->with('error',$resposne->message);
	    }
    }
	public function saveStep1(Request $request){
         $user_id = Auth::user()->id;
    	 $document_name = "UID";
    	 $document_number = $request->DOCUMENT_NUMBER;
    	 $exist = UserData::where('DOCUMENT_NUMBER', $document_number)->where('verify_status', 1)->count();
    	 if($exist > 0){
    	     return back()->with('error',"Aadhar card already exist");
    	 }
		 if ($request->hasFile('frontPic')) {
          $frontPic = $request->file('frontPic');
          $frontPic_name = time().'frontpic.'.$frontPic->getClientOriginalExtension();
          $destinationPath = public_path('/images/kycdata/'.$user_id.'/');
          $frontPic->move($destinationPath, $frontPic_name);
        }
       if ($request->hasFile('backPic')) {
          $backPic = $request->file('backPic');
          $backPic_name = time().'backPic.'.$backPic->getClientOriginalExtension();
          $destinationPath = public_path('/images/kycdata/'.$user_id.'/');
          $backPic->move($destinationPath, $backPic_name);
       }
		 $user_data_details = UserData::where('user_id', $user_id)->first();
		 $user_data_details->DOCUMENT_FIRST_NAME =  $request->fname;
		 $user_data_details->DOCUMENT_LAST_NAME =  $request->lname;
		 $user_data_details->DOCUMENT_NAME =  $document_name;
         $user_data_details->DOCUMENT_NUMBER =  $document_number;
         $user_data_details->DOCUMENT_FRONT_IMAGE = $frontPic_name;
         $user_data_details->DOCUMENT_BACK_IMAGE = $backPic_name;
         $user_data_details->verify_status = 2;
         if($user_data_details->save()){
            return redirect('/complete-kyc/kyc-submit');
         }
    	 return back()->with('error',"Something wents wrong");
    }

	public function step2(Request $request){

		 $user_id = Auth::user()->id;
    	 $user_data_details = UserData::where('user_id', $user_id)->first();

		return view('user.kyc_step2', compact('user_data_details'));

	}

   public function saveStep2(Request $request){

       $user_id = Auth::user()->id;

       $firstName = $request->firstName;
       $lastName = $request->lastName;
       $dob = $request->dob;
       $state = $request->state;

       $user_data_details = UserData::where('user_id', $user_id)->first();
       $user_data_details->DOCUMENT_FIRST_NAME = $firstName;
       $user_data_details->DOCUMENT_LAST_NAME = $lastName;
       $user_data_details->DOCUMENT_DOB = $dob;
       $user_data_details->DOCUMENT_STATE = $state;
       $user_data_details->save();
		//dd($user_data_details);
       return redirect('/complete-kyc/step3');
    }

   public function step3(Request $request){
		$user_id = Auth::user()->id;
		$user_data_details = UserData::where('user_id', $user_id)->first();
    	return view('user.kyc_step3', compact('user_data_details'));
	}

 	public function saveStep3(Request $request){
       $user_id = Auth::user()->id;
	   if ($request->hasFile('frontPic')) {
          $frontPic = $request->file('frontPic');
          $frontPic_name = time().'frontpic.'.$frontPic->getClientOriginalExtension();
          $destinationPath = public_path('/images/kycdata/'.$user_id.'/');
          $frontPic->move($destinationPath, $frontPic_name);
        }
       if ($request->hasFile('backPic')) {
          $backPic = $request->file('backPic');
          $backPic_name = time().'backPic.'.$backPic->getClientOriginalExtension();
          $destinationPath = public_path('/images/kycdata/'.$user_id.'/');
          $backPic->move($destinationPath, $backPic_name);
       }

      $user_data_details = UserData::where('user_id', $user_id)->first();
      $user_data_details->DOCUMENT_FRONT_IMAGE = $frontPic_name;
      $user_data_details->DOCUMENT_BACK_IMAGE = $backPic_name;
      $user_data_details->verify_status = 0;
      $user_data_details->save();

     // dd($user_data_details);
      return redirect('/complete-kyc/kyc-submit');
    }

    public function kyc_submit(Request $request){
	    return view('user.kyc_submit');
	}
    public function kyc_approve(Request $request){
        $user_id = Auth::user()->id;
        $data = UserData::where('user_id', $user_id)->first();
	    return view('user.kyc_approve',compact('data'));
	}

}
