@extends('layouts.front.front')
@section('content')
<style>
  #winnerForm,
  #loserForm,
  #cancelForm {
    background-color: #ebebeb;
    margin-top: 20px;
  }

  .result-input-group {
    display: inline-block;
    margin-top: 20px;
  }

  .cancel-textarea {
    display: inline-block;
    vertical-align: middle;
  }
</style>


<script>
	
	 $(function () {
		 
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		 
		 var interval;
          
          interval = setInterval(function()
			{ 
				$.ajax({
					type: "GET",
					dataType: 'json',
					url: '{{ route('dashboard.quick-challenges') }}',
					data: '',
					success:function(data){
						if(data.html){  //alert('11');
							$('#challenge-div').html('');
							$('#challenge-div').html(data.html);
						}else{
							//alert('Server Error!');
						}
				   },
				   error:function(data){
					   //alert('ero');
					},
					complete:function(data){ //alert(data.html);
						$('.loading').hide();
						
						//~ if( $(".popupresut").css('display') == 'block') { 
							//~ //alert('2255'); 
							//~ //var ch_id	=	$('.data-pd-popup-submit-result-open').attr('ch_id');
							//~ //alert(ch_id);
							//~ var ch_id	=	localStorage.getItem('ch_id');
							//~ var user_id	=	{{Auth::user()->id}};
							
							//~ if(ch_id){
								//~ $.ajax({
									//~ type: "POST",
									//~ dataType: 'json',
									//~ url: '{{ route('dashboard.get-room-id') }}',
									//~ data: { 'ch_id' : ch_id } ,
									//~ success:function(data){ 
										//~ if(data.room_id && data.o_id == user_id){ 
											//~ $('#res-room-id-submit').attr("disabled", true);
											//~ $('#res-room-id').attr("disabled", true);
											//~ $('#change-room-id').show();
											//~ $('#res-room-id').val(data.room_id);
										//~ }if(data.room_id && data.c_id == user_id){ 
											//~ $('#res-room-id-submit').attr("disabled", true);
											//~ $('#res-room-id').attr("disabled", true);
											//~ $('#change-room-id').hide();
											//~ $('#res-room-id').val(data.room_id);
										//~ }else if( !data.room_id && data.o_id == user_id){ 
											//~ $('#res-room-id-submit').attr("disabled", true);
											//~ $('#res-room-id').attr("disabled", true);
											//~ $('#change-room-id').hide();
										//~ }else if(!data.room_id && data.c_id == user_id && !$('#res-room-id').val()){ 
											
											//~ $('#res-room-id').val('');
											//~ $('#res-room-id-submit').attr("disabled", false);
											//~ $('#res-room-id').attr("readonly", false);
											//~ $('#change-room-id').hide();
										//~ }else if(!data.room_id && data.c_id == user_id && $('#res-room-id').val()){
											
											//~ //$('#res-room-id').val('');
											//~ $('#res-room-id-submit').attr("disabled", false);
											//~ $('#res-room-id').attr("readonly", false);
											//~ $('#res-room-id').attr("disabled", false);
											//~ $('#change-room-id').hide();
										//~ }
								   //~ },
								   //~ error:function(data){
									   //~ //alert('ero');
									//~ },
									//~ complete:function(data){ //alert(data.html);
										 
									//~ }
									
								//~ });
							//~ }						
						//~ } 
						
						
						 
					}
					
				});
			}, 2000);
		 
          //----- OPEN
          $(document).on('click', '[data-pd-popup-open]', function (e) {
			   
            var targeted_popup_class = $(this).attr("data-pd-popup-open");
            $('[data-pd-popup="' + targeted_popup_class + '"]').fadeIn(100);
            $("body").addClass("popup-open");
            e.preventDefault();
          });

          //----- CLOSE
          $(document).on('click', '[data-pd-popup-close]', function (e) {
			   
            var targeted_popup_class = $(this).attr("data-pd-popup-close");
            $('[data-pd-popup="' + targeted_popup_class + '"]').fadeOut(200);
            $("body").removeClass("popup-open");
            e.preventDefault();
          });
          
          function closePopup(){
			  $('.popup').fadeOut(200);
		  }
          
          $('#create-challenge').submit(function(e)
		  { 
			e.preventDefault();
			var amount	=	$('#challenge-amount').val();
			
			var flag			=	1;
			
			if(!amount){
				$('#challenge-amount-error').text('Please enter amount');
				$('#challenge-amount-error').addClass('error');
				$('#challenge-amount-error').show();
				flag = 0;
			}else if(! $.isNumeric(amount)){
				$('#challenge-amount-error').text('Please enter numeric value');
				$('#challenge-amount-error').addClass('error');
				$('#challenge-amount-error').show();
				flag = 0;
			}else if(!(amount == 30 || amount == 40 || amount%50==0)){							
				$('#challenge-amount-error').text('Invalid amount!');
				$('#challenge-amount-error').addClass('error');
				$('#challenge-amount-error').show();
				flag = 0;
			}else{
				$('#challenge-amount-error').text('');
				$('#challenge-amount-error').removeClass('error');
				$('#challenge-amount-error').hide();
			}
			
			
			if(flag){
				$form = $(this);
				
				 $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('dashboard.create-challenge') }}',
					data: $form.serialize(),
					beforeSend: function(){
						$('.loading').show();
					},
					success:function(data){
						if(data.html){ 
							$('#challenge-amount-error').text('');
							$('#challenge-amount-error').hide();
							$('#challenge-amount-error').hide();
							//swal(data.success);
							//$("#create-challenge").trigger("reset");
							$('#challenge-div').html(data.html);
						}else{
							$('#challenge-amount-error').text(data.error);
							$('#challenge-amount-error').show();
						}
				   },
				   error:function(data){
					   var errors = $.parseJSON(data.responseText);
					   $('#challenge-amount-error').text(errors.message);
					   $('#challenge-amount-error').show();
					},
					complete:function(){
						$('#create-challenge')[0].reset();
						$('.loading').hide();
					}
					
				});
			}
			
          });
          
          $('#challenge-amount').keyup(function(e)
		  { 
			var amount	=	$('#challenge-amount').val();
			var flag			=	1;
			var valid			=	1;
			
			if(!amount){
				$('#challenge-amount-error').text('Please enter amount');
				$('#challenge-amount-error').addClass('error');
				$('#challenge-amount-error').show();
				flag = 0;
			}else if(! $.isNumeric(amount)){
				$('#challenge-amount-error').text('Please enter numeric value');
				$('#challenge-amount-error').addClass('error');
				$('#challenge-amount-error').show();
				flag = 0;
			}else if(!(amount == 30 || amount == 40 || amount%50==0)){							
				$('#challenge-amount-error').text('Invalid amount!');
				$('#challenge-amount-error').addClass('error');
				$('#challenge-amount-error').show();
				flag = 0;
			}else{
				$('#challenge-amount-error').text('');
				$('#challenge-amount-error').removeClass('error');
				$('#challenge-amount-error').hide();
			}
			
			
          });
          
          
          $('.accept-other-challenge').click(function(){
			  $('.loading').show();
				var id	=	$(this).attr('ch-id');
				$form = $('#accept-other-challenge-'+id);
				
				 $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('dashboard.accept-other-challenge') }}',
					data: $form.serialize(),
					beforeSend: function(){
						
					},
					success:function(data){
						$('#challenge-div').html(data.html);
						$('.loading').hide();
						if(data.error){
							swal(data.error);
						}else if(data.html){ 
							$('#challenge-div').html(data.html);
						}else{
							
						}
				   },
				   complete:function(){
						
					}
					
				});
			});
			
          $('.accept-own-challenge').click(function(){
				var id	=	$(this).attr('ch-id');
				$('.loading').show();
				$form = $('#accept-own-challenge-'+id);
				
				 $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('dashboard.accept-own-challenge') }}',
					data: $form.serialize(),
					beforeSend: function(){
						
					},
					success:function(data){
						$('.loading').hide();
						$('#challenge-div').html(data.html);
						if(data.error){
							swal(data.error);
						}else if(data.html){ 
							$('#challenge-div').html(data.html);
						}else{
							
						}
				   },
				   complete:function(){
						
					}
					
				});
			});
			
          $('.deny-own-challenge').click(function(){
			  var id	=	$(this).attr('ch-id');
			  $('.loading').show();
						$form = $('#deny-own-challenge-'+id);				
						 $.ajax({
							type: "POST",
							dataType: 'json',
							url: '{{ route('dashboard.deny-own-challenge') }}',
							data: $form.serialize(),
							beforeSend: function(){
								
							},
							success:function(data){
								swal('Request denied successfully!');
								$('.loading').hide();
								$('#challenge-div').html(data.html);
								if(data.error){
									swal(data.error);
								}else if(data.html){ 
									$('#challenge-div').html(data.html);
								}else{
									
								}
						   },
							complete:function(){
								
							}
							
						});

				
			});
			
          $('.cancel-own-challenge').click(function(){
			  var id	=	$(this).attr('ch-id');
			 $('.loading').show();
						$form = $('#cancel-own-challenge-'+id);				
						 $.ajax({
							type: "POST",
							dataType: 'json',
							url: '{{ route('dashboard.cancel-own-challenge') }}',
							data: $form.serialize(),
							beforeSend: function(){
								
							},
							success:function(data){
								swal('Challenge cancelled successfully!');
								$('.loading').hide();
								$('#challenge-div').html(data.html);
								if(data.error){
									swal(data.error);
								}else if(data.html){ 
									$('#challenge-div').html(data.html);
								}else{
									
								}
						   },
							complete:function(){
								
							}
							
						});
				
				
				
			});
			
          $('.cancel-other-challenge').click(function(){
			  var id	=	$(this).attr('ch-id');
			  $('.cancel-other-challenge').attr("disabled",true);
			  $('.loading').show();
						$form = $('#cancel-other-challenge-'+id);				
						 $.ajax({
							type: "POST",
							dataType: 'json',
							url: '{{ route('dashboard.cancel-other-challenge') }}',
							data: $form.serialize(),
							beforeSend: function(){
								
							},
							success:function(data){
								swal('Request cancelled successfully!');
								$('.loading').hide();
								$('.cancel-other-challenge').attr("disabled",false);
								$('#challenge-div').html(data.html);
								if(data.error){
									swal(data.error);
								}else if(data.html){ 
									$('#challenge-div').html(data.html);
								}else{
									
								}
						   },
							complete:function(){
								
							}
							
						});
					
				
			});
			
			
         
			$('.allow-roomid').click(function(e){
				$('.loading').show();
				var id		=	$(this).attr('ch-id');
				var status	=	$(this).val();
				$('#r_id_status-'+id).val(status);
				$form = $('#chk-room-id-'+id);
					
					 $.ajax({
						type: "POST",
						dataType: 'json',
						url: '{{ route('dashboard.chk-room-id') }}',
						data: $form.serialize(),
						beforeSend: function(){
							
						},
						success:function(data){
							$('#roomIdPopup-'+id).hide();
							if(data.error){
								swal(data.error);
							}else if(data.html){ 
								$('#challenge-div').html(data.html);
							}else{
								
							}
					   },
					   complete:function(){
						   closePopup();
							$('.loading').hide();
							$("#chk-room-id-new").trigger("reset");
							$('#res-room-id').val('');
						}
						
				});
				
			});
     
         
         //------------------ Allow room id new start -------------//
         
		$('.allow-roomid-new').click(function(e){
				$('.loading').show();
				var id		=	$(this).attr('ch-id');
				var status	=	$(this).val();
				$('#val-r_id_status').val(status);
				$form = $('#chk-room-id-new');
					
					 $.ajax({
						type: "POST",
						dataType: 'json',
						url: '{{ route('dashboard.chk-room-id') }}',
						data: $form.serialize(),
						beforeSend: function(){
							
						},
						success:function(data){
							$('#roomIdPopup-'+id).hide();
							if(data.error){
								swal(data.error);
							}else if(data.html){ 
								$('#challenge-div').html(data.html);
							}else{
								
							}
					   },
					   complete:function(){
						   //closePopup();
							$('.popupresut').fadeOut(200);
							$('.loading').hide();
							$("#chk-room-id-new").trigger("reset");
							$('#res-room-id').val('');
							$("#resultSubmitRadioBtn").trigger("reset");
						}
						
				});
				
			});
		//------------------ Allow room id new end -------------//
         
        //---------------- Submit result new start --------------//
        $(document).on('click', '[data-pd-popup-submit-result-open]', function (e) {
			
			var user_id			=	{{ Auth::user()->id }};
			var ch_id			=	$(this).attr('ch_id');
			var c_id			=	$(this).attr('c_id');
			var o_id			=	$(this).attr('o_id');
			var amount			=	$(this).attr('amount');
			var r_id			=	$(this).attr('r_id');
			var r_id_status		=	$(this).attr('r_id_status');
			var creatorname		=	$(this).attr('creatorname');
			var opponentname	=	$(this).attr('opponentname');
			var c_result		=	$(this).attr('c_result');
			var token			=	$(this).attr('token');
			var omobile			=	$(this).attr('omobile');
			
			localStorage.setItem('ch_id',ch_id);
			
			$('#val-c-username').html(creatorname);
			$('#val-o-username').html(opponentname);
			$('#val-ch-amount').html(amount);
			$('#val-ch-id').val(ch_id);
			$('#val-c-id').val(c_id);
			$('#val-o-id').val(o_id);
			$('#val-amount').val(amount);
			$('#val-token').val(token);
			$('#oppenent-mobile').html(omobile);
			$('#oppenent-mobile-lnk').attr("href","https://wa.me/91"+omobile+"?text=How+To+Play,Please+Guide+Me");
			
			if(c_id == user_id && r_id == ''){
				$('#res-room-id-submit').attr("disabled",false);
				$('#res-room-id').attr("readonly",false);
				$('#res-room-id').attr("disabled",false);
				$('#change-room-id').hide();
			}else if(c_id == user_id && (r_id_status=='DEFAULT' || r_id_status=='CHANGE') && (c_result=='' || !c_result)){
				$('#res-room-id-submit').attr("disabled", true);
				$('#res-room-id').attr("readonly", true);
				$('#change-room-id').hide();
			}
			else if(o_id == user_id && r_id == ''){
				$('#res-room-id-submit').attr("disabled", true);
				$('#res-room-id').attr("disabled", true);
				$('#change-room-id').hide();
			}else if(o_id == user_id && (r_id_status=='DEFAULT' || r_id_status=='CHANGE') && (c_result=='' || !c_result)){
				$('#res-room-id-submit').attr("disabled", true);
				$('#res-room-id').attr("disabled", true);
				$('#change-room-id').show();
			}else{
				//~ $('#res-room-id-submit').attr("disabled", true);
				//~ $('#res-room-id').attr("disabled", true);
				//~ $('#change-room-id').hide();
			}
			
			if(r_id){
				$('#res-room-id').val(r_id);
			}else{
				$('#res-room-id').val('');
			}
			
			$('#res-c-username').html(creatorname);
			$('#res-o-username').html(opponentname);
			$('#res-ch-amount').html(amount);
			$('#res-ch-id').val(ch_id);
			$('#res-c-id').val(c_id);
			$('#res-o-id').val(o_id);
			$('#res-amount').val(amount);
			$('#res-token').val(token);
			
			$('.popupresut').fadeIn(100);
		});
		
		$('#res-room-id').keyup(function(){
				var room_id	=	$(this).val();
				
				if(!room_id){
					$('#room_id-error').text('Please enter room id');
					$('#room_id-error').addClass('error');
					$('#room_id-error').show();
				}else if(! $.isNumeric(room_id)){
					$('#room_id-error').text('Please enter numeric value');
					$('#room_id-error').addClass('error');
					$('#room_id-error').show();
				}else{
					$('#room_id-error').text('');
					$('#room_id-error').removeClass('error');
					$('#room_id-error').hide();
				}
			});
		
			$('#res-room-id-submit').click(function(e){
				e.preventDefault();
				
				var room_id	=	$('#res-room-id').val();
				
				var flag			=	1;
				
				if(!room_id){
					$('#room_id-error').text('Please enter room id');
					$('#room_id-error').addClass('error');
					$('#room_id-error').show();
					flag = 0;
				}else if(! $.isNumeric(room_id)){
					$('#room_id-error').text('Please enter numeric value');
					$('#room_id-error').addClass('error');
					$('#room_id-error').show();
					flag = 0;
				}else{
					$('#room_id-error').text('');
					$('#room_id-error').removeClass('error');
					$('#room_id-error').hide();
				}
				if(flag){
					
					 $form = $('#chk-room-id-new');
					
					 $.ajax({
						type: "POST",
						dataType: 'json',
						url: '{{ route('dashboard.chk-room-id') }}',
						data: $form.serialize(),
						
						beforeSend: function(){
							$('.loading').show();
						},
						success:function(data){
							
							if(data.error){
								swal(data.error);
							}else if(data.html){ 
								$('#challenge-div').html(data.html);
							}else{
								
							}
					   },
					   complete:function(){
						   //closePopup();
							$('.popupresut').fadeOut(200);
							$('.loading').hide();
							$('#chk-room-id-new')[0].reset();
							$("#chk-room-id-new").trigger("reset");
							$('#res-room-id').val('');
							$("#resultSubmitRadioBtn").trigger("reset");
						}
						
				});
				}
			});
          
		
		$('#resultSubmitRadioBtn input').on('change', function () {
			var room_id	=	$('#res-room-id').val();
			
				$('input[name="result"]').attr("readonly", false);
			var id	=	$(this).attr('ch-id');
			var selectedResult = $('input[name=result]:checked', '.resultRadioBtn').val();
			
			$('#result-submit-btn-res').show();
			if (selectedResult == 'WON') {
			  $('#cancel-text-res').hide();
			  $('#upload-image-res').show();
			} else if (selectedResult == 'LOSS') {
			  $('#cancel-text-res').hide();
			  $('#upload-image-res').hide();
			} else {
			  $('#upload-image-res').hide();
			  $('#cancel-text-res').show();
			}
			
			  $('#resultSubmitRadioBtn').submit(function(e)
			  { 
				e.preventDefault();
				e.stopImmediatePropagation();
				flag	=	1;
				if(selectedResult == 'WON'){
					var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
					var file = $('#result_img-res').val();
					if(!file){
						flag = 0;
						$('#result-error-res').text('Please upload image');
						$('#result-error-reason-res').text('');
						$('#result-error-res').show();
					}else if ($.inArray(file.split('.').pop().toLowerCase(), fileExtension) == -1) {
						flag = 0;
						$('#result-error-res').text('Only formats are allowed : '+fileExtension.join(', '));
						$('#result-error-reason-res').text('');
						$('#result-error-res').show();
					}
				}else if(selectedResult == 'CANCEL'){
					var cancel_reason = $('#cancel_reason-res').val();
					if(!cancel_reason){
						$('#result-error-reason-res').text('Please enter reason to cancel');
						$('#result-error-res').text('');
						$('#result-error-reason-res').show();
						flag = 0;
					}
				}else{
					$('#result-error-reason-res').text('');
					$('#result-error-reason-res').hide();
				}
				
				if(flag){
					$form = $(this);
					$('.loading').show();
					 $.ajax({
						url: '{{ route('dashboard.submit-result') }}',
						type: "POST",
						data: new FormData(this),
						processData: false,
						contentType: false,
						beforeSend: function(){
							//$('#result-submit-btn-res').attr('disabled',true);
							
						},
						success:function(data){
							
							if(data.error){
								$('.loading').hide();
								swal(data.error);
							}
							if(data){ 
								$('.loading').hide();
								$('#result-submit-error-res').text('');
								$('#result-submit-error-res').hide();
								$('#result-submit-error-res').hide();
								swal('Result submitted successfully!');
								//$("#create-challenge").trigger("reset");
								$('#challenge-div').html(data.html);
							}else{
								//$('.loading').hide();
								$('#result-submit-error-res').text(data.error);
								$('#result-submit-error-res').show();
							}
					   },
					   error:function(data){
						   $('.loading').hide();
						   swal('Result already submitted!');
						   //var errors = $.parseJSON(data.responseText);
						   //$('#result-submit-error-res').text(errors.message);
						   //$('#result-submit-error-res').show();
						},
						complete:function(){
							closePopup();
							$('#resultSubmitRadioBtn')[0].reset();
							$("#resultSubmitRadioBtn").trigger("reset");
							
							$("#chk-room-id-new").trigger("reset");
							$('#res-room-id').val('');
							//$('#result-submit-btn-res').attr('disabled',false);
							
						}
						
					});
				}
				
			  });
			
		  });
        //---------------- Submit result new end --------------//
          
        });

	</script>
	
	<div class="container" >
		<h1>Quick Ludo Rules:</h1>
		<ul>
			<li style="list-style: none">1. Only allowed in quick Ludo mode.</li>
			<li style="list-style: none">2. Challenge creator will create room in quick mode only.</li>
			<li style="list-style: none">3. For cancelling game video proof is must(download AZ screen recorder from play store).</li>
			<li style="list-style: none">4. If result not submit penalty 20rs and for wrong result penalty 50rs(be careful).</li>
		</ul>
	</div>

	<section class="challenge-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center create-challenge-block">
            <h1>Create Challenge</h1>
            <form action="{{route('dashboard.change-unique-id')}}" id="create-challenge" method="POST">
				@csrf
				<input type="hidden" name="quick_flag" value="1" />
              <div class="input-group">
                <input type="text" class="form-control amount-input" id="challenge-amount" placeholder="Enter Amount" name="amount">
                
                <div class="input-group-btn">
                  <button class="btn btn-default challenge-create-btn" type="submit"><i class="fa fa-plus-circle" aria-hidden="true"></i> Set Challenge</button>
                </div>
              </div>
            </form>
          </div>
          <div class="col-md-12 text-center">
			<div style="display:none; color:red;" id="challenge-amount-error">Enter amount</div>
          </div>
        </div>
      </div>
    </section>
    <section>
      <div class="container challenge-list" id="challenge-div">
		  
        
        
        @foreach($challenges as $id => $val)
			@if(($val->c_id == Auth::user()->id ) && $val->c_result) @continue; @endif
			@if(($val->o_id == Auth::user()->id ) && $val->o_result) @continue; @endif
				<div class="row challenge-row text-center" >
					@if($val->status == 'PENDING' && $val->c_id == Auth::user()->id)
						<div class="col-md-8 col-xs-8 challenge-text">
							<h1><span>{{Auth::user()->username}}</span> set challenge of Rs {{$val->amount}}</h1>
						 </div>
						 <div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" ch-id="{{$val->id}}" class="btn btn-danger play-btn cancel-own-challenge">Cancel</a>
							<form action="{{route('dashboard.cancel-own-challenge')}}" type="POST" id="cancel-own-challenge-{{$val->id}}">
								@csrf
								<input type="hidden" name="ch_id" value="{{$val->id}}" >
								<input type="hidden" name="c_id" value="{{$val->c_id}}" >
								<input type="hidden" name="o_id" value="{{$val->o_id}}" >
								<input type="hidden" name="amount" value="{{$val->amount}}" >
							</form>
						 </div>
					 @elseif($val->status == 'PENDING' && $val->c_id != Auth::user()->id)
						<div class="col-md-8 col-xs-8 challenge-text">
							<h1><span>{{$val->creatorname->username}}</span> set challenge of Point {{$val->amount}}</h1>
						 </div>
						 <div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" ch-id="{{$val->id}}" class="btn btn-primary play-btn accept-other-challenge">Play</a>
							<form action="{{route('dashboard.accept-other-challenge')}}" type="POST" id="accept-other-challenge-{{$val->id}}">
								@csrf
								<input type="hidden" name="ch_id" value="{{$val->id}}" >
								<input type="hidden" name="c_id" value="{{$val->c_id}}" >
								<input type="hidden" name="o_id" value="{{$val->o_id}}" >
								<input type="hidden" name="amount" value="{{$val->amount}}" >
							</form>
						 </div>
					
					@elseif($val->status == 'PLAYING' && $val->c_id == Auth::user()->id && $val->is_play_accept == 'ACCEPT' &&  ($val->r_id_status == 'DEFAULT' || $val->r_id_status == 'CHANGE'))
						<div class="col-md-8 col-xs-8 challenge-text">
							<h1><span>{{$val->creatorname->username}}</span> VS <span>{{$val->opponentname->username}}</span> for Point {{$val->amount}}</h1>
						</div>
						<div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" id="submitResultPopupNew" data-pd-popup-submit-result-open="submitResultPopupNew" ch_id="{{$val->id}}" c_id="{{$val->c_id}}" o_id="{{$val->o_id}}" amount="{{$val->amount}}" r_id="{{$val->r_id}}" r_id_status="{{$val->r_id_status}}" creatorname="{{$val->creatorname->username}}" opponentname="{{$val->opponentname->username}}" omobile="{{$val->opponentname->mobile_no}}" class="btn btn-info play-btn popup-open-link" c_result="" >Details</a>
						</div>
						
					@elseif($val->status == 'PLAYING' && $val->c_id == Auth::user()->id && $val->is_play_accept == 'PENDING')
						<div class="col-md-8 col-xs-8 challenge-text">
							<h1><span>{{$val->opponentname->username}}</span> accept your challenge for Point {{$val->amount}}</h1>
						</div>
						<div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" ch-id="{{$val->id}}" class="btn btn-success play-btn accept-own-challenge">Accept</a>
							<form action="{{route('dashboard.accept-own-challenge')}}" type="POST" id="accept-own-challenge-{{$val->id}}">
								@csrf
								<input type="hidden" name="ch_id" value="{{$val->id}}" >
								<input type="hidden" name="c_id" value="{{$val->c_id}}" >
								<input type="hidden" name="o_id" value="{{$val->o_id}}" >
								<input type="hidden" name="amount" value="{{$val->amount}}" >
							</form>
							<?php /* <a href="javascript:void(0)" ch-id="{{$val->id}}" class="btn btn-danger play-btn deny-own-challenge">Deny</a>
							<form action="{{route('dashboard.deny-own-challenge')}}" type="POST" id="deny-own-challenge-{{$val->id}}">
								@csrf
								<input type="hidden" name="ch_id" value="{{$val->id}}" >
								<input type="hidden" name="c_id" value="{{$val->c_id}}" >
								<input type="hidden" name="o_id" value="{{$val->o_id}}" >
								<input type="hidden" name="amount" value="{{$val->amount}}" >
							</form> */ ?>
						</div>
					@elseif($val->status == 'PLAYING' && $val->c_id == Auth::user()->id && $val->is_play_accept == 'DEFAULT')
						<div class="col-md-8 col-xs-8 challenge-text">
							<h1><span>{{$val->creatorname->username}}</span> VS <span>{{$val->opponentname->username}}</span> for Point {{$val->amount}}</h1>
						</div>
						<div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" ch-id="{{$val->id}}" class="btn btn-danger play-btn cancel-own-challenge">Cancel</a>
							<form action="{{route('dashboard.cancel-own-challenge')}}" type="POST" id="accept-other-challenge-{{$val->id}}">
								@csrf
								<input type="hidden" name="ch_id" value="{{$val->id}}" >
								<input type="hidden" name="c_id" value="{{$val->c_id}}" >
								<input type="hidden" name="o_id" value="{{$val->o_id}}" >
								<input type="hidden" name="amount" value="{{$val->amount}}" >
							</form>
						</div>
						
					
					
						
					@elseif($val->status == 'PLAYING' && $val->o_id == Auth::user()->id && $val->is_play_accept == 'ACCEPT' && ($val->r_id_status == 'DEFAULT' || $val->r_id_status == 'CHANGE'))
						<div class="col-md-8 col-xs-8 challenge-text">
							<h1><span>{{$val->creatorname->username}}</span> VS <span>{{$val->opponentname->username}}</span> for Point {{$val->amount}}</h1>
						</div>
						<div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" id="submitResultPopupNew" data-pd-popup-submit-result-open="submitResultPopupNew" token="{{ csrf_token() }}" ch_id="{{$val->id}}" c_id="{{$val->c_id}}" o_id="{{$val->o_id}}" amount="{{$val->amount}}" r_id="{{$val->r_id}}" r_id_status="{{$val->r_id_status}}" creatorname="{{$val->creatorname->username}}" opponentname="{{$val->opponentname->username}}" omobile="{{$val->creatorname->mobile_no}}" c_result="{{$val->c_result}}" class="btn btn-info play-btn popup-open-link" >Details</a>
						</div>
					
					@elseif($val->status == 'PLAYING' && $val->o_id == Auth::user()->id && ( $val->is_play_accept == 'DEFAULT' || $val->is_play_accept == 'PENDING'))
						<div class="col-md-8 col-xs-8 challenge-text">
							<h1><span>{{$val->creatorname->username}}</span> VS <span>{{$val->opponentname->username}}</span> for Point {{$val->amount}}</h1>
						</div>
						<div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" ch-id="{{$val->id}}" class="btn btn-danger play-btn cancel-other-challenge" >Cancel Req.</a>
							<form action="{{route('dashboard.cancel-other-challenge')}}" type="POST" id="cancel-other-challenge-{{$val->id}}">
								@csrf
								<input type="hidden" name="ch_id" value="{{$val->id}}" >
								<input type="hidden" name="c_id" value="{{$val->c_id}}" >
								<input type="hidden" name="o_id" value="{{$val->o_id}}" >
								<input type="hidden" name="amount" value="{{$val->amount}}" >
							</form>
						</div>
					@else
						<div class="col-md-8 challenge-text col-xs-8">
							<h1><span>{{$val->creatorname->username}}</span> VS <span>{{$val->opponentname->username}}</span> for Point {{$val->amount}}</h1>
						  </div>
						  <div class="col-md-4 col-xs-4">
							<a href="javascript:void(0)" class="btn btn-primary play-btn" disabled >Playing</a>
						</div>
					@endif
				</div>
			
        @endforeach
        
        
        
        
        
        
        @if($challenges->isEmpty())
        <div class="row challenge-row text-center">
			<div class="col-md-12" ><h1><span>No challenge found.</span></h1></div>
		</div>
        @endif
      </div>
      
      
		<div class="popup popupresut" data-pd-popup="submitResultPopupNew" style="display:none">
		  <div class="popup-inner">
			<div class="bet-details">
			  <h1>
				<span id="res-c-username"></span> vs <span id="res-o-username"></span> for Point <span id="res-ch-amount"></span>
			  </h1>
			  <?php /* <p class="oppenent">Oppenent Whatsapp Number : <span id="oppenent-mobile"> </span></p>
			  <a href="javascript:void(0)" target="_blank" id="oppenent-mobile-lnk">Click here to message your oppenent</a> */ ?>
			</div>
			
			<form class="form-inline" method="POST" id="chk-room-id-new" action="{{route('dashboard.chk-room-id')}}" >
				<input type="hidden" name="ch_id" value="" id="val-ch-id">
				<input type="hidden" name="_token" value="" id="val-token">
				<input type="hidden" name="c_id" value="" id="val-c-id">
				<input type="hidden" name="o_id" value="" id="val-o-id">
				<input type="hidden" name="amount" value="" id="val-amount">
				<input type="hidden" name="r_id_status" value="" id="val-r_id_status">
				<div class="form-group" style="margin-top:15px;">
					<div class="error"></div>
					<div class="success"></div>
					<input type="text" class="form-control " name="room_id" id="res-room-id" autocomplete="off" value="" readonly />
					<button type="submit" class="btn btn-primary" id="res-room-id-submit" disabled >Room ID</button>
					<div style="display:none;color:red;text-align:left;" id="room_id-error">Error</div>
				</div>
				
			</form>
			<div class="form-group " style="margin-top:15px;" id="change-room-id">
				<button type="submit" class="btn btn-danger allow-roomid-new" ch-id="" value="CHANGE" style="margin-right:20px" >Change Room ID</button>
			</div></br></br>
			<p class="bet-terms">Point 50 penalty charge if you update wrong and if you have won the game please post fair result for immediate balance transfer otherwise both player balance will be on hold and admin will have to take action and if do not update resolve within 20 minute of the match result will be on hold and you will be charged penalty.</p>
			<div class="result-submit">
			  <form id="resultSubmitRadioBtn" class="resultRadioBtn" method="POST"  enctype="multipart/form-data">
				
					<input type="hidden" name="ch_id" value="" id="res-ch-id">
					<input type="hidden" name="_token" value="" id="res-token">
					<input type="hidden" name="c_id" value="" id="res-c-id">
					<input type="hidden" name="o_id" value="" id="res-o-id">
					<input type="hidden" name="amount" value="" id="res-amount">
				<label class="radio-inline">
				  <input type="radio" name="result" value="WON" id="result-won-res" class="result-won">I Won
				</label>
				<label class="radio-inline">
				  <input type="radio" name="result" value="LOSS" id="result-loss-res" class="result-loss">I Loss
				</label>
				<label class="radio-inline">
				  <input type="radio" name="result" value="CANCEL" id="result-cancel-res" class="result-cancel">Cancel Match
				</label></br>
				<div style="display:none; color:red" id="result-submit-error-res"></div>
				<div style="display:none;" id="upload-image-res">
					<div class="form-group result-input-group">
					  <input type="file" name="result_img" id="result_img-res">
					  <div style="display:none; color:red" id="result-error-res"></div>
					</div>
					<div class="form-group result-input-group">
					  
					</div>
					<div class="form-group result-input-group">
					  
					</div><br/>
				</div>
				
				<div class="form-group result-input-group" style="display:none" id="cancel-text-res">
				  <textarea name="cancel_reason" id="cancel_reason-res" class="form-control cancel-textarea" placeholder="Cancel Reason"></textarea>
				  <div style="display:none; color:red" id="result-error-reason-res"></div>
				</div>
				<div class="form-group result-input-group" style="display:none" id="result-submit-btn-res">
				  <button type="submit" class="btn btn-danger">Submit</button>
				</div>
			  </form>
			</div>
			<a class="popup-close" data-pd-popup-close="submitResultPopupNew" href="#"> </a>
		  </div>
		</div>
         
      
    </section>
  

@endsection

