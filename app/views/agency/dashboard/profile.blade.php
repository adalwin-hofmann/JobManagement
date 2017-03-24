@extends('agency.layout')

@section('custom-styles')
	{{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}

	{{ HTML::style('/assets/css/bootstrap-colorpicker.min.css') }}
	{{ HTML::style('/assets/css/docs.css') }}
@stop

@section('body')
<main class="auth gray-container padding-top-xs padding-bottom-xs">
    
    <div class="container" style="background-color: white;">
	    <div class="row">
		    <div class="col-sm-4 col-sm-offset-4 margin-top-lg">
		        @if ($errors->has())
		        <div class="alert alert-danger alert-dismissibl fade in">
		            <button type="button" class="close" data-dismiss="alert">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">{{ trans('company.close') }}</span>
		            </button>
		            @foreach ($errors->all() as $error)
		        		<p>{{ $error }}</p>		
		        	@endforeach
		        </div>
		        @endif    
		        
		        <?php if (isset($alert)) { ?>
		        <div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
		            <button type="button" class="close" data-dismiss="alert">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">{{ trans('company.close') }}</span>
		            </button>
		            <p>
		                <?php echo $alert['msg'];?>
		            </p>
		        </div>
		        <?php } ?>
		    </div>    
	    </div> 
	    
	    
	    <form method="POST" action="{{ URL::route('agency.saveProfile') }}" role="form" class="form-login margin-top-normal" enctype="multipart/form-data">
	    
	    	<input type="hidden" name="company_id" value="{{ $agency->id }}">
	    	
	    	<div class="text-center">
	    		<h2 class="signup-sub-title"><i class="fa fa-briefcase"></i> {{ trans('company.edit_company_profile') }}</h2>
	    		<p class="signup-sub-description">{{ trans('company.msg_08') }}</p>
	    	</div>

            <div class="form-group">
	    	    <div class="col-sm-12 margin-top-lg">
                    <ul class="nav nav-tabs custom-nav-tabs text-center">
                        <li class="active"><a href="#tab-general" data-toggle="tab" class="text-uppercase"><i class="fa fa-file-text-o"></i> {{ trans('company.general') }}</a></li>
                        <li class=""><a href="#tab-services" data-toggle="tab" class="text-uppercase"><i class="fa fa-bar-chart-o"></i> {{ trans('company.services') }}</a></li>
                        <li class=""><a href="#tab-contact" data-toggle="tab" onclick="reloadMap()" class="text-uppercase"><i class="fa fa-bookmark"></i> {{ trans('company.contact_details') }}</a></li>
                        <li class=""><a href="#tab-rejection-template" data-toggle="tab" class="text-uppercase"><i class="fa fa-paper-plane-o"></i> {{ trans('company.rejection_template') }}</a></li>
                        <li class=""><a href="#tab-iframe" data-toggle="tab" class="text-uppercase"><i class="fa fa-rocket"></i> {{ trans('company.iframe') }}</a></li>
                        <li class=""><a href="#tab-follow-company" data-toggle="tab" class="text-uppercase"><i class="fa fa-building-o"></i> {{ trans('company.follow_company') }}</a></li>
                        <li class=""><a href="#tab-video-interview-template" data-toggle="tab" class="text-uppercase"><i class="fa fa-video-camera"></i> {{ trans('company.video_interview_template') }}</a></li>
                    </ul>

                    <div class="tab-content" id="custom-tab-content">
                        <div class="tab-pane row fade active in" id="tab-general">
                            <div class="form-group" id="div-general">
                                <div class="row">
                                    <div class="col-sm-5 col-sm-offset-1">
                                        @foreach ([
                                            'email' => trans('company.email').':',
                                            'password' => trans('company.password').':',
                                            'password_confirmation' => trans('company.confirm_password').':',
                                            'name' => trans('company.name').':',
                                            'tag' => trans('company.tag_line').':',
                                            'year' => trans('company.foundation_year').':',
                                            'teamsize_id' => trans('company.team_size').':',
                                            'category_id' => trans('company.category').':',
                                            'city_id' => trans('company.location').':',
                                        ] as $key => $value)
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-5">{{ Form::label($key, $value, ['class' => 'margin-top-xs']) }}</label>
                                                    <div class="col-sm-7">
                                                        @if ($key == 'city_id')
                                                            {{ Form::select($key
                                                               , $cities->lists('name', 'id')
                                                               , $agency->city_id
                                                               , array('class' => 'form-control')) }}
                                                        @elseif ($key == 'category_id')
                                                            {{ Form::select($key
                                                               , $categories->lists('name', 'id')
                                                               , $agency->category_id
                                                               , array('class' => 'form-control')) }}
                                                        @elseif ($key == 'teamsize_id')
                                                            <select class="form-control" name="teamsize_id" id="teamsize_id">
                                                                @foreach ($teamsizes as $item)
                                                                <option value="{{ $item->id }}" {{ $agency->teamsize_id == $item->id ? 'selected':'' }}>{{ $item->min." ~ ".$item->max }}</option>
                                                                @endforeach
                                                            </select>
                                                        @elseif ($key == 'password')
                                                            {{ Form::password($key, array('class' => 'form-control')) }}
                                                        @elseif ($key == 'password_confirmation')
                                                            {{ Form::password($key, array('class' => 'form-control')) }}
                                                        @else
                                                            {{ Form::text($key, $agency->{$key}, ['class' => 'form-control']) }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-sm-5 padding-left-normal">
                                        <div class="form-group margin-top-sm">
                                            <?php if($agency->logo != '') {?>
                                                <div class="margin-bottom-xs">
                                                    <img src="{{ HTTP_LOGO_PATH.$agency->logo}}" style="width: 100%;">
                                                </div>
                                                <div>
                                                    <div class="col-sm-4">
                                                        <label>{{ Form::label('about', 'Description:') }}</label>
                                                    </div>
                                                    <div class="col-sm-4 col-sm-offset-4" style="padding: 0px;">
                                                        <div class="fileUpload">
                                                            <span><i class="fa fa-picture-o"></i> {{ trans('company.change_logo') }}</span>
                                                            <input type="file" class="upload" name="logo"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    {{ Form::textarea('description', $agency->description, ['class' => 'form-control']) }}
                                                </div>
                                            <?php }else {?>
                                                <div>
                                                    <div class="col-sm-4">
                                                        <label>{{ Form::label('about', trans('company.description').':') }}</label>
                                                    </div>
                                                    <div class="col-sm-4 col-sm-offset-4" style="padding: 0px;">
                                                        <div class="fileUpload">
                                                            <span><i class="fa fa-picture-o"></i> Logo</span>
                                                            <input type="file" class="upload" name="logo"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    {{ Form::textarea('description', $agency->description, ['class' => 'form-control auth-about']) }}
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane row fade" id="tab-services">
                            <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                <h2 class="signup-sub-title"><i class="fa fa-bar-chart-o"></i> {{ trans('company.services') }}</h2>
                                <p class="signup-sub-description">{{ trans('company.msg_19') }}</p>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                                    <hr/>
                                </div>
                            </div>

                            <div id="service_list"></div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-2 col-sm-offset-1">
                                        <div class="row margin-top-sm padding-left-sm">
                                            <div class="form-group">
                                                <label for="" class="margin-top-xs">{{ trans('company.expertise') }}:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row margin-top-sm signup-long-input">
                                            <div class="form-group">
                                                <input class="form-control" name="expertise" type="text" value="{{ $agency->expertise }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-sm-offset-1">
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row signup-long-input">
                                            <div class="form-group">
                                                <p>{{ trans('company.msg_10') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                                    <hr/>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane row fade" id="tab-contact">
                            <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-contact">
                	    		<h2 class="signup-sub-title"><i class="fa fa-bookmark"></i> {{ trans('company.contact_details') }}</h2>
                	    		<p class="signup-sub-description">{{ trans('company.msg_11') }}</p>
                	    	</div>

                			<div class="form-group">
                				<div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                					<hr/>
                				</div>
                			</div>

                			<div class="form-group">
                				<div class="row">
                					<div class="col-sm-5 col-sm-offset-1">
                						<div class="row margin-top-sm">
                							<div class="form-group">
                								<label class="col-sm-5">{{ Form::label('', trans('company.address').':', ['class' => 'margin-top-xs']) }}</label>
                								<div class="col-sm-7">
                									{{ Form::text('address', $agency->address, ['class' => 'form-control']) }}
                								</div>
                							</div>
                						</div>
                					</div>
                					<div class="col-sm-5 padding-left-normal">
                						<div class="row margin-top-sm">
                							<div class="form-group">
                								<label class="col-sm-5">{{ Form::label('', trans('company.facebook').':', ['class' => 'margin-top-xs']) }}</label>
                								<div class="col-sm-7">
                									{{ Form::text('facebook', $agency->facebook, ['class' => 'form-control']) }}
                								</div>
                							</div>
                						</div>
                					</div>
                				</div>
                			</div>

                			<div class="form-group">
                				<div class="row">
                					<div class="col-sm-5 col-sm-offset-1">
                						<div class="row margin-top-sm">
                							<div class="form-group">
                								<label class="col-sm-5">{{ Form::label('', trans('company.phone_number').':', ['class' => 'margin-top-xs']) }}</label>
                								<div class="col-sm-7">
                									{{ Form::text('phone', $agency->phone, ['class' => 'form-control']) }}
                								</div>
                							</div>
                						</div>
                					</div>
                					<div class="col-sm-5 padding-left-normal">
                						<div class="row margin-top-sm">
                							<div class="form-group">
                								<label class="col-sm-5">{{ Form::label('', trans('company.linkedin').':', ['class' => 'margin-top-xs']) }}</label>
                								<div class="col-sm-7">
                									{{ Form::text('linkedin', $agency->linkedin, ['class' => 'form-control']) }}
                								</div>
                							</div>
                						</div>
                					</div>
                				</div>
                			</div>

                			<div class="form-group">
                				<div class="row">
                					<div class="col-sm-5 col-sm-offset-1">
                						<div class="row margin-top-sm">
                							<div class="form-group">
                								<label class="col-sm-5">{{ Form::label('', trans('company.website').':', ['class' => 'margin-top-xs']) }}</label>
                								<div class="col-sm-7">
                									{{ Form::text('website', $agency->website, ['class' => 'form-control']) }}
                								</div>
                							</div>
                						</div>
                					</div>
                					<div class="col-sm-5 padding-left-normal">
                						<div class="row margin-top-sm">
                							<div class="form-group">
                								<label class="col-sm-5">{{ Form::label('', trans('company.twitter').':', ['class' => 'margin-top-xs']) }}</label>
                								<div class="col-sm-7">
                									{{ Form::text('twitter', $agency->twitter, ['class' => 'form-control']) }}
                								</div>
                							</div>
                						</div>
                					</div>
                				</div>
                			</div>

                			<div class="form-group">
                				<div class="row">
                					<div class="col-sm-5 col-sm-offset-1">
                						<div class="row margin-top-sm">
                							<div class="form-group margin-top-xs">
                								<div class="col-sm-7 col-sm-offset-5">
                				                    {{ Form::checkbox('is_published', $agency->is_published, $agency->is_published, ['class' => 'checkbox-normal', 'id' => 'is_published']) }}
                				                    <label class="control-checkbox">{{ trans('company.msg_14') }}</label>
                								</div>
                							</div>
                						</div>
                					</div>
                					<div class="col-sm-5 padding-left-normal">
                						<div class="row margin-top-sm">
                							<div class="form-group">
                								<label class="col-sm-5">{{ Form::label('', trans('company.google').'+:', ['class' => 'margin-top-xs']) }}</label>
                								<div class="col-sm-7">
                									{{ Form::text('google', $agency->google, ['class' => 'form-control']) }}
                								</div>
                							</div>
                						</div>
                					</div>
                				</div>
                			</div>


                			<div class="form-group" style="margin-bottom: 0px;">
                				<div class="row">
                					<div class="col-sm-2 col-sm-offset-1">
                						<div class="row margin-top-sm padding-left-sm">
                							<div class="form-group">
                								{{ Form::label('', trans('company.google_maps_address').':', ['class' => 'margin-top-xs']) }}
                							</div>
                						</div>
                					</div>
                					<div class="col-sm-8">
                						<div class="row margin-top-sm signup-long-input">
                							<div class="form-group">
                								{{ Form::text('latlng', $agency->lat.', '.$agency->long, ['class' => 'form-control', 'readonly', 'id' => 'latlng']) }}
                							</div>
                						</div>
                					</div>
                				</div>
                			</div>

                	        <input type="hidden" name="lat" value="{{ $agency->lat }}" id="lat">
                	        <input type="hidden" name="lng" value="{{ $agency->long }}" id="lng">
                	        <input type="hidden" name="is_finished" value="1" id="is_finished">

                			<div class="form-group">
                				<div class="row">
                					<div class="col-sm-10 col-sm-offset-1">
                						<div class="row signup-long-input">
                							<div id="mapdiv" style="height:200px;"></div>
                						</div>
                					</div>
                				</div>
                			</div>
                        </div>
                        <div class="tab-pane row fade" id="tab-rejection-template">
                            <div class="form-group">
                                <div class="row">
                                    <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                        <p class="signup-sub-description">{{ trans('company.rejection_template_for_apply') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <hr/>
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">
                                        @foreach ([
                                            'apply_rejection_title' => trans('company.title'),
                                            'apply_rejection_content' => trans('company.description'),
                                        ] as $key => $value)
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-3">{{ Form::label($key, $value, ['class' => 'margin-top-xs']) }}</label>
                                                    <div class="col-sm-9">
                                                        @if ($key == 'apply_rejection_content')
                                                            {{ Form::textarea($key, $agency->{$key}, ['class' => 'form-control']) }}
                                                        @else
                                                            {{ Form::text($key, $agency->{$key}, ['class' => 'form-control']) }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                        <p class="signup-sub-description">{{ trans('company.rejection_template_for_recommendation') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <hr/>
                                        </div>
                                    </div>

                                    <div class="col-sm-10 col-sm-offset-1">
                                        @foreach ([
                                            'hint_rejection_title' => trans('company.title'),
                                            'hint_rejection_content' => trans('company.description'),
                                        ] as $key => $value)
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-3">{{ Form::label($key, $value, ['class' => 'margin-top-xs']) }}</label>
                                                    <div class="col-sm-9">
                                                        @if ($key == 'hint_rejection_content')
                                                            {{ Form::textarea($key, $agency->{$key}, ['class' => 'form-control']) }}
                                                        @else
                                                            {{ Form::text($key, $agency->{$key}, ['class' => 'form-control']) }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane row fade" id="tab-iframe">
                            <div class="form-group">
                                <div class="row">

                                    <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                        <p class="signup-sub-description">{{ trans('company.sharing_application_template') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <hr/>
                                        </div>
                                    </div>

                                    <div class="row margin-top-sm">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-sm-3">
                                                        <label class="margin-top-xs">Logo:</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @if ($agency->agency_sharing_logo != '')
                                                            <img src="{{ HTTP_LOGO_PATH.$agency->agency_sharing_logo }}" class="thumbnail" style="max-width: 100%"/>
                                                        @endif
                                                        <input class="form-control" type="file" value="{{ $agency->agency_sharing_logo }}" name="agency_sharing_logo">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <div class="col-sm-3">
                                                        <label class="margin-top-xs">Background:</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        @if ($agency->agency_sharing_background != '')
                                                            <img src="{{ HTTP_COMPANY_PHOTO_PATH.$agency->agency_sharing_background }}" class="thumbnail" style="max-width: 100%;"/>
                                                        @endif
                                                        <input class="form-control" type="file" value="{{ $agency->agency_sharing_background }}" name="agency_sharing_background">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                        <p class="signup-sub-description">{{ trans('company.iframe_link_url_for_job') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <hr/>
                                        </div>
                                    </div>

                                    <div class="row margin-top-sm">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="col-sm-3"><label class="margin-top-xs">{{ trans('company.url') }}:</label></label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" type="text" value="{{ HTTP_PATH.'widget/'.$agency->slug }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-3"><label class="margin-top-xs">{{ trans('company.overlay_color') }}:</label></label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group demo2">
                                                            <input type="text" value="{{ ($agency->overlay_color == '') ? 'rgba(0, 82, 208, 0.9)' : $agency->overlay_color  }}" class="form-control" name="overlay_color" />
                                                            <span class="input-group-addon"><i></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-9 col-sm-offset-3">
                                                            <div class="row margin-top-sm">
                                                                <div class="form-group margin-top-xs">
                                                                    <div class="col-sm-12">
                                                                        {{ Form::checkbox('is_show', $agency->is_show, $agency->is_show, ['class' => 'checkbox-normal', 'id' => 'is_show']) }}
                                                                        <label class="control-checkbox"> {{ trans('company.msg_20') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane row fade" id="tab-follow-company">
                            <div id="follow_company_list"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-5 col-sm-offset-1">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-sm-7 col-sm-offset-5">
                                                    <a style="color: #2980b9; cursor: pointer;" onclick="onAddFollowCompany('');"><i class="fa fa-plus-circle"></i> {{ trans('company.add_new_follow_company') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane row fade" id="tab-video-interview-template">
                            <div class="form-group">
                                <div class="row">

                                    <!-- Video Interview Screeen -->
                                    <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                        <p class="signup-sub-description">{{ trans('company.video_interview_screen') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <hr/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-8 col-sm-offset-2 video-interview-screen">
                                                <div class="col-sm-10 col-sm-offset-1">
                                                    <div class="video-interview-header">
                                                        <img src="{{ $agency->video_interview_logo == '' ? HTTP_LOGO_PATH.'default_company_logo.gif' : HTTP_LOGO_PATH.$agency->video_interview_logo }}" class="video-interview-company-logo" id="img-interview-logo">
                                                        <input type="file" class="upload" name="video-interview-logo" id="input-interview-logo">
                                                    </div>

                                                    <div class="video-interview-body">
                                                        <div class="col-sm-12 text-right">
                                                            {{ date('d/m/Y') }}
                                                        </div>
                                                        <div class="video-interview-body-content">
                                                            <img src="{{ $agency->video_interview_image == '' ? HTTP_COMPANY_PHOTO_PATH.'default.jpg' : HTTP_COMPANY_PHOTO_PATH.$agency->video_interview_image }}" class="video-interview-company-image" id="img-interview-image">
                                                            <div class="video-interview-text-box">
                                                                <textarea class="video-interview-text" name="video-interview-text">{{ $agency->video_interview_text == '' ? str_replace("<br/>", "\n", "Vacancy<br/>Cammio Video Pitch") : $agency->video_interview_text }}</textarea>
                                                            </div>
                                                            <img src="{{ HTTP_COMPANY_PHOTO_PATH.'stepbar.png' }}" class="video-interview-stepbar">
                                                            <input type="file" class="upload" name="video-interview-image"  id="input-interview-image">
                                                        </div>
                                                        <img src="{{ HTTP_COMPANY_PHOTO_PATH.'bottombar.png' }}" style="width: 100%;">
                                                    </div>
                                                </div>
                                                <img src="{{ $agency->video_interview_background != '' ? HTTP_COMPANY_PHOTO_PATH.$agency->video_interview_background : '' }}" class="video-interview-screen-background" id="img-interview-background">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <p>{{ trans('company.msg_36') }}</p>
                                                <p>{{ trans('company.msg_37') }}</p>
                                                <p>{{ trans('company.msg_38') }}</p>
                                                <div>
                                                    <div class="interview-fileUpload">
                                                        4. <span><i class="fa fa-picture-o"></i> Change Background</span>
                                                        <input type="file" class="upload" name="video-interview-background" id="input-interview-background">
                                                    </div>
                                                </div>
                                                <div class="margin-top-xs">
                                                    {{ trans('company.msg_44') }}
                                                    <?php
                                                        $interviewText = "Thank you for your responses.\nWe will review it and will let you know.\nBest regards.";
                                                        if ($agency->video_interview_end != '') $interviewText = $agency->video_interview_end;
                                                    ?>
                                                    <div class="col-sm-12 margin-top-xs">
                                                        <textarea name="video-interview-end" rows="5" style="width: 100%;">{{ $interviewText }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Questions & Questionnaires -->
                                    <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                        <p class="signup-sub-description">{{ trans('company.questions_questionnaires') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <hr/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="portlet box blue">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-edit"></i>{{ trans('company.questions_table') }}
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-toolbar">
                                                        <div class="btn-group">
                                                            <a id="sample_editable_1_new" class="btn green">
                                                            Add New <i class="fa fa-plus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <table class="table table-striped table-hover table-bordered" id="questions_table">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    #
                                                                </th>
                                                                <th>
                                                                    {{ trans('company.question') }}
                                                                </th>
                                                                <th>
                                                                    {{ trans('company.time') }}
                                                                </th>
                                                                <th>
                                                                    {{ trans('company.edit') }}
                                                                </th>
                                                                <th>
                                                                    {{ trans('company.delete') }}
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($questions as $key => $value)
                                                            <tr>
                                                                <td>
                                                                    {{ $key + 1 }}
                                                                </td>
                                                                <td>
                                                                    {{ $value->question }}
                                                                </td>
                                                                <td>
                                                                    {{ $value->time }}
                                                                </td>
                                                                <td>
                                                                    <a class="edit" href="javascript:;">
                                                                        Edit
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a class="delete" href="javascript:;">
                                                                        Delete
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>



                                            <div class="portlet box blue margin-top-xs">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-edit"></i>{{ trans('company.questionnaires_table') }}
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-toolbar">
                                                        <div class="btn-group">
                                                            <a id="questionnaires_table_new" class="btn green">
                                                            Add New <i class="fa fa-plus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="questionnaires_table_box">
                                                        <table class="table table-striped table-hover table-bordered" id="questionnaires_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        #
                                                                    </th>
                                                                    <th>
                                                                        {{ trans('company.title') }}
                                                                    </th>
                                                                    <th>
                                                                        {{ trans('company.edit') }}
                                                                    </th>
                                                                    <th>
                                                                        {{ trans('company.delete') }}
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($questionnaires as $key => $value)
                                                                <tr>
                                                                    <td>
                                                                        {{ $key + 1 }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $value->title }}
                                                                    </td>
                                                                    <td>
                                                                        <a class="edit" href="javascript:;">
                                                                            Edit
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <a class="delete" href="javascript:;">
                                                                            Delete
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Video Interview Template -->
                                    <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div-service">
                                        <p class="signup-sub-description">{{ trans('company.video_interview_template') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <hr/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-1">
                                            <div class="portlet box blue margin-top-xs">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-edit"></i>{{ trans('company.templates_table') }}
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-toolbar">
                                                        <div class="btn-group">
                                                            <a id="vi_templates_table_new" class="btn green">
                                                            Add New <i class="fa fa-plus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div id="vi_templates_table_box">
                                                        <table class="table table-striped table-hover table-bordered" id="vi_templates_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        #
                                                                    </th>
                                                                    <th>
                                                                        {{ trans('company.title') }}
                                                                    </th>
                                                                    <th>
                                                                        {{ trans('company.edit') }}
                                                                    </th>
                                                                    <th>
                                                                        {{ trans('company.delete') }}
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($templates as $key => $value)
                                                                <tr>
                                                                    <td>
                                                                        {{ $key + 1 }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $value->title }}
                                                                    </td>
                                                                    <td>
                                                                        <a class="edit" href="javascript:;">
                                                                            Edit
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <a class="delete" href="javascript:;">
                                                                            Delete
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


	        <div class="row padding-bottom-xl">
	            <div class="col-sm-2 col-sm-offset-5 margin-top-normal">
	                <button class="btn btn-lg btn-primary text-uppercase btn-block">{{ trans('company.save') }} <span class="glyphicon glyphicon-ok-circle"></span></button>
	            </div>
	        </div>
	    </form>    
    </div>
           
</main>


<!-- Model Div for Follow Company -->
<div id="clone_div_followCompany" class="hidden">
	<div class="form-group">
		<div class="row">
            <div class="col-sm-2 col-sm-offset-1">
                <div class="row margin-top-sm padding-left-sm">
                    <div class="form-group">
                        <label for="" class="margin-top-xs">{{ trans('company.follow_company') }}:</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="row margin-top-sm signup-long-input">
                    <div class="form-group">
                        <input class="form-control" name="follow_company_name[]" type="text" value="" id="follow_company_name">
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row margin-top-sm signup-long-input">
                    <div class="form-group margin-top-xs">
                        <a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteFollowCompany(this);"><i class="fa fa-trash"></i> {{ trans('company.delete_follow_company') }}</a>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<!-- EOF for Follow Company -->

<!-- Modal Div for Add Template -->
<div class="modal fade" id="viTemplateModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="msgModalLabel">{{ trans('company.video_interview_template') }}</h4>
            </div>

            <div class="modal-body">
                <div class="form-group ">
                    <div class="row margin-bottom-xs">
                        <input type="hidden" value="" id="js-input-vi-template-id" />
                        <div class="col-sm-3">
                            <label class="margin-top-xs">{{ trans('company.title') }}:</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="js-vi-template-title">
                        </div>

                        <div class="col-sm-3 margin-top-xs">
                            <label class="margin-top-xs">{{ trans('company.description') }}:</label>
                        </div>
                        <div class="col-sm-9 margin-top-xs">
                            <textarea  class="form-control" id="js-vi-template-description" rows="10"></textarea>
                        </div>

                        <div class="col-sm-12 margin-top-xs hidden" id="vi-template-alert-box">
                            <div class="alert alert-danger alert-dismissibl fade in" style="margin-bottom: 0px;">
                                <button type="button" class="close" onclick="hideVITemplateAlert(this);">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">{{ trans('company.close') }}</span>
                                </button>
                                <p id="p-vi-template-warnning">{{ trans('company.msg_39') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('company.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveVITemplate(this);">{{ trans('company.save') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- EOF for Add Template -->


<!-- Modal Div for Add Questionnaire -->
<div class="modal fade" id="questionnaireModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="msgModalLabel">{{ trans('company.questionnaire') }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <input type="hidden" value="" id="js-input-questionnaire-id">
                    <div class="row margin-bottom-xs">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">{{ trans('company.title') }}:</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="js-input-questionnaire-title">
                        </div>

                        <div class="col-sm-12 margin-top-xs hidden" id="questionnaire-title-alert-box">
                            <div class="alert alert-danger alert-dismissibl fade in" style="margin-bottom: 0px;">
                                <button type="button" class="close" onclick="hideQuestionnaireTitleAlert(this);">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">{{ trans('company.close') }}</span>
                                </button>
                                <p>{{ trans('company.msg_39') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="row">
                        <div class="col-sm-12">
                            <b>{{ trans('company.selected_questions') }}:</b>

                            <div class="col-sm-12 margin-top-xs hidden" id="questionnaire-questions-alert-box">
                                <div class="alert alert-danger alert-dismissibl fade in" style="margin-bottom: 0px;">
                                    <button type="button" class="close" onclick="hideQuestionnaireQuestionsAlert(this);">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">{{ trans('company.close') }}</span>
                                    </button>
                                    <p>{{ trans('company.msg_40') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="margin-top-xs" id="questions_box">

                        </div>
                    </div>

                    <hr/>

                    <div class="portlet box grey-cascade">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-globe"></i>{{ trans('company.select_questions') }}
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body" id="select_questions_table_box">
                            <table class="table table-striped table-bordered table-hover" id="select_questions_table">
                                <thead>
                                    <tr>
                                        <th class="table-checkbox">
                                            <input type="checkbox" class="group-checkable" data-set="#select_questions_table input.checkboxes"/>
                                        </th>
                                        <th>
                                            {{ trans('company.question')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questions as $key => $value)
                                    <tr class="odd gradeX">
                                        <td>
                                            <input type="checkbox" class="checkboxes" value="{{ $value->id }}">
                                        </td>
                                        <td>
                                            {{ $value->question }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('company.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveQuestionnaire(this);">{{ trans('company.save') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Div for Add Questionnaire -->

<!-- Model Div for Questions -->
<div id="clone_div_questions" class="row hidden">
    <div class="col-sm-10 col-sm-offset-1 margin-top-xs">
        <p id="js_p_question" style="margin-bottom: 0px;"></p>
    </div>
</div>
<!-- End Div for Questions -->

<!-- Model Div for Service -->
<div id="clone_div_service" class="hidden"> 
	<input type="hidden" name="service_id[]" value="" id="service_id">
	<div class="form-group" style="margin-bottom: 0px;">	
		<div class="row">
			<div class="col-sm-6 col-sm-offset-1">
				<div class="row margin-top-sm">
					<div class="form-group">
						<label class="col-sm-4 control-label margin-top-xs">{{ trans('company.service_name') }}:</label>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="service_name[]" id="service_name">
						</div>				
					</div>
				</div>
			</div>
			
			<div class="col-sm-4 padding-left-normal">
				<div class="row margin-top-sm">
					<div class="form-group">
						<label class="col-sm-5 margin-top-xs"> {{ trans('company.icon_code') }}:</label>
						<div class="col-sm-7">
							<input class="form-control" type="text" name="icon_code[]" readonly id="icon_code">
						</div>
					</div>
				</div>
			</div>
		</div>				                   
	</div>
	
	<div class="form-group" style="margin-bottom: 0px;">
		<div class="row">
			<div class="col-sm-2 col-sm-offset-1">
				<div class="row margin-top-sm" style="padding-left: 15px;">
					<div class="form-group">
						{{ Form::label('', trans('company.service_description').':', ['class' => 'margin-top-xs']) }}
					</div>
				</div>
			</div>
			<div class="col-sm-8">
				<div class="row margin-top-sm" style="padding: 0 15px 0 15px;">
					<div class="form-group">
						<textarea class="form-control" name="service_description[]" id="service_description" placeholder="Brief Description..." style="height: 100px;"></textarea>
					</div>
				</div>
			</div>
			<div class="col-sm-2 col-sm-offset-1">
			</div>
		</div>
	</div> 
	
	<div class="form-group">	
		<div class="row">
			<div class="col-sm-6 col-sm-offset-1">
				<div class="row">
					<div class="form-group">	
						<div class="col-sm-8 col-sm-offset-4">
							<a style="color: #2980b9; cursor: pointer;" onclick="onAddService('', '', '', '');"><i class="fa fa-plus-circle"></i> {{ trans('company.add_new_service') }}</a>
						</div>				
					</div>
				</div>
			</div>
			
			<div class="col-sm-4 padding-left-normal">
				<div class="row">
					<div class="form-group">
						<div class="col-sm-7 col-sm-offset-5">
							<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteService(this);"><i class="fa fa-trash"></i> {{ trans('company.delete_service') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>				                   
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<hr/>
		</div>
	</div>	
</div>
<!--  -->

@stop

@stop

@section('custom-scripts')
    {{ HTML::script('/assets/js/bootstrap-colorpicker.js') }}
    {{ HTML::script('/assets/js/docs.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
    @include('js.agency.dashboard.profile')
@stop