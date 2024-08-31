@extends('layouts.front.front')

@section('head')
<style>
	.overlayState {
		pointer-events: auto;
		opacity: 0.87;
	}

	.popupState {}

</style>
<title>Play Games Online and Earn Money | Ludo, Cricket, Chess, Carrom &amp;amp; Many More Game</title>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

@endsection



@section('content')

	<div class="main-area" style="padding-top:60px">
		<div class="kycPage">
			<form action="{{ url('/complete-kyc/save-step-2') }}" method="post" autocomplete="off">
				@csrf
				<div><span style="font-size: 1.5em;">Step 2</span> of 3</div>
				<p class="mt-2" style="color: rgb(149, 149, 149);">Enter details of {{ $user_data_details->DOCUMENT_NAME }}: <span style="font-weight: 500;">{{ $user_data_details->DOCUMENT_NUMBER }}</span></p><br>
				<div style="margin-top: 10px;">
					<div class="kyc-doc-input"><input type="text" name="firstName" id="firstName" required autocomplete="off">
						<div class="label">First Name</div>
					</div><br>
					<div class="kyc-doc-input mt-4"><input type="text" name="lastName" id="lastName"  required autocomplete="off">
						<div class="label">Last Name</div>
					</div><br>
					<div class="kyc-doc-input mt-4">
						<div class="label">Date of Birth</div><input id="dob" name="dob" max="2004-10-04" label="Date of Birth" type="date" class="input-date" required>
					</div>
					<div style="margin-top: 50px;"><span style="color: rgb(149, 149, 149); font-weight: 500;">State</span>
						<!--	<div style="margin-top: 50px;" onclick="addStatePopup()"><span style="color: rgb(149, 149, 149); font-weight: 500;">State</span>
					
						<div class="kyc-input-text" >Select State</div>
						<div class="arrow cxy"><img src="{{asset('frontend/images/global-black-chevronDown.png')}}" width="15px" alt=""></div>-->
						<select name="state" id="state" class="form-control" required style="height:55px; font-weight:bold">
							<option value="">Select One</option>
							<option>Arunachal Pradesh</option>
							<option>Bihar</option>
							<option>Chhattisgarh</option>
							<option>Goa</option>
							<option>Gujarat</option>
							<option>Haryana</option>
							<option>Himachal Pradesh</option>
							<option>Jammu and Kashmir</option>
							<option>Jharkhand</option>
							<option>Kerala</option>
							<option>Karnataka</option>
							<option>Madhya Pradesh</option>
							<option>Maharashtra</option>
							<option>Manipur</option>
							<option>Mizoram</option>
							<option>Punjab</option>
							<option>Rajasthan</option>
							<option>Tripura</option>
							<option>Tamil Nadu</option>
							<option>Uttarakhand</option>
							<option>Uttar Pradesh</option>
							<option>West Bengal</option>
							<option>Andaman and Nicobar Islands</option>
							<option>Chandigarh</option>
							<option>Dadra and Nagar Haveli</option>
							<option>Daman and Diu</option>
							<option>Delhi</option>
							<option>Lakshadweep</option>
							<option>Puducherry</option>
						</select>

					</div>
					<!--	<div class="kyc-select">
					<div class="overlayState overlay " id="OverlayStateID" onclick="removeStatePopup()"  ></div>
					<div class=" popupState box " id="PopupStateID"  >
						<div class="bg-white">
							<div class="header">
								<div class="d-flex position-relative align-items-center">
									<div class="header-text">Select State</div>
								</div>
							</div>
							<div style="padding-top: 80px;">
								<div class="option d-flex align-items-center">
									<div class="option-text">Arunachal Pradesh</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Bihar</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Chhattisgarh</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Goa</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Gujarat</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Haryana</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Himachal Pradesh</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Jammu and Kashmir</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Jharkhand</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Kerala</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Karnataka</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Madhya Pradesh</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Maharashtra</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Manipur</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Mizoram</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Punjab</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Rajasthan</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Tripura</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Tamil Nadu</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Uttarakhand</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Uttar Pradesh</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">West Bengal</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Andaman and Nicobar Islands</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Chandigarh</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Dadra and Nagar Haveli</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Daman and Diu</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Delhi</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Lakshadweep</div>
								</div>
								<div class="option d-flex align-items-center">
									<div class="option-text">Puducherry</div>
								</div>
							</div>
						</div>
					</div>
				</div>-->
				</div>
				<div style="padding-bottom: 80px;"></div>
				<div class="refer-footer"><!--<a href="{{ url('/complete-kyc/step1') }}" class="refer-button mr-2 cxy w-100 bg-secondary">Previous</a>
-->
					<input type="submit" class="refer-button cxy w-100 bg-primary" name="next2" value="Next">
				</div>
			</form>
		</div>
	</div>



	<script>
		/*$(document).ready(function()
 {
	
 });*/
		/*function removeStatePopup(){
			$('.popupState').hide();
			$('.overlayState').hide();
		}
		function addStatePopup(){
			 $("#PopupStateID").css({"top":"-240px", "bottom":"0px", "position":"absolute", "display":"block"});
		      $("#OverlayStateID").css({"pointer-events":"auto", "opacity":"0.87"});
			$('.box').show();
			$('.overlay').show();
			
		}*/

	</script>






@endsection
