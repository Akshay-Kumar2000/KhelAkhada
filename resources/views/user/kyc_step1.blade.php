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
	<div class="kycPage">
		<div><span style="font-size: 1.5em;">Step 1</span> of 3</div>
		<p class="mt-2" style="color: rgb(149, 149, 149);">You need to submit a document that shows that you are <span style="font-weight: 500;">above 18 years</span> of age and not a resident of <span style="font-weight: 500;">Assam, Odisha, Sikkim, Nagaland, Telangana, Andhra Pradesh, Tamil Nadu and Karnataka.</span></p><br>
		<form action="#" method="post">
			@csrf
			<div><span style="color: rgb(149, 149, 149); font-weight: 500;">Document Type</span>

				<select name="DOCUMENT_NAME" id="target" class="kyc-input-text form-control kyc-input mt-2" style="width:100%; padding:10px;">
					<!--onchange="showOtherOptions(this.value)"-->
					<option value="" style="">Select Document</option>
					<option value="UID" style="font-size:15px; ">Adhar Card</option>
					<option value="DL" style="font-size:15px; ">Driving Licence</option>
					<option value="VID" style="font-size:15px; ">Voter ID</option>
				</select>
			</div>

			<!--<div style="padding-bottom: 80px;"></div>
			<div class="refer-footer">
				<input type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" name="next1" value="NEXT" id="next" disabled>
			</div>-->

		</form>

		<div id="UID" class="inv">
			<form action="{{ url('/complete-kyc/save-step-1') }}" method="post">
				@csrf
				<input type="hidden" name="DOCUMENT_NAME" value="UID">
				<div style="margin-top: 50px;" class="UIDtext">
					<div class="kyc-doc-input">
						<input type="number" name="DOCUMENT_NUMBER" autocomplete="off" id="inpf1" minlength="12" required>
						<div class="label">Enter Aadhaar Card Number</div>
					</div>
				</div>
				<div style="padding-bottom: 80px;"></div>
				<div class="refer-footer">
					<input type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" value="NEXT"  id="next">
				</div>
			</form>
		</div>

		<div id="DL" class="inv">
			<form action="{{ url('/complete-kyc/save-step-1') }}" method="post">
			  <input type="hidden" name="DOCUMENT_NAME" value="DL">
				@csrf
				<div style="margin-top: 50px;" class="DLtext">
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER" id="inpf2" autocomplete="off" minlength="12" required>
						<div class="label">Enter DL Number</div>
					</div>
				</div>
				<div style="padding-bottom: 80px;"></div>
				<div class="refer-footer">
					<input type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" value="NEXT" id="next">
				</div>
			</form>
		</div>

		<div id="VID" class="inv">
			<form action="{{ url('/complete-kyc/save-step-1') }}" method="post">
			 <input type="hidden" name="DOCUMENT_NAME" value="VID">
				@csrf
				<div style="margin-top: 50px;" class="VIDtext">
					<div class="kyc-doc-input"><input type="text" name="DOCUMENT_NUMBER" id="inpf3" autocomplete="off" minlength="12" required>
						<div class="label">Enter Voter ID Number</div>
					</div>
				</div>
				<div style="padding-bottom: 80px;"></div>
				<div class="refer-footer">
					<input type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" value="NEXT" id="next">
				</div>
			</form>
		</div>



		<!--<div style="padding-bottom: 80px;"></div>
			<div class="refer-footer">
			<input type="submit" class="disabledButton btn btn-primary btn-lg" style="width:100%" name="next1"   value="NEXT">
			</div>-->


	</div>


	<script>
		/*$('.UIDtext').hide();
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
*/

	</script>

	<style>
		.inv {
			display: none;
		}

	</style>
	<script>
		document
			.getElementById('target')
			.addEventListener('change', function() {
				'use strict';
				var vis = document.querySelector('.vis'),
					target = document.getElementById(this.value);
				if (vis !== null) {
					vis.className = 'inv';
				}
				if (target !== null) {
					target.className = 'vis';
				}
			});


		function checkLength() {
			var textbox = document.getElementById("inpf1");
			if (textbox.value.length <= 12 && textbox.value.length >= 12) {
				return true;
			} else {
				alert("Your comment is too short, please write more.");
					return false;
			}
		}

	</script>
	
</div>

@endsection
