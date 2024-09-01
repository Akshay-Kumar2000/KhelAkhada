@extends('layouts.front.front')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="main-area" style="padding-top: 50px;">
        <div class="px-4 py-3">
            <!--<div class="games-section">-->
            <!--   <div class="d-flex position-relative align-items-center">-->
            <!--      <div class="games-section-title">Choose amount to add</div>-->
            <!--   </div>-->
            <!--</div>-->
            <!--<p style="color:red;text-align:left"><b>Note: </b> सफलतापूर्वक पेमेंट हो जाने के बाद <b>Got it</b> आने तक का इंतजार करे अनेथा आपका पेमेंट ऐड नहीं होगा </p>-->
            <div class="pb-3">
                @if (isset($_GET['test']))
                    <form method="post" id="make-payment" action="{{ route('add-money') }}">
                    @else
                        <form method="post" id="make-payment" action="{{ route('add-money-new') }}">
                @endif
                @csrf
                <div class="MuiFormControl-root mt-0 MuiFormControl-fullWidth">
                    <div class="MuiFormControl-root MuiTextField-root">
                        <label
                            class="MuiFormLabel-root MuiInputLabel-root MuiInputLabel-formControl MuiInputLabel-animated MuiInputLabel-shrink">Enter
                            Amount</label>
                        <div
                            class="MuiInputBase-root MuiInput-root MuiInput-underline jss13 MuiInputBase-formControl MuiInput-formControl MuiInputBase-adornedStart">
                            <div class="MuiInputAdornment-root MuiInputAdornment-positionStart">
                                <p class="MuiTypography-root MuiTypography-body1 MuiTypography-colorTextSecondary">₹</p>
                            </div>
                            <input aria-invalid="false" type="text" name="orderAmount" id="add-amount" value=""
                                class="MuiInputBase-input MuiInput-input MuiInputBase-inputAdornedStart" autocomplete="off">
                            @if ($errors->has('orderAmount'))
                                <span style="color:red">{{ $errors->first('orderAmount') }}</span>
                            @endif
                        </div>
                        <p class="MuiFormHelperText-root">Min: 10, Max: 20000</p>
                    </div>
                </div>

                <div class="games-window">
                    <div class="gameCard-container" style="border: 1px solid;" onclick="getAmount(100)">
                        <div class="add-fund-box addfund-box">
                            <div style="display: flex; align-items: baseline;">
                                <div class="collapseCard-title mr-1">₹</div>
                                <div class="collapseCard-title payment-amount" style="font-size:25px;">100</div>
                            </div>
                        </div>
                    </div>
                    <div class="gameCard-container px-0" style="border: 1px solid;" onclick="getAmount(250)">
                        <div class="add-fund-box addfund-box">
                            <div style="display: flex; align-items: baseline;">
                                <div class="collapseCard-title mr-1">₹</div>
                                <div class="collapseCard-title payment-amount" style="font-size:25px;">250</div>
                            </div>
                        </div>
                    </div>
                    <div class="gameCard-container" style="border: 1px solid;" onclick="getAmount(500)">
                        <div class="add-fund-box addfund-box">
                            <div style="display: flex; align-items: baseline;">
                                <div class="collapseCard-title mr-1">₹</div>
                                <div class="collapseCard-title payment-amount" style="font-size:25px;">500</div>
                            </div>
                        </div>
                    </div>
                    <div class="gameCard-container" style="border: 1px solid;" onclick="getAmount(1000)">
                        <div class="add-fund-box addfund-box">
                            <div style="display: flex; align-items: baseline;">
                                <div class="collapseCard-title mr-1">₹</div>
                                <div class="collapseCard-title payment-amount" style="font-size:25px;">1000</div>
                            </div>
                        </div>
                    </div>
                </div>
                </br>
            </div>
            <div class="refer-footer" style="height: 150px;align-items: flex-start;">
                <button type="submit" onclick="$('#popup').css('display','flex');"
                    class="refer-button cxy w-100 bg-secondary">Submit</button>
            </div>
            </form>

        </div>
    </div>
    </div>
    <div id="popup"
        style="display:none;position:fixed;width:100dvw;height:100dvh;top: 0;left: 0;z-index: 11111111;flex-direction: row;flex-wrap: nowrap;align-content: center;justify-content: center;align-items: center;background: #808080cc;color: white;">
        <h1 style="text-align:center;">
            <img src="https://media1.giphy.com/media/3NwOyKt1n7S6KccVQg/giphy.gif">
            {{-- <!--<svg xml:space="preserve" viewBox="0 0 100 100" y="0" x="0" xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" style="height: 100%; width: 100%; background: rgb(255, 255, 255);" width="200px" height="200px"><g class="ldl-scale" style="transform-origin: 50% 50%; transform: rotate(0deg) scale(0.6, 0.6);"><g class="ldl-ani" style="transform: rotate(0deg); transform-origin: 50px 50px; animation: 1s linear 0s infinite normal forwards running cycle-dc384288-fd43-4a3c-b434-9f498285d9d8;"><g class="ldl-layer"><g class="ldl-ani"><path fill="#f7b26a" d="M28 33.7C32.4 27.8 38.8 24 46.1 23l-1.6-11.5C34.1 13 25 18.4 18.7 26.7 12.7 34.5 10 44.2 11 54l-6.9 1 14.4 10.8 10.8-14.4-6.8 1c-.5-6.8 1.4-13.3 5.5-18.7z" style="fill: rgb(1, 129, 103);"></path></g></g><g class="ldl-layer"><g class="ldl-ani"><path fill="#e15c64" d="M79.1 69.7l-17.9-2.2 4.2 5.4c-11.9 8.1-28.2 5.7-37.2-5.7L19 74.4c7.7 9.9 19.2 15 30.9 15 7.9 0 15.8-2.4 22.7-7.3l4.3 5.5 2.2-17.9z" style="fill: rgb(26, 179, 148);"></path></g></g><g class="ldl-layer"><g class="ldl-ani"><path fill="#f47e5f" d="M85.9 34.9C82 25.8 75 18.6 66.1 14.6l2.6-6.5-16.6 7 7 16.6 2.6-6.4c6.1 2.9 10.8 7.8 13.4 14 2.9 6.8 2.9 14.2.2 21l10.8 4.4c4-9.5 3.9-20.2-.2-29.8z" style="fill: rgb(120, 231, 208);"></path></g></g><metadata xmlns:d="https://loading.io/stock/">-->
            <!--<d:name>roundabout,recycle,state,step,progress,next,circulate,turnaround</d:name>-->
            <!--<d:tags>cc-by,roundabout,recycle,state,step,progress,next,circulate,turnaround</d:tags>-->
            <!--<d:license>by</d:license>-->
            <!--<d:slug>a46a64</d:slug>-->
            <!--</metadata></g></g></svg>--> --}}
        </h1>
    </div>
    <div class="divider-y"></div>

    {{-- <script>
        function getAmount(amount) {
            $('#add-amount').val(amount);
        }
    </script>

    @if (session()->has('error'))
        <script>
            Swal.fire({
                title: "Oops!",
                text: "{{ session()->get('error') }}",
                icon: "error"
            });
        </script>
    @endif --}}

    <script>
        function getAmount(amount) {
            $('#add-amount').val(amount);
        }

        // Polling function to check payment status
        function checkPaymentStatus(orderId) {
            fetch(`/check-payment-status?order_id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = '/payment-success'; // Redirect on success
                    } else if (data.status === 'fail') {
                        window.location.href = '/payment-fail'; // Redirect on failure
                    }
                })
                .catch(() => {
                    console.log('Error checking payment status.');
                });
        }

        // Get orderId from form data or URL parameter
        function getOrderId() {
            // Example method to get order ID. Adjust based on your actual logic
            return '{{ session('order_id') }}';
        }

        // Start polling after form submission
        document.getElementById('make-payment').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Extract orderId from form or session
            const orderId = getOrderId();

            // Show popup
            $('#popup').css('display', 'flex');

            // Poll every 5 seconds
            const intervalId = setInterval(() => {
                checkPaymentStatus(orderId);
                // Clear interval when redirecting
                if (window.location.pathname === '/payment-success' || window.location.pathname ===
                    '/payment-fail') {
                    clearInterval(intervalId);
                }
            }, 5000);

            // Submit form after starting polling
            this.submit();
        });

        @if (session()->has('error'))
            Swal.fire({
                title: "Oops!",
                text: "{{ session()->get('error') }}",
                icon: "error"
            });
        @endif
    </script>
@endsection
