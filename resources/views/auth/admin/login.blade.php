@extends('layouts.admin.auth')
@section('content')

<div class="row flex-row h-100">
                <div class="col-12 my-auto">
                    <div class="password-form mx-auto">
                        <div class="logo-centered">
                            <a href="db-default.html">
                                <img src="{{ asset('admin/img/logo.png')}}" alt="logo">
                            </a>
                        </div>
                        <h3>{{ trans('panel.site_title') }}</h3>
                        <form method="POST" action="{{ route('admin-login') }}">
							@csrf
                            <div class="group material-input">
							    <input id="email" name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus value="{{ old('email', null) }}">
							    <span class="highlight"></span>
							    <span class="bar"></span>
							    <label>{{ trans('global.login_email') }}</label>
							    @if($errors->has('email'))
									<div class="invalid-feedback">
										{{ $errors->first('email') }}
									</div>
								@endif
							 </div>   
							 <div class="group material-input">
							    <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required >
							    <span class="highlight"></span>
							    <span class="bar"></span>
							    <label>{{ trans('global.login_password') }}</label>
							    @if($errors->has('password'))
									<div class="invalid-feedback">
										{{ $errors->first('password') }}
									</div>
								@endif
							    
                            </div>
                            <div class="button text-center">
								<button class="btn btn-lg btn-gradient-01" type="submit">{{ trans('global.login') }}</button>
							</div>
                        </form>
                        
                    </div>        
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->

@endsection

