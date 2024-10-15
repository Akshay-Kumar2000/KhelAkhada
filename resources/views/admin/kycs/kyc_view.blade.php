@extends('layouts.main')

@section('head')
    <title> Transcation History </title>
@endsection


@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Player Profile </h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">

            <div class="card-body">
                <div class="co-md-12" style="padding:30px">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ asset('frontend/images/avatars/Avatar2.png') }}"
                                style="width:120px; height:auto; border:5px solid #395fcf; border-radius:50%; float:left ">

                            @if ($user_details)
                                <div
                                    style="float:left; margin-top:20px; margin-bottom:40px; margin-left:20px; color: #395fcf">
                                    <h3>{{ $user_details->username }}</h3>
                                    <h3>{{ $user_details->mobile }}</h3>
                                </div>
                            @else
                                <div
                                    style="float:left; margin-top:20px; margin-bottom:40px; margin-left:20px; color: #395fcf">
                                    <h3>User details not found</h3>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-1">
                            <div class="win" style="padding-top:24px">
                                <img src="{{ asset('frontend/images/win.png') }}" style="width:60px">
                                <div style="font-size:40px; color:green; float:right">
                                    {{ optional($user_details)->total_win ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-1">
                            <div class="lost" style="padding-top:24px">
                                <img src="{{ asset('frontend/images/lost.png') }}" style="width:60px">
                                <span style="font-size:40px; color:red; float:right">
                                    {{ optional($user_details)->total_lost ?? 'N/A' }}
                                </span>

                            </div>
                        </div>

                        <div class="col-md-1"></div>
                        <div class="col-md-2">
                            <div class="status" style="padding-top:24px">
                                @if ($user_kyc_details->verify_status == 1)
                                    <img src="{{ asset('/backend/img/image_verified.png') }}"
                                        style="width:50px; float:left">
                                    &nbsp; <span style="font-size:25px; color:Green;">Verified</span>
                                @else
                                    <img src="{{ asset('/backend/img/under_review.png') }}"
                                        style="width:50px; float:left">&nbsp; <span
                                        style="font-size:20px; color:red;">Un-Verified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr style="background-color:#4e73df; color:white; font-weight:600">
                                    <th>
                                        <h5> Update Kyc Status</h5>
                                    </th>
                                    <th>
                                        @if ($user_kyc_details->verify_status == '1')
                                            <h5><a href="{{ url('admin/kyc-reject/' . $user_kyc_details->id) }}"
                                                    class="btn btn-xs btn-danger">Reject</a></h5>
                                        @else
                                            <h5><a href="{{ url('admin/kyc-verify/' . $user_kyc_details->id) }}"
                                                    class="btn btn-xs btn-success">Approve</a></h5>
                                            <h5><a href="{{ url('admin/kyc-reject/' . $user_kyc_details->id) }}"
                                                    class="btn btn-xs btn-danger">Reject</a></h5>
                                        @endif


                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-bordered">
                                <tr style="background-color:#4e73df; color:white; font-weight:600">
                                    <th colspan="2">
                                        <h5> KYC Documents Details</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Document Name</th>
                                    <td>{{ $user_kyc_details->DOCUMENT_NAME }}</td>
                                </tr>
                                <tr>
                                    <th>Document Number</th>
                                    <td>{{ $user_kyc_details->DOCUMENT_NUMBER }}</td>
                                </tr>
                                <tr>
                                    <th>First Name </th>
                                    <td>{{ $user_kyc_details->DOCUMENT_FIRST_NAME }}</td>
                                </tr>
                                <tr>
                                    <th>Last Name </th>
                                    <td>{{ $user_kyc_details->DOCUMENT_LAST_NAME }}</td>
                                </tr>
                                <tr>
                                    <th>Date Of Birth </th>
                                    <td>{{ $user_kyc_details->DOCUMENT_DOB }}</td>
                                </tr>
                                <tr>
                                    <th>State </th>
                                    <td>{{ $user_kyc_details->DOCUMENT_STATE }}</td>
                                </tr>
                                <tr>
                                    <th>Front Side Image</th>
                                    <td align="center">

                                        <img src="{{ asset('/images/kycdata/' . $user_kyc_details->user_id . '/' . $user_kyc_details->DOCUMENT_FRONT_IMAGE) }}"
                                            onclick="location.href='{{ asset('/images/kycdata/' . $user_kyc_details->user_id . '/' . $user_kyc_details->DOCUMENT_FRONT_IMAGE) }}'"
                                            style="width:50px" alt="">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Back Side Image</th>
                                    <td align="center">
                                        <img src="{{ asset('/images/kycdata/' . $user_kyc_details->user_id . '/' . $user_kyc_details->DOCUMENT_BACK_IMAGE) }}"
                                            onclick="location.href='{{ asset('/images/kycdata/' . $user_kyc_details->user_id . '/' . $user_kyc_details->DOCUMENT_BACK_IMAGE) }}'"
                                            style="width:50px" alt="">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Verify Document status</th>
                                    <td>
                                        @if ($user_kyc_details->verify_status == 0)
                                            <span class="btn btn-danger"> PENDING </span>
                                        @else
                                            <span class="btn btn-success"> VERIFIED</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <table class="table table-bordered">
                                <tr style="background-color:#4e73df; color:white; font-weight:600">
                                    <th colspan="2">
                                        <h5> Other Details</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ optional($user_details)->email ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <th>Wallet</th>
                                    <td>
                                        <img src="{{ asset('frontend/images/global-rupeeIcon.png') }}" style="width:25px"
                                            alt="">
                                        {{ optional($user_details)->wallet ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Refferal Code</th>
                                    <td>{{ isset($user_setting->referral) ? $user_setting->referral : '' }}</td>
                                </tr>
                                <tr>
                                    <th>Reffered By</th>
                                    <td>{{ isset($user_setting->used_referral) ? $user_setting->used_referral : '' }}</td>
                                </tr>

                                <tr>
                                    <th>Created at</th>
                                    {{-- <td>{{ $user_details->created_at }}</td> --}}
                                    <td>{{ optional($user_details)->created_at ?? 'N/A' }}</td>

                                </tr>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
