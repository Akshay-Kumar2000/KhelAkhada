@extends('layouts.front.front')
@section('content')

<div class="main-area" style="padding-top: 60px;">
         <nav aria-label="pagination navigation" class="MuiPagination-root d-flex justify-content-center">
            <ul class="MuiPagination">
               <li><a href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></li>
               <li><a class="active" href="#">1</a></li>
               <li><a href="#">1</a></li>
               <li><a href="#">2</a></li>
               <li><a href="#">3</a></li>
               <li><a href="#">4</a></li>
               <li><a href="#">5</a></li>
               <li><a href="#"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></li>
            </ul>
         </nav>

         <div class="w-100 py-3 d-flex align-items-center list-item">
            <div class="center-xy list-date mx-2">
               <div>3 Jun</div><small>1:52 PM</small>
            </div>
            <div class="list-divider-y"></div>
            <div class="mx-3 d-flex list-body">
               <div class="d-flex align-items-center"></div>
               <div class="d-flex flex-column font-8">Cash added using UPI.<div class="games-section-headline">Order ID:
                     TX-1647453714</div>
               </div>
            </div>
            <div class="right-0 d-flex align-items-end pr-3 flex-column">
               <div class="d-flex float-right font-8">
                  <div class="text-success">(+)</div>
                  <div class="ml-1 mb-1"><img height="21px" width="21px" src="images/global-rupeeIcon.png" alt="">
                  </div><span class="pl-1">100</span>
               </div>
               <div class="games-section-headline" style="font-size: 0.6em;">Closing Balance: 100</div>
            </div>
         </div>
         <!-- <div class="cxy flex-column px-4 text-center" style="margin-top: 70px;"><img src="images/no-data.jpg"
               width="280px" alt="">
            <div class="games-section-title mt-4" style="font-size: 1.2em;">No transactions yet!</div>
            <div class="games-section-headline mt-2" style="font-size: 0.85em;">Seems like you haven’t done any activity
               yet</div>
         </div> -->
      </div>
      <div class="divider-y"></div>

@endsection

