@extends('layouts.main') 
@section('title', 'Event Details')
@section('content')
@php use App\Http\Controllers\Admin\EventController; @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Event Details')}}</h5>
                            <span>{{ __('View complete details of a event')}}</span>
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
                                <a href="{{url('admin/events')}}">{{ __('Events')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $event->event_title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card" id="generate-pdf">
            <div class="card-header"><h3 class="d-block w-100"><b>Event Title : </b>{{ $event->title }}<small class="float-right"><b>Event Date & Time : </b>{{ $event->event_time }}</small></h3></div>
            <div class="card-body">
                <div class="row invoice-info">
                    <div class="col-sm-6 invoice-col">
                        <b>{{ __('ID: ')}}</b>#{{ $event->id }}<br>
                        <br>
                        @if($event->type  ==  'Virtual')<b>{{ __('Total Earning:')}}</b> {{ $event->earning_sum_amount }}<br><br> @endif
                        <b>{{ __('Created by:')}}</b> {{ $event->creator->name}}<br>
                        <b>{{ __('Joining Fee:')}}</b> {{ $event->price ? $event->price : 'N/A' }}<br>
                        <b>{{ __('Status:')}}</b>
                        @if($event->status == 0) Inactive
                        @elseif($event->status == 1) Active
                        @elseif($event->status == 2) Live
                        @elseif($event->status == 3) Completed
                        @elseif($event->status == 4) Force Stopped
                        @endif
                        <br>
                        <b>{{ __('Event Type:')}}</b> {{ $event->type}}<br>
                        <b>{{ __('Is Schedule:')}}</b> {{ $event->schedule ? 'Yes' : 'No' }}<br>
                        <b>{{ __('Created at:')}}</b> {{ $event->created_at }}<br>
                    </div>
                    <div class="col-sm-6 invoice-col">
                        <b>{{ __('Event Description:')}}</b> {{ $event->description }}<br><br>
                    </div>
                </div>

                @if($event->type == 'Stage')
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Booking ID')}}</th>
                                        <th>{{ __('User')}}</th>
                                        <th>{{ __('Seat Category')}}</th>
                                        <th>{{ __('Qty')}}</th>
                                        <th>{{ __('Amount')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    @foreach($event->eventbooking as $key => $val)
                                        <tr>
                                            <td>{{ $val->booking_id }}</td>
                                            <td>{{ $val->user->name }}</td>
                                            <td>{{ $val->seatcategory->name }}</td>
                                            <td>{{ $val->quantity }}</td>
                                            <td>{{ $val->amount }}</td>
                                        </tr>
                                        <?php $total    += $val->amount; ?>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" style="text-align:center"><b>Total</b></td>
                                        <td>{{$total}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

