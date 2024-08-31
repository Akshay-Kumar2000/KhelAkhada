@extends('layouts.main') 
@section('title', $rData->name)
@section('content')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .download-report{
            padding: 0;
            padding-right: 0px;
            padding-left: 0px;
            font-size: 20px;
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
    <script>
        $(function() {
            $('#date-range').daterangepicker({
                locale: {
                format: 'YYYY/MM/DD'
                }
            });
        });
    </script>
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
                            <li class="breadcrumb-item"><a href="#" >{{$rData->name}}</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card" >
            <div class="card-header">
                <h3 class="d-block w-100">{{$rData->name}}</h3>
                <a class="btn btn-success download-report" href="{{url('admin/reports/download-pdf/'.$rData->id)}}?search={{@$search}}" ><i class="ik ik-download"></i></a>
            </div>
            
            <form class="form-inline float-right" style="position: absolute; right: 100px;margin-top:15px">
                <input type="text" class="form-control mb-2 mr-sm-2" id="date-range" name="search" value="{{@$search}}" placeholder="Search here" />
                <button type="submit" class="btn btn-info mb-2">Search by date</button>
            </form>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No.')}}</th>
                                    <th style="text-align:center">{{ __('Payout ID')}}</th>
                                    <th style="text-align:center">{{ __('Creator')}}</th>
                                    <th style="text-align:center">{{ __('From Date')}}</th>
                                    <th style="text-align:center">{{ __('To Date')}}</th>
                                    <th style="text-align:center">{{ __('Payment Date')}}</th>
                                    <th style="text-align:center">{{ __('Amount')}}</th>
                                    <th style="text-align:center">{{ __('TDS(in %)')}}</th>
                                    <th style="text-align:center">{{ __('Settled Amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $tAmount     =   0;
                                    $tSAmount    =   0;
                                @endphp
                                @foreach($payouts as $key => $val)
                                    <tr>
                                        <td>{{($key + 1)}}</td>
                                        <td style="text-align:center">{{ $val->id }}</td>
                                        <td style="text-align:center">{{ $val->user->name }}</td>
                                        <td style="text-align:center">{{ $val->from_date }}</td>
                                        <td style="text-align:center">{{ $val->to_date }}</td>
                                        <td style="text-align:center">{{ $val->created_at }}</td>
                                        <td style="text-align:center">{{ $val->amount }}</td>
                                        <td style="text-align:center">{{ $val->tds }}</td>
                                        <td style="text-align:center">{{ $val->net_settled }}</td>
                                    </tr>
                                    @php
                                        $tAmount     +=  $val->amount;
                                        $tSAmount    +=  $val->net_settled;
                                    @endphp
                                @endforeach  
                                @if($payouts)
                                    <tr>
                                        <td colspan="6" style="text-align:center"><b>Total</b></td>
                                        <td style="text-align:center"><b>{{$tAmount}}</b></td>
                                        <td></td>
                                        <td style="text-align:center"><b>{{$tSAmount}}</b></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

