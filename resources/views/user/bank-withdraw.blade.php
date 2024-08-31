@extends('layouts.front.front')
@section('content')
<style>
    .mt-4, .my-4 {
    margin-top: -0.5rem !important;
}
.MuiInputLabel-formControl {
    margin-bottom: 9px;
}
</style>
<div class="main-area" style="padding-top: 60px;">
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
                              src="{{asset('front/images/bank.png')}}" alt=""></div>
                        <div class="d-flex justify-content-center flex-column ml-4">
                           <div class="jss19">Bank Transfer</div>
                           <div class="jss20">Minimum withdrawal amount ₹300</div>
                        </div>
                        <!--<a href="{{url('withdraw-request')}}" class="btn btn-sm btn-info position-absolute" style="right: 1.5rem; font-weight: 600; font-size: 0.75em;">EDIT</a>-->
                     </div>
                  </div>
				  <form method="POST" action="{{ route('bank-withdraw') }}" id="withDraw">
                  <div class="MuiFormControl-root MuiTextField-root mt-4 w-100"><label
                        class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated"
                        data-shrink="false">Account Number</label>
                     <div
                        class="MuiInputBase-root MuiInput-root MuiInput-underline MuiInputBase-formControl MuiInput-formControl">
                        <input  name="account_no" id="account_no" type="tel"
                           class="MuiInputBase-input MuiInput-input" value="{{$lastData != null ? $lastData->account_no : ""}}"></div>
						   <span id="account_no-error" style="color:red;display:none">error</span>
                  </div>
                  <div class="MuiFormControl-root MuiTextField-root mt-4 w-100"><label
                        class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated"
                        data-shrink="false">Re-Enter Account Number</label>
                     <div
                        class="MuiInputBase-root MuiInput-root MuiInput-underline MuiInputBase-formControl MuiInput-formControl">
                        <input  name="account_nos" id="account_nos" type="tel"
                           class="MuiInputBase-input MuiInput-input" value="{{$lastData != null ? $lastData->account_no : ""}}"></div>
						   <span id="account_no-errors" style="color:red;display:none">error</span>
                  </div>
                  <div class="MuiFormControl-root MuiTextField-root mt-4 w-100"><label
                        class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated"
                        data-shrink="false">IFSC Code</label>
                     <div
                        class="MuiInputBase-root MuiInput-root MuiInput-underline MuiInputBase-formControl MuiInput-formControl">
                        <input  name="ifsc_code" id="ifsc_code" type="text"
                           class="MuiInputBase-input MuiInput-input" value="{{$lastData != null ? $lastData->ifsc_code : ""}}" style="text-transform: uppercase;"></div>
						   <span id="ifsc_code-error" style="color:red;display:none">error</span>
                  </div>
                  <div class="MuiFormControl-root MuiTextField-root mt-4 w-100"><label
                        class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated"
                        data-shrink="false">Acount Holder Name</label>
                     <div
                        class="MuiInputBase-root MuiInput-root MuiInput-underline MuiInputBase-formControl MuiInput-formControl">
                        <input  name="acc_holder_name" id="acc_holder_name" type="text"
                           class="MuiInputBase-input MuiInput-input" value="{{$lastData != null ? $lastData->upi : ""}}" style="text-transform: uppercase;"></div>
						   <span id="acc_holder_name-error" style="color:red;display:none">error</span>
                  </div>
                  <div class="MuiFormControl-root MuiTextField-root mt-4 w-100"><label
                        class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated MuiInputLabel-shrink"
                        data-shrink="true">Enter Amount</label>
                     <div
                        class="MuiInputBase-root MuiInput-root MuiInput-underline jss21 MuiInputBase-formControl MuiInput-formControl MuiInputBase-adornedStart">
                        <div class="MuiInputAdornment-root MuiInputAdornment-positionStart">
                           <p class="MuiTypography-root MuiTypography-body1 MuiTypography-colorTextSecondary">₹</p>
                        </div><input  name="amount" id="withdraw_amount"  type="tel"
                           class="MuiInputBase-input MuiInput-input MuiInputBase-inputAdornedStart" value="">
                     </div>
					 <span id="withdraw_amount-error" style="color:red;display:none">error</span>
                  </div>
					
                  <div class="refer-footer"><button class="refer-button cxy w-100 bg-primary">Withdraw</button></div>
				  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="divider-y"></div>

<script>

$(function () {
		 
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});


      $('#withDraw').submit(function(e)
		  {
			e.preventDefault();
			
			var account_no		=	$('#account_no').val();
			var withdraw_amount	=	$('#withdraw_amount').val();
			var ifsc_code    	=	$('#ifsc_code').val();
			var flag			=	1;		
			if(!ifsc_code){
				$('#ifsc_code-error').text('Please enter ifsc code');
				$('#ifsc_code-error').addClass('error');
				$('#ifsc_code-error').show();
				flag = 0;
			}else{
				$('#ifsc_code-error').hide();
			}
			
			if(!account_no){
				$('#account_no-error').text('Please enter account number');
				$('#account_no-error').addClass('error');
				$('#account_no-error').show();
				flag = 0;
			}else if(! $.isNumeric(account_no)){
				$('#account_no-error').text('Please enter numeric value');
				$('#account_no-error').addClass('error');
				$('#account_no-error').show();
				flag = 0;
			}else{
				$('#account_no-error').hide();
			}

			if(!withdraw_amount){
				$('#withdraw_amount-error').text('Please enter amount');
				$('#withdraw_amount-error').addClass('error');
				$('#withdraw_amount-error').show();
				flag = 0;
			}else if(! $.isNumeric(withdraw_amount)){
				$('#withdraw_amount-error').text('Please enter numeric value');
				$('#withdraw_amount-error').addClass('error');
				$('#withdraw_amount-error').show();
				flag = 0;
			}else if(withdraw_amount < 300 ){
				$('#withdraw_amount-error').text('Amount should be greater than 190');
				$('#withdraw_amount-error').addClass('error');
				$('#withdraw_amount-error').show();
				flag = 0;
			}else{
				$('#withdraw_amount-error').hide();
			}
			
			if(flag){
				$form = $(this);
				
				 $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('bank-withdraw') }}',
					data: $form.serialize(),
					beforeSend: function(){
						$('.loading').show();
					},
					success:function(data){
						if(data.success){
							$('#withdraw_amount-error').hide();
							$('#account_no-error').hide();
							$('#ifsc_code-error').hide();
							//$('#withdraw-modal').hide();
							alert(data.success);
							$('#withDraw')[0].reset();
							$("#withDraw").trigger("reset");
							//location.reload(); 
						}else{
							$('#withdraw_amount-error').text(data.error);
							$('#withdraw_amount-error').show();
						}
						if(data.wallet_amount){
						   $('#wallet_amount').text(data.wallet_amount);
						   $('#wallet-balance').text(data.wallet_amount);
					   }
				   },
				   complete:function(data){ 
					   $('.loading').hide();
					   $('#withDraw')[0].reset();
						$("#withDraw").trigger("reset");
						//location.reload(); 
				   }
					
				});
			}
		  });
	});
</script>
@endsection

