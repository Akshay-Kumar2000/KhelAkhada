@extends('layouts.front.front')
@section('content')

            <div class="main-area" style="padding-top: 60px;">
               <div class="p-3" style="background: rgb(250, 250, 250);">
                 
                  <div class="center-xy py-2">
                     <div><img class="border-50" height="80px" width="80px" src="{{asset('front/images/author.svg')}}" alt=""></div>
                     <span class="battle-input-header mr-1">{{ Auth::user()->mobile }}</span>
                     <div class="text-bold my-3" id="profile-edit-div"><span id="player-username">{{ Auth::user()->username }}</span><img class="ml-2" id="profile-edit-icon" width="20px" src="{{asset('front/images/icon-edit.jpg')}}" alt="" style="cursor: pointer;"></div>
                     <div class="text-bold my-3" id="profile-username-div" style="display:none">
                        <form action="javascript:void(0)" id="change-unique-id" method="POST" >
                           <input class="profile-wallet" name="username" value="{{Auth::user()->username}}" style="height: 35px;" id="unique-id"/>
                        </form>
                     </div>
                     
                  </div>
               </div>
               <div class="divider-y"></div>
			    <div class="p-3">
                 
                  <div class="text-bold">Complete Profile</div>
                  <div class="kyc-complete">
                   
                    
                        <div class="react-swipeable-view-container ">
                           <div>
                          @if($userDatac != 0)
				        @if($userData[0]->verify_status == 1)
						   <a class="d-flex align-items-center profile-wallet bg-light mx-1 my-4 py-3" href="/complete-kyc/approve">
							<picture class="ml-4"><img width="32px" src="{{asset('frontend/images/kyc-icon-new.png')}}" alt=""></picture>
							   <div class="ml-5 mytext text-muted "><span style="float:left; margin-top:8px;">KYC Completed &nbsp;  </span><img src="{{ asset('/backend/img/approved.png') }}" style="width:35px;"></div>
						</a>
						@elseif($userData[0]->verify_status == null || $userData[0]->verify_status == 0)
						<a class="d-flex align-items-center profile-wallet bg-light mx-1 my-4 py-3" href="{{url('/complete-kyc/step1')}}">
							<picture class="ml-4"><img width="32px" src="{{asset('frontend/images/kyc-icon-new.png')}}" alt=""></picture>
							<div class="ml-5 mytext text-muted "><span style="float:left; margin-top:8px;">KYC Rejected &nbsp;  </span><img src="{{ asset('/backend/img/rejected.png') }}" style="width:35px;"></div>
						</a>
						@elseif($userData[0]->verify_status == 2)
							    <a class="d-flex align-items-center profile-wallet bg-light mx-1 my-4 py-3" href="{{url('complete-kyc/kyc-submit')}}">
									<picture class="ml-4"><img width="32px" src="{{asset('frontend/images/kyc-icon-new.png')}}" alt=""></picture>
									<div class="ml-5 mytext text-muted "><span style="float:left; margin-top:8px;">KYC Under Review&nbsp;  </span><img src="{{ asset('/backend/img/under_review.png') }}" style="width:35px;"></div>
								</a>
						@else
							<a class="d-flex align-items-center profile-wallet bg-light mx-1 my-4 py-3" href="{{url('/complete-kyc/step1')}}">
								<picture class="ml-4"><img width="32px" src="{{asset('frontend/images/kyc-icon-new.png')}}" alt=""></picture>
								<div class="ml-5 mytext text-muted ">Complete Your KYC</div>
							</a>
						@endif
						@endif
                          </div>
                          </br>
                          
                          
                        </div>
                     
                    
                  </div>
 
               </div>
              <!----
               <div class="p-3">
                 
                  <div class="text-bold">Complete Profile</div>
                  <div class="kyc-complete">
                   
                    
                        <div class="react-swipeable-view-container ">
                           <div>
                              <a class="d-flex align-items-center profile-wallet bg-light mx-1 mt-3 py-3" href="#">
                                 <div class="ml-4"><img width="32px" src="{{asset('front/images/kyc-icon-new.png')}}" alt=""></div>
                                 <div class="ml-5 mytext text-muted ">Complete KYC</div>
                              </a>

                          </div>
                          </br>
                          
                          
                           <div>
                              <a class="d-flex align-items-center profile-wallet bg-light mx-1 my-3 py-3" href="#">
                                 <div class="ml-4"><img width="32px" src="{{asset('front/images/mail.png')}}" alt=""></div>
                                 <div class="ml-5 mytext text-muted ">Update Email ID</div>
                              </a>
                           </div>
                        </div>
                     
                    
                  </div>
 
               </div>
