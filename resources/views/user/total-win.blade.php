@extends('layouts.front.front')
@section('content')


	<section>
      <div class="container">
        <div class="row">
          <div class="col-md-12 about-content">
            <h1>Total Win</h1>
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Match Info</th>
                  <th>Match Winner</th>
                  <th>Challenge Point</th>
                  <th>Date</th>
<!--
                  <th>Action</th>
-->
                </tr>
              </thead>
              <tbody>
				  @foreach($challenges as $id => $val)
                <tr>
                  <td>{{$val->creatorname->username ?? 'N/A'}} vs {{$val->opponentname->username ?? ' N/A '}}</td>
                  <td>
					  @if($val->c_result == 'WON' && $val->o_result == 'LOSS')
						<span class="badge badge-success" style="background-color:green;">You Won</span>
						@elseif($val->o_result == 'WON' && $val->c_result == 'LOSS')
						<span class="badge badge-success" style="background-color:green;">{{$val->opponentname->username ?? 'N/A'}} Won </span>
						@elseif($val->o_result == 'LOSS' && $val->c_result == 'LOSS')
						<span class="badge badge-danger" style="background-color:red;">Both are selected: Loss</span>
						@elseif($val->o_result == 'WON' && $val->c_result == 'WON')
						<span class="badge badge-danger" style="background-color:red;">Both are selected: Won</span>
						@elseif($val->o_result == 'CANCEL' && $val->c_result == 'CANCEL')
						<span class="badge badge-danger" style="background-color:red;">Both are selected: Cancel</span>
						@else
						<span class="badge badge-info" style="background-color:#31b0d5;">N/A</span>
					  @endif
					  
                  </td>
                  <td>{{$val->amount}}</td>
                  <td>{{$val->created_at}}</td>
<?php /*
                  <td>
					  <a href="javascript:void(0);" cid="{{$val->creatorname->id}}" oid="{{$val->opponentname->id ?? ''}}" omobile="{{$val->opponentname->mobile_no ?? ''}}" cmobile="{{$val->creatorname->mobile_no}}" amount="{{$val->amount }}" opponentname="{{$val->opponentname->username ?? ' N/A '}}" creatorname="{{$val->creatorname->username}}" data-pd-popup-open="challengeResult" class="btn btn-info pull-left final-result check-result">
                      <i class="fa fa-eye" aria-hidden="true"></i></a>
                  </td>
*/ ?>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    
    <div class="popup" data-pd-popup="challengeResult">
        <div class="popup-inner">
          <div class="bet-details">
            <h1>
              <span id="creatorname"></span> vs <span id="opponentname"></span> for Point <span id="amount"></span>/-
            </h1>
            <p class="oppenent">Oppenent Whatsapp Number <span id="oppenent-mobile"></span></p>
 
            <p style="font-size:17px;"><a style="text-decoration:none;" id="href" href="" target="_blank">Click here to message your oppenent</a></p>
          </div>
          <p class="bet-terms">Point 50 penalty charge if you update wrong and if you have won the game please post fair result for immediate balance transfer otherwise both player balance will be on hold and admin will have to take action and if do not update resolve within 20 minute of the match result will be on hold and you will be charged penalty.</p>
          <a class="popup-close" data-pd-popup-close="challengeResult" href="#"> </a>
        </div>
      </div>
      
 <script>
	 $(function () {
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
	 
		$(document).on('click', '[data-pd-popup-open]', function (e) {
			$('.popup').fadeIn(100);
			var omobile	=	$(this).attr('omobile');
			var cmobile	=	$(this).attr('cmobile');
			var cid	=	$(this).attr('cid');
			var oid	=	$(this).attr('oid');
			var amount	=	$(this).attr('amount');
			var opponentname	=	$(this).attr('opponentname');
			var creatorname	=	$(this).attr('creatorname');
			var user_id			=	{{ Auth::user()->id }};
			
			$('#opponentname').html(opponentname);
			$('#creatorname').html(creatorname);
			$('#amount').html(amount);
			
			if(cid == user_id && oid){
				$('#href').attr('href','https://wa.me/91'+omobile+'?text=How+To+Play,+Please+Guide+Me');
			}else if(oid == user_id && cid){
				$('#href').attr('href','https://wa.me/91'+cmobile+'?text=How+To+Play,+Please+Guide+Me');
			}else{
				$('#href').attr('href','javascript:void(0)');
				$('#href').removeAttr('target');
			}
			
		});
	});
 </script>     

@endsection

