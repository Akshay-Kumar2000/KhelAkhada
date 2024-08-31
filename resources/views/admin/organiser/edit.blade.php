@extends('layouts.main') 
@section('title', 'Edit Organiser')
@section('content')
    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Edit Organiser')}}</h5>
                            <span>{{ __('Edit organiser')}}</span>
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
                                <a href="#">{{ __('Update Organiser')}}</a>
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
                        <h3>{{ __('Create Organiser')}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('edit-organiser') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="organiser_id" value="{{$organiser->id}}">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" >
                                        <label for="name">{{ __('Organiser Title')}}<span class="text-red">*</span></label>
                                        <input required id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $organiser->title }}" placeholder="Enter Organiser Title">
                                        <div class="help-block with-errors"></div>

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>       
                                    <div class="form-group">
                                        <label for="profile_pic">{{ __('Organiser Logo')}}<span class="text-red">*</span></label>
                                        <input id="organiser" type="file" class="form-control" name="logo" />
                                        <div class="help-block with-errors"></div>

                                        @error('logo')
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
