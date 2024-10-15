@extends('layouts.main')

@section('head')
    <title> KYC </title>
@endsection


@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">KYC</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">APPROVED KYC</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="ap_table table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Mobile no.</th>
                                <th>Document Type</th>
                                <th>Document Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Mobile no.</th>
                                <th>Document Type</th>
                                <th>Document Number</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>

                            <?php $i = 1; ?>
                            @foreach ($approved as $row)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        {{ $row->user_id }}
                                    </td>
                                    <td>
                                        {{ $row->username }}
                                    </td>
                                    <td>
                                        {{ $row->mobile }}
                                    </td>
                                    <td>
                                        @if ($row->DOCUMENT_NAME == 'UID')
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
                                        <a href="{{ url('admin/kyc-details/' . $row->user_id) }}"
                                            class="btn btn-info btn-sm btn-xs" title="View ">View</a>

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

    {{-- <script src="https://cleverpages.in/libs/jquery/dist/jquery.min.js"></script> --}}
    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Load DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

    <!-- Load DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            let table = new DataTable('.ap_table', {
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ]
            });
        });
    </script>
@endsection
