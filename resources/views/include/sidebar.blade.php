<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{route('admin-dashboard')}}">
            <div class="logo-img">
               <img height="30" src="{{ asset('img/logo_white.png')}}" class="header-brand-img" title="RADMIN">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
        $segment3 = request()->segment(3);
    @endphp

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ ($segment2 == 'dashboard') ? 'active' : '' }}">
                    <a href="{{route('admin-dashboard')}}"><i class="ik ik-home"></i><span>{{ __('Dashboard')}}</span></a>
                </div>

				<div class="nav-item {{ ($segment2 == 'kyc-pending' || $segment2 == 'kyc-pending') ? 'active' : '' }}">
				<a href="{{url('admin/kyc-pending')}}"><i class="ik ik-users"></i><span>{{ __('Kyc Pending')}}</span></a>
				</div>

				<div class="nav-item {{ ($segment2 == 'kyc-approved' || $segment2 == 'kyc-approved') ? 'active' : '' }}">
				<a href="{{url('admin/kyc-approved')}}"><i class="ik ik-users"></i><span>{{ __('Kyc Approved')}}</span></a>
				</div>
				@can('manage_role')
				<div class="nav-item {{ ($segment2 == 'roles' || $segment2 == 'roles') ? 'active' : '' }}">
				<a href="{{url('admin/roles')}}"><i class="ik ik-users"></i><span>{{ __('Roles')}}</span></a>
				</div>
				@endcan
				@can('manage_permission')
				<div class="nav-item {{ ($segment2 == 'permission' || $segment2 == 'permission') ? 'active' : '' }}">
				<a href="{{url('admin/permission')}}"><i class="ik ik-users"></i><span>{{ __('Permission')}}</span></a>
				</div>
				@endcan
				@can('manage_user')
                    <div class="nav-item {{ ($segment2 == 'users' || $segment2 == 'user') ? 'active' : '' }}">
                        <a href="{{url('admin/users')}}"><i class="ik ik-users"></i><span>{{ __('Users')}}</span></a>
                    </div>
                @endcan
				@can('access_admin')
				<div class="nav-item {{ ($segment2 == 'admin' || $segment2 == 'admin') ? 'active' : '' }}">
				<a href="{{url('admin/admin')}}"><i class="ik ik-users"></i><span>{{ __('Admin')}}</span></a>
				</div>
				@endcan
                @can('manage_challenge')
                    <div class="nav-item {{ ($segment2 == 'challenges' || $segment2 == 'challenge') ? 'active' : '' }}">
                        <a href="{{url('admin/challenges')}}"><i class="ik ik-cpu"></i><span>{{ __('Challenges')}}</span></a>
                    </div>
                @endcan

                @can('manage_transaction')
                    <div class="nav-item {{ ($segment2 == 'transactions') ? 'active' : '' }}">
                        <a href="{{url('admin/transactions')}}"><i class="ik ik-list"></i><span>{{ __('Transactions')}}</span></a>
                    </div>
                @endcan

                @can('manage_manual_payment')
                    <div class="nav-item {{ ($segment2 == 'manual-payments') ? 'active' : '' }}">
                        <a href="{{url('admin/manual-payments')}}"><i class="ik ik-dollar-sign"></i><span>{{ __('Manual Payments')}}</span></a>
                    </div>
                @endcan

                @can('manage_withdraw')
                    <div class="nav-item {{ ($segment2 == 'withdraw-requests') ? 'active' : '' }}">
                        <a href="{{url('admin/withdraw-requests')}}"><i class="ik ik-aperture"></i><span>{{ __('Withdraw Requests')}}</span></a>
                    </div>
                @endcan

                @can('manage_withdraw')
                    <div class="nav-item {{ ($segment2 == 'user-complaints') ? 'active' : '' }}">
                        <a href="{{url('admin/user-complaints')}}"><i class="ik ik-message-circle"></i><span>{{ __('User Complaints')}}</span></a>
                    </div>
                @endcan

                @can('manage_roomcode')
                    <div class="nav-item {{ ($segment2 == 'room-codes') ? 'active' : '' }}">
                        <a href="{{url('admin/room-codes')}}"><i class="ik ik-codepen"></i><span>{{ __('Room Codes')}}</span></a>
                    </div>
                @endcan

                @can('manage_report')
                    <div class="nav-item {{ ($segment2 == 'reports') ? 'active' : '' }}">
                        <a href="{{url('admin/reports')}}"><i class="ik ik-book-open"></i><span>{{ __('Reports')}}</span></a>
                    </div>
                @endcan

                @can('manage_setting')
                    <div class="nav-item {{ ($segment2 == 'settings') ? 'active' : '' }}">
                        <a href="{{url('admin/settings')}}"><i class="ik ik-settings"></i><span>{{ __('Site Settings')}}</span></a>
                    </div>
                @endcan
                <div class="nav-item">
                    <a href="{{ url('logout') }}">
                        <i class="ik ik-power"></i>
                        {{ __('Logout')}}
                    </a>
                </div>

        </div>
    </div>
</div>
