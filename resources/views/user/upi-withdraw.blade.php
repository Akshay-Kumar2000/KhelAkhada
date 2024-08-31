@extends('layouts.front.front')
@section('content')

<div class="main-area" style="padding-top: 60px;position:relative;">
    <div id="with_success" style="
    display: none;
    height: 65dvh;
    width: 100%;
    position: absolute;
    z-index: 1;
    flex-direction: column;
    flex-wrap: nowrap;
    align-content: center;
    justify-content: center;
    align-items: center;
">
        <img src="https://assets.materialup.com/uploads/9ba2d687-d7d3-4361-8aee-7b2a3c074761/preview.gif">
    </div>
         <div class="d-flex align-items-center px-4 py-3">
            <div class="games-section-headline" style="font-size: 0.85em;">Winning Cash Balance</div>
            <div class="games-section-title position-absolute" style="right: 1.5rem;"><img class="mr-1 mb-1"
                  src="{{asset('front/images/global-rupeeIcon.png')}}" width="20px" alt="">₹<span id="wallet-balance">{{$winningAmount}}</span></div>
         </div>
         <div class="divider-x"></div>
         <div class="px-4 py-3">
            <div class="d-flex flex-column">
               <div class="games-section-title">Withdraw through<div class="add-fund-box mt-4"
                     style="padding-top: 0px; height: 60px;">
                     <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center" style="height: 60px;"><img width="48px"
                              src="{{asset('front/images/upi.webp')}}" alt=""></div>
                        <div class="d-flex justify-content-center flex-column ml-4">
                           <div class="jss19">Withdraw to UPI</div>
                           <div class="jss20">Minimum withdrawal amount ₹300</div>
                        </div><a href="{{url('withdraw-request')}}" class="btn btn-sm btn-info position-absolute"
                           style="right: 1.5rem; font-weight: 600; font-size: 0.75em;">EDIT</a>
                     </div>
                  </div>
                  <form method="POST" action="{{ route('upi-withdraw') }}" id="withDraw">
                  <div class="MuiFormControl-root MuiTextField-root mt-4 w-100"><label
                        class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated MuiInputLabel-shrink MuiFormLabel-filled"
                        data-shrink="true">Enter UPI ID</label>
                     <div
                        class="MuiInputBase-root MuiInput-root MuiInput-underline MuiInputBase-formControl MuiInput-formControl">
                        <input aria-invalid="false" id="upi_id" name="upi_id" type="text"
                           class="MuiInputBase-input MuiInput-input" value="" ></div>
                        <span id="upi_id-error" style="color:red;display:none">error</span>
                  </div>
                  <span id="withdraw_amount_upi_verify" style="color:green;display:none"></span>
                  <div class="MuiFormControl-root MuiTextField-root mt-4 w-100"><label
                        class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated MuiInputLabel-shrink"
                        data-shrink="true">Enter Amount</label>
                     <div
                        class="MuiInputBase-root MuiInput-root MuiInput-underline jss21 MuiInputBase-formControl MuiInput-formControl MuiInputBase-adornedStart">
                        <div class="MuiInputAdornment-root MuiInputAdornment-positionStart">
                           <p class="MuiTypography-root MuiTypography-body1 MuiTypography-colorTextSecondary">₹</p>
                        </div><input aria-invalid="false" name="amount" id="withdraw_amount" type="tel"
                           class="MuiInputBase-input MuiInput-input MuiInputBase-inputAdornedStart" value="">                           
                     </div>
                     <span id="withdraw_amount-error" style="color:red;display:none">error</span>
                  </div>
                  <div class="refer-footer"><button class="refer-button cxy w-100 bg-primary" onclick="$('#popup').css('display','flex');" type="submit">Withdraw</button></div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <audio id="myAudio" controls style="display:none;">
        <source src="preview.mp3" type="audio/mp3">
        Your browser does not support the audio tag.
    </audio>
   <div id="popup" style="display:none;position:fixed;width:100dvw;height:100dvh;top: 0;left: 0;z-index: 11111111;flex-direction: row;flex-wrap: nowrap;align-content: center;justify-content: center;align-items: center;background: #808080cc;color: white;">
       <h1 style="text-align:center;">Loading..</h1>
   </div>
   <div class="divider-y"></div>
