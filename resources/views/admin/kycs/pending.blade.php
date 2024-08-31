@extends('layouts.main') 
@section('head')
<title> KYC </title>
@endsection


@section('content')

     <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">KYC</h1>
            <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">PENDING KYC</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User ID</th>
                                            <th>Document Name</th>
                                            <th>Document Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                      <tr>
                                            <th>#</th>
                                            <th>User ID</th>
                                            <th>Document Name</th>
                                            <th>Document Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                  
                                      <?php $i=1; ?>
                                       @foreach($pending as $row)
                               
                                        <tr>
                                            <td>{{ $i }}</td>
											<td> 
                                          		{{ $row->user_id }}
                                          	</td>
                                             <td>
                                             @if($row->DOCUMENT_NAME == 'UID')
                                               Aadhar Card
                                             @elseif($row->DOCUMENT_NAME == 'DL')
                                               Driving Licence
                                             @else
                                               Voter ID Card
                                             @endif
                                          	</td>
                                          		<td>
                                          		{{ $row->DOCUMENT_NUMBER }}
                                          	</td>
                                          	
                                           
											<td>
                                             <a href="{{ url('admin/kyc-details/'.$row->user_id) }}" class="btn btn-info btn-sm btn-xs" title="View ">View</a>
                                             
                                             </td>
                                        </tr>
                                         <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->   
       

@endsection
