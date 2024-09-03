@extends('layouts.main')
@section('title', 'Challenges')
@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Challenges') }}</h5>
                            <span>{{ __('List of all events') }}</span>
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
                        <h3>{{ __('All Challenges') }}</h3>
                    </div>
                    <form class="form-inline">
                        <input type="text" class="form-control mb-2 mr-sm-2" name="search" placeholder="Search here"
                            value="{{ $search ? $search : '' }}" />
                        Search in:
                        <input type="radio" class="form-control mb-2 mr-sm-2"
                            @if ($search_in == 'c_id') checked @endif name="search_in" value="c_id">Creator
                        <input type="radio" class="form-control mb-2 mr-sm-2"
                            @if ($search_in == 'o_id') checked @endif name="search_in" value="o_id">Opponent
                        <button type="submit" class="btn btn-info mb-2">Search</button>
                    </form>
                    <div class="card-body p-0 table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="user_table">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Creator') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Opponent') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Room Code') }}</th>
                                        <th>{{ __('Winner') }}</th>
                                        <th>{{ __('Date & Time') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($challenges as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $val->id }}</th>
                                            <td>{{ $val->creator ? $val->creator->username : 'N/A' }}</td>
                                            <td>{{ $val->amount }}</td>
                                            <td>{{ $val->opponent ? $val->opponent->username : 'N/A' }}</td>
                                            <td>{{ $val->type }}</td>
                                            <td>{{ $val->rcode }}</td>
                                            <td>
                                                @if (isset($val->result->is_cancel) && $val->result->is_cancel == 1)
                                                    Cancelled
                                                @else
                                                    {{ $val->result && $val->result->winner ? $val->result->winner->username : 'N/A' }}
                                                @endif
                                            </td>
                                            <td>{{ $val->created_at }}</td>
                                            <td>
                                                @switch($val->status)
                                                    @case(0)
                                                        <span class="text-blue">Completed</span>
                                                    @break

                                                    @case(1)
                                                        <span class="text-green">Open</span>
                                                    @break

                                                    @case(2)
                                                        <span class="text-green">Joined</span>
                                                    @break

                                                    @case(3)
                                                        <span class="text-green">Accept</span>
                                                    @break

                                                    @case(4)
                                                        <span class="text-green">Game Started</span>
                                                    @break

                                                    @case(5)
                                                        <span class="text-red">Hold</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @can('manage_challenge')
                                                    <a href="{{ url('admin/challenge/' . $val->id) }}">
                                                        <i class="ik ik-eye text-blue f-16" title="View details"></i>
                                                    </a>
                                                    @if ($val->status != 0 && $val->status != 5)
                                                        <a href="{{ url('admin/challenge/roomcode/' . $val->id) }}"
                                                            style="color:red">Room Code</a>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $challenges->links() }}
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
                XLSX.writeFile(wb, fn || ('challenges.' + (type || 'xlsx')));
        }
    </script>
    <!-- push external js -->
    @push('script')
        <!--server side users table script-->
        <script src="{{ asset('js/custom.js') }}"></script>
    @endpush
@endsection
