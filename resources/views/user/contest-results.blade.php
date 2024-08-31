@extends('layouts.front.front')
@section('content')


	<section>
      <div class="container">
        <div class="row">
          <div class="col-md-12 about-content">
            <h1>Contest Results</h1>
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Sr. No</th>
                  <th>Joining Chips</th>
                  <th>Player Name</th>
                  <th>Game Date</th>
                  <th>Winning Chips</th>
                </tr>
                
              </thead>
              
              <tbody>
				  
				  @foreach($results as $id => $val)
				  
					<tr>
					  <td>{{ $id + 1}}</td>
            <td>
						  {{ @$val->amount }}
					  </td>
					  <td>
						  {{ @$val->winnerdata->username }}
					  </td>
					  <td>
						  {{ @$val->t_date }}
					  </td>
					  <td>{{ (@$val->prize) }}</td>
					</tr>
                @endforeach
                
              </tbody>
            </table>
             
          </div>
        </div>
      </div>
    </section>

@endsection

