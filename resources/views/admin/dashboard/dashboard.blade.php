@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/chartist/dist/chartist.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="row">
            <!-- page statustic chart start -->
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/users') }}">
                    <div class="card card-red text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['today_users'] }}</h4>
                                    <p class="mb-0">{{ __('New users') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="fa fa-users f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/users') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['total_users'] }}</h4>
                                    <p class="mb-0">{{ __('Total users ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/users') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['total_wallet_balance'] }}</h4>
                                    <p class="mb-0">{{ __('Total Wallet Balance ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="#">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['today_recharge'] - $data['today_withdraw'] }}</h4>
                                    <p class="mb-0">{{ __('Today income ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="#">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['total_recharge'] - $data['total_withdraw'] }}</h4>
                                    <p class="mb-0">{{ __('Total income ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/transactions') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['month_recharge'] }}</h4>
                                    <p class="mb-0">{{ __('Monthly Recharge ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/transactions') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['total_recharge'] }}</h4>
                                    <p class="mb-0">{{ __('Total Recharge ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/transactions') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['today_recharge'] }}</h4>
                                    <p class="mb-0">{{ __('Today Recharge ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/withdraw-requests') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['today_withdraw'] }}</h4>
                                    <p class="mb-0">{{ __('Today withdrawal ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/withdraw-requests') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['total_withdraw'] }}</h4>
                                    <p class="mb-0">{{ __('Total Withdraw ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/challenges') }}">
                    <div class="card card-blue text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['total_challenges'] }}</h4>
                                    <p class="mb-0">{{ __('Total games') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-user f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/challenges?status=Hold') }}">
                    <div class="card card-green text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['hold_challenges'] }}</h4>
                                    <p class="mb-0">{{ __('Pending games ') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-globe f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/challenges?status=Playing') }}">
                    <div class="card card-yellow text-white">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['playing_challenges'] }}</h4>
                                    <p class="mb-0">{{ __('Playing Games') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-film f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a href="{{ url('admin/challenges?status=Play_wait') }}">
                    <div class="card card-yellow text-white">
                        <div class="card-block" style="background: goldenrod;">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['one_person_response'] }}</h4>
                                    <p class="mb-0">{{ __('One person response') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-film f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-6">
                <a href="#">
                    <div class="card card-yellow text-white">
                        <div class="card-block" style="background: goldenrod;">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="mb-0">{{ $data['total_month_refer_income'] }}</h4>
                                    <p class="mb-0">{{ __('Monthly Refer Income') }}</p>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="ik ik-film f-30"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- page statustic chart end -->


            <!-- Streams record start -->
            <div class="col-md-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h3>{{ __('Last Recharges By Day') }}</h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="ik ik-chevron-left action-toggle"></i></li>
                                <li><i class="ik ik-minus minimize-card"></i></li>
                                <li><i class="ik ik-x close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block p-b-0">
                        <div class="table-responsive scroll-widget">
                            <table class="table table-hover table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['rechargesByDay'] as $key => $val)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $key }}</td>
                                            <td>{{ $val }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Streams record end -->
            <!-- sale 2 card start -->
            <div class="col-md-12 col-xl-12">
                <div class="card card-green">
                    <div class="card-header ">
                        <div class="col">
                            <h6 class="mb-5" style="color:white;">{{ __('Income In ') . @date('F') }}</h6>
                            <h5 class="mb-0  fw-700" style="color:white;">{{ __('₹') . $data['total_recharge'] }}</h5>
                        </div>
                    </div>
                    <div class="card-block text-center">
                        <div id="sale-diff" class="chart-shadow"></div>
                    </div>
                </div>
            </div>
            <!-- sale 2 card end -->

            <!-- Streams record start -->
            <div class="col-md-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h3>{{ __('Recharges') }}</h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="ik ik-chevron-left action-toggle"></i></li>
                                <li><i class="ik ik-minus minimize-card"></i></li>
                                <li><i class="ik ik-x close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block p-b-0">
                        <div class="table-responsive scroll-widget">
                            <table class="table table-hover table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Player') }}</th>
                                        <th>{{ __('Source ID') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Stream Time') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['recharges'] as $key => $val)
                                        <tr>
                                            <td>{{ $val->id }}</td>
                                            {{-- <td>{{ $val->playername->username }}</td> --}}
                                            <td>{{ $val->source_id }}</td>
                                            <td>{{ $val->amount }}</td>
                                            <td>{{ $val->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="{{ url('admin/transactions') }}"
                                class=" b-b-primary text-primary">{{ __('View all transactions') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Streams record end -->

        </div>
    </div>
        <!-- push external js -->
        @push('script')
        <!-- Include jQuery before any plugin scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Owl Carousel and other plugin scripts -->
        <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

        <!-- AmCharts and other scripts -->
        <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>

        <!-- Your custom scripts -->
        <script src="{{ asset('js/widget-statistic.js') }}"></script>
        <script src="{{ asset('js/widget-data.js') }}"></script>
        <script src="{{ asset('js/dashboard-charts.js') }}"></script>

        <!-- Sample alert function -->
        <script>
            window.addEventListener('beforeunload', function(e) {
                alert('kk');
                e.preventDefault();
                e.returnValue = '';
            });
        </script>
    @endpush
@endsection
