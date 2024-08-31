@extends('layouts.front.front')
@section('content')


	<section>
      <div class="container">
        <div class="row">
          <div class="col-md-12 about-content">
            <h1>Prize Results</h1>
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Sr. No</th>
                  <th>Player Name</th>
                  <th>Result Date</th>
                  <th>Prize Amount</th>
                </tr>
                
              </thead>
              
              <tbody>
				  
				  @foreach($results as $id => $val)
				  
					<tr>
					  <td>{{ $id + 1}}</td>
					  <td>
						  {{ @$val->playername->username }}
					  </td>
					  <td>
						  {{ @$val->date }}
					  </td>
					  <td>{{ @$val->amount }}</td>
					</tr>
                @endforeach
                
              </tbody>
            </table>
             
          </div>
        </div>
      </div>
    </section>

@endsection

