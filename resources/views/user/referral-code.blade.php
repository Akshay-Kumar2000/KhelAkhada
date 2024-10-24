@extends('layouts.front.front')
@section('content')
    <div class="main-area" style="padding-top: 60px;">
        <div class="center-xy">
            <div class="divider-x"></div>
        </div>
    </div>

    <div class="mb-3 card">
        <div class="bg-light text-dark card-header">
            <center><b>Your Referral Earnings</b></center>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex flex-column border-end flex-grow-1 align-items-center justify-content-center">
                    <span class="text-capitalize fw-bold" style="font-size: 0.8rem;"><b>referred players</b></span>
                    <span>{{ $uReferral }}</span>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center flex-grow-1">
                    <span class="text-capitalize fw-bold" style="font-size: 0.8rem;"><b>Referral Earning</b></span>
                    <span>₹{{ $refEarning }}</span>
                </div>
            </div>
        </div>
    </div>

    <!--------ludoplayers-------->

    <div class="mb-3 card">
        <div class="bg-light text-dark card-header">
            <center><b>Referral Code</b></center>
        </div>
        <div class="card-body">
            <div>
                <div>
                    <!-----<center><img src="https://ludo-players.s3.ap-south-1.amazonaws.com/cdn/lp/illustrations/refer.webp" alt="refer" width="150" height="150"></center>---->
                </div>
                <div>
                    <div>
                        <div>
                            <div class="input-group">

                                <input type="text" class="form-control p-2" disabled=""
                                    value="{{ $userData->setting->referral }}"id="myInput2">
                                <button class="btn btn-primary text-uppercase" onclick="myFunction()">copy</button>
                            </div>
                        </div>
                    </div>
                    <p class="text-uppercase fw-bold fs-3 p-0 m-0 my-3">
                        <center>
                            <h5>OR</h5>
                        </center>
                    </p>
                    <div class="d-grid">
                        <a
                            href="whatsapp://send?text=Play Ludo and earn Rs10000 daily.%0ACommission Charge - 5% Only%0AReferral - 3% On All Games%0A24x7 Live Chat Support%0AInstant Withdrawal Via UPI/Bank%0ARegister Now, My refer code is {{ $userData->setting->referral }}.%0A👇%0Ahttps://akplayers.com/login?referral={{ $userData->setting->referral }}">
                            {{-- href="whatsapp://send?text=Play Ludo and earn Rs10000 daily.%0ACommission Charge - 5% Only%0AReferral - 3% On All Games%0A24x7 Live Chat Support%0AInstant Withdrawal Via UPI/Bank%0ARegister Now, My refer code is {{ $userData->setting->referral }}.%0A👇%0Ahttps://akplayers.com/login?referral={{ $userData->setting->referral }}"> --}}
                            <button class="btn btn-success btn-md w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="1em" height="1em"
                                    fill="currentColor" class="me-2">
                                    <path
                                        d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z">
                                    </path>
                                </svg>
                                <span class="text-capitalize">share on whatsapp</span>
                            </button>
                        </a>
                    </div>
                    <div class="d-grid mt-2">
                        <input hidden type="text" value="{{ $userData->setting->referral }}" id="myInput">
                        <button class="btn btn-secondary btn-md w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="1em" height="1em"
                                fill="currentColor" class="me-2">
                                <path
                                    d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z">
                                </path>
                                <path
                                    d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z">
                                </path>
                            </svg>
                            <span class="text-capitalize" onclick="myFunction()">copy to clipboard</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------ludoplayers-------->

    <div class="divider-x"></div>

    <div class="mb-3 card">
        <div class="bg-light text-dark card-header">
            <center><b>How It Works</b></center>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">You can refer and <b>Earn 2%</b> of your referral winning, every time</li>
                <li class="list-group-item">Like if your player plays for <b>10000</b> and wins, You will get <b>₹200</b> as
                    referral amount.</li>
            </ul>
        </div>
    </div>

    <script>
        function myFunction() {
            // Get the text field
            var copyText = document.getElementById("myInput2");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 999999999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            // Alert the copied text
            alert("Referral Code Copied: " + copyText.value);
        }
    </script>


    <script>
        function myFunction() {
            // Get the text field
            var copyText = document.getElementById("myInput");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 999999999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            // Alert the copied text
            alert("Referral Code Copied: " + copyText.value);
        }
    </script>


    <div class="divider-y"></div>

    <script>
        function copyClipboard(id) {
            var r = document.createRange();
            r.selectNode(document.getElementById(id));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(r);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            alert('Referral code copied!');
        }

        function shareOnWA(referral) {
            var text = 'Play+Ludo+and+earn+₹10000+daily.++';
            text += 'https://khelmoj.in/login?referral=' + referral + '.++';
            text += 'Register+Now,+My+refer+code+is+' + referral + '.';

            window.location.href = 'https://wa.me/?text=' + text;
        }
    </script>
@endsection
