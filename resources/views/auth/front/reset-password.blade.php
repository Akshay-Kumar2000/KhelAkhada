@extends('layouts.front.front')
@section('content')

<section>
  <div class="row">
	<div class="col-md-12 login-bg">

	</div>
	<div class="container">
	  <div class="col-md-6 col-md-offset-3 login-form-block">
		<h2 id="heading">Forgot Password</h2>
		@if (session()->has('message'))
			<div class="alert alert-success alert-dissmissible show" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
				{{ session('message') }}
			</div>
		@endif
		
		<div class="reset-password-errors" style="display:none">
			
		</div>
		
		<form action="{{ route('match-otp') }}" method="post" id="forgot-password">
			@csrf
			<input name="mobile_no" type="hidden" value="{{$mobileNumber}}">
			  <div class="form-group">
				<input type="text" name="otp" class="form-control login-input {{ $errors->has('otp') ? ' is-invalid' : '' }}" placeholder="Enter OTP" value="{{ old('otp') }}" required>
				<p style="display:none; color:red;" id="forgot-password-error">Error</p>
				@if($errors->has('otp'))
					<div class="invalid-feedback">
						{{ $errors->first('otp') }}
					</div>
				@endif
			  </div>
			  <div class="form-group" >
				<a href="" id="resend-otp">Resend OTP</a>
			  </div>
			  <div class="form-group text-center">
				<button type="submit" class="btn btn-info login-form-btn">Match</button>
			  </div>
		</form>
		<form action="{{ route('reset-password') }}" method="post" id="reset-password" style="display:none;">
			@csrf
			<input name="mobile_no" type="hidden" value="{{$mobileNumber}}">
			  <div class="form-group">
                <input type="password" class="form-control login-input" name="password"  placeholder="Enter Password">
                
					<div class="invalid-feedback password-err" style="color:red;">
						
					</div>
				
              </div>
              <div class="form-group">
                <input type="password" class="form-control login-input" name="confirm_password"  placeholder="Enter Confirm Password">
                <div class="invalid-feedback confirm-password-err" style="color:red;">
						
					</div>
              </div>
			  <div class="form-group text-center">
				<button type="submit" class="btn btn-info login-form-btn">Reset Password</button>
			  </div>
		</form>
	  </div>
	</div>
  </div>
</section>

<script>
	$(function () {
		$('#resend-otp').click(function(e){
			$('.loading').show(); 
			e.preventDefault();
			var mobile	=	{{$mobileNumber}};
			$.ajax({
					type: "GET",
					dataType: 'json',
					url: '{{ route('resend-otp') }}',
					data: { 'mobile' : mobile ,},
					success:function(data){
						$('.alert-dissmissible').hide();
						swal(data.message);
				   },
				   error:function(data){
					   //alert('ero');
					},
					complete:function(data){ //alert(data.html);
						$('.loading').hide(); 
					}
					
				});
		});
		
		$('#forgot-password').submit(function(e){
			e.preventDefault();
			$('.loading').show(); 
			var mobile	=	{{$mobileNumber}};
			$form = $(this);
			$.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('match-otp') }}',
					data: $form.serialize(),
					success:function(data){
						if(data.match){ 
							$('#forgot-password-error').hide();
							$('#forgot-password-error').text('');
							$('#heading').text('Reset Password');
							$('#forgot-password').hide();
							$('#reset-password').show();
						}else if(data.error){
							$('#forgot-password-error').show();
							$('#forgot-password-error').text(data.error);
						}
				   },
				   error:function(data){
					   //alert('error');
					},
					complete:function(data){ //alert(data.html);
						$('.loading').hide(); 
					}
					
				});
		});
		
		$('#reset-password').submit(function(e){
			e.preventDefault();
			$('.loading').show(); 
			var mobile	=	{{$mobileNumber}};
			$form = $(this);
			$.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('reset-password') }}',
					data: $form.serialize(),
					success:function(data){ 
						//swal("Your password reset successfully!");
						if(data.route){
							location.href	=	data.route;
						}
				   },
				   error:function(data){ 
					   if(data){
						   var response = JSON.parse(data.responseText);
						   if(response.errors.password){
								$('.password-err').text(response.errors.password);
							}else{
								$('.password-err').text('');
							}
							if(response.errors.confirm_password){
								$('.confirm-password-err').text(response.errors.confirm_password);
							}else{
								$('.confirm-password-err').text('');
							}
						}
					},
					complete:function(data){ //alert(data.html);
						$('.loading').hide(); 
					}
					
				});
		});
	});
</script>

@endsection

