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
		<div class="cxy flex-column mx-5 mt-5">
			<picture class="ml-4" style="width: 80%; height: auto;"><img src="{{asset('frontend/images/kyc-in-process.png')}}" alt="" style="max-width: 100%;"></picture>
			<div class="font-11 mt-4">Your KYC is in <span class="text-danger">Validation</span></div>
			<div class="my-3 text-center" style="width: 100%;">
				<div class="footer-text" style="font-size: 0.9em;">We are verifying your details. You will be notified when your KYC is completed.</div>
			</div>
		</div>
	</div>





@endsection
