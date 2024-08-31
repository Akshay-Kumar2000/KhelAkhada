@extends('layouts.front.front')
@section('content')
<div class="main-area" style="padding-top: 60px;position:relative;">
         <div class="d-flex align-items-center px-4 py-3">
            <div class="games-section-headline" style="font-size: 0.85em;">Winning Cash Balance</div>
            <div class="games-section-title position-absolute" style="right: 1.5rem;"><img class="mr-1 mb-1"
                  src="{{asset('front/images/global-rupeeIcon.png')}}" width="20px" alt="">₹<span id="wallet-balance">{{$winningAmount}}</span></div>
         </div>
         <div class="divider-x"></div>
    <style data-jss="" data-meta="MuiSvgIcon">
        .MuiSvgIcon-root {
            fill: currentColor;
            width: 1em;
            height: 1em;
            display: inline-block;
            font-size: 1.5rem;
            transition: fill 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
            flex-shrink: 0;
            user-select: none;
        }

        .MuiSvgIcon-colorPrimary {
            color: #3f51b5;
        }

        .MuiSvgIcon-colorSecondary {
            color: #f50057;
        }

        .MuiSvgIcon-colorAction {
            color: rgba(0, 0, 0, 0.54);
        }

        .MuiSvgIcon-colorError {
            color: #f44336;
        }

        .MuiSvgIcon-colorDisabled {
            color: rgba(0, 0, 0, 0.26);
        }

        .MuiSvgIcon-fontSizeInherit {
            font-size: inherit;
        }

        .MuiSvgIcon-fontSizeSmall {
            font-size: 1.25rem;
        }

        .MuiSvgIcon-fontSizeLarge {
            font-size: 2.1875rem;
        }
    </style>
    <style data-jss="" data-meta="MuiInputBase">
        @-webkit-keyframes mui-auto-fill {}

        @-webkit-keyframes mui-auto-fill-cancel {}

        .MuiInputBase-root {
            color: rgba(0, 0, 0, 0.87);
            cursor: text;
            display: inline-flex;
            position: relative;
            font-size: 1rem;
            box-sizing: border-box;
            align-items: center;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.1876em;
            letter-spacing: 0.00938em;
        }

        .MuiInputBase-root.Mui-disabled {
            color: rgba(0, 0, 0, 0.38);
            cursor: default;
        }

        .MuiInputBase-multiline {
            padding: 6px 0 7px;
        }

        .MuiInputBase-multiline.MuiInputBase-marginDense {
            padding-top: 3px;
        }

        .MuiInputBase-fullWidth {
            width: 100%;
        }

        .MuiInputBase-input {
            font: inherit;
            color: currentColor;
            width: 100%;
            border: 0;
            height: 1.1876em;
            margin: 0;
            display: block;
            padding: 6px 0 7px;
            min-width: 0;
            background: none;
            box-sizing: content-box;
            animation-name: mui-auto-fill-cancel;
            letter-spacing: inherit;
            animation-duration: 10ms;
            -webkit-tap-highlight-color: transparent;
        }

        .MuiInputBase-input::-webkit-input-placeholder {
            color: currentColor;
            opacity: 0.42;
            transition: opacity 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        }

        .MuiInputBase-input::-moz-placeholder {
            color: currentColor;
            opacity: 0.42;
            transition: opacity 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        }

        .MuiInputBase-input:-ms-input-placeholder {
            color: currentColor;
            opacity: 0.42;
            transition: opacity 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        }

        .MuiInputBase-input::-ms-input-placeholder {
            color: currentColor;
            opacity: 0.42;
            transition: opacity 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        }

        .MuiInputBase-input:focus {
            outline: 0;
        }

        .MuiInputBase-input:invalid {
            box-shadow: none;
        }

        .MuiInputBase-input::-webkit-search-decoration {
            -webkit-appearance: none;
        }

        .MuiInputBase-input.Mui-disabled {
            opacity: 1;
        }

        .MuiInputBase-input:-webkit-autofill {
            animation-name: mui-auto-fill;
            animation-duration: 5000s;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input::-webkit-input-placeholder {
            opacity: 0 !important;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input::-moz-placeholder {
            opacity: 0 !important;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input:-ms-input-placeholder {
            opacity: 0 !important;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input::-ms-input-placeholder {
            opacity: 0 !important;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input:focus::-webkit-input-placeholder {
            opacity: 0.42;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input:focus::-moz-placeholder {
            opacity: 0.42;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input:focus:-ms-input-placeholder {
            opacity: 0.42;
        }

        label[data-shrink=false]+.MuiInputBase-formControl .MuiInputBase-input:focus::-ms-input-placeholder {
            opacity: 0.42;
        }

        .MuiInputBase-inputMarginDense {
            padding-top: 3px;
        }

        .MuiInputBase-inputMultiline {
            height: auto;
            resize: none;
            padding: 0;
        }

        .MuiInputBase-inputTypeSearch {
            -moz-appearance: textfield;
            -webkit-appearance: textfield;
        }
    </style>
    <style data-jss="" data-meta="MuiFilledInput">
        .MuiFilledInput-root {
            position: relative;
            transition: background-color 200ms cubic-bezier(0.0, 0, 0.2, 1) 0ms;
            background-color: rgba(0, 0, 0, 0.09);
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        .MuiFilledInput-root:hover {
            background-color: rgba(0, 0, 0, 0.13);
        }

        .MuiFilledInput-root.Mui-focused {
            background-color: rgba(0, 0, 0, 0.09);
        }

        .MuiFilledInput-root.Mui-disabled {
            background-color: rgba(0, 0, 0, 0.12);
        }

        @media (hover: none) {
            .MuiFilledInput-root:hover {
                background-color: rgba(0, 0, 0, 0.09);
            }
        }

        .MuiFilledInput-colorSecondary.MuiFilledInput-underline:after {
            border-bottom-color: #f50057;
        }

        .MuiFilledInput-underline:after {
            left: 0;
            right: 0;
            bottom: 0;
            content: "";
            position: absolute;
            transform: scaleX(0);
            transition: transform 200ms cubic-bezier(0.0, 0, 0.2, 1) 0ms;
            border-bottom: 2px solid #3f51b5;
            pointer-events: none;
        }

        .MuiFilledInput-underline.Mui-focused:after {
            transform: scaleX(1);
        }

        .MuiFilledInput-underline.Mui-error:after {
            transform: scaleX(1);
            border-bottom-color: #f44336;
        }

        .MuiFilledInput-underline:before {
            left: 0;
            right: 0;
            bottom: 0;
            content: "\00a0";
            position: absolute;
            transition: border-bottom-color 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
            border-bottom: 1px solid rgba(0, 0, 0, 0.42);
            pointer-events: none;
        }

        .MuiFilledInput-underline:hover:before {
            border-bottom: 1px solid rgba(0, 0, 0, 0.87);
        }

        .MuiFilledInput-underline.Mui-disabled:before {
            border-bottom-style: dotted;
        }

        .MuiFilledInput-adornedStart {
            padding-left: 12px;
        }

        .MuiFilledInput-adornedEnd {
            padding-right: 12px;
        }

        .MuiFilledInput-multiline {
            padding: 27px 12px 10px;
        }

        .MuiFilledInput-multiline.MuiFilledInput-marginDense {
            padding-top: 23px;
            padding-bottom: 6px;
        }

        .MuiFilledInput-input {
            padding: 27px 12px 10px;
        }

        .MuiFilledInput-input:-webkit-autofill {
            border-top-left-radius: inherit;
            border-top-right-radius: inherit;
        }

        .MuiFilledInput-inputMarginDense {
            padding-top: 23px;
            padding-bottom: 6px;
        }

        .MuiFilledInput-inputHiddenLabel {
            padding-top: 18px;
            padding-bottom: 19px;
        }

        .MuiFilledInput-inputHiddenLabel.MuiFilledInput-inputMarginDense {
            padding-top: 10px;
            padding-bottom: 11px;
        }

        .MuiFilledInput-inputMultiline {
            padding: 0;
        }

        .MuiFilledInput-inputAdornedStart {
            padding-left: 0;
        }

        .MuiFilledInput-inputAdornedEnd {
            padding-right: 0;
        }
    </style>
    <style data-jss="" data-meta="MuiFormControl">
        .MuiFormControl-root {
            border: 0;
            margin: 0;
            display: inline-flex;
            padding: 0;
            position: relative;
            min-width: 0;
            flex-direction: column;
            vertical-align: top;
        }

        .MuiFormControl-marginNormal {
            margin-top: 16px;
            margin-bottom: 8px;
        }

        .MuiFormControl-marginDense {
            margin-top: 8px;
            margin-bottom: 4px;
        }

        .MuiFormControl-fullWidth {
            width: 100%;
        }
    </style>
    <style data-jss="" data-meta="MuiFormHelperText">
        .MuiFormHelperText-root {
            color: rgba(0, 0, 0, 0.54);
            margin: 0;
            font-size: 0.75rem;
            margin-top: 3px;
            text-align: left;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.66;
            letter-spacing: 0.03333em;
        }

        .MuiFormHelperText-root.Mui-disabled {
            color: rgba(0, 0, 0, 0.38);
        }

        .MuiFormHelperText-root.Mui-error {
            color: #f44336;
        }

        .MuiFormHelperText-marginDense {
            margin-top: 4px;
        }

        .MuiFormHelperText-contained {
            margin-left: 14px;
            margin-right: 14px;
        }
    </style>
    <style data-jss="" data-meta="MuiTextField">

    </style>
    <style data-jss="" data-meta="MuiTypography">
        .MuiTypography-root {
            margin: 0;
        }

        .MuiTypography-body2 {
            font-size: 0.875rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.43;
            letter-spacing: 0.01071em;
        }

        .MuiTypography-body1 {
            font-size: 1rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.5;
            letter-spacing: 0.00938em;
        }

        .MuiTypography-caption {
            font-size: 0.75rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.66;
            letter-spacing: 0.03333em;
        }

        .MuiTypography-button {
            font-size: 0.875rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 500;
            line-height: 1.75;
            letter-spacing: 0.02857em;
            text-transform: uppercase;
        }

        .MuiTypography-h1 {
            font-size: 6rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 300;
            line-height: 1.167;
            letter-spacing: -0.01562em;
        }

        .MuiTypography-h2 {
            font-size: 3.75rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 300;
            line-height: 1.2;
            letter-spacing: -0.00833em;
        }

        .MuiTypography-h3 {
            font-size: 3rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.167;
            letter-spacing: 0em;
        }

        .MuiTypography-h4 {
            font-size: 2.125rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.235;
            letter-spacing: 0.00735em;
        }

        .MuiTypography-h5 {
            font-size: 1.5rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.334;
            letter-spacing: 0em;
        }

        .MuiTypography-h6 {
            font-size: 1.25rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 500;
            line-height: 1.6;
            letter-spacing: 0.0075em;
        }

        .MuiTypography-subtitle1 {
            font-size: 1rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.75;
            letter-spacing: 0.00938em;
        }

        .MuiTypography-subtitle2 {
            font-size: 0.875rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 500;
            line-height: 1.57;
            letter-spacing: 0.00714em;
        }

        .MuiTypography-overline {
            font-size: 0.75rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 2.66;
            letter-spacing: 0.08333em;
            text-transform: uppercase;
        }

        .MuiTypography-srOnly {
            width: 1px;
            height: 1px;
            overflow: hidden;
            position: absolute;
        }

        .MuiTypography-alignLeft {
            text-align: left;
        }

        .MuiTypography-alignCenter {
            text-align: center;
        }

        .MuiTypography-alignRight {
            text-align: right;
        }

        .MuiTypography-alignJustify {
            text-align: justify;
        }

        .MuiTypography-noWrap {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .MuiTypography-gutterBottom {
            margin-bottom: 0.35em;
        }

        .MuiTypography-paragraph {
            margin-bottom: 16px;
        }

        .MuiTypography-colorInherit {
            color: inherit;
        }

        .MuiTypography-colorPrimary {
            color: #3f51b5;
        }

        .MuiTypography-colorSecondary {
            color: #f50057;
        }

        .MuiTypography-colorTextPrimary {
            color: rgba(0, 0, 0, 0.87);
        }

        .MuiTypography-colorTextSecondary {
            color: rgba(0, 0, 0, 0.54);
        }

        .MuiTypography-colorError {
            color: #f44336;
        }

        .MuiTypography-displayInline {
            display: inline;
        }

        .MuiTypography-displayBlock {
            display: block;
        }
    </style>
    <style data-jss="" data-meta="MuiInputAdornment">
        .MuiInputAdornment-root {
            height: 0.01em;
            display: flex;
            max-height: 2em;
            align-items: center;
            white-space: nowrap;
        }

        .MuiInputAdornment-filled.MuiInputAdornment-positionStart:not(.MuiInputAdornment-hiddenLabel) {
            margin-top: 16px;
        }

        .MuiInputAdornment-positionStart {
            margin-right: 8px;
        }

        .MuiInputAdornment-positionEnd {
            margin-left: 8px;
        }

        .MuiInputAdornment-disablePointerEvents {
            pointer-events: none;
        }
    </style>
    <style data-jss="" data-meta="makeStyles">
        .jss1 {
            font-size: 0.9em;
            font-weight: 500;
        }

        .jss2 {
            color: #007ac1;
            font-size: 0.8em;
        }

        .jss3 {
            color: #B36916;
            font-size: 0.65em;
        }

        .jss4 {
            font-size: 1.7em;
            font-weight: 700;
            background-color: #b1aeae17;
        }

        .jss4:hover {
            background-color: #b1aeae17;
        }
        .add-fund-box.active{
            border-color: rgb(0, 111, 255);
        }
        .option-tick.active{
            display:block !important;
        }
    </style>
    <div id="with_success" style="position:fixed;top: 0;left: 0;display: none;width: 100%;height: 100vh;background-color: #12c06a;z-index: 10;flex-direction: column;flex-wrap: nowrap;align-content: center;justify-content: center;align-items: center;">
        <img src="https://cdn.uxhack.co/static/Hacks/images/success-tick.gif">
    </div>
<div class="px-4 py-3">
    <div class="d-flex flex-column">
        <div class="games-section-title">
            @if(!$user_kyc || $user_kyc->verify_status == 0)
				<div class="p-4 bg-light">
					<div class="card text-center mt-3">
						<div style="margin-bottom: 20px;">
							<img src="https://media.licdn.com/dms/image/C4E12AQHzghUKW-4h8g/article-cover_image-shrink_600_2000/0/1624299773515?e=2147483647&v=beta&t=w-mP80ouuXQ3dXqCZPN2z7dCB4uXTnBfKHacyRK2Ty8" alt="" width="50%" class="mt-4">
							<div class="ml-1 mt-2 mytext" style="color:red; font-weight:800">Complete KYC to take Withdrawals</div><br>
							<a href="{{ url('/complete-kyc/step1') }}" class="btn btn-primary"> Complete KYC </a>
						</div>
					</div>

				</div>
			@else
            <div>Withdraw Amount &amp; TDS<div class="MuiFormControl-root MuiTextField-root mt-2 w-100">
                    <div
                        class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline jss4 MuiInputBase-formControl MuiInputBase-adornedStart MuiFilledInput-adornedStart">
                        <div class="MuiInputAdornment-root MuiInputAdornment-positionStart MuiInputAdornment-filled"
                            style="margin-top: 0px;">
                            <p class="MuiTypography-root MuiTypography-body1 MuiTypography-colorTextSecondary">₹</p>
                        </div>
                        <input aria-invalid="false" id="amount" placeholder="Enter Amount" type="tel" oninput="makeOnlyIntehger(this)" class="MuiInputBase-input MuiFilledInput-input MuiInputBase-inputAdornedStart MuiFilledInput-inputAdornedStart" value="" style="padding-top: 10px;">
                    </div>
                    <p class="MuiFormHelperText-root MuiFormHelperText-contained">Minimum: 200</p>
                </div>
            </div>
            <p style="color:red;" id="withdraw_amount-error"></p>
            <div class="mt-4">Select Withdrawal Modes<span class="ml-2">
                <button onclick="location.href='/edit-bank'"
                        class="btn btn-sm btn-danger ml-auto mr-2" style="z-index: 1;"><svg
                            class="MuiSvgIcon-root mr-1 MuiSvgIcon-fontSizeSmall" focusable="false" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path
                                d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z">
                            </path>
                        </svg>Edit Modes</button>
                        </span>
                <div class="mt-3">
                    @if($ModeOn[0] == "yes")
                    <div class="mt-4" style="cursor:pointer;" onclick="SelectMode(this,'upi')">
                        <div class="add-fund-box d-flex align-items-center" style="padding-top: 15px; height: 60px;">
                            <div class="d-flex align-items-center">
                                <img width="48px" src="/images/upi.webp" alt="">
                                <div class="d-flex justify-content-center flex-column ml-4">
                                    <div class="jss1">Withdraw to UPI</div>
                                    @if($UPIbankDetail)
                                    <div class="jss2" style="font-size: 0.7em;">UPI Id: {{$UPIbankDetail->number}}</div>
                                    <div class="jss2" style="font-size: 0.7em;">Verified Name: {{$UPIbankDetail->name}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-auto mr-4 text-primary option-tick" style="display:none;">
                                <img src="https://khelbro.com/images/select-blue-checkIcon.png" width="15px" alt="">
                            </div>
                            @if(!$UPIbankDetail)
                            <div class="ml-auto mr-4 text-primary" onclick="location.href='/editBankDeatil/upi'">Link</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($ModeOn[1] == "yes")
                    <div class="mt-4" style="cursor:pointer;" onclick="SelectMode(this,'imps')">
                        <div class="add-fund-box d-flex align-items-center" style="padding-top: 15px; height: 80px;">
                            <div class="d-flex align-items-center"><img width="48px" src="/images/bank.png" alt="">
                                <div class="d-flex justify-content-center flex-column ml-4">
                                    <div class="jss1">Bank Transfer</div>
                                    @if($IMPSbankDetail)
                                    <div class="jss2" style="font-size: 0.7em;">Bank Account: {{$IMPSbankDetail->number}}</div>
                                    <div class="jss2" style="font-size: 0.7em;">IFSC Code: {{$IMPSbankDetail->ifsc}}</div>
                                    <div class="jss2" style="font-size: 0.7em;">Verified Name: {{$IMPSbankDetail->name}}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-auto mr-4 text-primary option-tick" style="display:none;">
                                <img src="https://khelbro.com/images/select-blue-checkIcon.png" width="15px" alt="">
                            </div>
                            @if(!$IMPSbankDetail)
                            <div class="ml-auto mr-4 text-primary" onclick="location.href='/editBankDeatil/imps'">Link</div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="refer-footer"><button type="button" id="SubmitWithdrawal" class="refer-button cxy w-100 bg-primary">Withdraw</button>
            </div>
            @endif
        </div>
    </div>
</div>
      </div>
   </div>
   <audio id="myAudio" controls style="display:none;">
        <source src="preview.mp3" type="audio/mp3">
        Your browser does not support the audio tag.
    </audio>
   <div id="Loading_pp" style="display:none;position:fixed;width:100dvw;height:100dvh;top: 0;left: 0;z-index: 11111111;flex-direction: row;flex-wrap: nowrap;align-content: center;justify-content: center;align-items: center;background: #ffffff;color: white;">
       <h1 style="text-align:center;">
           <img style="width: 100%;" src="https://cdn.dribbble.com/users/274482/screenshots/4828558/media/774456fdd41fb9ae14ec16da5ccfef62.gif"></h1>
   </div>
   <div class="divider-y"></div>
<script>
let MethodPayment = null;
function makeOnlyIntehger(a){
    let value = parseInt($(a).val());
    // Check if the value is an integer
    if (!Number.isInteger(value)) {
        // Convert the value to an integer
        value = Math.floor(value);
    }
    $('#amount').val(value);
}
function SelectMode(a,b){
    $('.add-fund-box').removeClass('active');
    $('.option-tick').removeClass('active');
    $(a).children('.add-fund-box').addClass('active');
    $(a).children('.add-fund-box').children('.option-tick').addClass('active');
    MethodPayment = b;
}
$(function () {
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var audio = document.getElementById("myAudio");

        // Function to play the audio
        function playAudio() {
            audio.play();
        }

        // Function to pause the audio
        function pauseAudio() {
            audio.pause();
        }

        // Function to set the volume
        function setVolume(volume) {
            audio.volume = volume;
        }
        $('#SubmitWithdrawal').on('click',function(){
            let Amount = $('#amount').val();
            let flag = 1;
            if(Amount == ''){
                alert('Enter Amount');
                flag = 0;
            }else if(Amount < 200){
                alert('Enter min. 200');
                flag = 0;
            }else if(MethodPayment == null){
                alert('Select any one method');
                flag = 0;
            }else if(MethodPayment != 'upi' && MethodPayment != 'imps'){
                alert('Select valid method');
                flag = 0;
            }
            if(flag == 1){
                $.ajax({
					type: "POST",
					dataType: 'json',
					url: '{{ route('universal-withdraw') }}',
					data: {
					    'amount':Amount,
					    'type': MethodPayment
					},
					beforeSend: function(){
						$('#Loading_pp').css('display','flex');
					},
					success:function(data){
			            $('#Loading_pp').css('display','none');
						if(data.success){
			                playAudio();
			                setVolume(1);
			                $('#with_success').css('display','flex');
			             //   $('#with_success').css('display','flex');
			                setTimeout(()=>{
					             window.location.href = '/';
					        },2000);
						}else{
							$('#withdraw_amount-error').text(data.error);
							$('#withdraw_amount-error').show();
						}
						if(data.wallet_amount){
						   $('#wallet_amount').text(data.wallet_amount);
                           $('#wallet-balance').text(data.wallet_amount);
					    }
					    setTimeout(()=>{
					        $('#with_success').css('display','none');
					    },3000);
				   },
				   complete:function(data){
					   $('#Loading_pp').css('display','none');
					   $('#amount').val('');
				   }
				});
            }
        });
	});
</script>
@endsection

