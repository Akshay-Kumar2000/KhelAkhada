@extends('layouts.front.front')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="main-area" style="padding-top: 60px;">
        <div class="p-4 bg-light">

        </div>
        <div class="divider-x"></div>
        <div class="p-4 bg-light">
            <div class="wallet-card" style=" background-image: url(../../front/images/bg-wallets-deposit.png);">
                <div class="d-flex align-items-center">
                    <div class="mr-1"><img height="26px" width="26px"
                            src="{{ asset('front/images/global-rupeeIcon.png') }}" alt=""></div><span
                        class="text-white" style="font-size: 1.3em; font-weight: 900;">{{ $userData->wallet }}</span>
                </div>
                <div class="text-white text-uppercase" style="font-size: 0.9em; font-weight: 500;">Deposit Cash</div>
                <div class="mt-5" style="font-size: 0.9em; color: rgb(191, 211, 255);">Can be used to play Tournaments
                    &amp; Battles.<br>Cannot be withdrawn to Paytm or Bank.</div>
                <a href="{{ url('add-money') }}"
                    class="walletCard-btn d-flex justify-content-center align-items-center text-uppercase">Add Cash</a>
            </div>
            <div class="wallet-card" style="background-image: url(../../front/images/bg-wallets-deposit.png);">
                <div class="d-flex align-items-center">
                    <div class="mr-1"><img height="26px" width="26px"
                            src="{{ asset('front/images/global-rupeeIcon.png') }}" alt=""></div><span
                        class="text-white" style="font-size: 1.3em; font-weight: 900;">{{ $winningAmount }}</span>
                </div>
                <div class="text-white text-uppercase" style="font-size: 0.9em; font-weight: 500;">Winnings Cash</div>
                <div class="mt-5" style="font-size: 0.9em; color: rgb(216, 224, 255);">Can be withdrawn to Paytm or Bank.
                    Can be used to play Tournaments &amp; Battles.</div><a href="/universal-withdraw"
                    class="walletCard-btn d-flex justify-content-center align-items-center text-uppercase">Withdraw</a>
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
