@extends('layouts.front.front')

@push('meta')
<meta name="robots" content="noindex">
@endpush

@section('content')

<!-- <div class="leftContainer">
      <div class="headerContainer">
         <a id="menu-toggle" class="cxy h-100">
            <div class="sideNavIcon mr-2"><img src="{{asset('front/images/nav-menu-icon.svg')}}" alt=""></div>
         </a>
         <a href="index.html">
            <div class="ml-2 navLogo d-flex"><img src="{{asset('front/images/logo.png')}}" alt=""></div>
         </a>
         <div class="menu-items"><a type="button" class="login-btn" href="login.html">LOGIN</a></div>
         <span class="mx-5"></span>
      </div> -->

      <div class="main-area" style="padding-top: 60px;">
         <!--<div class="collapseCard-container">-->
         <!--   <div class="collapseCard">-->
         <!--      <a href="#" style="text-decoration: none;" target="_blank">-->
         <!--         <div class="collapseCard-body"-->
         <!--            style="height: 64px; opacity: 1; transition: height 0.3s ease 0s, opacity 0.3s ease 0s;">-->
         <!--            <div class="collapseCard-text">How to win money?</div>-->
         <!--            <div class="collapseCard-closeIcon"><img class="position-relative" src="{{asset('front/images/close-icon.png')}}"-->
         <!--                  alt="" width="14px" height="14px">-->
         <!--               </div>-->
         <!--         </div>-->
         <!--      </a>-->
               <!--<div class="collapseCard-header" style="left: 22px; transition: left 0.3s ease 0s;">-->
               <!--   <div><img height="10px" width="14px" src="{{asset('front/images/youtub-icon.png')}}" alt=""></div>-->
               <!--   <div class="collapseCard-title ml-1 mt-1">Video Help</div>-->
               <!--</div>-->
         <!--   </div>-->
         <!--</div>-->
         <section class="games-section" style="padding-top: 0 !important;">
             <p style="background: #dc3545!important;margin-bottom:5px;height: 30px;padding: 3px;margin: 0;text-align: center;color: white;">Commission: 5% ,Refral : 3% for all games</p>
         </section>
         <section class="games-section p-3" style="padding-top: 0 !important;">
             <!--<div class="m-1 text-danger">-->
             <!--    Notice - OTP किसी को ना दे। हमारी टीम किसी भी काम के लिए OTP नही लेती है। यदि आप किसी को OTP देते है तो उसके जिम्मेदार आप होंग। -->

             <!--</div>-->
             @if($kyc == 0)
			<div class="card text-center mt-3">
				<div style="display:flex;align-items: center;align-content: center;flex-wrap: nowrap;flex-direction: row;justify-content: space-around;padding:5px;">
					<div class="ml-1 mt-2 mytext" style="color:red; font-weight:800;">Complete KYC to take Withdrawals</div>
					<a href="{{ url('/complete-kyc/step1') }}" class="btn btn-danger" style="width: 210px;"> Complete KYC </a>
				</div>
			</div>
			@endif
            <div class="d-flex align-items-center games-section-title">Our Games</div>
            <div class="games-section-headline mt-2 mb-1">

               <div class="games-window">
                  <div class="gameCard-container">
                    <div class="text-danger"><span class="blink text-danger d-block text-right">◉ Ludo Classic Only (LIVE)</span></div>
                     <a class="gameCard" href="{{url('challenges')}}">
                        <div class="gameCard-image"><img width="100%" src="{{asset('front/images/ludo-2.jpg')}}" alt=""></div>
                        <div class="gameCard-title">Ludo Classic</div>
                        <div class="gameCard-icon"><img src="{{asset('front/images/award-blue.png')}}" alt=""></div>
                     </a>
                  </div>
                  <div class="gameCard-container">
                     <span class="blink text-danger d-block text-right">◉ Ludo Classic Only (LIVE)</span>
                     <a class="gameCard" href="{{url('challenges')}}">
                        <div class="gameCard-image">
                          <img width="100%" src="{{asset('front/images/ludo-1.jpg')}}" alt=""></div>
                        <div class="gameCard-title">Ludo Classic</div>
                        <div class="gameCard-icon"><img src="{{asset('front/images/award-blue.png')}}" alt=""></div>
                     </a>
                  </div>
                  <!--<div class="gameCard-container">-->
                  <!--   <span class="blink text-danger d-block text-right">◉ COMING SOON</span>-->
                  <!--   <a class="gameCard" href="#">-->
                  <!--      <div class="gameCard-image">-->
                  <!--        <img width="100%" src="{{asset('front/images/ludo2.png')}}" alt=""></div>-->
                  <!--      <div class="gameCard-title">Quick Ludo</div>-->
                  <!--      <div class="gameCard-icon"><img src="{{asset('front/images/award-blue.png')}}" alt=""></div>-->
                  <!--   </a>-->
                  <!--</div>-->
                  <!--<div class="gameCard-container">-->
                  <!--   <span class="blink text-danger d-block text-right">◉ COMING SOON</span>-->
                  <!--   <a class="gameCard" href="#">-->
                  <!--      <div class="gameCard-image"><img width="100%" src="{{asset('front/images/download.jpg')}}" alt=""></div>-->
                  <!--      <div class="gameCard-title">Snake & Ladder</div>-->
                  <!--      <div class="gameCard-icon"><img src="{{asset('front/images/award-blue.png')}}" alt=""></div>-->
                  <!--   </a>-->
                  <!--</div>-->
               </div>

            </div>
              <section class="footer" style="margin-top:15px">
            <div class="footer-divider"></div>
            <div class="footer-menu p-4">
               <div class="row footer-links">
                  <div class="col-12 pb-3">
                     <div class="footer-logo">
                         <img width="80px" src="/copyright.png" style="width:100%"> <br>
                         <img width="80px" src="/front/images/khelmoj123.png" alt="">
                    </div>
                  </div>
                  <a class="col-6" href="/about-us">About us</a>
                  <a class="col-6" href="/terms-and-conditions">Terms & Condition</a>
                  <a class="col-6" href="/privacy-policy">Privacy Policy</a>
                  <a class="col-6" href="/refund-and-cancellation-policy">Refund/Cancellation Policy</a>
                  <a class="col-6" href="/contact-us">Contact Us</a>
                  <a class="col-6" href="/responsible-gaming">Responsible Gaming</a>
                  <a class="col-6" href="/platform-commission">Platform Commission</a>
               </div>
            </div>

            <div class="footer-divider"></div>

         </section>
            <div class="px-3 py-4">
      <div class="footer-text-bold">About Us</div><br>
      <div class="footer-text">AK Adda is a real-money gaming product owned
         ("AK Adda" or "We" or "Us" or "Our").</div><br>
      <div class="footer-text-bold">Our Business &amp; Products</div><br>
      <div class="footer-text">We are an HTML5 game-publishing company and our mission is to make accessing games fast
         and easy by removing the friction of app-installs.</div><br>
      <div class="footer-text">AK Adda is a skill-based real-money gaming platform accessible only for our users in
         India. It is accessible on <a target="_blank" rel="noopener noreferrer"
            href="https://akadda.com/">https://akadda.com/</a>. On akadda, users can compete for real cash in
         Tournaments and Battles. They can encash their winnings via popular options such as Paytm Wallet, Amazon Pay,
         Bank Transfer, Mobile Recharges etc.</div><br>
      <div class="footer-text-bold">Our Games</div><br>
      <div class="footer-text">AK Adda has a wide-variety of high-quality, premium HTML5 games. Our games are especially
         compressed and optimised to work on low-end devices, uncommon browsers, and patchy internet speeds.</div><br>
      <div class="footer-text">We have games across several popular categories: Arcade, Action, Adventure, Sports &amp;
         Racing, Strategy, Puzzle &amp; Logic. We also have a strong portfolio of multiplayer games such as Ludo, Chess,
         8 Ball Pool, Carrom, Tic Tac Toe, Archery, Quiz, Chinese Checkers and more! Some of our popular titles are:
         Escape Run, Bubble Wipeout, Tower Twist, Cricket Gunda, Ludo With Friends. If you have any suggestions around
         new games that we should add or if you are a game developer yourself and want to work with us, don't hesitate
         to drop in a line at <a href="#">support@akadda.com</a>!</div>
   </div>
         </section>
         <section class="footer">
            <div class="footer-divider"></div>
            <div class="footer-menu p-4">
               <div class="row footer-links">
               </div>
            </div>

            <div class="footer-divider"></div>

         </section>

      </div>
   </div>
   <div class="divider-y"></div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Assuming the payment status is passed as a query parameter or available in a variable
            const urlParams = new URLSearchParams(window.location.search);
            const paymentStatus = urlParams.get('payment_status'); // Example: "success" or "failed"

            if (paymentStatus === 'success') {
                // Handle successful payment
                alert('Payment was successful!');
                // You can update the user's wallet or do other things here
            } else if (paymentStatus === 'failed') {
                // Handle failed payment
                alert('Payment failed. Please try again.');
            }

            // You can also make an AJAX call to update the wallet balance or fetch updated information
        });
    </script>
@endsection
