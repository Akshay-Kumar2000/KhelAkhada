@extends('layouts.main') 
@section('title', $user->name)
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    @endpush

    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Edit Creator')}}</h5>
                            <span>{{ __('Edit Creator Data')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('/')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Creator')}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <!-- clean unescaped data is to avoid potential XSS risk -->
                                {{ clean($user->name, 'titles')}}
                            </li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ url('admin/creator/update') }}" enctype="multipart/form-data">
                        @csrf
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label for="name">{{ __('Creator name')}}<span class="text-red">*</span></label>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ clean($user->name, 'titles')}}" required>
                                        <div class="help-block with-errors"></div>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">{{ __('Email')}}<span class="text-red">*</span></label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ clean($user->email, 'titles')}}" required>
                                        <div class="help-block with-errors"></div>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">{{ __('Mobile No.')}}<span class="text-red">*</span></label>
                                        <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ clean($user->mobile, 'titles')}}" placeholder="Enter mobile no" required>
                                        <div class="help-block with-errors" ></div>

                                        @error('mobile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">{{ __('Description')}}<span class="text-red">*</span></label>
                                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Description" required>{{ clean($user->description, 'titles')}}</textarea>
                                        <div class="help-block with-errors" ></div>

                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">{{ __('Password')}}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  >
                                        <div class="help-block with-errors"></div>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirm">{{ __('Confirm Password')}}</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="profile_pic">{{ __('Profile Image')}}</label>
                                        <input id="profile_pic" type="file" class="form-control" name="profile_pic" />
                                        <div class="help-block with-errors"></div>

                                        @error('profile_pic')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <img src="https://raw-celeb-bucket.s3.ap-south-1.amazonaws.com/profile/{{$user->profile_pic}}" width="300px" height="200px" />
                                    </div>
                                    <div class="form-group">
                                        <label for="cover_pic">{{ __('Cover Image')}}</label>
                                        <input id="cover_pic" type="file" class="form-control" name="cover_pic" />
                                        <div class="help-block with-errors"></div>

                                        @error('cover_pic')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <img src="https://raw-celeb-bucket.s3.ap-south-1.amazonaws.com/cover/{{$user->cover_pic}}" width="300px" height="200px" />
                                    </div>
                                
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <button type="submit" class="btn btn-primary form-control-right float-right">{{ __('Update')}}</button>
                                    </div>
                                </div>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script') 
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <!--get role wise permissiom ajax script-->
        <script src="{{ asset('js/get-role.js') }}"></script>
    @endpush
@endsection
