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
			<div><span style="font-size: 1.5em;">Step 3</span> of 3</div>
			<p class="mt-2" style="color: rgb(149, 149, 149);">Upload photo of {{ $user_data_details->DOCUMENT_NAME }}: <span style="font-weight: 500;">{{ $user_data_details->DOCUMENT_NUMBER }}</span></p><br>
			<div style="margin-top: 10px;">
				<div class="mytext" style="font-size: 1.1em;">Ensure that your <span style="font-weight: 700;">name</span>, <span style="font-weight: 700;">birthdate</span> and <span style="font-weight: 700;">document number</span> are clearly visible in the document photo.</div>
			</div>
			<form method="post" action="{{ url('/complete-kyc/save-step3') }}" enctype="multipart/form-data">
				@csrf
				<br>
				<div style="border:1px dotted grey; border-radius:3px ">
				    <label style="font-weight:900;">आधार कार्ड का अगला भाग अपलोड करें</label>
				<input type="file" name="frontPic" class="form-control" required style="padding:41px; background-color:#f8f8f8">
			
				</div>
				<br>
				<div style="border:1px dotted grey; border-radius:3px ">
				    <label style="font-weight:900;">आधार कार्ड का पिछला भाग अपलोड करें</label>
				<input type="file" name="backPic" class="form-control" required style="padding:41px; background-color:#f8f8f8">
				
				</div>
				<div style="padding-bottom: 80px;"></div>
				<div class="refer-footer"><!--<a href="{{url('complete-kyc/step2')}}" class="refer-button mr-2 cxy w-100 bg-secondary">Previous</a>--><input type="submit" class="refer-button cxy w-100 bg-primary" name="complete" value="Complete"></div>
			</form>
		</div>
	</div>








@endsection
