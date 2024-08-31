<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ trans('panel.site_title') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Google Fonts -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script> -->
        <script>
          WebFont.load({
            google: {"families":["Montserrat:400,500,600,700","Noto+Sans:400,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/img/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/img/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/img/favicon-16x16.png') }}">
        <!-- Stylesheet -->
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/base/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/base/elisyam-1.5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/owl-carousel/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/owl-carousel/owl.theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/datatables/datatables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/select2/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/bootstrap-datetimepicker.css') }}">
        <script src="{{ asset('admin/vendors/js/base/jquery.min.js') }}"></script>
        @yield('styles')
    </head>
    <body id="page-top">
        <!-- Begin Preloader -->
        <div id="preloader">
            <div class="canvas">
                <img src="{{ asset('admin/img/logo.png') }}" alt="logo" class="loader-logo">
                <div class="spinner"></div>   
            </div>
        </div>
        <!-- End Preloader -->
        <div class="page">
            @include('partials.admin.header')
            <!-- Begin Page Content -->
            <div class="page-content d-flex align-items-stretch">
               @include('partials.admin.side-bar') 
               @yield('content')
            </div>
            <!-- End Page Content -->
        </div>
        <!-- Begin Success Modal -->
        <div id="delay-modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="sa-icon sa-success animate" style="display: block;">
                            <span class="sa-line sa-tip animateSuccessTip"></span>
                            <span class="sa-line sa-long animateSuccessLong"></span>
                            <div class="sa-placeholder"></div>
                            <div class="sa-fix"></div>
                        </div>
                        <div class="section-title mt-5 mb-5">
                            <h2 class="text-dark">Meeting successfully created</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Success Modal -->
        <!-- Begin Modal -->
        <div id="modal-view-event" class="modal modal-top fade calendar-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title event-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">close</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="media">
                            <div class="media-left align-self-center mr-3">
                                <div class="event-icon"></div>
                            </div>
                            <div class="media-body align-self-center mt-3 mb-3">
                                <div class="event-body"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <form id="logoutbyform" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>
        </div>
        <!-- End Modal -->
        <!-- Begin Vendor Js (Common) -->
        
        <script src="{{ asset('admin/vendors/js/base/core.min.js') }}"></script>
        <!-- End Vendor Js (Common)-->
        <!-- Begin Page Vendor Js (Dascboard) -->
        <script src="{{ asset('admin/vendors/js/nicescroll/nicescroll.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/chart/chart.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/progress/circle-progress.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/calendar/moment.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/calendar/fullcalendar.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/owl-carousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/app/app.js') }}"></script>
        <!-- End Page Vendor Js (Dashborad)-->
        <!-- Begin Page Snippets (Dashborad)-->
        <script src="{{ asset('admin/js/dashboard/db-default.js') }}"></script>
        <!-- End Page Snippets (Dashborad)-->
        
        <!-- Begin Page Vendor Js (Listing Pages)-->
        <script src="{{ asset('admin/vendors/js/datatables/datatables.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datatables/jszip.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datatables/vfs_fonts.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datatables/buttons.print.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/nicescroll/nicescroll.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/datepicker/bootstrap-datetimepicker.min.js') }}"></script>
        <script src="{{ asset('admin/vendors/js/app/app.min.js') }}"></script>
        <script src="{{ asset('admin/js/components/datepicker/datepicker.js') }}"></script>
        <!-- End Page Vendor Js (Listing Pages)-->
        <!-- Begin Page Snippets (Listing Pages)-->
        <script src="{{ asset('admin/js/components/tables/tables.js') }}"></script>
        <!-- End Page Snippets (Listing Pages)-->
        <!-- Form Validation page start -->
        <script src="{{ asset('admin/js/components/validation/validation.min.js') }}"></script>
        <!-- Form Validation page end -->
        <script src="{{ asset('admin/vendors/js/select2/select2.min.js') }}"></script>
        @yield('scripts')
    </body>
</html>
