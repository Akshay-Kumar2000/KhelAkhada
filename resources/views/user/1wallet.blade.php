@extends('layouts.front.front')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .wallet-card {
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }

        .wallet-card * {
            color: black;
        }
    </style>
    <div class="main-area" style="padding-top: 60px;">
        <div class="p-4 bg-light">
        </div>
        <div class="divider-x"></div>
        <div class="p-4 bg-light">
            <div class="wallet-card h-100">
                <div class="text-center mb-2" style="font-size: 0.9em; font-weight: 500;">Deposit Chips</div>
                <hr />
                <div class="alert alert-primary" style="font-size: 0.9em; color: black;">यह चिप्स Spin & Buy की गई चिप्स है
                    इनसे सिर्फ गेम खेले जा सकते है !! Bank या UPI में निकाला नही जा सकता है.</div>
                <div class="text-center" style="font-size: 0.9em; font-weight: 500;">Chips</div>
                <div class="d-flex align-items-center justify-content-center">
                    <div class="mr-1">
                        <!--<img height="26px" width="26px" src="{{ asset('front/images/global-rupeeIcon.png') }}" alt="">-->
                    </div>
                    <span style="font-size: 1.3em; font-weight: 900;">{{ $userData->wallet }}</span>
                </div>
                <a href="{{ url('add-money') }}" class="btn btn-primary text-white w-100 mt-2"
                    style="font-size: 0.9em;">Add</a>
            </div>
            <div class="wallet-card h-100">
                <div class="text-center mb-2" style="font-size: 0.9em; font-weight: 500;">Winning Chips</div>
                <hr />
                <div class="alert alert-primary" style="font-size: 0.9em; color: black;">यह चिप्स गेम से जीती हुई एवं रेफरल
                    से कमाई हुई है इन्हे Bank या UPI में निकाला जा सकता है !! इन चिप्स से गेम भी खेला जा सकता है.</div>
                <div class="text-center" style="font-size: 0.9em; font-weight: 500;">Chips</div>
                <div class="d-flex align-items-center justify-content-center">
                    <div class="mr-1">
                        <!--<img height="26px" width="26px" src="{{ asset('front/images/global-rupeeIcon.png') }}" alt="">-->
                    </div>
                    <span class="" style="font-size: 1.3em; font-weight: 900;">{{ $winningAmount }}</span>
                </div>
                <a href="{{ url('withdraw-request') }}" class="btn btn-primary text-white w-100 mt-2"
                    style="font-size: 0.9em;">Withdraw</a>
            </div>
        </div>
    </div>
    </div>
    <div class="divider-y"></div>
    @if (session()->has('error'))
        <script>
            Swal.fire({
                title: "Oops!",
                text: "{{ session()->get('error') }}",
                icon: "error"
            });
        </script>
    @endif
@endsection
