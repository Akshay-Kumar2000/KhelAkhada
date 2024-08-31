@extends('layouts.front.front')
@section('content')
    <div class="divider-x"></div>
    <div class="px-4 py-3">
        <div class="d-flex flex-column">
            <div class="mt-3">
                <div class="games-section-title"><b>Edit Withdrawal Details</b></div>
                @if ($ModeOn[0] == 'yes')
                    <div class="mt-4">
                        <div class="add-fund-box d-flex align-items-center" style="padding-top: 15px; height: 80px;">
                            <div class="d-flex align-items-center">
                                <img width="48px" src="/images/upi.webp" alt="">
                                <div class="d-flex justify-content-center flex-column ml-4 font-weight-bold">
                                    <div class="jss5">Edit UPI Details</div>
                                    @if ($UPIbankDetail)
                                        <div class="jss2" style="font-size: 0.7em;">UPI Id: {{ $UPIbankDetail->number }}
                                        </div>
                                        <div class="jss2" style="font-size: 0.7em;">Verified Name:
                                            {{ $UPIbankDetail->name }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-auto mr-4 text-danger" onclick="location.href='/editBankDeatil/upi'">EDIT</div>
                        </div>
                    </div>
                @endif
                @if ($ModeOn[1] == 'yes')
                    <div class="mt-4">
                        <div class="add-fund-box d-flex align-items-center" style="padding-top: 15px; height: 80px;">
                            <div class="d-flex align-items-center">
                                <img width="48px" src="/images/bank.png" alt="">
                                <div class="d-flex justify-content-center flex-column ml-4 font-weight-bold">
                                    <div class="jss5">Edit Bank Account</div>
                                    @if ($IMPSbankDetail)
                                        <div class="jss2" style="font-size: 0.7em;">Bank Account:
                                            {{ $IMPSbankDetail->number }}</div>
                                        <div class="jss2" style="font-size: 0.7em;">IFSC Code: {{ $IMPSbankDetail->ifsc }}
                                        </div>
                                        <div class="jss2" style="font-size: 0.7em;">Verified Name:
                                            {{ $IMPSbankDetail->name }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-auto mr-4 text-danger" onclick="location.href='/editBankDeatil/imps'">EDIT</div>
                        </div>
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
    <div id="popup"
        style="display:none;position:fixed;width:100dvw;height:100dvh;top: 0;left: 0;z-index: 11111111;flex-direction: row;flex-wrap: nowrap;align-content: center;justify-content: center;align-items: center;background: #808080cc;color: white;">
        <h1 style="text-align:center;">Loading..</h1>
    </div>
    <div class="divider-y"></div>
    {{-- <script>s --}}
@endsection
