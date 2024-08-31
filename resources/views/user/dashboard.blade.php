@extends('layouts.front.front')
@section('content')

	<section class="common-section dashboard-sections">
	<br/>
  <marquee><b style="color:red">अपने ोस्तो को रेर करें और पा 2% कमीशन। </b></marquee>	
	<!-- <marquee><b>
		<a href="{{ route('contests') }}" style="color:red;text-decoration:none;">
		10x तक प्रइज जीतने के लिए कांटेस् गेम्स खेले कांटेस्ट खलने के लिए यहाँ क्लिक करे। </a> </b></marquee> -->
      <div class="container">
        <div class="account-summary">
          <h1>Account Summary</h1>
        </div>
        <div class="row">
          <div class="col-md-3 col-xs-6 topbar text-center" style="border-bottom: 2px solid #f30e0e;">
            <div class="player-icon-block">
              <img src="{{ asset('front/images/player-icon.png') }}" alt="">
            </div>
            <h2>{{Auth::user()->name}}</h2>
            <h3>
				<form action="javascript:void(0)" id="change-unique-id" method="POST" >
					@csrf
					@-<input type="text" class="unique-user-id" value="{{Auth::user()->username}}" name="username" id="unique-id" />
				</form>
			</h3>
          </div>
          <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #efb207;">
            <h2>Total Chips</h2>
            <h3> <span id="wallet_amount">{{number_format(Auth::user()->wallet,2) ?? '0.00'}} </span> </h3>
          </div>
          <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #337ab7;">
            <h2>Total Won</h2>
            <h3> {{ number_format($total_won,2) ?? '0.00' }}</h3>
          </div>
          <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #64b161;">
            <h2>Total Withdrawal</h2>
            <h3> {{ number_format($total_withdraw,2) ?? '0.00' }}</h3>
          </div>
        </div>
      </div>
    </section>
    <section class="dashboard-sections">
      <div class="container">
        <div class="account-summary">
          <h1>Details</h1>
        </div>
        <div class="row">
          <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #efb207;">
            <?php /* <a href="{{ route('payment-requests') }}" class="btn btn-primary add-money add-withdrawal" >Add Chips</a> */ ?>
            <a href="{{ route('add-money') }}" class="btn btn-primary add-money add-withdrawal" >Add Chips</a>
            <a href="{{ route('withdraw-request') }}" class="btn btn-primary add-withdrawal" >Withdrawal Chips</a>
          </div>
		  <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #efb207;">
            <?php /* <a href="{{ route('payment-requests') }}" class="btn btn-primary add-money add-withdrawal" >Add Chips</a> */ ?>
            <a href="{{ route('transactions') }}" class="btn btn-primary add-withdrawal " >History</a>
			<a href="https://wa.me/?text=Click+here+to+use+my+referral+:+https://kd124.com/register?referral_code={{@Auth::user()->referral_code}}" target="_blank" style="text-decoration:none;" class="btn btn-primary add-withdrawal">Share</a>  
            
          </div>
          <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #f30e0e;">
            <a href="{{ route('challenges') }}" class="btn btn-danger add-withdrawal play-ludo">Play Ludo</a>
          </div>
          <?php /*
          <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #f30e0e;">
            <a href="{{ route('contests') }}" class="btn btn-danger add-withdrawal play-ludo">Play Contest</a>
          </div>
          
          <div class="col-md-3 col-xs-6 topbar balance-block text-center" style="border-bottom: 2px solid #269abc;">
            <a href="{{ route('quick-challenges') }}" class="btn btn-info add-withdrawal play-ludo" style="background-color: #e0b106;border-color: #e0b106;">Play Quick Ludo</a>
          </div> */ ?>
        </div>
      </div>
    </section>    
