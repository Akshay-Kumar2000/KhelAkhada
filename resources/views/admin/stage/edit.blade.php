@extends('layouts.main') 
@section('title', 'Edit Stage')
@section('content')
    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Edit Stage')}}</h5>
                            <span>{{ __('Update existing stage')}}</span>
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
                                <a href="#">{{ __('Update Stage')}}</a>
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
                        <h3>{{ __('Update Stage')}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('edit-stage') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="stage_id" value="{{$stage->id}}" />
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" >
                                        <label for="name">{{ __('Stage Name')}}<span class="text-red">*</span></label>
                                        <input required id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $stage->name }}" placeholder="Enter Stage Name">
                                        <div class="help-block with-errors"></div>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>                             
                                    <div class="form-group" >
                                        <label for="name">{{ __('Stage Venue')}}<span class="text-red">*</span></label>
                                        <input required id="venue" type="text" class="form-control @error('venue') is-invalid @enderror" name="venue" value="{{ $stage->venue }}" placeholder="Enter Stage Venue">
                                        <div class="help-block with-errors"></div>

                                        @error('venue')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>                             
                                    <div class="form-group" >
                                        <label for="name">{{ __('Stage Link')}}<span class="text-red">*</span></label>
                                        <input required id="link" type="text" class="form-control @error('link') is-invalid @enderror" name="link" value="{{ $stage->link }}" placeholder="Enter Stage Link">
                                        <div class="help-block with-errors"></div>

                                        @error('link')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>                             
                                </div>
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
