@extends('admin.layout')

@section('custom_styles')
    {{ HTML::style('/assets/css/style_user.css') }}
@stop

@section('content')

@if ($errors->has())
<div class="alert alert-danger alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    @foreach ($errors->all() as $error)
		{{ $error }}		
	@endforeach
</div>
@endif

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">User Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>User</span>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<span>Edit</span>
			</li>
		</ul>
	</div>
</div>

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Edit User
		</div>
	</div>
    <div class="portlet-body">
        <div class="row">
            <form method="POST" action="{{ URL::route('admin.user.store') }}" role="form" class="form-login margin-top-normal" id="js_user_profile_form" enctype="multipart/form-data">

                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="preview" value="0" id="js_user_preview">

                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        @foreach ([
                            'name' => 'Full Name:',
                            'email' => 'Email:',
                            'password' => 'Password:',
                            'password_confirmation' => 'Confirm Password:',
                            'gender' => 'Gender:',
                            'birthday' => 'I was born on:',
                            'year' => 'Years of experience:',
                            'category_id' => 'Industry:',
                            'city_id' => 'Location:'
                        ] as $key => $value)
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-5">{{ Form::label($key, $value, ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-7">
                                        @if ($key == 'city_id')
                                            {{ Form::select($key
                                               , $cities->lists('name', 'id')
                                               , $user->city_id
                                               , array('class' => 'form-control')) }}
                                        @elseif ($key == 'category_id')
                                            {{ Form::select($key
                                               , $categories->lists('name', 'id')
                                               , $user->category_id
                                               , array('class' => 'form-control')) }}
                                        @elseif ($key == 'gender')
                                            {{ Form::select($key
                                               , array('0' => 'Male', '1' => 'Female')
                                               , $user->gender
                                               , array('class' => 'form-control')) }}
                                        @elseif ($key == 'password')
                                            {{ Form::password($key, array('class' => 'form-control')) }}
                                        @elseif ($key == 'password_confirmation')
                                            {{ Form::password($key, array('class' => 'form-control')) }}
                                        @elseif ($key == 'birthday')
                                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" data-date-viewmode="years" data-date-minviewmode="months">
                                                <input type="text" class="form-control" readonly="" name="birthday" value="{{ $user->birthday }}">
                                                <span class="input-group-btn">
                                                <button class="btn default" type="button" style="padding-top: 11px; padding-bottom: 11px;"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        @elseif ($key == 'email')
                                            {{ Form::text($key, $user->{$key}, ['class' => 'form-control', 'readonly']) }}
                                        @else
                                            {{ Form::text($key, $user->{$key}, ['class' => 'form-control']) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-sm-5 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                <div>
                                    <div class="col-sm-4">
                                        <label>{{ Form::label('about', 'About me:') }}</label>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0px;">
                                        <div class="fileUpload">
                                            <span><i class="fa fa-camera"></i> Profile Picture</span>
                                            <input type="file" class="upload" name="profile_image"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0px;">
                                        <div class="fileUpload">
                                            <span><i class="fa fa-picture-o"></i> Cover Image</span>
                                            <input type="file" class="upload" name="cover_image"/>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    {{ Form::textarea('about', $user->about, ['class' => 'form-control auth-about']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-bar-chart-o"></i> Skills</h2>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                        <hr/>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-5 col-sm-offset-1">
                                <div class="row margin-top-sm">
                                    <div class="form-group">
                                        <label class="col-sm-5">{{ Form::label('', 'Professional Title:', ['class' => 'margin-top-xs']) }}</label>
                                        <div class="col-sm-7">
                                            {{ Form::text('professional_title', $user->professional_title, ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 padding-left-normal">
                                <div class="row margin-top-sm">
                                    <div class="form-group">
                                        <label class="col-sm-6">{{ Form::label('', 'Career Level:', ['class' => 'margin-top-xs']) }}</label>
                                        <div class="col-sm-6">
                                            {{ Form::select('level_id'
                                               , $levels->lists('name', 'id')
                                               , $user->level_id
                                               , array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                <label class="col-sm-5" style="color:#2dcc70;">{{ Form::label('', 'Communication:', ['class' => 'margin-top-xs']) }}</label>
                                <div class="col-sm-3">
                                    {{ Form::text('communication_value', $user->communication_value, ['class' => 'form-control']) }}
                                </div>
                                <label class="col-sm-4">{{ Form::label('', '% (1 to 100 value)', ['class' => 'margin-top-xs']) }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                {{ Form::textarea('communication_note', $user->communication_note, ['class' => 'form-control', 'placeholder'=>'Notes...']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                <label class="col-sm-5" style="color:#e84c3d;">{{ Form::label('', 'Organisational:', ['class' => 'margin-top-xs']) }}</label>
                                <div class="col-sm-3">
                                    {{ Form::text('organisational_value', $user->organisational_value, ['class' => 'form-control']) }}
                                </div>
                                <label class="col-sm-4">{{ Form::label('', '% (1 to 100 value)', ['class' => 'margin-top-xs']) }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                {{ Form::textarea('organisational_note', $user->organisational_note, ['class' => 'form-control', 'placeholder'=>'Notes...']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', 'Job Related:', ['class' => 'margin-top-xs']) }}</label>
                                <div class="col-sm-3">
                                    {{ Form::text('job_related_value', $user->job_related_value, ['class' => 'form-control']) }}
                                </div>
                                <label class="col-sm-4">{{ Form::label('', '% (1 to 100 value)', ['class' => 'margin-top-xs']) }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                {{ Form::textarea('job_related_note', $user->job_related_note, ['class' => 'form-control', 'placeholder'=>'Notes...']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="skill_list">

                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5 col-sm-offset-1">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', 'Native Language:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-7">
                                        {{ Form::select('native_language_id'
                                           , $languages->lists('name', 'id')
                                           , $user->native_language_id
                                           , array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <div class="col-sm-12 margin-top-xs">
                                        <a style="color: #2980b9; cursor: pointer;" onclick="onAddForeignLanguage('', '', '', '');"><i class="fa fa-plus-circle"></i> Add Foreign Language</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="language_list"></div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2 col-sm-offset-1">
                            <div class="row margin-top-sm padding-left-sm">
                                <div class="form-group">
                                    {{ Form::label('', 'Hobbies:', ['class' => 'margin-top-xs']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="row margin-top-sm signup-long-input">
                                <div class="form-group">
                                    {{ Form::text('hobbies', $user->hobbies, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-sm-offset-1">
                        </div>
                        <div class="col-sm-8">
                            <div class="row signup-long-input">
                                <div class="form-group">
                                    <p>Insert multiple hobbies and separate them using commas.</p>
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


                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-bank"></i> Education</h2>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="institution_list"></div>

                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-trophy"></i> Awards & Honors</h2>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="award_list"></div>

                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-building-o"></i> Work Experience</h2>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="work_list"></div>


                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-comment"></i> Testimonials</h2>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="testimonial_list"></div>

                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-money"></i> Salary & Job Types</h2>
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
                                    <label class="col-sm-5">{{ Form::label('', 'Remuneration Amount:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-7">
                                        {{ Form::text('renumeration_amount', $user->renumeration_amount, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-6">{{ Form::label('', 'Per:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-6">
                                        {{ Form::label('', 'Month', ['class' => 'margin-top-xs']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2 col-sm-offset-1">
                            <div class="row margin-top-sm padding-left-sm">
                                <div class="form-group">
                                    {{ Form::label('', 'Job Types:', ['class' => 'margin-top-xs']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="row margin-top-sm signup-long-input">
                                <div class="form-group margin-top-xs">
                                    <div class="col-sm-2">
                                        {{ Form::checkbox('is_freelance', 0, $user->is_freelance, ['class' => 'checkbox-normal', 'id' => 'is_freelance']) }}
                                        <label class="control-checkbox">Freelance</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {{ Form::checkbox('is_parttime', 0, $user->is_parttime, ['class' => 'checkbox-normal', 'id' => 'is_parttime']) }}
                                        <label class="control-checkbox">Part-Time</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {{ Form::checkbox('is_fulltime', 0, $user->is_fulltime, ['class' => 'checkbox-normal', 'id' => 'is_fulltime']) }}
                                        <label class="control-checkbox">Full-Time</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {{ Form::checkbox('is_internship', 0, $user->internship, ['class' => 'checkbox-normal', 'id' => 'is_internship']) }}
                                        <label class="control-checkbox">Internship</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {{ Form::checkbox('is_volunteer', 0, $user->is_volunteer, ['class' => 'checkbox-normal', 'id' => 'is_volunteer']) }}
                                        <label class="control-checkbox">Volunteer</label>
                                    </div>
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


                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-bookmark"></i> Contact Details</h2>
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
                                    <label class="col-sm-5">{{ Form::label('', 'Address:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-7">
                                        {{ Form::text('address', $user->address, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-6">{{ Form::label('', 'Facebook:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-6">
                                        {{ Form::text('facebook', $user->facebook, ['class' => 'form-control']) }}
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
                                    <label class="col-sm-5">{{ Form::label('', 'Phone number:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-7">
                                        {{ Form::text('phone', $user->phone, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-6">{{ Form::label('', 'Linkedin:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-6">
                                        {{ Form::text('linkedin', $user->linkedin, ['class' => 'form-control']) }}
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
                                    <label class="col-sm-5">{{ Form::label('', 'Website:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-7">
                                        {{ Form::text('website', $user->website, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-6">{{ Form::label('', 'Twitter:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-6">
                                        {{ Form::text('twitter', $user->twitter, ['class' => 'form-control']) }}
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
                                        {{ Form::checkbox('is_published', 0, $user->is_published, ['class' => 'checkbox-normal', 'id' => 'is_published']) }}
                                        <label class="control-checkbox">Publish my email address</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-6">{{ Form::label('', 'Google+:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-6">
                                        {{ Form::text('google', $user->google, ['class' => 'form-control']) }}
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
                                    {{ Form::label('', 'Google Maps Address:', ['class' => 'margin-top-xs']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="row margin-top-sm signup-long-input">
                                <div class="form-group">
                                    {{ Form::text('latlng', $user->lat.', '.$user->lng, ['class' => 'form-control', 'readonly', 'id' => 'latlng']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="lat" value="{{ $user->lat }}" id="lat">
                <input type="hidden" name="lng" value="{{ $user->lng }}" id="lng">
                <input type="hidden" name="is_finished" value="1" id="is_finished">

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-3">
                            <div class="row signup-long-input">
                                <div id="mapdiv" style="height:200px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-2 control-label col-sm-offset-1">Active</label>
                        <div class="col-sm-8">
                            {{ Form::select('is_active'
                                , array('0' => 'No', '1' => 'Yes')
                                , $user->is_active
                                , array('class' => 'form-control')) }}
                        </div>
                    </div>
                </div>


                <div class="form-group margin-top-sm">
                  <div class="col-sm-offset-2 col-sm-10 text-center">
                      <button class="btn btn-success">
                          <span class="glyphicon glyphicon-ok-circle"></span> Save
                      </button>
                      <a href="{{ URL::route('admin.user') }}" class="btn btn-primary">
                          <span class="glyphicon glyphicon-share-alt"></span> Back
                      </a>
                  </div>
                </div>

            </form>

        </div>

    </div>
</div>



<!-- Model Div for Skill -->
<div id="clone_div_skill" class="hidden row">

	<input type="hidden" name="skill_id[]" value="" id="skill_id">

	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', 'Skill Name:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7">
						<input class="form-control" id="skill_name" name="skill_name[]" type="text">
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5">
			<div class="row margin-top-sm">
				<div class="form-group">
					<div class="col-sm-3">
						<input class="form-control" id="skill_value" name="skill_value[]" type="text">
					</div>
					<label class="col-sm-4">{{ Form::label('', '% (1 to 100 value)', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-5 margin-top-xs">
						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteSkill(this);"><i class="fa fa-trash"></i> Delete Skill</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5"></label>
					<div class="col-sm-7">
						<a style="color: #2980b9; cursor: pointer;" onclick="onAddSkill('', '', '');"><i class="fa fa-plus-circle"></i> Add New Skill</a>
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
<!--  -->

<!-- Model Div for Language -->
<div id="clone_div_language" class="hidden">
	<div class="form-group">
		<div class="row">
			<div class="col-sm-5 col-sm-offset-1">
	            <div class="row margin-top-sm">
                    <div class="form-group">
                        <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', 'Foreign Language:', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7">
                            {{ Form::select('foreign_language_id[]'
                               , $languages->lists('name', 'id')
                               , null
                               , array('class' => 'form-control', 'id' => 'foreign_language_id')) }}
                        </div>
                        <div class="col-sm-5"></div>
                        <div class="col-sm-7 margin-top-xs">
                        	<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteForeignLanguage(this);"><i class="fa fa-trash"></i> Delete Foreign Language</a>
                        </div>
                    </div>
	            </div>
			</div>
			<div class="col-sm-5 padding-left-normal">
	            <div class="row margin-top-sm">
                    <div class="form-group">
                        <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', 'Understanding:', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7">
							<select class="form-control" id="understanding" name="understanding[]">
								<option value="1">Very bad</option>
								<option value="2">Bad</option>
								<option value="3">Normal</option>
								<option value="4">Good</option>
								<option value="5">Best</option>
							</select>
                        </div>
                        <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', 'Speaking:', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7 margin-top-sm">
							<select class="form-control" id="speaking" name="speaking[]">
								<option value="1">Very bad</option>
								<option value="2">Bad</option>
								<option value="3">Normal</option>
								<option value="4">Good</option>
								<option value="5">Best</option>
							</select>
                        </div>
                        <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', 'Writing:', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7 margin-top-sm">
							<select class="form-control" id="writing" name="writing[]">
								<option value="1">Very bad</option>
								<option value="2">Bad</option>
								<option value="3">Normal</option>
								<option value="4">Good</option>
								<option value="5">Best</option>
							</select>
                        </div>
                    </div>
	            </div>
			</div>
		</div>
	</div>
</div>
<!--  -->

<!-- Model Div for Institution -->
<div id="clone_div_institution" class="hidden">
	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', 'Institution Name:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7">
						<input class="form-control" id="institution_name" name="institution_name[]" type="text">
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 padding-left-normal">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-6">{{ Form::label('', 'Qualification & Faculty:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-6">
						<input class="form-control" id="qualification" name="qualification[]" type="text">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', 'Period:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-3">
						<input class="form-control" id="period_start" name="period_start[]" type="text">
					</div>
					<div class="col-sm-1">{{ Form::label('', '-', ['class' => 'margin-top-xs']) }}</div>
					<div class="col-sm-3">
						<input class="form-control" id="period_end" name="period_end[]" type="text">
					</div>

					<label class="col-sm-5 margin-top-sm">{{ Form::label('', 'Location:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7 margin-top-sm">
						<input class="form-control" id="location" name="location[]" type="text">
					</div>
					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
						<a style="color: #2980b9; cursor: pointer;" onclick="onAddInstitution();"><i class="fa fa-plus-circle"></i> Add New Institution</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 padding-left-normal">
			<div class="row margin-top-sm">
				<div class="form-group">
					<div class="col-sm-12">
						<textarea class="form-control" placeholder="Notes..." style="height: 104px;" id="institution_note" name="institution_note[]" cols="50" rows="10"></textarea>
					</div>
					<div class="col-sm-12 margin-top-sm text-right">
						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteInstitution(this);"><i class="fa fa-trash"></i> Delete Institution</a>
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
<!--  -->


<!-- Model Div for Award -->
<div id="clone_div_award" class="hidden">
	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', 'Competition Name:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7">
						<input class="form-control" id="competition_name" name="competition_name[]" type="text">
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 padding-left-normal">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-6">{{ Form::label('', 'Prize:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-6">
						<input class="form-control" id="prize" name="prize[]" type="text">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', 'Year:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7">
						<input class="form-control" id="competition_year" name="competition_year[]" type="text">
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 padding-left-normal">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-6">{{ Form::label('', 'Location:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-6">
						<input class="form-control" id="competition_location" name="competition_location[]" type="text">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5"></label>
					<div class="col-sm-7">
						<a style="color: #2980b9; cursor: pointer;" onclick="onAddAward('', '', '', '');"><i class="fa fa-plus-circle"></i> Add New Award</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 padding-left-normal">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-6"></label>
					<div class="col-sm-6 text-right">
						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteAward(this);"><i class="fa fa-trash"></i> Delete Award</a>
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
<!--  -->

<!-- Model Div for Work -->
<div id="clone_div_work" class="hidden">

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-1">
                    <div class="row margin-top-sm">
                        <div class="form-group">
                            <label class="col-sm-5">{{ Form::label('', 'Organisation Name:', ['class' => 'margin-top-xs']) }}</label>
                            <div class="col-sm-7">
                                <input class="form-control" id="name" name="organisation_name[]" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 padding-left-normal">
                    <div class="row margin-top-sm">
                        <div class="form-group">
                            <label class="col-sm-6">{{ Form::label('', 'Job Position:', ['class' => 'margin-top-xs']) }}</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="position" name="job_position[]" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', 'Period:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-3">
						<input class="form-control" id="start" name="work_period_start[]" type="text">
					</div>
					<div class="col-sm-1">{{ Form::label('', '-', ['class' => 'margin-top-xs']) }}</div>
					<div class="col-sm-3">
						<input class="form-control" id="end" name="work_period_end[]" type="text">
					</div>

					<label class="col-sm-5 margin-top-sm">{{ Form::label('', 'Job Type:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7 margin-top-sm">
						{{ Form::select('work_job_type[]'
                        	, $types->lists('name', 'id')
                            , null
                            , array('class' => 'form-control', 'id' => 'type_id')) }}
					</div>
					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
						<a style="color: #2980b9; cursor: pointer;" onclick="onAddWork('', '', '', '', '', '');"><i class="fa fa-plus-circle"></i> Add New Organisation</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 padding-left-normal">
			<div class="row margin-top-sm">
				<div class="form-group">
					<div class="col-sm-12">
						<textarea class="form-control" placeholder="Notes..." style="height: 104px;" id="notes" name="work_note[]" cols="50" rows="10"></textarea>
					</div>
					<div class="col-sm-12 margin-top-sm text-right">
						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteWork(this);"><i class="fa fa-trash"></i> Delete Organisation</a>
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
<!--  -->

<!-- Model Div for Testimonial -->
<div id="clone_div_testimonial" class="hidden">
	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', 'Full Name:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7">
						<input class="form-control" id="name" name="testimonial_name[]" type="text">
					</div>

					<label class="col-sm-5 margin-top-sm">{{ Form::label('', 'Organisation:', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7 margin-top-sm">
						<input class="form-control" id="organisation" name="testimonial_organisation[]" type="text">
					</div>
					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
						<a style="color: #2980b9; cursor: pointer;" onclick="onAddTestimonial();"><i class="fa fa-plus-circle"></i> Add New Testimonial</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-5 padding-left-normal">
			<div class="row margin-top-sm">
				<div class="form-group">
					<div class="col-sm-12">
						<textarea class="form-control" placeholder="Testimonial..." style="height: 104px;" id="notes" name="testimonial_note[]" cols="50" rows="10"></textarea>
					</div>
					<div class="col-sm-12 margin-top-sm text-right">
						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteTestimonial(this);"><i class="fa fa-trash"></i> Delete Testimonial</a>
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
<!--  -->

@stop

@stop

@section('custom-scripts')
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/clockface/js/clockface.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	{{ HTML::script('/assets/metronic/assets/global/scripts/metronic.js') }}
	{{ HTML::script('/assets/metronic/assets/admin/pages/scripts/components-pickers.js') }}
	<!-- END PAGE LEVEL SCRIPTS -->

	<script>
        jQuery(document).ready(function() {
           	// initiate layout and plugins
           	Metronic.init(); // init metronic core components
          	ComponentsPickers.init();
        });
    </script>

    @include('js.admin.user.edit')
@stop
