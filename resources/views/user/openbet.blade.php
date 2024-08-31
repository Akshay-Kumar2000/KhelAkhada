@foreach($myChallenges as $key => $mval)	
				<?php 
					if($mval->amount == 50){
						$a_amount	=	5;
					}elseif($mval->amount > 50 && $mval->amount <=250){
						$a_amount	=	10/100*($mval->amount);
					}elseif($mval->amount > 250 && $mval->amount <=500){
						$a_amount	=	25;
					}elseif($mval->amount > 500){
						$a_amount	=	5/100*($mval->amount);
					}
					$prize	=	(2 * $mval->amount) - $a_amount;
				?>
				@if($mval->status == 1 && $mval->c_id == Auth::user()->id)	
          

         
					<div class="betCard mt-1" id="chdiv-{{$mval->id}}">
						<div class="d-flex">
                          <span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR<img class="mx-1" src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt="">{{$mval->amount}}</span>
							<div class="betCard-title d-flex align-items-center text-uppercase">
								<span class="ml-auto" id="{{$mval->id}}-buttons">
									<button class="btn btn-danger px-3 btn-sm" onclick="cancelChallengeCre({{$mval->id}})">DELETE</button>
								</span>
							</div>
						</div>
						<div class="py-1 row">
							<div class="pr-3 text-center col-5">
							<div class="pl-2"><img class="border-50" src="{{ asset('front/images/author.svg')}}" width="21px" height="21px"
								alt=""></div>
							<div style="line-height: 1;"><span class="betCard-playerName">{{$mval->cname}}</span></div>
							</div>
							<div class="pr-3 text-center col-2 cxy">
							<div><img src="{{ asset('front/images/vs.png')}}" width="30px" alt=""></div>
							</div>
							<div class="text-center col-5">
							<div class="pl-2"><img class="border-50" id="{{$mval->id}}-loading" src="{{ asset('front/images/small-loading.gif')}}" width="21px" height="21px"
								alt=""></div>
							<div style="line-height: 1;"><span class="betCard-playerName" id="{{$mval->id}}-finding">Finding Player</span></div>
							</div>
						</div>						
					</div>
            
          
				@elseif($mval->status == 1 && $mval->o_id == Auth::user()->id)
          
					<div id="chdiv-{{$mval->id}}" class="betCard mt-1">
						<span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #f02236;">{{$mval->cname}} </span></span>
						<div class="d-flex pl-3">
							<div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$mval->amount}}</span>
							</div>
							</div>
							<div><span class="betCard-subTitle">Prize</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$prize}}</span>
							</div>
							</div><button id="{{$mval->id}}-play" class="bg-secondary playButton cxy" onclick="playChallenge({{$mval->id}});">Play</button>
						</div>
					</div>
          
          
				@elseif($mval->status ==2 && $mval->c_id == Auth::user()->id)
          
          
					<div class="betCard mt-1" id="chdiv-{{$mval->id}}">
						<div class="d-flex"><span class="betCard-title pl-3 d-flex align-items-center text-uppercase">PLAYING FOR<img
								class="mx-1" src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt="">{{$prize}}</span>
							<div class="betCard-title d-flex align-items-center text-uppercase">
								<span class="ml-auto" id="{{$mval->id}}-buttons">
									<button id="{{$mval->id}}-accept" class="btn btn-success px-3 btn-sm" style="cursor: pointer;float: left;width: 65px;height: 31px;" onclick="acceptChallenge({{$mval->id}})">START</button>
									<button id="{{$mval->id}}-deny" class="btn btn-danger px-3 btn-sm" style="cursor: pointer;float: right;width: 72px;height: 31px;" onclick="denyChallenge({{$mval->id}})">REJECT</button>
								</span>
							</div>
						</div>
						<div class="py-1 row">
							<div class="pr-3 text-center col-5">
							<div class="pl-2"><img class="border-50" src="{{ asset('front/images/author.svg')}}" width="21px" height="21px"
								alt=""></div>
							<div style="line-height: 1;"><span class="betCard-playerName">{{$mval->cname}}</span></div>
							</div>
							<div class="pr-3 text-center col-2 cxy">
							<div><img src="{{ asset('front/images/vs.png')}}" width="30px" alt=""></div>
							</div>
							<div class="text-center col-5">
							<div class="pl-2"><img class="border-50" src="{{ asset('front/images/author.svg')}}" width="21px" height="21px"
								alt=""></div>
							<div style="line-height: 1;"><span class="betCard-playerName">{{$mval->oname}}</span></div>
							</div>
						</div>						
					</div>
          
          
				@elseif($mval->status ==2 && $mval->o_id == Auth::user()->id)
          
          
					<div id="chdiv-{{$mval->id}}" class="betCard mt-1">
						<span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">You </span></span>
						<div class="d-flex pl-3">
							<div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$mval->amount}}</span>
							</div>
							</div>
							<div><span class="betCard-subTitle">Prize</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$prize}}</span>
							</div>
							</div>							
							<button id="{{$mval->id}}-requested" class="bg-warning playButton cxy" onclick="cancelChallengeReq({{$mval->id}})">Requested</button>
						</div>
					</div>	
        
				@elseif($mval->status == 3 && $mval->o_id == Auth::user()->id)
          
          
					<div id="chdiv-{{$mval->id}}" class="betCard mt-1">
						<span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">{{$mval->cname}} </span></span>
						<div class="d-flex pl-3">
							<div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$mval->amount}}</span>
							</div>
							</div>
							<div><span class="betCard-subTitle">Prize</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$prize}}</span>
							</div>
							</div>							
							<button id="{{$mval->id}}-start-btn" class="bg-success playButton cxy" onclick="startChallenge({{$mval->id}})">START</button>
						</div>
					</div>
          
				@endif
			@endforeach	
		
		
			@foreach($challenges as $key => $val)	
				<?php 
					if($val->amount == 50){
						$a_amount	=	5;
					}elseif($val->amount > 50 && $val->amount <=250){
						$a_amount	=	10/100*($val->amount);
					}elseif($val->amount > 250 && $val->amount <=500){
						$a_amount	=	25;
					}elseif($val->amount > 500){
						$a_amount	=	5/100*($val->amount);
					}
					$prize	=	(2 * $val->amount) - $a_amount;
				?>
        
        
				@if($val->c_id == Auth::user()->id)
        

					<div id="chdiv-{{$val->id}}" class="betCard mt-1">
						<span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">You </span></span>
						<div class="d-flex pl-3">
							<div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$val->amount}}</span>
							</div>
							</div>
							<div><span class="betCard-subTitle">Prize</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$prize}}</span>
							</div>
							</div>
							<span class="ml-auto" id="{{$val->id}}-buttons">
								<button class="bg-danger playButton cxy" onclick="cancelChallengeCre({{$val->id}})">Cancel</button>
							</span>
						</div>
					</div>
        
				@else($val->c_id != Auth::user()->id)
        
      
					<div id="chdiv-{{$val->id}}" class="betCard mt-1">
						<span class="betCard-title pl-3 d-flex align-items-center text-uppercase">CHALLENGE FROM<span class="ml-1" style="color: #072c92;">{{$val->cname}} </span></span>
						<div class="d-flex pl-3">
							<div class="pr-3 pb-1"><span class="betCard-subTitle">Entry Fee</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$val->amount}}</span>
							</div>
							</div>
							<div><span class="betCard-subTitle">Prize</span>
							<div><img src="{{ asset('front/images/global-rupeeIcon.png')}}" width="21px" alt=""><span class="betCard-amount">{{$prize}}</span>
							</div>
							</div><button id="{{$val->id}}-play" class="bg-secondary playButton cxy" onclick="playChallenge({{$val->id}});">Play</button>
						</div>
					</div>
     
@endif					
			@endforeach	