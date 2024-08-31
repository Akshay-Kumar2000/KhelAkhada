@extends('layouts.main') 
@section('title', 'Profile')
@section('content')
    

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Profile')}}</h5>
                            <span>{{ __('Creator Details')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/creators') }}">{{ __('Creators')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $user->name}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center"> 
                            <img src="https://raw-celeb-bucket.s3.ap-south-1.amazonaws.com/profile/{{$user->profile_pic}}" class="rounded-circle" width="150" height="180" />
                            <h4 class="card-title mt-10">{{ $user->name}}</h4>
                        </div>
                    </div>
                    <hr class="mb-0"> 
                    <div class="card-body"> 
                        <small class="text-muted d-block">{{ __('Email address')}} </small>
                        <h6>{{$user->email}}</h6> 
                        <small class="text-muted d-block">{{ __('Mobile')}} </small>
                        <h6>{{$user->mobile}}</h6> 
                        <span class="badge badge-success" title="Account Status">@if($user->status) Active @else Inactive @endif </span>
                        </br><small class="text-muted d-block">{{ __('Description')}} </small>
                        <h6>{{$user->description}}</h6> 
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true">{{ __('All Events')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="current-month" role="tabpanel" aria-labelledby="pills-timeline-tab">
                            <div class="card">
                                <div class="card-body">
                                    <table id="advanced_table" class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Event Time</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($events as $k => $v)
                                            <tr>
                                                <td>{{$v->id}}</td>
                                                <td>{{$v->title}}</td>
                                                <td>{{$v->type}}</td>
                                                <td>{{$v->event_time}}</td>
                                                @if($v->status == 0)
                                                    <td class="text-red">Inactive</td>
                                                @elseif($v->status == 1)
                                                    <td class="text-blue">Active</td>
                                                @elseif($v->status == 2)
                                                    <td class="text-green">Live</td>
                                                @elseif($v->status == 3)
                                                    <td class="text-green">Completed</td>
                                                @elseif($v->status == 4)
                                                    <td class="text-red">Force Stoped</td>
                                                @endif
                                                <td>
                                                @can('manage_event')
                                                <a href="{{url('admin/event/detail/'.$v->id)}}"><i class="ik ik-eye  text-blue f-16" title="View details"></i></a>
                                                @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                            <br>
                                            <tr><td colspan="6" >{{$events->links()}}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

