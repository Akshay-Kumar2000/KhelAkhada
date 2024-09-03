@extends('layouts.main')
@section('title', 'Challenge Details')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Challenge Details')}}</h5>
                            <span>{{ __('View complete details of a challenge')}}</span>
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
                                <a href="{{url('admin/challenges')}}">{{ __('Challenge')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $challenge->id }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card" id="generate-pdf">
            <div class="card-header">
                <h3 class="d-block w-100">
                    <b>Amount : </b>{{ $challenge->amount }}<small class="float-center"><br><b> Date & Time : </b>{{ $challenge->created_at }}</small>
                    @if($challenge->status != 0)
                        <button onclick="calcelGame({{ $challenge->id }})" class="btn btn-danger mb-2 float-right">Cancel Challenge</button>
                    @endif
                </h3>
        </div>
            <div class="card-body">
                <div class="row invoice-info">
                    <div class="col-sm-6 invoice-col">
                        <b>{{ __('ID: ')}}</b>#{{ $challenge->id }}<br>
                        <b>{{ __('Type: ')}}</b>{{ $challenge->type }}<br>

                    </div>
                    <div class="col-sm-6 invoice-col">
                        <b>{{ __('Room Code:')}}</b> {{ $challenge->rcode }}<br>
                        <b>{{ __('Status:')}}</b>
                            @switch($challenge->status)
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
                        <br><br>
                    </div>

                    @php
                    $waiting = '';
                    $winner = '<span style="color:green;font-weight:900;">Winner</span>';
                    $lost = '<span style="color:red;font-weight:900;">Lost</span>';
                    @endphp
                    @if(isset($challenge->usersresult))
                        @foreach($challenge->usersresult as $kk => $vv)
                            @if($vv->user_id == $challenge->c_id)
                                <div class="col-sm-6 invoice-col">
                                    <h2>Creator Results
                                    {!!$resultowner!!}
                                    </h2>
                                    @if($challenge->status != 0)
                                        <button onclick="gameWinner({{ $challenge->id }}, {{ $vv->user_id }})" class="btn btn-info mb-2 float-right">Set Winner to {{ $challenge->creator->username}}</button>
                                    @endif
                                    <b>{{ __('Created by:')}}</b> {{ $challenge->creator->username}}<br>
                                    <b>{{ __('Submitted at:')}}</b> {{ $vv->created_at}}<br>
                                    <b>{{ __('Result:')}}</b> <span class="@if($vv->result == 'Won') text-green @elseif($vv->result == 'Loss') text-red @elseif($vv->result == 'Cancel') text-orange @endif">{{ $vv->result  }}</span><br>
                                    <b>{{ __('Reason:')}}</b> {{ $vv->reason ? $vv->reason : 'N/A'}}<br>
                                    <b>{{ __('Screenshot:')}}</b>
                                    <a href="{{asset('/'.$vv->image)}}" target="_blank"><img src="{{$vv->image == "" ? "https://t3.ftcdn.net/jpg/04/62/93/66/360_F_462936689_BpEEcxfgMuYPfTaIAOC1tCDurmsno7Sp.jpg" : asset('/'.$vv->image)}}" height="500" width="300"/></a>
                                    <br>
                                </div>
                            @endif
                            @if($vv->user_id == $challenge->o_id)
                                <div class="col-sm-6 invoice-col">
                                    <h2>Opponent Results
                                    {!!$resultplayer1!!}
                                    </h2>
                                    @if($challenge->status != 0)
                                        <button onclick="gameWinner({{ $challenge->id }}, {{ $vv->user_id }})" class="btn btn-info mb-2 float-right">Set Winner to {{ $challenge->opponent->username}}</button>
                                    @endif
                                    <b>{{ __('Accepted by:') }}</b>
                                    {{ $challenge->opponent ? $challenge->opponent->username : 'N/A' }}<br>
                                    <b>{{ __('Submitted at:')}}</b> {{ $vv->created_at}}<br>
                                    <b>{{ __('Result:')}}</b> <span class="@if($vv->result == 'Won') text-green @elseif($vv->result == 'Loss') text-red @elseif($vv->result == 'Cancel') text-orange @endif">{{ $vv->result  }}</span><br>
                                    <b>{{ __('Reason:')}}</b> {{ $vv->reason ? $vv->reason : 'N/A'}}<br>
                                    <b>{{ __('Screenshot:')}}</b>
                                    <a href="{{asset('/'.$vv->image)}}" target="_blank"><img src="{{$vv->image == "" ? "https://t3.ftcdn.net/jpg/04/62/93/66/360_F_462936689_BpEEcxfgMuYPfTaIAOC1tCDurmsno7Sp.jpg" : asset('/'.$vv->image)}}" height="500" width="300"/></a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                <br>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('ID')}}</th>
                                    <th>{{ __('Player')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Amount')}}</th>
                                    <th>{{ __('Added Time')}}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                    @foreach($challenge->transactions as $key => $val)
                                        <tr>
                                            <td>{{$val->id}}</td>
                                            <td>{{@$val->playername->username}}</td>
                                            <td>{{$val->status}}</td>
                                            <td>{{$val->amount}}</td>
                                            <td>{{ $val->created_at}}</td>
                                            <td>
                                                @if($challenge->status == 4)
                                                    <a title="Update wallet balance" class="btn btn-success" onclick="return confirm('Are you sure want to perform this action ?')" href="{{url('admin/challenge/make-winner/'.$challenge->id.'/'.$val->playername->id)}}" >Set Winner</a>
                                                @endif
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

    <script>

        $(function(){
            $.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
        });

        $.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

        function calcelGame(chid){
           var cnfrm    = confirm('Are you sure want to cancel the game ?');
           if(cnfrm){
            $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: '{{ route('cancel-admin-game') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'ch_id' : chid
                    },
                    beforeSend: function(){
                        $('.loading').show();
                    },
                    success:function(data){
                        //socket.emit('createChallengeServer', data.data);

					},
					error:function(data){
						var errors = $.parseJSON(data.responseText);
						alert(errors.message);
					},
					complete:function(data){
                        var success = $.parseJSON(data.responseText);
                        alert(success.message);

						$('.loading').hide();
                        location.reload();
					}

				});
           }
        }

        function gameWinner(chid,uid){
           var cnfrm    = confirm('Are you sure want to cancel the game ?');
           if(cnfrm){
            $.ajax({
                    type: "POST",
                    dataType: 'text',
                    url: '{{ route('set-game-winner') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'ch_id' : chid,
                        'user_id' : uid
                    },
                    beforeSend: function(){
                        $('.loading').show();
                    },
                    success:function(data){
                        //socket.emit('createChallengeServer', data.data);
					},
					error:function(data){
						var errors = $.parseJSON(data.responseText);
						alert(errors.message);
					},
					complete:function(data){
                        var success = $.parseJSON(data.responseText);
                        alert(success.message);

						$('.loading').hide();
                        location.reload();
					}

				});
           }
        }
    </script>
@endsection