<div class="popup" data-pd-popup="withdraw" id="withdraw-modal">
    <div class="popup-inner">
        <div class="bet-details">
            <h1>
                Withdraw Chips
            </h1>
            <div class="alert alert-danger" id="error-msg" style="text-align:left; color:red;display:none;"></div>
            <!--<p class="oppenent">Oppenent Whatsapp Number <span id="oppenent-mobile"></span></p>-->
            <!--<p style="font-size:17px;">Click here to message your oppenent</p>-->
        </div>
          <form method="POST" action="{{ route('withdraw-request') }}" id="withDraw">
			  @csrf
            <div class="form-group">
                <label>Select Wallet</label>
                <select class="form-control" name="withdraw_in" id="withdraw_in">
                    <option value="">Withdraw In</option>
                    <option value="PAYTM">In Paytm Wallet</option>
                    <option value="PHONEPE">In PhonePe Wallet</option>
                    <option value="GOOGLEPAY">In Google Pay</option>
                </select>
                <div style="text-align:left; color:red;display:none;" id="withdraw_in-error">Error select</div>
            </div>
            <div class="form-group">
                <label>Enter Paytm/PhonePe/Google Pay Number</label>
                <input type="text" name="wallet_number" id="wallet_number" placeholder="Enter Mobile Number"  class="form-control">
                <div style="text-align:left; color:red;display:none;" id="wallet_number-error">Error phone</div>
            </div>
            <div class="form-group">
                <label>Enter Amount</label>
                <input type="text" name="withdraw_amt" id="withdraw_amt" placeholder="Enter Amount" value="" class="form-control" autocomplete="off">
                <div style="text-align:left; color:red;display:none;" id="withdraw_amt-error">Error select</div>
            </div>
                <input type="submit" value="Request Now" class="btn btn-primary form-control">
          </form>
        <a class="popup-close" data-pd-popup-close="withdraw" href="#"> </a>
    </div>
</div>
<div class="popup" data-pd-popup="add-money">
    <div class="popup-inner">
        <div class="bet-details">
            <h1>
                Add Chips
            </h1>
            <div class="alert alert-danger" id="errorMsg" style="display:none;"></div>
            @if ($errors->any())
				<div class="alert alert-danger">
					<strong>Whoops!</strong> Something went wrong<br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
            <div class="alert alert-success" id="successMsg" style="display:none;"></div>
            <!--<p class="oppenent">Oppenent Whatsapp Number <span id="oppenent-mobile"></span></p>-->
            <!--<p style="font-size:17px;">Click here to message your oppenent</p>-->
        </div>
        <?php /* ?>
          <form class="needs-validation form-inline" method="POST" action="{{ route('dashboard.payment') }}" id="add-money">
			  @csrf
            <div class="">
                <input type="text" name="amount" placeholder="Enter Amount" id="add_money" value="" class="form-control" autocomplete="off">                    
                
                <input type="submit" value="Add Now" name="submit_btn" class="btn btn-primary form-control">
                <div style="color:red;display:none;" id="add_money-error">Error select</div>
                    <!-- <a href="http://localhost/kd124//user/login" type="submit" name="submit" value="submit"class="btn btn-primary form-control btn-registration-next text-center">NEXT</a> -->
                
            </div>
          </form> <?php */ ?>
        <a class="popup-close" data-pd-popup-close="add-money" href="#"> </a>
        
        <p>  <b>Note: </b>
        	तकनीकी समस्या के कारण भुगतान 2 दिनों तक वॉलेट में नहीं जोड़ा जा सकेगा, इसलिए कृपया 90000000 पर भुगतान करके स्क्रीनशॉट एडमिन को भेजें।डमिन को भेजे।</p>
	<p>असुविधा े लिए खेद हैं। </p>
        
    </div>
</div>

<script>
	
		
		
		$(function () {
			
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		
		
			@if ($payment_msg)
		
				swal( "<?php echo $payment_msg; ?>" );
		
			@endif
			
			
			//----- OPEN
			$(document).on('click','[data-pd-popup-open]', function(e) {
				var targeted_popup_class = $(this).attr("data-pd-popup-open");
				$('[data-pd-popup="' + targeted_popup_class + '"]').fadeIn(100);
				$("body").addClass("popup-open");
				e.preventDefault();
			});

			//----- CLOSE
				$(document).on('click','[data-pd-popup-close]', function(e) {
					var targeted_popup_class = $(this).attr("data-pd-popup-close");
					$('[data-pd-popup="' + targeted_popup_class + '"]').fadeOut(200);
					$("body").removeClass("popup-open");
					e.preventDefault();
				});
		
		$('#unique-id').focusout(function(e){
			e.preventDefault();
			var uid	=	$(this).val();
			var flag 	=	1;
			if(!uid){
				swal("Unique ID may not be empty!");
				flag = 0;
			}
			
			var regex = new RegExp("^[a-zA-Z0-9]+$");
			
			//~ if (!regex.test(uid)) {
			   //~ swal("Special characters are not allowed in Unique ID!");
				//~ flag = 0;
			//~ }
			
			if(flag){
				$form = $(this);
				
				 $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('change-unique-id') }}',
					data: $form.serialize(),
					success:function(data){
						if(data.message){
							swal(data.message);
						}
				   }
					
				});
			}
			
		});
		
	});
	</script>

@endsection