<script>

$(function () {
		 
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		
		var audio = document.getElementById("myAudio");

        // Function to play the audio
        function playAudio() {
            audio.play();
        }

        // Function to pause the audio
        function pauseAudio() {
            audio.pause();
        }

        // Function to set the volume
        function setVolume(volume) {
            audio.volume = volume;
        }
        
      $('#withDraw').submit(function(e)
		  {
			e.preventDefault();
			
			var upi_id		=	$('#upi_id').val();
			var withdraw_amount	=	$('#withdraw_amount').val();
			var flag			=	1;		
			if(!upi_id){
				$('#upi_id-error').text('Please enter upi ID');
				$('#upi_id-error').addClass('error');
				$('#upi_id-error').show();
				flag = 0;
				$('#popup').css('display','none');
			}
			
			if(!withdraw_amount){
				$('#withdraw_amount-error').text('Please enter amount');
				$('#withdraw_amount-error').addClass('error');
				$('#withdraw_amount-error').show();
				flag = 0;
				$('#popup').css('display','none');
			}else if(! $.isNumeric(withdraw_amount)){
				$('#withdraw_amount-error').text('Please enter numeric value');
				$('#withdraw_amount-error').addClass('error');
				$('#withdraw_amount-error').show();
				flag = 0;
				$('#popup').css('display','none');
			}else if(withdraw_amount < 300 ){
				$('#withdraw_amount-error').text('Amount should be greater than 190');
				$('#withdraw_amount-error').addClass('error');
				$('#withdraw_amount-error').show();
				flag = 0;
				$('#popup').css('display','none');
			}
			
			if(flag){
			    $('#popup').css('display','flex');
				$form = $(this);
				 $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('upi-withdraw') }}',
					data: $form.serialize(),
					beforeSend: function(){
						$('.loading').show();
					},
					success:function(data){
			            $('#popup').css('display','none');
						if(data.success){
			             //   $('#with_success').css('display','flex');
							$('#withdraw_amount-error').text('');
							$('#withdraw_amount-error').hide();
							//$('#withdraw-modal').hide();
							$("#withdraw_amount_upi_verify").show();
							$("#withdraw_amount_upi_verify").html(data.upi);
			                $('#with_success').css('display','flex');
							$('#withDraw')[0].reset();
							$("#withDraw").trigger("reset");
			                $('#with_success').css('display','flex');
			                playAudio();
			                setVolume(1);
			                setTimeout(()=>{
					             window.location.href = '/wallet';
					        },2000);
						}else{
			             //   $('#with_success').css('display','flex');
							$('#withdraw_amount-error').text(data.error);
							$('#withdraw_amount-error').show();
						}
						if(data.wallet_amount){
						   $('#wallet_amount').text(data.wallet_amount);
                           $('#wallet-balance').text(data.wallet_amount);
					    }
					    setTimeout(()=>{
					        $('#with_success').css('display','none');
					    },3000);
				   },
				   complete:function(data){ 
					   $('.loading').hide();
					   $('#withDraw')[0].reset();
						$("#withDraw").trigger("reset");
					    setTimeout(()=>{
					        $('#with_success').css('display','none');
					    },3000);
				   }
					
				});
			}
		  });
		  
		  $("#upi_id").on('change',function(){
		      $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('check-upi') }}',
					data: {
					    "upiid":$("#upi_id").val(),
					    "_token": "{{csrf_token()}}"
					},
					beforeSend: function(){
						$('.loading').show();
					},
					success:function(data){
						if(data.status == "true"){
						    $("#withdraw_amount_upi_verify").show();
							$("#withdraw_amount_upi_verify").html(data.message);
						}else{
							$('#withdraw_amount-error').text(data.error);
							$('#withdraw_amount-error').show();
					        $('#withDraw')[0].reset();
						    $("#withDraw").trigger("reset");
						}
				   },
				   complete:function(data){ 
					   $('.loading').hide();
				   }
					
				});
		  })
	});
</script>
@endsection

