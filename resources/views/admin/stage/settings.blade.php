@extends('layouts.main') 
@section('title', 'Stage Seat Settings')
@section('content')
    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Stage Seat Settings')}}</h5>
                            <span>{{ __('Change stage seat settings')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Stage Seat Settings')}}</a>
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
                        <h3>{{ __('Stage Seat Settings')}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('update-setting') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="stage_id" value="{{ $stage_id }}" />
                            <div class="row">
                                @foreach($categories as $key => $val)
                                    <input type="hidden" name="categories[]" value="{{ $key }}" />
                                    @if(in_array($key,$sArrays))
                                        @foreach($settings as $k => $v)
                                            @if($v->category_id == $key)
                                                <input type="hidden" name="settings[]" value="{{ $v->id }}" />
                                                <div class="col-sm-4" style="border-right: solid;">
                                                    <h4 style="text-align:center"><b>{{$val}}</b></h4>
                                                    <div class="form-group" >
                                                        <label for="name">{{ __('Stage Seat Capacity')}}<span class="text-red">*</span></label>
                                                        <input required type="text" class="form-control @error('capacities') is-invalid @enderror" name="capacities[]" value="{{ $v->capacity }}" placeholder="Enter seat capacity of {{$val}} category">
                                                        <div class="help-block with-errors"></div>

                                                        @error('capacities')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>                             
                                                    <div class="form-group" >
                                                        <label for="name">{{ __('Stage Seat Price')}}<span class="text-red">*</span></label>
                                                        <input required  type="text" class="form-control @error('prices') is-invalid @enderror" name="prices[]" value="{{ $v->price }}" placeholder="Enter seat price of {{$val}} category">
                                                        <div class="help-block with-errors"></div>

                                                        @error('prices')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>                             
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                    <input type="hidden" name="settings[]" value="" />
                                        <div class="col-sm-4" style="border-right: solid;">
                                            <h4 style="text-align:center"><b>{{$val}}</b></h4>
                                            <div class="form-group" >
                                                <label for="name">{{ __('Stage Seat Capacity')}}<span class="text-red">*</span></label>
                                                <input required type="text" class="form-control @error('capacities') is-invalid @enderror" name="capacities[]" value="{{ old('capacities') }}" placeholder="Enter seat capacity of {{$val}} category">
                                                <div class="help-block with-errors"></div>

                                                @error('capacities')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>                             
                                            <div class="form-group" >
                                                <label for="name">{{ __('Stage Seat Price')}}<span class="text-red">*</span></label>
                                                <input required  type="text" class="form-control @error('prices') is-invalid @enderror" name="prices[]" value="{{ old('prices') }}" placeholder="Enter seat price of {{$val}} category">
                                                <div class="help-block with-errors"></div>

                                                @error('prices')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>                             
                                        </div>
                                    @endif
                                @endforeach
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
