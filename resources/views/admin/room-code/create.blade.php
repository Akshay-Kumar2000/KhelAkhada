@extends('layouts.main')
@section('title', 'Create Room Codes')
@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Create Room Codes') }}</h5>
                            <span>{{ __('Create new room codes') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Create Room Code') }}</a>
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
                <div class="card ">
                    <div class="card-header">
                        <h3>{{ __('Create Room Code') }}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('create-room-code') }}">
                            @csrf
                            <div class="row">
                                @for ($i = 0; $i < 12; $i++)
                                    <div class="col-sm-2">

                                        <div class="form-group">
                                            <label for="name">{{ __('Room Code') }}<span
                                                    class="text-red">*</span></label>
                                            <input id="room_codes" type="text"
                                                class="form-control @error('link') is-invalid @enderror" name="room_codes[]"
                                                placeholder="Enter room codes">
                                            <div class="help-block with-errors"></div>

                                            @error('room_codes')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                @endfor
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

    @push('script')
        <script>
            function linkType(val) {
                if (val.value == 'External') {
                    $('#select-link-div').show();
                    $('#select-event-div').hide();
                    $('#select-creator-div').hide();
                } else if (val.value == 'Event') {
                    $('#select-link-div').hide();
                    $('#select-event-div').show();
                    $('#select-creator-div').hide();
                } else if (val.value == 'Creator') {
                    $('#select-link-div').hide();
                    $('#select-event-div').hide();
                    $('#select-creator-div').show();
                } else {
                    $('#select-link-div').hide();
                    $('#select-event-div').hide();
                    $('#select-creator-div').hide();
                }
            }
        </script>
    @endpush

@endsection
