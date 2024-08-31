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

	/* Result Submit Screen */

.result-container{
  border: 1px solid #ddd;
  padding-bottom: 50px;
  padding-top: 20px;
}

.result-submit-block h4 {
  color: #db0f12;
}

.recorder-block, .penalty-block{
  width: 50%;
  margin: 0 auto;
  border: 1px solid #333;
  padding: 10px;
  border-radius: 25px;
  margin-top: 40px;
}

.penalty-block {
  margin-top: 20px;
}

.recorder-block p, .penalty-block p {
  margin-bottom: 0;
}

.recorder-block p span {
  font-weight: 600;
}

#result_img-res{
  margin: 0 auto;
  width: 225px;
  padding: 20px;
}

.img-preview {
  width: 200px;
}



@media only screen and (max-width:767px)
{
    .recorder-block, .penalty-block{
      width: 100%;
    }
}
</style>


<script>
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
			$('#blah')
				.attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	function copyClipboard() {
	  /* Get the text field */
	  var copyText = document.getElementById("res-room-id");
	  var res_room 	=	$("#res-room-id").val();

		if(res_room && res_room!= null){
		  /* Select the text field */
		  copyText.select();
		  copyText.setSelectionRange(0, 99999); /* For mobile devices */

		  /* Copy the text inside the text field */
		  document.execCommand("copy");

		  /* Alert the copied text */
		  alert("Room Code Copied");
	  }else{
		  alert("Room Code not found");
	  }
	}
	
	$(function () {
		 
		 setTimeout(location.reload.bind(location), 60000);
		 
		 
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		 
		  $("#result-won-res").click(function(){
			  $("#upload-image-res").show();
			  $("#screeshot-preview").show();
		  });

		  $("#result-loss-res").click(function(){
			  $("#upload-image-res").hide();
			  $("#screeshot-preview").hide();
		  });

        });
	
	</script>
    <section>
			<div class="row">
				<div class="col-md-12 buy-chips-content">
					<h1>Submit Result</h1>
					<div class="container text-center result-container">
                        <div class="result-submit-block">
						<h2><b id="wait-rrom-code" class="blink_me" style="font-size: 18px;">Congratulation! You are at Level {{$contest->current_level}}</b>
						<b><a class="pull-right" style="text-decoration:none;font-size:18px" href="route('dashboard.contests')" >Contests</a></b></h2>
                            <hr>
							
							<h4 id="res-room-id">Your Room Code is : {{@$rcode}}</h4>
							

							<a href="javascript:void(0)" type="button" id="clipboard" onclick="copyClipboard()" class="btn btn-info " >Copy Room Code</a>
							
                        </div>
                        <div class="recorder-block">
                            <p>Use AZ Recorder App to record game</p>
                            <p>For cancelling game, <span>VIDEO PROOF</span> is necessary otherwise game will not be cancelled.</p>
                        </div>
                        <div class="penalty-block">
                            <p>40 Penallty = Wrong Update. 25 Penallty = If you don't update after losing.</p>
                        </div>
                        <hr>

						<form action="{{route('dashboard.contest-submit-result')}}" method="POST"  enctype="multipart/form-data">

							<input type="hidden" name="room_code" value="{{$rcode}}" >
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="slug" value=" {{$contest->slug}}">
							<input type="hidden" name="level" value=" {{$contest->current_level}}">
							
							<label class="radio-inline">
								<input type="radio" name="result" value="WON" id="result-won-res" class="result-won" required >I Won
							</label>
							<label class="radio-inline">
								<input type="radio" name="result" value="LOSS" id="result-loss-res" class="result-loss">I Loss
							</label>

							<div style="display:none; color:red" id="result-submit-error-res"></div>
							<div style="display:none;" id="upload-image-res">
								<div class="form-group result-input-group">
								<input type="file" name="result_img" id="result_img-res" onchange="readURL(this);">
								<div style="display:none; color:red" id="result-error-res"></div>
								</div>
								<div class="form-group result-input-group">
								
								</div>
								<div class="form-group result-input-group">
								
								</div><br/>
							</div>
							
							<div class="form-group result-input-group" id="result-submit-btn-res" >
							<button type="submit" class="btn btn-danger">Submit</button>
							</div>
							<div class="screeshot-preview" id="screeshot-preview" style="display:none;">
								<img id="blah" src="http://placehold.it/180" class="img-preview" alt="your image" />
							</div>
						</form>

                        
					</div>
				</div>
			</div>
		</section>
  

@endsection

