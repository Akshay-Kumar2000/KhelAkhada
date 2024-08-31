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
<div class="main-area" style="padding-top: 60px;">
	@if(is_null($user_data_details->DOCUMENT_NAME) && is_null($user_data_details->DOCUMENT_NUMBER))
	<div class="kycPage">
		<div><span style="font-size: 1.5em;">Step 1</span> of 3</div>
		<p class="mt-2" style="color: rgb(149, 149, 149);">You need to submit a document that shows that you are <span style="font-weight: 500;">above 18 years</span> of age and not a resident of <span style="font-weight: 500;">Assam, Odisha, Sikkim, Nagaland, Telangana, Andhra Pradesh, Tamil Nadu and Karnataka.</span></p><br>
		<form action="{{ url('/complete-kyc') }}" method="post">
			@csrf
			<div><span style="color: rgb(149, 149, 149); font-weight: 500;">Document Type</span>

				<select name="DOCUMENT_NAME" id="" class="kyc-input-text form-control kyc-input mt-2" style="width:100%; padding:10px;" onchange="showOtherOptions(this.value)">
					<option value="" style="">Select Document</option>
					<option value="UID" style="font-size:15px; ">Adhar Card</option>
					<option value="DL" style="font-size:15px; ">Driving Licence</option>
					<option value="VID" style="font-size:15px; ">Voter ID</option>
				</select>

				<div style="margin-top: 50px;" class="UIDtext">
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER_UID" id="inpf1" autocomplete="off" >
						<div class="label">Enter Aadhaar Card Number</div>
					</div>
				</div>
				<div style="margin-top: 50px;" class="DLtext">
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER_DL" id="inpf2" autocomplete="off" >
						<div class="label">Enter DL Number</div>
					</div>
				</div>
				<div style="margin-top: 50px;" class="VIDtext">
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER_VID" id="inpf3" autocomplete="off" >
						<div class="label">Enter Voter ID Number</div>
					</div>
				</div>
				<!--	<div class="kyc-input-text">Select Document</div>
				<div class="arrow cxy"><img src="{{asset('frontend/images/global-black-chevronDown.png')}}" width="15px" alt=""></div> -->
			</div>

			<div style="padding-bottom: 80px;"></div>
			<div class="refer-footer"><!--<button type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" name="next1"  disabled>NEXT</button>-->
			<input type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" name="next1"   value="NEXT">
			</div>
		</form>

	</div>


	<script>
		$('.UIDtext').hide();
		$('.DLtext').hide();
		$('.VIDtext').hide();

		function showOtherOptions(val) {
			if (val === "UID") {
				$('.UIDtext').show();
				$('.DLtext').hide();
				$('.VIDtext').hide();
			}
			if (val === "DL") {
				$('.UIDtext').hide();
				$('.DLtext').show();
				$('.VIDtext').hide();
			}
			if (val === "VID") {
				$('.UIDtext').hide();
				$('.DLtext').hide();
				$('.VIDtext').show();
			}

		}

		$('#inpf1').keyup(function() {
			var textBox = $('#inpf1').val() * 1;
			if ((textBox >= 11)) {
				$('.disabledButton').prop("disabled", false);
			} else {
				$('.disabledButton').prop("disabled", true);
			}
		});

		$('#inpf2').keyup(function() {
			var textBox = $('#inpf2').val() * 1;
			if ((textBox >= 11)) {
				$('.disabledButton').prop("disabled", false);
			} else {
				$('.disabledButton').prop("disabled", true);
			}
		});

		$('#inpf3').keyup(function() {
			var textBox = $('#inpf3').val() * 1;
			if ((textBox >= 11)) {
				$('.disabledButton').prop("disabled", false);
			} else {
				$('.disabledButton').prop("disabled", true);
			}
		});

	</script>


	@elseif(is_null($user_data_details->DOCUMENT_FIRST_NAME) && !is_null($user_data_details->DOCUMENT_NAME) )





	<div class="main-area">
		<div class="kycPage">
			<form action="{{ url('/complete-kyc') }}" method="post" autocomplete="off">
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
				<div class="refer-footer"><button class="refer-button mr-2 cxy w-100 bg-secondary">Previous</button>

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





	@elseif(is_null($user_data_details->DOCUMENT_FRONT_IMAGE) && !is_null($user_data_details->DOCUMENT_FIRST_NAME) && !is_null($user_data_details->DOCUMENT_NAME))


	<div class="main-area" style="padding-top: 0px;">
		<div class="kycPage">
			<div><span style="font-size: 1.5em;">Step 3</span> of 3</div>
			<p class="mt-2" style="color: rgb(149, 149, 149);">Upload photo of {{ $user_data_details->DOCUMENT_NAME }}: <span style="font-weight: 500;">{{ $user_data_details->DOCUMENT_NUMBER }}</span></p><br>
			<div style="margin-top: 10px;">
				<div class="mytext" style="font-size: 1.1em;">Ensure that your <span style="font-weight: 700;">name</span>, <span style="font-weight: 700;">birthdate</span> and <span style="font-weight: 700;">document number</span> are clearly visible in the document photo.</div>
			</div>
			<form method="post" action="{{ url('/complete-kyc') }}" enctype="multipart/form-data">
				@csrf
				<br>
				<div style="border:1px dotted grey; border-radius:3px ">
				<input type="file" name="frontPic" class="form-control" required style="padding:41px; background-color:#f8f8f8">
			
				</div>
				<br>
				<div style="border:1px dotted grey; border-radius:3px ">
				<input type="file" name="backPic" class="form-control" required style="padding:41px; background-color:#f8f8f8">
				
				</div>
				<div style="padding-bottom: 80px;"></div>
				<div class="refer-footer"><button class="refer-button mr-2 cxy w-100 bg-secondary">Previous</button><input type="submit" class="refer-button cxy w-100 bg-primary" name="complete" value="Complete"></div>
			</form>
		</div>
	</div>






@else
	<div class="main-area" style="padding-top: 0px;">
		<div class="cxy flex-column mx-5 mt-5">
			<picture class="ml-4" style="width: 80%; height: auto;"><img src="{{asset('frontend/images/kyc-in-process.png')}}" alt="" style="max-width: 100%;"></picture>
			<div class="font-11 mt-4">Your KYC is in <span class="text-danger">Validation</span></div>
			<div class="my-3 text-center" style="width: 100%;">
				<div class="footer-text" style="font-size: 0.9em;">We are verifying your details. You will be notified when your KYC is completed.</div>
			</div>
		</div>
	</div>


	





	@endif
</div>

@endsection
