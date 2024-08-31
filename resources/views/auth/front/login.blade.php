<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="{{ asset('front/images/khelmoj123.png') }}" />
    <title>AK Adda | Best online ludo platform</title>
    <meta content="ApnaLudo" name="description">
    <meta
        content="ludo khelo,online ludo, online games, play with real players, best ludo website, ludo earning, earn by playing ludo, playing ludo king,  ludo contest, Best Ludo website in kota , ludo tournament , ludo khelo paise kamao, khelo ludo, Ludo Players, Ludo king."
        name="keywords">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700,800,900">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}">

    <script src="{{ asset('front/js/jquery-3.6.1.min.js') }}"></script>

</head>

<body>


    <div class="leftContainer splash-bg login-page-bg" style="background-image:url(././front/images/splash-bg.png);">

        <div class="main-area">
            <div class="alert alert-danger alert-dismissible fade show" id="success-error-div" role="alert"
                style="dispaly:none;position:fixed;top:50px;width:100%;z-index:100;">
                <span id="success-error-message"></span>
            </div>
            <div class="position-absolute w-100 center-xy mx-auto sign-otp">
                <form id="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="referral" value="{{ $referral }}" />
                    <div class="sign-up-screen">
                        <div class="sign-up-title text-white mb-4">Sign in or Sign up</div>
                        <div class="bg-white cxy flex-column">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">+91</div>
                                </div>
                                <input class="form-control" id="mobile-no" name="mobile" type="tel"
                                    placeholder="Mobile number" value="">

                            </div>

                            <div class="input-group pt-2" id="verify-otp-div" style="display:none">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">OTP</div>
                                </div>
                                <input class="form-control" id="verify-otp" name="otp" type="tel"
                                    placeholder="Enter OTP" autocomplete="off" value="">
                            </div>
                        </div>
                        <input type="submit" value="Submit" class="submit-signup" />
                    </div>



                </form>
                <p></p>
                <p style="color:white;margin-left:50px;margin-right:30px">By proceeding, you agree to our <a
                        href="{{ route('front.terms-and-conditions') }}">Terms of Use</a>, <a
                        href="{{ route('front.privacy-policy') }}">Privacy Policy</a> and that you are 18 years or
                    older. You are not playing from Assam, Odisha, Nagaland, Sikkim, Meghalaya, Andhra Pradesh, or
                    Telangana.</p>
            </div>

        </div>
        <div class="divider-y"></div>
        <div class="rightContainer">
            <div class="rcBanner flex-center">
                <div class="rcBanner-img-container"><img src="{{ asset('front/images/khelmoj123.png') }}"
                        alt=""></div>
                <div class="rcBanner-text">AK Adda <span class="rcBanner-text-bold">Win Real Cash!</span></div>
            </div>
        </div>


        <script type="text/javascript">
            $(function() {
                $('#success-error-div').hide();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#login-form').submit(function(e) {
                    e.preventDefault();

                    var mobile = $('#mobile-no').val();
                    var otp = $('#verify-otp').val();
                    var flag = 1;
                    if (!mobile) {
                        hideSuccessErrorDiv('alert-success', 'alert-danger', 'Please enter mobile number');
                        flag = 0;
                    } else if (!$.isNumeric(mobile)) {
                        hideSuccessErrorDiv('alert-success', 'alert-danger', 'Please enter numeric value');
                        flag = 0;
                    } else if (mobile.length != 10) {
                        hideSuccessErrorDiv('alert-success', 'alert-danger',
                            'Please enter 10 digit mobile number');
                        flag = 0;
                    }

                    if (otp && !$.isNumeric(otp)) {
                        hideSuccessErrorDiv('alert-success', 'alert-danger', 'Please enter numeric value');
                        flag = 0;
                        $('#verify-otp').val('')
                    } else if (otp && otp.length != 6) {
                        hideSuccessErrorDiv('alert-success', 'alert-danger', 'Please enter 6 digit OTP');
                        flag = 0;
                        $('#verify-otp').val('')
                    }

                    if (flag) {
                        $form = $(this);

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: '{{ route('login') }}',
                            data: $form.serialize(),
                            beforeSend: function() {
                                $('.loading').show();
                            },
                            success: function(data) {
                                console.log(data);
                                if (data.status == 1) {
                                    hideSuccessErrorDiv('alert-danger', 'alert-success', data
                                        .message);
                                    $('#verify-otp').val('');
                                    $('#verify-otp-div').show();
                                    $('#mobile-no').attr('readonly', 'readonly');
                                }

                                if (data.status == 2) {
                                    window.location.href = data.url;
                                }
                            },
                            error: function(data) {
                                console.log(data);
                                //alert(data.responseJSON.message);
                                hideSuccessErrorDiv('alert-success', 'alert-danger', data
                                    .responseJSON.message);
                                $('#verify-otp').val('');
                            },
                            complete: function(data) {
                                //    $('.loading').hide();
                                //    $('#withDraw')[0].reset();
                                // 	$("#withDraw").trigger("reset");
                                //location.reload();
                            }

                        });
                    }
                });

            });

            function hideSuccessErrorDiv(remove_class, add_class, message) {
                $('#success-error-div').removeClass(remove_class);
                $('#success-error-div').addClass(add_class);
                $('#success-error-div').show();
                $('#success-error-message').text(message);
                $("#success-error-div").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-error-div").hide(500);
                });
            }
        </script>

</body>

</html>
