@extends('layouts.main') 
@section('title', 'Reports')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Reports')}}</h5>
                            <span>{{ __('View complete reports')}}</span>
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
                                <a href="{{url('admin/reports')}}">{{ __('Reports')}}</a>
                            </li>
                            
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card" >
            <div class="card-header"><h3 class="d-block w-100">{{ __('All Reports') }}</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.')}}</th>
                                    <th style="text-align:center">{{ __('Report ID')}}</th>
                                    <th style="text-align:center">{{ __('Report Name')}}</th>
                                    <th style="text-align:center">{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $key => $val)
                                    <tr>
                                        <td>{{($key + 1)}}</td>
                                        <td style="text-align:center">{{ $val->id }}</td>
                                        <td style="text-align:center">{{ $val->name }}</td>
                                        <td style="text-align:center">
                                            <a href="{{url('admin/reports/'.$val->id)}}"><i class="ik ik-eye f-16" title="View Details"></i></a>
                                        </td>
                                    </tr>
                                @endforeach  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

