@extends('layouts.main')
@section('title', 'Settings')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Settings') }}</h5>
                            <span>{{ __('List of all setting fields') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin-dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Settings') }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header ">
                        <h3>{{ __('All Settings') }}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('update-settings') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php //echo $settings[0]; die;
                                    ?>
                                    <div class="form-group">
                                        <label for="name">{{ __('Auto Room Code ') }}<span
                                                class="text-red">*</span></label>
                                        <input type="checkbox" class="js-single" name="auto_room_code"
                                            @if ($settings[0]) checked @endif />
                                        <div class="help-block with-errors"></div>

                                        @error('room_codes')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="GatewayChoice">{{ __('Gateway Choice') }}<span
                                                class="text-red">*</span></label>
                                        <select id="GatewayChoice" name="GatewayChoice" required>
                                            <option value="">Select Gateway</option>
                                            <option value="mpay" {{ $settings[6] == 'mpay' ? 'selected' : '' }}>MPAY
                                                Payment Gateway</option>
                                            <option value="phonepeupi"
                                                {{ $settings[6] == 'phonepeupi' ? 'selected' : '' }}>Phonepe Upi Gateway
                                                Payment Gateway</option>
                                            <option value="upi" {{ $settings[6] == 'upi' ? 'selected' : '' }}>Upi
                                                Gateway Payment Gateway</option>
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('room_code_expire_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('Room Code Expires Time(in minutes)') }}<span
                                                class="text-red">*</span></label>
                                        <input id="room_code_expire_in" type="text" value="{{ $settings[1] }}"
                                            class="form-control @error('link') is-invalid @enderror"
                                            name="room_code_expire_in" placeholder="Room Code Expires Time">
                                        <div class="help-block with-errors"></div>

                                        @error('room_code_expire_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('Withdrawal Open') }}<span
                                                class="text-red">*</span></label>
                                        <select id="WithdrawalStatus" value="{{ $settings[8] }}" class="form-control"
                                            name="WithdrawalStatus">
                                            <option value="">Select Mode</option>
                                            <option value="yes" {{ $settings[8] == 'yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="no" {{ $settings[8] == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('room_code_expire_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('Deposit Open') }}<span
                                                class="text-red">*</span></label>
                                        <select id="DepositStatus" class="form-control" name="DepositStatus">
                                            <option value="yes"
                                                {{ isset($settings[12]) && $settings[12] == 'yes' ? 'selected' : '' }}>
                                                Yes</option>
                                            <option value="no"
                                                {{ isset($settings[12]) && $settings[12] == 'no' ? 'selected' : '' }}>No
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                        @error('DepositStatus')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('Maintainance Mode') }}<span
                                                class="text-red">*</span></label>
                                        <select id="maintainance" value="{{ $settings[1] }}" class="form-control"
                                            name="maintainance_mode">
                                            <option value="">Select Mode</option>
                                            <option value="yes" {{ $settings[2] == 'yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="no" {{ $settings[2] == 'no' ? 'selected' : '' }}>No</option>
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('room_code_expire_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('Withdraw automatic') }}<span
                                                class="text-red">*</span></label>
                                        <select id="maintainance" value="{{ $settings[1] }}" class="form-control"
                                            name="auto_withdraw">
                                            <option value="">Select Status</option>
                                            <option value="yes" {{ $settings[3] == 'yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="no" {{ $settings[3] == 'no' ? 'selected' : '' }}>No
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('room_code_expire_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('UPI Withdraw') }}<span
                                                class="text-red">*</span></label>
                                        <select id="UPIwithdraw" value="{{ $settings[9] }}" class="form-control"
                                            name="UPIwithdraw">
                                            <option value="">Select Status</option>
                                            <option value="yes" {{ $settings[9] == 'yes' ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="no" {{ $settings[9] == 'no' ? 'selected' : '' }}>No
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('room_code_expire_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ __('Notice') }}<span class="text-red">*</span></label>
                                        <input id="notice" type="text" value="{{ $settings[5] }}"
                                            class="form-control @error('link') is-invalid @enderror" name="notice"
                                            placeholder="Notice">
                                        <div class="help-block with-errors"></div>

                                        @error('room_code_expire_in')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('js/form-advanced.js') }}"></script>
    @endpush
@endsection
