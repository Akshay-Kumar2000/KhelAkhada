@extends('layouts.front.front')
@section('content')
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
    </style>
    <div class="divider-x"></div>
    <div class="main-area" style="padding-top: 60px;">
        <div class="px-4 py-3">
            <div class="d-flex flex-column">
                <div class="games-section-title">
                    Add Withdrawal Mode
                    <form method="post" onsubmit="$('#popup').show()">
                        @csrf
                        <div class="MuiFormControl-root MuiTextField-root mt-2 w-100">
                            @if (session()->has('error'))
                                <p class="alert alert-danger">{{ session()->get('error') }}</p>
                            @endif
                            @if ($type == 'upi')
                                <div
                                    class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-formControl">
                                    <input aria-invalid="false" name="upi_id" placeholder="Enter UPI ID" type="text"
                                        class="MuiInputBase-input MuiFilledInput-input" value=""
                                        style="padding-top: 10px;" required>
                                </div>
                            @endif
                            @if ($type == 'imps')
                                <div class="MuiFormControl-root MuiTextField-root mt-2 w-100">
                                    <div
                                        class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-formControl">
                                        <input aria-invalid="false" name="accountNumber" placeholder="Enter Account Number"
                                            type="number" class="MuiInputBase-input MuiFilledInput-input" value=""
                                            style="padding-top: 10px;" required>
                                    </div>
                                </div>
                                <div class="MuiFormControl-root MuiTextField-root mt-2 w-100">
                                    <div
                                        class="MuiInputBase-root MuiFilledInput-root MuiFilledInput-underline MuiInputBase-formControl">
                                        <input aria-invalid="false" name="ifsc" onchange="handleIFSCChange(this)"
                                            placeholder="Enter IFSC Code" type="text"
                                            class="MuiInputBase-input MuiFilledInput-input" value=""
                                            style="padding-top: 10px;" required>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <p id="validation_message"></p>
                        <button type="submit" class="refer-button mt-4 cxy w-100 bg-primary">Verify Detail</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <audio id="myAudio" controls style="display:none;">
        <source src="preview.mp3" type="audio/mp3">
        Your browser does not support the audio tag.
    </audio>
    <div id="popup"
        style="display:none;position:fixed;width:100dvw;height:100dvh;top: 0;left: 0;z-index: 11111111;flex-direction: row;flex-wrap: nowrap;align-content: center;justify-content: center;align-items: center;background: #808080cc;color: white;">
        <h1 style="text-align:center;">Loading..</h1>
    </div>
    <div class="divider-y"></div>
    <script>
        function validateIFSC(ifsc) {
            // Regular expression for validating IFSC code
            const ifscRegex = /^[A-Z]{4}0[A-Z0-9]{6}$/;
            return ifscRegex.test(ifsc);
        }

        function handleIFSCChange(a) {
            const ifscCode = $(a).val();
            const isValid = validateIFSC(ifscCode);
            const messageElement = $('#validation_message');

            if (isValid) {
                messageElement.text('Valid IFSC code').css('color', 'green');
            } else {
                messageElement.text('Invalid IFSC code').css('color', 'red');
                $(a).val('');
            }
        }
    </script>
@endsection
