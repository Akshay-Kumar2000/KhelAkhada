@extends('layouts.front.front')
@section('content')

<section>
      <div class="row">
        <div class="col-md-12 login-bg">

        </div>
        <div class="container">
          <div class="col-md-6 col-md-offset-3 login-form-block">
            <h2>CREATE YOUR ACCOUNT</h2>
            <form action="{{ route('register-user') }}" method="POST">
				@csrf
              <div class="form-group">
                <input type="text" class="form-control login-input" name="name" placeholder="Enter Your Name" value="{{ old('name') }}">
                @if($errors->has('name'))
					<div class="invalid-feedback">
						{{ $errors->first('name') }}
					</div>
				@endif
              </div>
              <div class="form-group">
                <input type="text" class="form-control login-input col-md-4" id="mobile-no" name="whatsapp_no" value="{{ old('whatsapp_no') }}" placeholder="Enter Your Whatsup No." >
                
                @if($errors->has('whatsapp_no'))
					<div class="invalid-feedback">
						{{ $errors->first('whatsapp_no') }}
					</div>
				@endif
              </div><br><br>
              <div class="form-group">
                <input type="password" class="form-control login-input" name="password"  placeholder="Enter Password">
                @if($errors->has('password'))
					<div class="invalid-feedback">
						{{ $errors->first('password') }}
					</div>
				@endif
              </div>
              <div class="form-group">
                <input type="password" class="form-control login-input" name="confirm_password"  placeholder="Enter Confirm Password">
                @if($errors->has('confirm_password'))
					<div class="invalid-feedback">
						{{ $errors->first('confirm_password') }}
					</div>
				@endif
              </div>
              <div class="form-group">
				@if(isset($referral_code))
                	<input type="text" class="form-control login-input" name="referral_code" value="{{ $referral_code }}" placeholder="Enter Referral Code (If Any)">
				@else
                	<input type="text" class="form-control login-input" name="referral_code" value="{{ old('referral_code') }}" placeholder="Enter Referral Code (If Any)">
				@endif
                @if($errors->has('referral_code'))
					<div class="invalid-feedback">
						{{ $errors->first('referral_code') }}
					</div>
				@endif
              </div>
              <div class="form-group">
                <input type="checkbox" class="form-control col-md-6 pull-left" name="i_agree" value="Yes" /> <span class="col-md-6 pull-right"> I Agree that I am 18 years or older</span>
                @if($errors->has('i_agree'))
					<div class="invalid-feedback">
						{{ $errors->first('i_agree') }}
					</div>
				@endif
              </div>
              <div class="form-group">
                <input type="checkbox" class="form-control  pull-left" name="terms_conditions" value="Yes" /> <span class="col-md-6 pull-right"> I have read and agree to the <a href="{{route('front.terms-and-conditions')}}">Terms & Conditions</a> and the <a href="{{route('front.privacy-policy')}}">Privacy Policy</a>.</span>
                @if($errors->has('terms_conditions'))
					<div class="invalid-feedback">
						{{ $errors->first('terms_conditions') }}
					</div>
				@endif
              </div>
              
              <div class="form-group text-center">
                <button type="submit" class="btn btn-info login-form-btn" id="signup-btn">Register</button>
              </div>
            </form>
          </div>
          <div class="col-md-6 col-md-offset-3 login-form text-center">
            <p>Already Have An Account?</p>
            <a href="{{ route('login') }}" class="btn btn-warning signup-btn">Login</a>
          </div>
        </div>
      </div>
    </section>
    
<div class="popup" data-pd-popup="add-money">
    <div class="popup-inner">
        <div class="bet-details">
            <h1>OTP</h1>
            <div class="alert alert-success" id="successMsg" style="display:none;"></div>
        </div>
          <form class="needs-validation form-inline" method="POST" action="" id="add-money">
			  @csrf
            <div class="">
                <input type="text" name="otp" placeholder="Enter OTP" id="otp-val" value="" class="form-control" autocomplete="off">    
                <p style="display:none;color:red;" id="otp-error">Error</p>                
                <input type="button" id="otp-submit" value="Submit" name="submit_btn" class="btn btn-primary form-control">
                <div style="color:red;display:none;" id="add_money-error">Error select</div>
            </div>
          </form>
        <a class="popup-close" data-pd-popup-close="add-money" href="#"> </a>
    </div>
</div>


<script>
	$(function () {
			
			//----- CLOSE
				$(document).on('click','[data-pd-popup-close]', function(e) {
					var targeted_popup_class = $(this).attr("data-pd-popup-close");
					$('[data-pd-popup="' + targeted_popup_class + '"]').fadeOut(200);
					$("body").removeClass("popup-open");
					e.preventDefault();
				});
			
			$(document).on('click','[data-pd-popup-open]', function(e) {
				
				var mobile	=	$('#mobile-no').val();
				if(!mobile){
					swal('Please insert mobile no. first.');
				}else{
					var token	=	'{{ csrf_token() }}';
					$('.loading').show();
					
					$.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route("verify-mobile") }}',
					data: {'_token':token,'mobile': mobile},
					success:function(data){
						$('.loading').hide();
						if(data.success){
							$('#successMsg').show();
							$('#successMsg').text(data.success);
							$('[data-pd-popup]').fadeIn(100);
						}
				   },
				   complete:function(data){ 
					   
				   }
					
				});
					
				}
			});
				
			$('#otp-submit').click(function(e){
				e.preventDefault();
				
				var otp	=	$('#otp-val').val();
				var mobile	=	$('#mobile-no').val();
				if(!otp){
					$('#otp-error').show();
					$('#otp-error').text('Insert OTP');
				}else{
					var token	=	'{{ csrf_token() }}';
					$('.loading').show();
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '{{ route("verify-otp") }}',
						data: {'_token':token,'otp': otp,'mobile':mobile},
						success:function(data){
							$('.loading').hide();
							if(data.success){
								$('#otp-error').text('');
								$('#otp-error').hide();
								
								$('#signup-btn').attr('disabled',false);
								$('[data-pd-popup]').fadeOut(200);
								swal(data.success);
								$('#otp-val').val('');
							}else{
								$('#otp-error').show();
								$('#otp-error').text(data.error);
							}
					   },
					   error:function(data){
						   $('.loading').hide();
						   var response = JSON.parse(data.responseText);
						   $('#otp-error').show();
						   $('#otp-error').text(response.errors['otp']);
					   },
					   complete:function(data){ 
						   
					   }
						
					});
				}
				
			});
			
	});
</script>


@endsection

