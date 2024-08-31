@extends('layouts.main') 
@section('title', 'User Statement')
@section('content')
@php use App\Http\Controllers\Admin\EventController; @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('User Statement')}}</h5>
                            <span>{{ __('View all transactions of a user ')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin-dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{url('admin/users')}}">{{ __('Users')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card" id="generate-pdf">
            <div class="card-header"><h3 class="d-block w-100">{{ $user->name }}</h3></div>
            <div class="card-body">
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <b>{{ __('Total Recharges(Rs. ): ')}}</b>{{ $user->recharges_sum_amount }} <br>
                        <br>
                        <b>{{ __('Total Won Amount(Rs. ):')}}</b> {{ $user->wonamount_sum_amount}}<br>
                        <b>{{ __('Total Referral Amount(Rs. ):')}}</b> {{ $user->referralamt_sum_amount}}<br>
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <b>{{ __('Wallet Balance(Rs. ):')}}</b> {{$user->wallet}}<br>
                        <br>
                        <b>{{ __('Total Withdraw Amount(Rs. ):')}}</b> {{ $user->withdrawamt_sum_amount}}<br>                        
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('#Txn ID')}}</th>
                                    <th>{{ __('Txn Purpose')}}</th>
                                    <th style="text-align:center">{{ __('Amount')}}</th>
                                    <th style="text-align:center">{{ __('Date & Time')}}</th>
                                  <th style="text-align:center">Closing Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_diff = 0; ?>
                                @foreach($txns  as $key => $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>
                                            @switch($val->status)
                                                @case('Wallet')
                                                    Wallet Recharge
                                                    <?php $total_diff = $total_diff + $val->amount; ?>
                                                    @break
                                                @case('Create')
                                                    Create Game
                                                    <?php $total_diff = $total_diff - $val->amount; ?>
                                                    @break
                                                @case('Play')
                                                    Play Game
                                                    <?php $total_diff = $total_diff - $val->amount; ?>
                                                    @break
                                                @case('Won')
                                                    Won Game
                                                    <?php $total_diff = $total_diff + $val->amount; ?>
                                                    @break
                                                @case('Cancel')
                                                    Cancel Game
                                                    <?php $total_diff = $total_diff + $val->amount; ?>
                                                    @break
                                                @case('Referral')
                                                    Referral
                                                    <?php $total_diff = $total_diff + $val->amount; ?>
                                                    @break
                                                @case('Prize')
                                                    Prize Game
                                                    @break
                                                @case('Withdraw')
                                                    Withdraw Game
                                                    <?php $total_diff = $total_diff - $val->amount; ?>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $val->amount }}
                                        </td>
                                        <td style="text-align:center">{{ $val->created_at }}</td>
                                      <td style="text-align:center">{{$val->closing_balance}}</td>
                                    </tr>
                                @endforeach
                                    

                                    <tr>
                                        <td colspan="5" style="text-align:center">{{ $txns->links() }}</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

