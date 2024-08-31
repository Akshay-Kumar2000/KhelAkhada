@extends('layouts.main') 
@section('title', 'Create Event')
@section('content')
    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-film bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Create Event')}}</h5>
                            <span>{{ __('Create new Virtual/Stage Event')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Create Event')}}</a>
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
                <div class="card ">
                    <div class="card-header">
                        <h3>{{ __('Create Event')}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('create-event') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <label for="name">{{ __('Select Creator')}}<span class="text-red">*</span></label>
                                        <select name="creator" id="creator" class="form-control" required>
                                            <option>Select Creator</option>
                                            @foreach($creators as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('creator')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name">{{ __('Event title')}}<span class="text-red">*</span></label>
                                        <input id="name" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="Enter event title" required>
                                        <div class="help-block with-errors"></div>

                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">{{ __('Event Description')}}<span class="text-red">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" placeholder="Enter event description" required></textarea>
                                        <div class="help-block with-errors" ></div>

                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name">{{ __('Select Event Type')}}<span class="text-red">*</span></label>
                                        <select name="event_type" id="event_type" class="form-control" required onchange="eventType(this);">
                                            <option value="0">Select Event Type</option>
                                            <option value="Stage">Stage</option>
                                            <option value="Virtual">Virtual</option>
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('event_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">{{ __('Date & time')}}<span class="text-red">*</span></label>
                                        <input id="inlinedatetimepicker" maxlength="10" type="text" class="form-control @error('event_time') is-invalid @enderror" name="event_time" value="{{ old('event_time') }}" placeholder="Select event date & time" required>
                                        <div class="help-block with-errors" ></div>

                                        @error('event_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="event-price-div" style="display:none">
                                        <label for="email">{{ __('Event Price')}}<span class="text-red">*</span></label>
                                        <input id="price" maxlength="10" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" placeholder="Event price">
                                        <div class="help-block with-errors" ></div>

                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="profile_pic">{{ __('Banner Image')}}<span class="text-red">*</span></label>
                                        <input id="banner" type="file" class="form-control" name="banner" />
                                        <div class="help-block with-errors"></div>

                                        @error('banner')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="select-stage-div" style="display:none">
                                        <label for="name">{{ __('Select Stage')}}<span class="text-red">*</span></label>
                                        <select name="stage_id" id="stage_id" class="form-control" onchange="getStageDetail(this);">
                                            <option value="0">Select Stage</option>
                                            @foreach($stages as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        @error('creator')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                                    </div>
                                </div>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script') 
        <script>
            function getStageDetail(stage){
                
                if(stage.value > 0){
                    
                    var orig    = $(location).attr('origin');
                    //alert(orig);
                    var url     =   orig+'/laughter/admin/get-stage-categories/'+stage.value;

                    $.ajax({
                        url: url,
                    success: function (response) {
                            var len = response.length;
                                $("#subtopicsId option[value!='']").remove(); // keep first 
                                for (var i = 0; i < len; i++) {
                                    var id = response[i]['baseIdentity']['id'];
                                    var name = response[i]['name'];
                                    $("#subtopicsId").append("<option value='" + id + "'>" + name + "</option>");
                                }
                            },
                            error: function (e) {
                                console.log("ERROR : ", e);
                            }
                    });
                }
            }

            function eventType(val){
                if(val.value ==   'Stage'){
                    $('#select-stage-div').show();
                    $('#event-price-div').hide();
                }else if(val.value ==   'Virtual'){
                    $('#event-price-div').show();
                    $('#select-stage-div').hide();
                }else{
                    $('#event-price-div').hide();
                    $('#select-stage-div').hide();
                }
            }
        </script>
    @endpush

@endsection
