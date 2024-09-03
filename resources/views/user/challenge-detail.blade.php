@extends('layouts.front.front')
@section('content')
    <style>
        .blink_me {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
    <div class="main-area main-area-box" style="padding-top: 60px;">
        <div class="alert alert-danger alert-dismissible fade show" id="success-error-div" role="alert"
            style="dispaly:none;position:fixed;top:50px;width:100%;z-index:100;">
            <span id="success-error-message"></span>
            <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
       </button> -->
        </div>
        <div class="divider-x"></div>

        <style>
            .card {
                --bs-card-spacer-y: 1rem;
                --bs-card-spacer-x: 1rem;
                --bs-card-title-spacer-y: 0.5rem;
                --bs-card-border-width: 1px;
                --bs-card-border-color: var(--bs-border-color-translucent);
                --bs-card-border-radius: 0.375rem;
                --bs-card-box-shadow: ;
                --bs-card-inner-border-radius: calc(0.375rem - 1px);
                --bs-card-cap-padding-y: 0.5rem;
                --bs-card-cap-padding-x: 1rem;
                --bs-card-cap-bg: rgba(0, 0, 0, .03);
                --bs-card-cap-color: ;
                --bs-card-height: ;
                --bs-card-color: ;
                --bs-card-bg: #fff;
                --bs-card-img-overlay-padding: 1rem;
                --bs-card-group-margin: 0.75rem;
                word-wrap: break-word;
                background-clip: initial;
                background-color: var(--bs-card-bg);
                border: var(--bs-card-border-width) solid var(--bs-card-border-color);
                border-radius: var(--bs-card-border-radius);
                display: flex;
                flex-direction: column;
                height: var(--bs-card-height);
                min-width: 0;
                position: relative;
            }
        </style>

        <div class="mb-2 shadow card">
            <div class="text-start card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column align-items-start vstack gap-2">
                        <div class="bg-dark rounded-circle me-2" style="height: 24px; width: 24px;">
                            <img src="{{ asset('front/images/author.svg') }}" alt="avatar">
                        </div>
                        <span class="fw-semibold text-truncate text-start" style="width: 100px;">{{ $chData->cname }}</span>
                    </div>
                    <div class="d-flex flex-column align-items-center vstack gap-2">
                        <span>
                            <em><img src="{{ asset('front/images/vs.png') }}" alt="verses-icon" width="24"
                                    height="35"></em>
                        </span>
                        <?php
                        $a_amount = (10 / 100) * $chData->amount;
                        $prize = 2 * $chData->amount - $a_amount;
                        ?>
                        {{-- <span class="text-success fw-bold text-center"><b>₹{{$prize}}</b></span> --}}
                    </div>
                    <div class="d-flex flex-column align-items-end vstack gap-2">
                        <div class="bg-dark rounded-circle" style="height: 24px; width: 24px;">
                            <img src="{{ asset('front/images/author.svg') }}" alt="avatar">
                        </div>
                        <div class="pr-3 text-center col-9">
                            <span class="fw-semibold text-truncate text-end"
                                style="width: 100px;">{{ $chData->oname }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function playSound() {
                var sound = document.getElementById("audio");
                sound.play();
            }
        </script>





        <div class="room-code">
            <div class="room-code-bg text-center">
                <div>
                    <h6 class="text-center text-danger mt-2">Important Notification</h6>
                    <p class="text-danger text-center m-2" style="font-size:12px;">रूम कोड केवल क्लासिक मोड में ही स्वीकार
                        किया जाएगा। लुडोकिंग पे जा के रूम कोड क्रिएट कर के निचे दिए ऑप्शन में डाले।</p>

                </div>
                <div class="mb-2 shadow card">
                    <div class="bg-light text-dark text-capitalize card-header">room code</div>
                    <div class="card-body">
                        @if (!$chData->rcode && $chData->c_id == Auth::user()->id && $chData->status >= 3)
                            <form action="/roomcode_add" method="post" onsubmit="getRoomCode(e)">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <p class="text-danger">{{ session()->has('error') ? session()->get('error') : '' }}</p>
                                <input type="hidden" hidden readonly name="id" value="{{ $chData->id }}">
                                <input type="number" name="code" id="room_code" placeholder="Enter Room Code" />
                                <button class="btn btn-primary">Submit</button>
                            </form>
                        @else
                            @if ($chData->status == 0)
                                <h3 class="py-3 fw-bold" id="res-room-id-new-{{ @$chData->id }}">Already Cancelled</h3>
                            @else
                                <h3 class="py-3 fw-bold" id="res-room-id-new-{{ @$chData->id }}">
                                    {{ $chData->rcode > 0 ? $chData->rcode : 'Waiting for creating room' }}</h3>
                            @endif
                        @endif
                        <div class="d-grid">
                            <audio id="audio" src="{{ asset('front/sound/accept.mp3') }}" autoplay="false"></audio>
                            <a onclick="playSound();">
                                <button class="btn btn-primary px-3 btn-sm copy-room-code" id="copyRoomCode"
                                    onclick="copyClipboardNew({{ @$chData->id }})"
                                    style="display: {{ !$chData->rcode ? 'none' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="1em"
                                        height="1em" fill="white" class="me-1">
                                        <path fill-rule="evenodd"
                                            d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1Zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5v-1Zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2Z">
                                        </path>
                                    </svg>
                                    <span id="onclick=playSound();">Copy RoomCode</span>
                                </button>
                            </a>
                        </div>






                        <div class="d-grid mt-1">
                        </div>
                    </div>
                </div>
                @if (!$chData->rcode)
                    <div class="alert-success" id="alert-success-div">
                        <p class="blink_me">Wait for room code</p>
                    </div>
                @endif

            </div>
        </div>
        <div class="match-status">
            <h6 class="title">Match Status</h6>
            <p>After completion of your game, select the status of the game and post your screenshot
                below.</p>
            @if (isset($chData->userresult))
                <p style="color:red">You have submitted result as <b>"{{ $chData->userresult->result }}"</b> at
                    {{ $chData->userresult->created_at }}</p>
            @else
                <div class="spinner-border" role="status" style="margin-left:45%;display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="radio-btn d-flex justify-content-between">
                    <form id="resultSubmitRadioBtn" class="resultRadioBtn" method="POST" action="javascript:void(0)"
                        enctype="multipart/form-data">
                        <input type="hidden" name="ch_id" value="{{ $chData->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="float-left" style="margin-right:35px">
                            <input type="radio" name="result" value="Won" id="result-won-res">
                            <label class="btn btn-success btn-sm" for="result-won-res">I Won</label>
                        </div>

                        <div class="float-left" style="margin-right:35px">
                            <input type="radio" name="result" value="Loss" id="result-loss-res">
                            <label class="btn btn-danger btn-sm" for="result-loss-res">I Lost</label>
                        </div>

                        <div class="float-right">
                            <input type="radio" name="result" value="Cancel" id="result-cancel-res">
                            <label class="btn btn-primary btn-sm" for="result-cancel-res">
                                Cancel</label>
                        </div>

                        <div style="display:none" id="cancel-text-res">
                            <select name="reason" id="cancel_reason-res">
                                <option value="No Room Code">No Room Code</option>
                                <option value="Room Code in Popular Mode">Room Code in Popular Mode</option>
                                <option value="Game Not Started">Game Not Started</option>
                                <option value="Not Joined">Not Joined</option>
                                <option value="Not Playing">Not Playing</option>
                                <option value="other">Other</option>
                            </select>
                            <!--<textarea name="reason" id="cancel_reason-res" placeholder="Cancel Reason">दूसरा गेम ज्वाइन करने के लिए</textarea>-->
                            <div style="display:none; color:red" id="result-error-reason-res"></div>
                        </div>

                        <!-------list------>

                        <!-------list------>
                        <div style="display:none" id="upload-image-res">
                            <input type="file" name="result_img" id="result_img-res"
                                onchange="compressAndUpload(this);" accept="image/*">
                            <div style="display:none; color:red" id="result-error-res"></div>
                        </div>
                        <div class="screeshot-preview" style="display:none">
                            <img id="blah" src="http://placehold.it/180" class="img-preview" alt=""
                                width="300px" />
                        </div>
                </div>
                <div class="submit-btn mx-4 mb-3" style="display:flex;justify-content:center;">

                    <button class="btn btn-success" style="width: 180px;" type="submit"
                        id="result-submit-btn-res">Submit</button>

                </div>
                </form>
        </div>
        @endif
    </div>
    <div class="card-header">
        <div class="rules">
            <span class="cxy mb-1">
                <b>Penalty</b>
            </span>
            <ol class="list-group list-group-numbered">
                <li class="list-group-item">Record every game while playing.</li>
                <li class="list-group-item">For cancellation of game, video proof is necessary.</li>
                <li class="list-group-item">
                    <h6 class="d-none  text-danger d-block text-center">◉ महत्वपूर्ण नोट : fraud/fake स्क्रीनशॉट अपलोड न
                        करें, अन्यथा आपके वॉलेट बैलेंस पर penalty लगाई जायेगी | गलत स्क्रीनशॉट अपलोड करने पर 100
                        रुपये पेनल्टी लगेगी </h6>
                </li>
                <li class="list-group-item">
                    <h6 class="d-none  text-danger d-block text-center">◉ महत्वपूर्ण नोट:कृपया गलत गेम परिणाम अपलोड न करें,
                        अन्यथा आपके वॉलेट बैलेंस पर penalty लगाई जायगी। गलत रिजल्ट अपडेट करने पर 50 रुपये पेनल्टी लगेगी।
                    </h6>
                </li>
                <li class="list-group-item">
                    <h6 class="d-none  text-danger d-block text-center">महत्वपूर्ण नोट: यदि आप गेम के परिणामों को अपडेट
                        नहीं करते हैं, तो आपके वॉलेट बैलेंस पर जुर्माना लगाया जाएगा। रिजल्ट अपडेट नहीं करने पर 25 रुपये
                        पेनल्टी लगेगी।</h6>
                </li>
            </ol>
        </div>
    </div>
    </div>
    <!--Match-->
    </div>
    </div>
    <input type="hidden" id="rcode" value="{{ $chData->rcode }}">
    </div>


    <div class="divider-y"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <script src="https://cdn.socket.io/4.5.0/socket.io.min.js"
        integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous">
    </script>
    <script>
        function compressAndUpload() {
            const input = document.getElementById('result_img-res');
            const compressedImage = document.getElementById('blah');
            const imageForm = document.getElementById('resultSubmitRadioBtn');
            const file = input.files[0];
            if (!file) {
                alert('Please select an image.');
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;

                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Set the canvas dimensions to the image dimensions
                    canvas.width = img.width;
                    canvas.height = img.height;

                    // Draw the image on the canvas
                    ctx.drawImage(img, 0, 0);

                    // Get the base64-encoded data URL of the compressed image
                    const compressedDataURL = canvas.toDataURL('image/jpeg', 0.5); // Adjust the quality as needed

                    // Display the compressed image
                    compressedImage.src = compressedDataURL;

                    // Create a new file from the compressed data URL
                    const compressedFile = dataURItoBlob(compressedDataURL);

                    const compressedFileList = new DataTransfer();
                    compressedFileList.items.add(new File([compressedFile], 'compressed_image.jpg'));

                    // Replace the original file input's files with the compressed file
                    input.files = compressedFileList.files;
                };
            };

            reader.readAsDataURL(file);
        }

        // Function to convert data URI to Blob
        function dataURItoBlob(dataURI) {
            const byteString = atob(dataURI.split(',')[1]);
            const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], {
                type: mimeString
            });
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function copyClipboard(ch_id) {
            /* Get the text field */
            var copyText = document.getElementById("res-room-id-" + ch_id);
            var res_room = $("#res-room-id-" + ch_id).text();

            if (res_room && res_room != null) {
                /* Select the text field */

                var range = document.createRange();
                range.selectNode(document.getElementById("res-room-id-" + ch_id));
                window.getSelection().removeAllRanges(); // clear current selection
                window.getSelection().addRange(range); // to select text
                document.execCommand("copy");

                /* Alert the copied text */
                //alert("Room Code Copied");
                $('#success-error-div').removeClass('alert-danger');
                $('#success-error-div').addClass('alert-success');
                $('#success-error-div').show();
                $('#success-error-message').text('Room Code Copied!');
                $("#success-error-div").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-error-div").hide(500);
                });
            } else {
                $('#success-error-div').removeClass('alert-success');
                $('#success-error-div').addClass('alert-danger');
                $('#success-error-div').show();
                $('#success-error-message').text('Room Code not found!');
                $("#success-error-div").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-error-div").hide(500);
                });
            }
        }

        function copyClipboardNew(ch_id) {
            /* Get the text field */
            var copyText = document.getElementById("res-room-id-new-" + ch_id);
            var res_room = $("#res-room-id-new-" + ch_id).text();

            if (res_room && res_room != null) {
                /* Select the text field */

                var range = document.createRange();
                range.selectNode(document.getElementById("res-room-id-new-" + ch_id));
                window.getSelection().removeAllRanges(); // clear current selection
                window.getSelection().addRange(range); // to select text
                document.execCommand("copy");

                /* Alert the copied text */
                //alert("Room Code Copied");
                $('#success-error-div').removeClass('alert-danger');
                $('#success-error-div').addClass('alert-success');
                $('#success-error-div').show();
                $('#success-error-message').text('Room Code Copied!');
                $("#success-error-div").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-error-div").hide(500);
                });
            } else {
                $('#success-error-div').removeClass('alert-success');
                $('#success-error-div').addClass('alert-danger');
                $('#success-error-div').show();
                $('#success-error-message').text('Room Code not found!');
                $("#success-error-div").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-error-div").hide(500);
                });
            }
        }

        function playSound() {
            var url = " {{ asset('front/sound/notification_tone.mp3') }} ";
            const audio = new Audio(url);
            audio.play();
        }

        function createSocket() {
            let socket = io('https://server.rajasthaniludo.com/');
            return socket;
        }

        function getRoomCode() {
            var logged_in_user = '{{ Auth::user()->id }}';
            var room_code_game = '{{ $chData->rcode }}';
            var creator_id = '{{ $chData->c_id }}';
            var opponent_id = '{{ $chData->o_id }}';
            var challenge_id = '{{ $chData->id }}';

            if (opponent_id == logged_in_user && (room_code_game == 0 || room_code_game == null)) {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: '{{ route('get-room-code') }}',
                    data: 'ch_id=' + challenge_id,

                    beforeSend: function() {
                        //$('.loading').show();
                    },
                    success: function(data) {
                        if (data.data != 0) {
                            $('#copyRoomCode').show();
                            $('#rcode').val(data.data);
                            $('#res-room-id-new-' + challenge_id).html(data.data);
                        }
                        $('#chk-room-id-new').hide();
                    },
                    error: function(data) {
                        var errors = $.parseJSON(data.responseText);
                        $('#challenge-roomcode-error').text(errors.errors.room_id);
                        $('#challenge-roomcode-error').show();
                    },
                    complete: function() {
                        $('.loading').hide();
                    }

                });
            }
        }


        $(function() {
            $("#success-error-div").hide();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function roomCodeSoc(data) {
                var html = '';
                var user_id = '{{ Auth::user()->id }}';
                $('#res-room-id-submit-' + data.id).attr("disabled", true);
                $('#res-room-id-submit-' + data.id).hide();
                $('#clipboard').show();
                $('#room-code-submit').attr("disabled", true);
                $('#res-room-id-' + data.id).attr("readonly", true);
                $('#change-room-id').show();
                $('.copy-room-code').show();
                $('#alert-success-div').hide();
                $('#res-room-id-new-' + data.id).text(data.rcode);

                $('#challenge-div').prepend(html);
            }

            function hideSuccessErrorDiv() {
                $("#success-error-div").fadeTo(2000, 500).slideUp(500, function() {
                    $("#success-error-div").hide(500);
                    $(".spinner-border").hide(500);
                });
            }

            $('.res-room-id-class').keyup(function() {
                var room_id = $(this).val();

                if (!room_id) {
                    $('#room_id-error').text('ू कड डालिे');
                    $('#room_id-error').addClass('error');
                    $('#room_id-error').show();
                } else if (!$.isNumeric(room_id)) {
                    $('#room_id-error').text('ू कोड अंो ें डािये');
                    $('#room_id-error').addClass('error');
                    $('#room_id-error').show();
                } else {
                    $('#room_id-error').text('');
                    $('#room_id-error').removeClass('error');
                    $('#room_id-error').hide();
                }
            });

            $('#chk-room-id-new').submit(function(e) {
                e.preventDefault();
                var room_id = $('.res-room-id-class').val();
                var ch_id = '{{ $chData->id }}';
                var flag = 1;

                if (!room_id) {
                    $('#room_id-error').text('ू कड डािय');
                    $('#room_id-error').addClass('error');
                    $('#room_id-error').show();
                    flag = 0;
                } else if (!$.isNumeric(room_id)) {
                    $('#room_id-error').text('रू को अंको में लिये');
                    $('#room_id-error').addClass('error');
                    $('#room_id-error').show();
                    flag = 0;
                } else {
                    $('#room_id-error').text('');
                    $('#room_id-error').removeClass('error');
                    $('#room_id-error').hide();
                }
                if (flag) {

                    $form = $('#chk-room-id-new');
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: '{{ route('chk-room-id') }}',
                        data: $form.serialize(),

                        beforeSend: function() {
                            //$('.loading').show();
                        },
                        success: function(data) {
                            socket.emit('roomCodeServer', data.data);
                            $('#res-room-id-submit-' + data.data.id).text("Update Room Code");
                            $('#res-room-id-submit-' + data.data.id).attr("disabled", true);
                            $('#res-room-id-' + data.data.id).attr("readonly", true);
                            $('#res-room-id-' + data.data.id).attr("disabled", true);
                            $('#change-room-id').hide();
                            $('#chk-room-id-new').hide();
                            $('#res-room-id-new-' + ch_id).text(room_id);
                            $('.copy-room-code').show();
                            $('#alert-success-div').hide();
                        },
                        error: function(data) {
                            var errors = $.parseJSON(data.responseText);
                            $('#challenge-roomcode-error').text(errors.errors.room_id);
                            $('#challenge-roomcode-error').show();
                        },
                        complete: function() {
                            $('.loading').hide();
                        }

                    });
                }
            });

            $('#resultSubmitRadioBtn input').on('change', function() {
                var room_id = $('.res-room-id-class').val();

                $('input[name="result"]').attr("readonly", false);
                var id = $(this).attr('ch-id');
                var selectedResult = $('input[name=result]:checked', '.resultRadioBtn').val();

                $('#result-submit-btn-res').show();
                if (selectedResult == 'Won') {
                    $('#cancel-text-res').hide();
                    $('.screeshot-preview').show();
                    $('#upload-image-res').show();
                } else if (selectedResult == 'Loss') {
                    $('#cancel-text-res').hide();
                    $('#upload-image-res').hide();
                    $('.screeshot-preview').hide();
                } else {
                    $('#upload-image-res').hide();
                    $('#cancel-text-res').show();
                    $('.screeshot-preview').hide();
                }

                $('#resultSubmitRadioBtn').submit(function(e) {
                    var ch_id = $('#res-ch-id').val();
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    flag = 1;
                    var selectedResult1 = $('input[name=result]:checked', '.resultRadioBtn').val();
                    if (selectedResult1 == 'Won') {
                        var fileExtension = ['jpeg', 'jpg', 'png'];
                        var file = $('#result_img-res').val();
                        if (!file) {
                            flag = 0;
                            $('#success-error-div').removeClass('alert-success');
                            $('#success-error-div').addClass('alert-danger');
                            $('#success-error-div').show();
                            $('#success-error-message').text('Please upload image!');
                            hideSuccessErrorDiv();
                        } else if ($.inArray(file.split('.').pop().toLowerCase(), fileExtension) ==
                            -1) {
                            flag = 0;
                            $('#success-error-div').removeClass('alert-success');
                            $('#success-error-div').addClass('alert-danger');
                            $('#success-error-div').show();
                            $('#success-error-message').text('Only formats are allowed : ' +
                                fileExtension.join(', '));
                            hideSuccessErrorDiv();
                        }
                    } else if (selectedResult1 == 'Cancel') {
                        var cancel_reason = $('#cancel_reason-res').val();
                        if (!cancel_reason) {
                            $('#success-error-div').removeClass('alert-success');
                            $('#success-error-div').addClass('alert-danger');
                            $('#success-error-div').show();
                            $('#success-error-message').text('Please enter reason to cancel!');
                            hideSuccessErrorDiv();
                            flag = 0;
                        }
                    }

                    if (flag) {

                        $form = $(this);

                        $(".spinner-border").show();
                        //$('.loading').css('visibility', 'visible'); //to show
                        //$(".loading").attr("style", "display:block");
                        $.ajax({
                            url: "{{ route('submit-result') }}",
                            type: "POST",
                            async: false,
                            data: new FormData(this),
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                //$('#result-submit-btn-res').attr('disabled',true);
                                //$(".loading").show();
                            },
                            success: function(data) {
                                //alert('Result submitted successfully!');
                                $('#success-error-div').removeClass('alert-danger');
                                $('#success-error-div').addClass('alert-success');
                                $('#success-error-div').show();
                                $('#success-error-message').text(
                                    'Result submitted successfully!');
                                window.location.href = "{{ route('challenges') }}";
                            },
                            error: function(data) {
                                //alert('Server Error!');
                                $('#success-error-div').removeClass('alert-success');
                                $('#success-error-div').addClass('alert-danger');
                                $('#success-error-div').show();

                                // alert('Result submitted successfully!!!!');
                                // window.location.href = "{{ route('challenges') }}";
                                var errors = $.parseJSON(data.responseText);
                                $('#success-error-message').text(errors.message);
                                console.log(errors.message);
                                hideSuccessErrorDiv();
                                //$(".spinner-border").hide();
                                //    $('#result-submit-error-res').text(errors.ch_id);
                                //    $('#result-submit-error-res').show();
                            },
                            complete: function() {
                                // $('.loading').hide();
                                //closePopup();
                                // $('#resultSubmitRadioBtn')[0].reset();
                                // $("#resultSubmitRadioBtn").trigger("reset");

                                // $("#chk-room-id-new").trigger("reset");
                                // $('.res-room-id-class').val('');
                                // window.location.href = "{{ route('challenges') }}";
                            }

                        });
                    }

                });

            });
            //---------------- Submit result new end --------------//
            let Roomcode = setInterval(() => {
                getRoomCode();
                if ($("#rcode").val() != "0" && $("#rcode").val() != undefined) {
                    clearInterval(Roomcode);
                }
            }, 1000);
        });
        // getRoomCode();
    </script>
    </script>

@endsection
