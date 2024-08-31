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
  
  .blink_me {
	  animation: blinker 1s linear infinite;
	}

	@keyframes blinker {  
	  50% { opacity: 0; }
	}
</style>


<script>

	function closePopup(){
		$('.popup').fadeOut(200);
	}

	
	//----- CLOSE
	$(document).on('click','[data-pd-popup-close]', function(e) {
		closePopup();
	});

	$(function () {
		 
		 setTimeout(location.reload.bind(location), 300000);
		 
		 
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		
		$('.join-contest').click(function(e)
		  { 
			var contest_id	=	$(this).attr("contest-id");
			
			$("#ludoking-modal").show();
			
			$("#ludo-king-submit").click(function(e){
				
				var ludo_king_id		=	$('#ludo_king_id').val();
				var flag			=	1;
				if(!ludo_king_id){
					$('#ludo_king_id-error').text('Please enter ludo king ID');
					$('#ludo_king_id-error').addClass('error');
					$('#ludo_king_id-error').show();
					flag = 0;
				}else{
					$('#ludo_king_id-error').text('');
					$('#ludo_king_id-error').removeClass('error');
					$('#ludo_king_id-error').hide();
				}
				$("#ludo-king-"+contest_id).val(ludo_king_id);
				if(flag){
					$form = $(this);
					
					$('.loading').show();
					$form = $('#join-contest-'+contest_id);
					
					 $.ajax({
						type: "POST",
						dataType: 'json',
						url: '{{ route('dashboard.join-contest') }}',
						data: $form.serialize(),
						beforeSend: function(){
							
						},
						success:function(data){
							$("#ludoking-modal").hide();
							$('.loading').hide();
							
							if(data.error){
								swal(data.error);
							}else if(data.success){ 
								swal(data.success);
								$("#link-join-"+contest_id).text('Details');
								$("#link-join-"+contest_id).removeClass('btn-success');
								$("#link-join-"+contest_id).addClass('btn-info');
								$("#link-join-"+contest_id).attr("href","{{ url('dashboard/contest') }}"+"/"+data.slug);
							}else{
								
							}
					   },
					   complete:function(){
							
						}
						
					});
				}
			});
			
		  });


		Pusher.logToConsole = true;
    
		var pusher = new Pusher('b767da0904aaacf26ba0', {
		  cluster: 'ap2'
		});

		var user_id	=	'{{Auth::user()->id}}';
		var c_token	=	'{{ csrf_token() }}';
		var channel = pusher.subscribe('contests');
		channel.bind('App\\Events\\contestListing', function(data) {
			if(data.user.j_count == 0 && user_id != data.user.player_id){
				$("#link-join-"+data.user.contest_id).removeClass('btn-success');
				$("#link-join-"+data.user.contest_id).addClass('btn-danger');
				$("#link-join-"+data.user.contest_id).attr("href","javascript:void(0)");
				$("#link-join-"+data.user.contest_id).text("Full");
			}else{
				$("#user-join-coint-"+data.user.contest_id).text(data.user.j_count);
			}
		});
    });

	
	</script>

    <section>
      <div class="container">
	  <div class="row">
          <div class="col-md-12 about-content">
           <b>Rules : </b> <span style="font-size:10px">1. कांटेस्ट ज्वाइन करने से पहले अपनी लूडो किंग ID डाले। गलत लूडो किंग ID डालने पर आपको कांटेस्ट से बाहर कर दिया जायेगा।</span><br>
		   <span style="font-size:10px">2. दिए गए टाइम पर आपको गेम खेलना पड़ेगा। लेट होने पर आपको कांटेस्ट से बाहर कर दिया जायेगा।</span>
		   <span style="font-size:10px">3. कांटेस्ट स्टार्ट होने से 15 मिनट पहले कांटेस्ट लॉक हो जायेगा, फिर आप ज्वाइन नहीं कर पाएंगे।</span><br>
		   <span style="font-size:10px">4. अपने रूम कोड को किसी के भी साथ शेयर न करे, ऐसा करने पर आपको डिसक्वालिफाई कर दिया जायेगा और पेनेल्टी भी लगाई जाएगी।</span>
		  </div>
          <div class="col-md-12 about-content">
            <h1>Contests</h1>
			<b><a class="pull-right pull-left" style="text-decoration:none;font-size:18px;" href="{{asset('front/images/how-to-play.jpeg')}}" >How to play</a></b>
			<b><a class="pull-right pull-right" style="text-decoration:none;font-size:18px;" href="{{route('dashboard.contest-results')}}" >Contest Results</a></b>
		  </div>
		</div>
        @foreach($contests as $id => $val)
			<div class="row challenge-row text-center" id="chdiv-{{$val->id}}">
				<div class="col-md-8 col-xs-8 challenge-text ">
					<h1>Joining fee: <b>{{$val->amount}}</b> start time:  <b>{{$val->t_date}}</b> Prize: <b style="color:red">{{@$val->prize}}</b> </h1>
				</div>
				<div class="col-md-4 col-xs-4">
					@if(@$val->userjoin->player_id == Auth::user()->id)
						<a href="{{ url('dashboard/contest/'.$val->slug) }}" class="btn btn-info " >Details</a>
					@elseif($val->player_count <= $val->joins->count())
						<a href="javascript:void(0)" class="btn btn-danger " >Full</a>
					@elseif($val->status == 2 || $val->status == 3)
						<a href="javascript:void(0)" class="btn btn-info " >Started</a>
					@elseif($val->status == 4)
						<a href="javascript:void(0)" class="btn btn-warning " >Locked</a>
					@else
						<a href="javascript:void(0)" class="btn btn-success join-contest" id="link-join-{{$val->id}}" contest-id="{{$val->id}}" >
							Join Now(<span id="user-join-coint-{{$val->id}}">{{$val->player_count - $val->joins->count()}}</span>)
						</a>
						<form action="{{route('dashboard.join-contest')}}" type="POST" id="join-contest-{{$val->id}}">
							@csrf
							<input type="hidden" name="t_id" value="{{$val->id}}" >
							<input type="hidden" name="amount" value="{{$val->amount}}" >
							<input type="hidden" name="ludo_king" id="ludo-king-{{$val->id}}" value="" >
						</form>
					@endif
				</div>
			</div>
			
        @endforeach
        
    </div>
      
	<div class="popup" data-pd-popup="add-ludoking-id" id="ludoking-modal">
		<div class="popup-inner">
			<div class="bet-details">
				<h1>
					Add Ludo King ID
				</h1>
				<div class="alert alert-danger" id="error-msg" style="text-align:left; color:red;display:none;"></div>
				
			</div>
			<form method="POST" action="javascript:void(0)" >
				@csrf
				<div class="form-group">
					<label>Enter ID</label>
					<input type="text" name="ludo_king_id" id="ludo_king_id" placeholder="Enter ID" value="" class="form-control" autocomplete="off">
					<div style="text-align:left; color:red;display:none;" id="ludo_king_id-error">Error select</div>
				</div>
					<input type="submit" value="Submit" id="ludo-king-submit" class="btn btn-primary form-control">
			</form
			<a class="popup-close" data-pd-popup-close="withdraw" href="#"> </a>
		</div>
	</div>
      
    </section>
  

@endsection