---->
               


<!--------refrels----->      
              
              
             <div class="mb-3 shadow card">
               <center><div class="bg-light text-dark card-header"><b>Metrics</b></div></center>
<div class="card-body">
<div class="g-0 gx-2 mb-2 row">
<div class="col">
<div class="d-flex flex-column align-items-stretch justify-content-start h-100 w-100 card">
<div class="text-capitalize text-start px-2 card-header" style="font-size: 0.9rem;"><div class="hstack gap-1">
<img src="https://ludoplayers.com/static/media/sword.9cc91e4925dc62491c20.webp" width="16px" alt="games played">
<span>games played</span>
</div></div>
<div class="fs-5 fw-semibold text-start py-1 px-2 card-body">{{$total_games}}</div>
</div></div>
<div class="col">
<div class="d-flex flex-column align-items-stretch justify-content-start h-100 w-100 card">
<div class="text-capitalize text-start px-2 card-header" style="font-size: 0.9rem;"><div class="hstack gap-1">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="currentColor">
<path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"></path>
<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
<path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z">
</path></svg>
<span>chips won</span>
</div></div>
<div class="fs-5 fw-semibold text-start py-1 px-2 card-body">{{$total_won}}</div>
</div></div></div>
<div class="g-0 gx-2 row"><div class="col">
<div class="d-flex flex-column align-items-stretch justify-content-start h-100 w-100 card">
<div class="text-capitalize text-start px-2 card-header" style="font-size: 0.9rem;">
<div class="hstack gap-1">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="currentColor">
<path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"></path></svg>
<span>referral earning</span>
</div></div>
<div class="fs-5 fw-semibold text-start py-1 px-2 card-body">0.00</div>
</div></div>
<div class="col">
<div class="d-flex flex-column align-items-stretch justify-content-start h-100 w-100 card">
<div class="text-capitalize text-start px-2 card-header" style="font-size: 0.9rem;">
<div class="hstack gap-1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" fill="currentColor">
<path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
</svg>
<span>Penalty</span>
</div></div>
<div class="fs-5 fw-semibold text-start py-1 px-2 card-body">0.00</div>
</div></div></div></div></div>
              
              
               <div class="divider-x"></div>
               <div class="p-3">
                 <a href="{{route('logout')}}" class="center-xy text-uppercase py-2 border border-danger bg-danger text-white">Log Out</a>
              </div>
               
            </div>
         </div>
   <script>
      $(function () {		 
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

         $("#profile-edit-icon").click(function(){
            $('#profile-edit-div').hide();
            $('#profile-username-div').show();
         });

         $('#unique-id').focusout(function(e){
            e.preventDefault();
            var uid	=	$(this).val();
            var flag 	=	1;
            if(!uid){
               alert("Unique ID may not be empty!");
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
                        alert(data.message);
                        $('#profile-edit-div').show();
                        $('#profile-username-div').hide();
                        $('#player-username').text(uid);
                     }
                  }
                  
               });
            }
            
         });

         $('#used-referral').focusout(function(e){
            e.preventDefault();
            var referral	=	$(this).val();
            var flag 	=	1;
            if(!referral){
               alert('Please enter referral code');				
               flag = 0;
            }else if(referral.length != 8){
               alert('Please enter 8 digit referral code');
               flag = 0;
            }
            
            if(flag){
               $form = $(this);
               
               $.ajax({
                  type: "POST",
                  dataType: 'json',
                  url: '{{ route('use-referral-code') }}',
                  data: $form.serialize(),
                  success:function(data){
                     if(data.message){
                        alert(data.message);
                        $("#used-referral").prop('disabled', true);
                        $('#referral-checked').show();
                        // $('#profile-username-div').hide();
                        // $('#player-username').text(uid);
                     }
                  }
                  
               });
            }
            
         });
		});
   </script>
@endsection

