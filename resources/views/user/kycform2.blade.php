@extends('layouts.master')

@section('head')
<title>Play Games Online and Earn Money | Ludo, Cricket, Chess, Carrom &amp;amp; Many More Game</title>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
@endsection



@section('content')
<div class="main-area" style="padding-top: 60px;">

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
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER" id="inpf1" autocomplete="off" value="" >
						<div class="label">Enter Aadhaar Card Number</div>
					</div>
				</div>
				<div style="margin-top: 50px;" class="DLtext">
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER" id="inpf2" autocomplete="off" value="">
						<div class="label">Enter DL Number</div>
					</div>
				</div>
				<div style="margin-top: 50px;" class="VIDtext">
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER" id="inpf3" autocomplete="off" value="">
						<div class="label">Enter Voter ID Number</div>
					</div>
				</div>
				<!--	<div class="kyc-input-text">Select Document</div>
				<div class="arrow cxy"><img src="{{asset('frontend/images/global-black-chevronDown.png')}}" width="15px" alt=""></div>-->
			</div>

			<div style="padding-bottom: 80px;"></div>
			<div class="refer-footer"><button type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" name="next1" value="1" disabled>NEXT</button></div>
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
		if ((textBox >= 1)) {
			$('.disabledButton').prop("disabled", false);
		} else {
			$('.disabledButton').prop("disabled", true);
		}
	});
	
	 $('#inpf2').keyup(function() {
		var textBox = $('#inpf2').val() * 1;
		if ((textBox >= 1)) {
			$('.disabledButton').prop("disabled", false);
		} else {
			$('.disabledButton').prop("disabled", true);
		}
	});
	
	 $('#inpf3').keyup(function() {
		var textBox = $('#inpf3').val() * 1;
		if ((textBox >= 1)) {
			$('.disabledButton').prop("disabled", false);
		} else {
			$('.disabledButton').prop("disabled", true);
		}
	});
</script>
	

</div>

@endsection
