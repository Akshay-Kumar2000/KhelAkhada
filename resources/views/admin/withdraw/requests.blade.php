@extends('layouts.main') 
@section('title', 'Withdraw Requests')
@section('content')       
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Withdraw Requests')}}</h5>
                            <span>{{ __('List of all withdraw requests')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item pull-left">
                                <a href="javascript:void(0)" onclick="exportData()"><i class="ik ik-download"></i>Download in excel</a>
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
                        <h3>{{ __('All Withdraw Requests')}}</h3>
                        <form class="form-inline" style="position: absolute; right: 30px;">                    
                            <input type="text" class="form-control mb-2 mr-sm-2" name="search" placeholder="Search here" value="{{ $search ? $search : '' }}" />
                            <button type="submit" class="btn btn-info mb-2">Search</button>
                        </form>
                    </div>
                    <div class="card-body p-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="user_table">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID')}}</th>
                                        <th>{{ __('Mannual')}}</th>
                                        <th>{{ __('User')}}</th>
                                        <th>{{ __('Current balance')}}</th>
                                        <th>{{ __('Amount')}}</th>
                                        <th>{{ __('UPI')}}</th>
                                        <th>{{ __('Account No')}}</th>
                                        <th>{{ __('IFSC')}}</th>
                                        <th>{{ __('Type')}}</th>
                                        <th>{{ __('Status')}}</th>
                                        <th>{{ __('Created at')}}</th>
                                        <th>{{ __('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $key => $val)
                                        <tr>
                                            <th scope="row">{{$val->id}}</th>
                                            <td>
                                                @if($val->status == 'Unpaid')
                                                    <a href="{{ route('accept-request-mannual', $val->id) }}" onclick="return confirm('Are you sure want to perform this action?')" class="btn btn-primary mb-2 ">Accept Mannual</a>
                                                @else
                                                    <a href="javascript:void;" class="btn btn-primary mb-2 ">Complete</a>
                                                @endif
                                            </td>
                                            <td>{{ @$val->playername->username}}</td>
                                            <td>{{ @$val->playername->wallet}}</td>
                                            <td>{{ $val->amount }}</td>
                                            <td>{{ $val->upi }}</td>
                                            <td>{{ $val->account_no }}</td>
                                            <td>{{ $val->ifsc_code }}</td>
                                            <td>{{ $val->type }}</td>
                                            <td>{{ $val->status}}</td>
                                            <td>{{$val->created_at}}</td>
                                            <td>
                                                @if($val->status == 'Unpaid')
                                                    <!--<a href="{{ route('accept-request-mannual', $val->id) }}" onclick="return confirm('Are you sure want to perform this action?')" class="btn btn-success mb-2 ">Accept Mannual</a>-->
                                                    <a href="{{ route('accept-request', $val->id) }}" onclick="return confirm('Are you sure want to perform this action?')" class="btn btn-success mb-2 ">Accept</a>
                                                    <a href="{{ route('decline-request', $val->id) }}" onclick="return confirm('Are you sure want to perform this action?')" class="btn btn-danger mb-2 ">Decline</a>
                                                @else
                                                <a href="javascript:void;" class="btn btn-primary mb-2 ">{{$val->status}}</a>
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

    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function exportData(type, fn, dl){
            var elt = document.getElementById('user_table');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('withdraw-requests.' + (type || 'xlsx')));
        }
    </script>
@endsection
