<div class="default-sidebar">
	<!-- Begin Side Navbar -->
	<nav class="side-navbar box-scroll sidebar-scroll">
		<!-- Begin Main Navigation -->
		<ul class="list-unstyled">
			@can('dashboard_access')
			<?php /* <li class="active">
				<a href="{{ route("admin.home") }}"><i class="la la-dashboard"></i><span>{{ trans('global.dashboard') }}</span></a>
			</li> <?php */ ?>
			@endcan
			@can('user_management_access')
				<li class="{{ request()->is('admin/roles') || request()->is('admin/roles/*') || request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}"><a href="#dropdown-employee" aria-expanded="false" data-toggle="collapse"><i class="la la-user-secret"></i><span>{{ trans('global.employee_management') }}</span></a>
					<ul id="dropdown-employee" class="collapse list-unstyled pt-0 {{ request()->is('admin/roles') || request()->is('admin/roles/*') || request()->is('admin/users') || request()->is('admin/users/*') ? 'show' : '' }}">
						<?php /* @can('permission_access')
							<li><a href="{{ route('admin.permissions.index') }}">{{ trans('cruds.permission.title') }}</a></li>
						@endcan  */ ?>
						@can('role_access')
							<li><a href="{{ route('admin.roles.index') }}" class="{{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">{{ trans('cruds.role.title') }}</a></li>
						@endcan
						@can('user_access')
							<li><a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users')  ? 'active' : '' }}">{{ trans('cruds.user.fields.employees') }}</a></li>
						@endcan
						@can('user_create')
							<li><a href="{{ route('admin.users.create') }}" class="{{ request()->is('admin/users/*') ? 'active' : '' }}">{{ trans('cruds.user.fields.employees') }} {{ trans('global.registration') }}</a></li>
						@endcan
					</ul>
				</li>
			@endcan
			@can('player_access')
				<li class="{{ request()->is('admin/players') || request()->is('admin/players/*') ? 'active' : '' }}" aria-expanded="false"><a href="#dropdown-user" aria-expanded="false" data-toggle="collapse"><i class="la la-user"></i><span>{{ trans('global.player_management') }}</span></a>
					<ul id="dropdown-user" class="collapse list-unstyled pt-0 {{ request()->is('admin/players') || request()->is('admin/players/*') ? 'show' : '' }}">
						@can('player_access')
							<li><a href="{{ route('admin.players.index') }}" class="{{ request()->is('admin/players') ? 'active' : '' }}">{{ trans('cruds.player.title') }}</a></li>
						@endcan
						@can('player_create')
							<li><a href="{{ route('admin.players.create') }}" class="{{ request()->is('admin/players/*') ? 'active' : '' }}">{{ trans('global.player_registration') }}</a></li>
						@endcan
					</ul>
				</li>
			@endcan
			@can('challenge_access')
				<li class="{{ request()->is('admin/challenges') || request()->is('admin/challenges/*') ? 'active' : '' }}"><a href="#dropdown-challenge" aria-expanded="false" data-toggle="collapse"><i class="la la-trophy"></i><span>{{ trans('global.results') }}</span></a>
					<ul id="dropdown-challenge" class="collapse list-unstyled pt-0 {{ request()->is('admin/challenges') || request()->is('admin/challenges/*') ? 'show ' : '' }}">
						@can('challenge_edit')
							<li><a href="{{ route('admin.challenges.index') }}" class="{{ request()->is('admin/challenges') || request()->is('admin/challenges/*') ? 'active' : '' }}">{{ trans('cruds.challenge.title') }}</a></li>
						@endcan
					</ul>
				</li>
			@endcan
			@can('withdrawrequest_access')
			<li class="{{ request()->is('admin/withdraw-requests') ? 'active' : '' }}">
				<a href="{{ route("admin.withdraw-requests.index") }}"><i class="la la-rupee"></i><span>{{ trans('global.withdraw_requests') }}</span></a>
			</li>
			@endcan
			@can('paymentrequest_access')
			<li class="{{ request()->is('admin/payment-requests') ? 'active' : '' }}">
				<a href="{{ route("admin.payment-requests.index") }}"><i class="la la-rupee"></i><span>Payment Requests</span></a>
			</li>
			@endcan
			@can('paymenttransaction_access')
			<li class="{{ request()->is('admin/payment-transactions') || request()->is('admin/manual-payments') ? 'active' : '' }}" aria-expanded="false"><a href="#dropdown-payment" aria-expanded="false" data-toggle="collapse"><i class="la la-money"></i><span>{{ trans('global.payment_transactions') }}</span></a>
				<ul id="dropdown-payment" class="collapse list-unstyled pt-0 {{ request()->is('admin/payment-transactions') || request()->is('admin/manual-payments') ? 'show' : '' }}">
					<li class="active">
						<a href="{{ route("admin.payment-transactions.index") }}" class="{{ request()->is('admin/payment-transactions')  ? 'active' : '' }}" ><span>{{ trans('global.online_transaction') }}</span></a>
					</li>
					<li class="active">
						<a href="{{ route("admin.manual-payments.index") }}" class="{{ request()->is('admin/manual-payments')  ? 'active' : '' }}"><span>{{ trans('global.manual_transaction') }}</span></a>
					</li>
				</ul>
			</li>
			
			@endcan
			
			<li class="{{ request()->is('admin/contests') ? 'active' : '' }}" aria-expanded="false"><a href="#dropdown-contest" aria-expanded="false" data-toggle="collapse"><i class="la la-gift"></i><span>Contests</span></a>
				<ul id="dropdown-contest" class="collapse list-unstyled pt-0 {{ request()->is('admin/contests') ? 'show' : '' }}">
					<li class="active">
						<a href="{{ route("admin.contests.index") }}" class="{{ request()->is('admin/contests')  ? 'active' : '' }}" ><span>Contests</span></a>
					</li>
					<li class="active">
						<a href="{{ route("admin.contests.create") }}" class="{{ request()->is('admin/contests/create')  ? 'active' : '' }}"><span>Create Contest</span></a>
					</li>
				</ul>
			</li>
			
			@can('report_access')
			<li class="{{ request()->is('admin/reports') ? 'active' : '' }}">
				<a href="{{ route("admin.reports.index") }}"><i class="la la-book"></i><span>{{ trans('global.reports') }}</span></a>
			</li>
			@endcan
			<li><a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logoutbyform').submit();"><i class="la la-power-off"></i><span>{{ trans('global.logout') }}</span></a></li>
		</ul>
		<!-- End Main Navigation -->
	</nav>
	<!-- End Side Navbar -->
</div>
<!-- End Left Sidebar -->
