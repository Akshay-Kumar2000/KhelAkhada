@extends('layouts.front.front')
@section('content')


	<section>
      <div class="container">
		<div class="row">
		</div>
        <div class="row">
          <div class="col-md-12 about-content">
            <h1>Leaderboard</h1>
            
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
                <tr><th colspan=4>इस लिस्ट में अभी तक सबसे ज्यादा  गेम जीतने वाले लीडर्स की गिनती की जाती है।</th></tr>
                      <tr>
                        <th>Rank</th>
                        <th>Player Name</th>
                        <th>Winning Games</th>
                        <th>Winning Total</th>
                      </tr>
                      
                    </thead>
                    
                    <tbody>
                
                @foreach($leaders as $id => $val)
                  @if($val->playername)
                    <tr>
                      <td>{{ $id + 1}}</td>
                      <td>
                        {{ @$val->playername->username }}
                      </td>
                      <td>
                        {{ @$val->win_count }}
                      </td>
                      <td>{{ round($val->win_amount,5)}}</td>
                    </tr>
                  @endif
                @endforeach
              </tbody>
            </table>
                     
          </div>
        </div>
      </div>
    </section>

@endsection

