@extends('layouts.main') 
@section('title', 'User Complaints')
@section('content')       
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('User Complaints')}}</h5>
                            <span>{{ __('List of all user complaints')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin-dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('User Complaints')}}</a>
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
                        <h3>{{ __('All User Complaints')}}</h3>
                    </div>
                    <div class="card-body p-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID')}}</th>
                                        <th>{{ __('User')}}</th>
                                        <th>{{ __('Message')}}</th>
                                        <th>{{ __('Image')}}</th>
                                        <th>{{ __('Status')}}</th>
                                        <th>{{ __('Created at')}}</th>
                                        <th>{{ __('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $key => $val)
                                        <tr>
                                            <th scope="row">{{$val->id}}</th>
                                            <td>{{ @$val->playername->username}}</td>
                                            <td>{{ @$val->message}}</td>
                                            <td><a href="{{ asset($val->image) }}" target="_blank"><img src="{{ asset($val->image) }}" width="180px"/></a></td>
                                            <td>
                                                @if($val->status == 0) <span class="text-green"><b>Solved</b></span>
                                                @elseif($val->status == 1) <span class="text-red"><b>Pending</b></span>
                                                @elseif($val->status == 2) <span class="text-orange"><b>Hold</b></span>
                                                @endif
                                            </td>
                                            <td>{{$val->created_at}}</td>
                                            <td>
                                                @if($val->status == 1 || $val->status == 2)
                                                    <a href="{{ route('complaint-action', $val->id) }}" onclick="return confirm('Are you sure want to perform this action?')" class="btn btn-success mb-2 ">Solve</a>                                                    
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $requests->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
