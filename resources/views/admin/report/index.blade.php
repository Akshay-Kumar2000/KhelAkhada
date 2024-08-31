@extends('layouts.main') 
@section('title', 'Reports')
@section('content')
    <!-- push external head elements to head -->    
    @push('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endpush
    
    <script>        
        $( function() {
            $('#date-range').daterangepicker({
                locale: {
                format: 'YYYY/MM/DD'
                }
            });
        })
    </script>
    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-unlock bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Reports')}}</h5>
                            <span>{{ __('Reports')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('/dashboard')}}}"><i class="ik ik-home">Dashboard</i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Reports')}}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <!-- only those have manage_permission permission will get access -->
            @can('creator_report')
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>{{ __('Calculate data')}}</h3>
                            <form class="form-inline float-right" style="position: absolute; right: 100px;">
                                <input type="text" class="form-control mb-2 mr-sm-2" id="date-range" name="search" value="{{$data['search']}}" placeholder="Search here" />
                                <button type="submit" class="btn btn-info mb-2">Calculate Commission</button>                            
                            </form>                        
                        </div>                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-3">
                            <div class="card-body">
                                <table class="table col-md-12">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Date from')}}</th>
                                            <th>{{ __('Date to')}}</th>
                                            <th>{{ __('Total Games')}}</th>
                                            <th>{{ __('Total Recharge')}}</th>
                                            <th>{{ __('Admin Commossion')}}</th>
                                            <th>{{ __('Referral')}}</th>
                                            <th>{{ __('Withdrawl')}}</th>                                        
                                            <th>{{ __('Available Balance')}}</th>                                        
                                        </tr>
                                        
                                        <tr>
                                            <td>{{$data['from_date']}}</td>
                                            <td>{{$data['to_date']}}</td>
                                            <td>{{$data['games']}}</td>
                                            <td>{{$data['recharge']}}</td>                                            
                                            <td>{{$data['commission']}}</td>
                                            <td>{{$data['referral']}}</td>
                                            <td>{{$data['withdrawl']}}</td>
                                            <td>{{$data['balance']}}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>        
    </div>
    <!-- push external js -->
    
@endsection
