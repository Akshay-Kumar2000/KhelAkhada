@extends('layouts.front.front')
@section('content')

  <script>
    $(function () {
		 
		 setTimeout(location.reload.bind(location), 60000);
		 
		 
		 $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		 
		  
        });

  </script>

	<section>
      <div class="container">
      @if (session()->has('success'))
						<div class="alert alert-success" role="alert">
							{{ session('success') }}
						</div>
					@endif
		<div class="row">
      <span style="color:red"><b>Note : </b>कृपया अपनी सही Ludo King ID से ही ज्वाइन करे, जिसे अपने ज्वाइन करते टाइम डाला था| </span>
			<div class="col-md-3">
				<b>Joining Fee: </b>{{$contest->amount}} chips
			</div>
			<div class="col-md-3">
				<b>Maximum Players: </b>{{$contest->player_count}}
			</div>
			<div class="col-md-3">
				<b>Joined Players: </b>{{$pData->count()}}
			</div>
        <div class="col-md-3">
          <b>1st Prize: </b>{{$contest->prize}} chips</br>
          <b>2nd Prize: </b>{{$contest->amount }} chips</br>
          <b>3rd Prize: </b>{{$contest->amount / 2 }} chips</br>
          <b>4th Prize: </b>{{$contest->amount / 2 }} chips
        </div>
		</div>
        <div class="row">
          <div class="col-md-12 about-content"><br>          
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Sr. No.</th>
                  <th>Player Name</th>
                  <th>Current Level</th>
                  <th>Table No.</th>
                </tr>
                
              </thead>
              
              <tbody>
				  
				  @foreach($players as $id => $val)
					<tr>
					  <td>{{ $id + 1}}</td>
					  <td>
						  {{ @$val->player->username }}
					  </td>
            		  <td>Level {{$val->current_level}}</td>
					  @if($contest->status == 2 || $contest->status == 3))<td>{{@$val->table_no->table_no}}</td>@endif
					</tr>
                @endforeach
                <?php if(empty($players)){ ?> <tr><td colspan=4 style="text-align:center">No data found</td></tr> <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

@endsection

