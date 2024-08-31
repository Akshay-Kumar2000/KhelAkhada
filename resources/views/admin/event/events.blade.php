@extends('layouts.main') 
@section('title', 'Events')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    @endpush
    @php use App\Http\Controllers\Admin\EventController; @endphp
    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Events')}}</h5>
                            <span>{{ __('List of all events')}}</span>
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
                                <a href="#">{{ __('Events')}}</a>
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
                        <h3>{{ __('All Events')}}</h3>
                        <form class="form-inline" style="position: absolute; right: 30px;">
                            <input type="text" class="form-control mb-2 mr-sm-2" name="search" placeholder="Search here" value="{{ $search ? $search : '' }}" />
                            <button type="submit" class="btn btn-info mb-2">Search</button>
                        </form>
                    </div>
                    <div class="card-body p-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID')}}</th>
                                        <th>{{ __('Title')}}</th>
                                        <th>{{ __('Creator')}}</th>
                                        <th>{{ __('Joining Fee')}}</th>
                                        <th>{{ __('Joined Users')}}</th>
                                        <th>{{ __('Earning')}}</th>
                                        <th>{{ __('Type')}}</th>
                                        <th>{{ __('Is Schedule')}}</th>
                                        <th>{{ __('Event Date & Time')}}</th>
                                        <th>{{ __('Status')}}</th>
                                        <th>{{ __('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $key => $val)
                                        <tr>
                                            <th scope="row">{{$val->id}}</th>
                                            <td>{{$val->title}}</td>
                                            <td>{{$val->creator->name}}</td>
                                            <td>{{$val->price ? $val->price : '---'}}</td>
                                            <td>{{$val->joinusers->count()}}</td>
                                            <td>{{ $val->earning_sum_amount ? $val->earning_sum_amount : 0 }}</td>
                                            <td>{{ $val->type }}</td>
                                            <td>{{ $val->schedule ? 'Yes' : 'No' }}</td>
                                            <td>{{ $val->event_time }}</td>
                                            <td class="@if($val->status == 4 || $val->status == 0) text-red @elseif($val->status == 2 || $val->status == 1) text-green @elseif($val->status == 3) text-blue @endif">
                                                @if($val->status == 1) Active @elseif($val->status == 2) Live @elseif($val->status == 0) Inactive @elseif($val->status == 3) Completed @elseif($val->status == 4) Force Stop @endif
                                            </td>
                                            <td>
                                                @can('manage_event')
                                                <a href="{{url('admin/event/detail/'.$val->id)}}"><i class="ik ik-eye  text-blue f-16" title="View details"></i></a>
                                                @endcan
                                            
                                                @can('stop_event')
                                                    @if($val->status == 2)
                                                        <a onclick="return confirm('Are you sure want to perform this action?')" href="{{url('admin/event/stop-event/'.$val->id)}}"><i class="ik ik-stop-circle  text-blue f-16" title="Forced stop"></i></a>
                                                    @elseif($val->status == 4)
                                                        <a onclick="alert('Already ended!')" href="javascript:void(0)"><i class="ik ik-stop-circle  text-red f-16" title="Forced stoped"></i></a>                                                    
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $events->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
    <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <!--server side users table script-->
    <script src="{{ asset('js/custom.js') }}"></script>
    @endpush
@endsection
