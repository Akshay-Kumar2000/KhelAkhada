@extends('layouts.front.front')
@section('content')

<div class="main-area" style="padding-top: 60px;">
    @if ($transactions->hasPages())
         <nav aria-label="pagination navigation" class="MuiPagination-root d-flex justify-content-center">
            <ul class="MuiPagination">
            @if ($transactions->onFirstPage())
                <li><a href="#" aria-disabled="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></li>
            @else
                <li><a href="{{ $transactions->previousPageUrl() }}"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></li>                
            @endif
               <li><a @if($page == 1 || $page == 0) class="active" @endif href="https://khelmoj.in/referral-history?page=1">1</a></li>
               <li><a @if($page == 2) class="active" @endif href="/referral-history?page=2">2</a></li>
               <li><a @if($page == 3) class="active" @endif href="/referral-history?page=3">3</a></li>
               <li><a @if($page == 4) class="active" @endif href="/referral-history?page=4">4</a></li>
               <li><a @if($page == 5) class="active" @endif href="/referral-history?page=5">5</a></li>
               @if ($transactions->hasMorePages())
                    <li><a href="{{ $transactions->nextPageUrl() }}" ><i class="fa fa-chevron-right" aria-hidden="true"></i></a></li>                    
                @else
                    <li><a href="#" aria-disabled="true"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></li>
                @endif
            </ul>
         </nav>
    @endif
    <script>
function myFunction() {
  location.replace("{{url('transactions')}}")
}
function myFunction2() {
  location.replace("{{url('game-history')}}")
}
function myFunction3() {
  location.replace("{{url('referral-history')}}")
}
</script>
    <div class="d-flex align-items-center justify-content-start overflow-auto pt-3 px-0 container">
      <span class="text-capitalize me-2 py-2 px-4 border text-dark badge rounded-pill" style="cursor: pointer;"onclick="myFunction()">Payment</span>
      <span class="text-capitalize me-2 py-2 px-4 border text-dark badge rounded-pill" style="cursor: pointer;"onclick="myFunction2()">Game</span>
      <span class="text-capitalize me-2 py-2 px-4 border text-dark badge rounded-pill text-white bg-primary" style="cursor: pointer;"onclick="myFunction3()">Referral</span>
    </div>
         @foreach($transactions as $id => $val)                    
            <div class="w-100 py-3 d-flex align-items-center list-item">
                <div class="center-xy list-date mx-2">
                <div>{{ date("d M", strtotime($val->created_at))}}</div><small>{{ date("H:i A", strtotime($val->created_at))}}</small>
                </div>
                <div class="list-divider-y"></div>
                <div class="mx-3 d-flex list-body">
                  <div class="d-flex align-items-center">
                      <div class="mr-2"><img height="32px" width="32px" src="{{ asset('front/images/ludo.jpeg')}}" alt=""
                            style="border-radius: 5px;"></div>
                  </div>
                  <div class="d-flex flex-column font-8">Referral earning.<div class="games-section-headline">Earned From:
                        <b>{{$val->remark}}</b></div>
                  </div>
                </div>
                <div class="right-0 d-flex align-items-end pr-3 flex-column">
                  <div class="d-flex float-right font-8">
                      <div class="text-success">(+)</div>
                      <div class="ml-1 mb-1"><img height="21px" width="21px" src="{{ asset('front/images/global-rupeeIcon.png')}}" alt="">
                      </div><span class="pl-1">{{$val->amount}}</span>
                  </div>
                 <div class="games-section-headline" style="font-size: 0.6em;">Closing Balance:  {{$val->closing_balance}}</div> 
                </div>
            </div>
         @endforeach


         @if($transactions->count() == 0 )
            <div class="cxy flex-column px-4 text-center" style="margin-top: 70px;"><img src="{{asset('front/images/no-data.jpg')}}"
                width="280px" alt="">
                <div class="games-section-title mt-4" style="font-size: 1.2em;">No transactions yet!</div>
                <div class="games-section-headline mt-2" style="font-size: 0.85em;">Seems like you haven’t done any activity
                yet</div>
            </div>
         @endif
      </div>
      <div class="divider-y"></div>

@endsection

