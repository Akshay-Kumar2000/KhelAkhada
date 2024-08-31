@extends('layouts.front.front')
@section('content')


	<section>
      <div class="container">
		<div class="row">
		</div>
        <div class="row">
          <div class="col-md-12 about-content">
            <h1>Player Informations</h1>
            
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
              <thead>                
                      <tr>
                        <th>ID</th>
                        <th>Player Name</th>
                        <th>Username</th>
                        <th>Mobile</th>
                        <th>Used Referral</th>
                        <th>Wallet</th>
                        <th>Created at</th>
                      </tr>
                      
                    </thead>
                    
                    <tbody>
                
                @foreach($users as $id => $val)
                    <tr>
                      <td>{{ $val->id}}</td>
                      <td>
                        {{ @$val->name }}
                      </td>
                      <td>
                        {{ @$val->username }}
                      </td>
                      <td>
                        {{ @$val->mobile }}
                      </td>
                      <td>
                        {{ @$val->setting->used_referral }}
                      </td>
                      <td>{{ $val->wallet}}</td>
                      <td>{{ $val->created_At}}</td>
                    </tr>
                @endforeach
              </tbody>
              <tr><td>{{ $users->links() }}</td></tr>
            </table>
                     
          </div>
        </div>
      </div>
    </section>

@endsection

