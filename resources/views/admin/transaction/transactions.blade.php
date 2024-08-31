@extends('layouts.main')
@section('title', 'Transactions')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Transactions') }}</h5>
                            <span>{{ __('List of all transactions') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item pull-left">
                                <a href="javascript:void(0)" onclick="exportData()"><i class="ik ik-download"></i>Download
                                    in excel</a>
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
                        <h3>{{ __('All Transactions') }}</h3>
                    </div>
                    <form class="form-inline">
                        <input type="text" class="form-control mb-2 mr-sm-2" name="search" placeholder="Search here"
                            value="{{ $search ? $search : '' }}" />
                        <button type="submit" class="btn btn-info mb-2">Search</button>
                    </form>
                    <div class="card-body p-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="user_table">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Order ID') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Gateway') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $val->id }}</th>
                                            <td>{{ $val->order_id }}</td>
                                            <td>{{ $val->playername ? $val->playername->username : 'N/A' }}</td>
                                            <td>{{ $val->amount }}</td>
                                            <td>{{ $val->gateway }}</td>
                                            <td><b
                                                    class="text-{{ $val->status ? 'green' : 'red' }}">{{ $val->status ? 'Success' : 'Fail' }}</b>
                                            </td>
                                            <td>{{ $val->created_at }}</td>
                                            <td>
                                                @if ($val->status == 0)
                                                    <a title="Update wallet balance" class="btn btn-success"
                                                        onclick="return confirm('Are you sure want to perform this action ?')"
                                                        href="{{ url('admin/transactions/approve/' . $val->id) }}">Approve</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function exportData(type, fn, dl) {
            var elt = document.getElementById('user_table');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('online-transactions.' + (type || 'xlsx')));
        }
    </script>
@endsection
