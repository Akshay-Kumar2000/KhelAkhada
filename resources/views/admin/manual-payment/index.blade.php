@extends('layouts.main')
@section('title', 'Manual Payments')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-users bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Manual Payments') }}</h5>
                            <span>{{ __('List of manual payments') }}</span>
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
                <div class="card p-3">
                    <div class="card-header">
                        <h3>{{ __('Manual Payments') }}</h3>

                        <form class="form-inline" style="position: absolute; right: 30px;">
                            <input type="text" class="form-control mb-2 mr-sm-2" name="search" placeholder="Search here"
                                value="{{ $search ? $search : '' }}" />
                            <button type="submit" class="btn btn-info mb-2">Search</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="user_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('#ID') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Old Balance') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Added at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $key => $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ isset($val->user->username) ? $val->user->username : '' }}</td>
                                        <td>{{ $val->old_balance }}</td>
                                        <td>{{ $val->balance }}</td>
                                        <td>{{ $val->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $transactions->links() }}
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
                XLSX.writeFile(wb, fn || ('manual-payments.' + (type || 'xlsx')));
        }
    </script>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <!--server side users table script-->
        <script src="{{ asset('js/custom.js') }}"></script>
    @endpush
@endsection
