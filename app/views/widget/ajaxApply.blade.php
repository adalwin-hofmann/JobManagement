<div class="text-center">
    <h2 class="signup-sub-title"><i class="fa fa-file-text-o"></i> {{ trans('user.edit_your_resume') }}</h2>
    <p class="signup-sub-description">{{ trans('user.msg_01') }}</p>
</div>

<div class="form-group">
    <div class="col-sm-12 margin-top-lg">
        <ul class="nav nav-tabs custom-nav-tabs text-center">
            <li class="active" id="li-tab-0"><a href="#tab-0" data-toggle="tab" onclick="setTabIndex(0);"><i class="fa fa-file-text-o"></i> {{ trans('user.general') }}</a></li>
            <li class="" id="li-tab-1"><a href="#tab-1" data-toggle="tab" onclick="setTabIndex(1);"><i class="fa fa-bar-chart-o"></i> {{ trans('user.skills') }}</a></li>
            <li class="" id="li-tab-2"><a href="#tab-2" data-toggle="tab" onclick="setTabIndex(2);"><i class="fa fa-bank"></i> {{ trans('user.education') }}</a></li>
            <li class="" id="li-tab-3"><a href="#tab-3" data-toggle="tab" onclick="setTabIndex(3);"><i class="fa fa-trophy"></i> {{ trans('user.awards_honors') }}</a></li>
            <li class="" id="li-tab-4"><a href="#tab-4" data-toggle="tab" onclick="setTabIndex(4);"><i class="fa fa-building-o"></i> {{ trans('user.work_experience') }}</a></li>
            <li class="" id="li-tab-5"><a href="#tab-5" data-toggle="tab" onclick="setTabIndex(5);"><i class="fa fa-money"></i> Salary &amp; {{ trans('user.job_types') }}</a></li>
            <li class="" id="li-tab-6"><a href="#tab-6" data-toggle="tab" onclick="reloadMap()"><i class="fa fa-bookmark"></i> {{ trans('user.contact_details') }}</a></li>
        </ul>

        <div class="tab-content" id="custom-tab-content">
            <div class="tab-pane row fade active in" id="tab-0">
                <div class="form-group" id="div_general">
                    <div class="col-sm-6">
                        @foreach ([
                            'email' => trans('user.email').':',
                            'name' => trans('user.full_name').':',
                            'password' => trans('user.password').':',
                            'password_confirmation' => trans('user.confirm_password').':',
                            'gender' => trans('user.gender').':',
                            'birthday' => trans('user.i_was_born_on').':',
                            'year' => trans('user.years_of_experience').':',
                            'category_id' => trans('user.industry').':',
                            'city_id' => trans('user.location').':'
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
                                               , $user->gender_id
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
                                            {{ Form::text($key, $user->email, ['class' => 'form-control', 'id'=> 'js-input-email']) }}
                                        @else
                                            {{ Form::text($key, $user->{$key}, ['class' => 'form-control']) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-sm-6 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div id="div-cover-image">
                                <img src="{{ HTTP_PHOTO_PATH.$user->cover_image }}" id="img-cover" width="100%" height="200px"  style="display: none;" />
                            </div>
                            <div id="div-profile-image">
                                <img style="width:100px; height:100px; border: 2px solid #FFF; overflow: hidden;" src="{{ HTTP_PHOTO_PATH.$user->profile_image }}" id="img-profile" class="img-circle">
                            </div>
                        </div>
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                <div>
                                    <div class="col-sm-4">
                                        <label>{{ Form::label('about', trans('user.about_me').':') }}</label>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0px;">
                                        <div class="fileUpload">
                                            <span><i class="fa fa-camera"></i> {{ trans('user.profile_picture') }}</span>
                                            <input type="file" class="upload" name="profile_image" onchange="reloadProfileImage(this)"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="padding: 0px;">
                                        <div class="fileUpload">
                                            <span><i class="fa fa-picture-o"></i> {{ trans('user.cover_image') }}</span>
                                            <input type="file" class="upload" name="cover_image" onchange="reloadCoverImage(this)"/>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    {{ Form::textarea('about', $user->about, ['class' => 'form-control user-auth-about']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane row fade" id="tab-1">
                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div_skills">
                    <h2 class="signup-sub-title"><i class="fa fa-bar-chart-o"></i> {{ trans('user.skills') }}</h2>
                    <p class="signup-sub-description">{{ trans('user.msg_02') }}</p>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="row margin-top-sm">
                                    <div class="form-group">
                                        <label class="col-sm-5">{{ Form::label('', 'Professional Title:', ['class' => 'margin-top-xs']) }}</label>
                                        <div class="col-sm-7">
                                            {{ Form::text('professional_title', $user->professional_title, ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 padding-left-normal">
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
                    <div class="col-sm-6">
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
                    <div class="col-sm-6 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                {{ Form::textarea('communication_note', $user->communication_note, ['class' => 'form-control', 'placeholder'=>'Notes...']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
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
                    <div class="col-sm-6 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                {{ Form::textarea('organisational_note', $user->organisational_note, ['class' => 'form-control', 'placeholder'=>'Notes...']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
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
                    <div class="col-sm-6 padding-left-normal">
                        <div class="row margin-top-sm">
                            <div class="form-group">
                                {{ Form::textarea('job_related_note', $user->job_related_note, ['class' => 'form-control', 'placeholder'=>'Notes...']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="skill_list">
                    @foreach ($userSkills as $item)
                        <div id="skill_item" class="row">

                            <input type="hidden" name="skill_id[]" value="{{ $item->id }}" id="skill_id">

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="row margin-top-sm">
                                        <div class="form-group">
                                            <label class="col-sm-5">{{ Form::label('', trans('user.skill_name').':', ['class' => 'margin-top-xs']) }}</label>
                                            <div class="col-sm-7">
                                                <input class="form-control typeahead tt-query" id="skill_name" name="skill_name[]" value="{{ $item->name }}" type="text" autocomplete="off" spellcheck="false">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row margin-top-sm">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <input class="form-control" id="skill_value" name="skill_value[]" value="{{ $item->value }}" type="text">
                                            </div>
                                            <label class="col-sm-4">{{ Form::label('', trans('user.years_of_experience'), ['class' => 'margin-top-xs']) }}</label>
                                            <div class="col-sm-5 margin-top-xs">
                                                <a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteSkill(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_skill') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row margin-top-sm">
                                        <div class="form-group">
                                            <label class="col-sm-5"></label>
                                            <div class="col-sm-7">
                                                <a style="color: #2980b9; cursor: pointer;" onclick="onAddSkill('', '', '');"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_skill') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 margin-top-sm">
                                    <hr/>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count($userSkills) == 0)
                        <div id="skill_item" class="row">

                        	<input type="hidden" name="skill_id[]" value="" id="skill_id">

                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.skill_name').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control typeahead tt-query" id="skill_name" name="skill_name[]" type="text" autocomplete="off" spellcheck="false">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<div class="col-sm-3">
                        						<input class="form-control" id="skill_value" name="skill_value[]" type="text">
                        					</div>
                        					<label class="col-sm-4">{{ Form::label('', trans('user.years_of_experience'), ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-5 margin-top-xs">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteSkill(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_skill') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5"></label>
                        					<div class="col-sm-7">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddSkill('', '', '');"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_skill') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>
                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
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
                        <div class="col-sm-6 padding-left-normal">
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

                <div id="language_list">

                    @foreach($userLanguages as $item)
                        <div id="language_item">
                        	<div class="form-group">
                        		<div class="row">
                        			<div class="col-sm-6">
                        	            <div class="row margin-top-sm">
                                            <div class="form-group">
                                                <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', trans('user.foreign_language').':', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7">
                                                    {{ Form::select('foreign_language_id[]'
                                                       , $languages->lists('name', 'id')
                                                       , $item->language_id
                                                       , array('class' => 'form-control', 'id' => 'foreign_language_id')) }}
                                                </div>
                                                <div class="col-sm-5"></div>
                                                <div class="col-sm-7 margin-top-xs">
                                                	<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteForeignLanguage(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_foreign_language') }}</a>
                                                </div>
                                            </div>
                        	            </div>
                        			</div>
                        			<div class="col-sm-6 padding-left-normal">
                        	            <div class="row margin-top-sm">
                                            <div class="form-group">
                                                <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', 'Understanding:', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7">
                        							<select class="form-control" id="understanding" name="understanding[]">
                        								<option value="1" @if ($item->understanding == 1) selected @endif>{{ trans('user.very_bad') }}</option>
                        								<option value="2" @if ($item->understanding == 2) selected @endif>{{ trans('user.bad') }}</option>
                        								<option value="3" @if ($item->understanding == 3) selected @endif>{{ trans('user.normal') }}</option>
                        								<option value="4" @if ($item->understanding == 4) selected @endif>{{ trans('user.good') }}</option>
                        								<option value="5" @if ($item->understanding == 5) selected @endif>{{ trans('user.best') }}</option>
                        							</select>
                                                </div>
                                                <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', 'Speaking:', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7 margin-top-sm">
                        							<select class="form-control" id="speaking" name="speaking[]">
                        								<option value="1" @if ($item->speaking == 1) selected @endif>{{ trans('user.very_bad') }}</option>
                        								<option value="2" @if ($item->speaking == 2) selected @endif>{{ trans('user.bad') }}</option>
                        								<option value="3" @if ($item->speaking == 3) selected @endif>{{ trans('user.normal') }}</option>
                        								<option value="4" @if ($item->speaking == 4) selected @endif>{{ trans('user.good') }}</option>
                        								<option value="5" @if ($item->speaking == 5) selected @endif>{{ trans('user.best') }}</option>
                        							</select>
                                                </div>
                                                <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', 'Writing:', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7 margin-top-sm">
                        							<select class="form-control" id="writing" name="writing[]">
                        								<option value="1" @if ($item->writing == 1) selected @endif>{{ trans('user.very_bad') }}</option>
                        								<option value="2" @if ($item->writing == 2) selected @endif>{{ trans('user.bad') }}</option>
                        								<option value="3" @if ($item->writing == 3) selected @endif>{{ trans('user.normal') }}</option>
                        								<option value="4" @if ($item->writing == 4) selected @endif>{{ trans('user.good') }}</option>
                        								<option value="5" @if ($item->writing == 5) selected @endif>{{ trans('user.best') }}</option>
                        							</select>
                                                </div>
                                            </div>
                        	            </div>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                    @endforeach

                    @if (count($userLanguages) == 0)
                        <div id="language_item">
                        	<div class="form-group">
                        		<div class="row">
                        			<div class="col-sm-6">
                        	            <div class="row margin-top-sm">
                                            <div class="form-group">
                                                <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', trans('user.foreign_language').':', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7">
                                                    {{ Form::select('foreign_language_id[]'
                                                       , $languages->lists('name', 'id')
                                                       , null
                                                       , array('class' => 'form-control', 'id' => 'foreign_language_id')) }}
                                                </div>
                                                <div class="col-sm-5"></div>
                                                <div class="col-sm-7 margin-top-xs">
                                                	<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteForeignLanguage(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_foreign_language') }}</a>
                                                </div>
                                            </div>
                        	            </div>
                        			</div>
                        			<div class="col-sm-6 padding-left-normal">
                        	            <div class="row margin-top-sm">
                                            <div class="form-group">
                                                <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', 'Understanding:', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7">
                        							<select class="form-control" id="understanding" name="understanding[]">
                        								<option value="1">{{ trans('user.very_bad') }}</option>
                        								<option value="2">{{ trans('user.bad') }}</option>
                        								<option value="3">{{ trans('user.normal') }}</option>
                        								<option value="4">{{ trans('user.good') }}</option>
                        								<option value="5">{{ trans('user.best') }}</option>
                        							</select>
                                                </div>
                                                <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', 'Speaking:', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7 margin-top-sm">
                        							<select class="form-control" id="speaking" name="speaking[]">
                        								<option value="1">{{ trans('user.very_bad') }}</option>
                        								<option value="2">{{ trans('user.bad') }}</option>
                        								<option value="3">{{ trans('user.normal') }}</option>
                        								<option value="4">{{ trans('user.good') }}</option>
                        								<option value="5">{{ trans('user.best') }}</option>
                        							</select>
                                                </div>
                                                <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', 'Writing:', ['class' => 'margin-top-xs']) }}</label>
                                                <div class="col-sm-7 margin-top-sm">
                        							<select class="form-control" id="writing" name="writing[]">
                        								<option value="1">{{ trans('user.very_bad') }}</option>
                        								<option value="2">{{ trans('user.bad') }}</option>
                        								<option value="3">{{ trans('user.normal') }}</option>
                        								<option value="4">{{ trans('user.good') }}</option>
                        								<option value="5">{{ trans('user.best') }}</option>
                        							</select>
                                                </div>
                                            </div>
                        	            </div>
                        			</div>
                        		</div>
                        	</div>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="row margin-top-sm padding-left-sm">
                                <div class="form-group">
                                    {{ Form::label('', 'Hobbies:', ['class' => 'margin-top-xs']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="row margin-top-sm signup-long-input">
                                <div class="form-group">
                                    {{ Form::text('hobbies', $user->hobbies, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-9">
                            <div class="row signup-long-input">
                                <div class="form-group">
                                    <p>{{ trans('user.msg_03') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane row fade" id="tab-2">
                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div_education">
                    <h2 class="signup-sub-title"><i class="fa fa-bank"></i> {{ trans('user.education') }}</h2>
                    <p class="signup-sub-description">{{ trans('user.msg_04') }}</p>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr>
                    </div>
                </div>

                <div id="institution_list">

                    @foreach ($userEducations as $item)
                        <div id="institution_item">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="row margin-top-sm">
                                        <div class="form-group">
                                            <label class="col-sm-5">{{ Form::label('', trans('user.institution_name').':', ['class' => 'margin-top-xs']) }}</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" id="institution_name" name="institution_name[]" type="text" value="{{ $item->name }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 padding-left-normal">
                                    <div class="row margin-top-sm">
                                        <div class="form-group">
                                            <label class="col-sm-6">{{ Form::label('', trans('user.qualification_faculty').':', ['class' => 'margin-top-xs']) }}</label>
                                            <div class="col-sm-6">
                                                <input class="form-control" id="qualification" name="qualification[]" type="text" value="{{ $item->faculty }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="row margin-top-sm">
                                        <div class="form-group">
                                            <label class="col-sm-5">{{ Form::label('', trans('user.period').':', ['class' => 'margin-top-xs']) }}</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" id="period_start" name="period_start[]" type="text" value="{{ $item->start }}">
                                            </div>
                                            <div class="col-sm-1">{{ Form::label('', '-', ['class' => 'margin-top-xs']) }}</div>
                                            <div class="col-sm-3">
                                                <input class="form-control" id="period_end" name="period_end[]" type="text" value="{{ $item->end }}">
                                            </div>

                                            <label class="col-sm-5 margin-top-sm">{{ Form::label('', trans('user.location').':', ['class' => 'margin-top-xs']) }}</label>
                                            <div class="col-sm-7 margin-top-sm">
                                                <input class="form-control" id="location" name="location[]" type="text" value="{{ $item->location }}">
                                            </div>
                                            <div class="col-sm-7 col-sm-offset-5 margin-top-sm">
                                                <a style="color: #2980b9; cursor: pointer;" onclick="onAddInstitution();"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_institution') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 padding-left-normal">
                                    <div class="row margin-top-sm">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea class="form-control" placeholder="Notes..." style="height: 104px;" id="institution_note" name="institution_note[]" cols="50" rows="10">{{ $item->notes }}</textarea>
                                            </div>
                                            <div class="col-sm-12 margin-top-sm text-right">
                                                <a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteInstitution(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_institution') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12 margin-top-sm">
                                    <hr/>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count($userEducations) == 0)
                        <div id="institutioin_item">
                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.institution_name').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control" id="institution_name" name="institution_name[]" type="text">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-6">{{ Form::label('', trans('user.qualification_faculty').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-6">
                        						<input class="form-control" id="qualification" name="qualification[]" type="text">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.period').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-3">
                        						<input class="form-control" id="period_start" name="period_start[]" type="text">
                        					</div>
                        					<div class="col-sm-1">{{ Form::label('', '-', ['class' => 'margin-top-xs']) }}</div>
                        					<div class="col-sm-3">
                        						<input class="form-control" id="period_end" name="period_end[]" type="text">
                        					</div>

                        					<label class="col-sm-5 margin-top-sm">{{ Form::label('', trans('user.location').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7 margin-top-sm">
                        						<input class="form-control" id="location" name="location[]" type="text">
                        					</div>
                        					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddInstitution();"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_institution') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<div class="col-sm-12">
                        						<textarea class="form-control" placeholder="Notes..." style="height: 104px;" id="institution_note" name="institution_note[]" cols="50" rows="10"></textarea>
                        					</div>
                        					<div class="col-sm-12 margin-top-sm text-right">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteInstitution(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_institution') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="tab-pane row fade" id="tab-3">
                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-trophy"></i> {{ trans('user.awards_honors') }}</h2>
                    <p class="signup-sub-description">{{ trans('user.msg_05') }}</p>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="award_list">
                    @foreach ($userAwards as $item)
                        <div id="award_item">
                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.competition_name').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control" id="competition_name" name="competition_name[]" type="text" value="{{ $item->name }}">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-6">{{ Form::label('', trans('user.prize').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-6">
                        						<input class="form-control" id="prize" name="prize[]" type="text" value="{{ $item->prize }}">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.year').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control" id="competition_year" name="competition_year[]" type="text" value="{{ $item->year }}">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-6">{{ Form::label('', trans('user.location').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-6">
                        						<input class="form-control" id="competition_location" name="competition_location[]" type="text" value="{{ $item->location }}">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5"></label>
                        					<div class="col-sm-7">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddAward('', '', '', '');"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_award') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-6"></label>
                        					<div class="col-sm-6 text-right">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteAward(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_award') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endforeach

                    @if (count($userAwards) == 0)
                        <div id="award_item">
                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.competition_name').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control" id="competition_name" name="competition_name[]" type="text">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-6">{{ Form::label('', trans('user.prize').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-6">
                        						<input class="form-control" id="prize" name="prize[]" type="text">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.year').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control" id="competition_year" name="competition_year[]" type="text">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-6">{{ Form::label('', trans('user.location').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-6">
                        						<input class="form-control" id="competition_location" name="competition_location[]" type="text">
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5"></label>
                        					<div class="col-sm-7">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddAward('', '', '', '');"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_award') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-6"></label>
                        					<div class="col-sm-6 text-right">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteAward(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_award') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="tab-pane row fade" id="tab-4">
                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div_experience">
                    <h2 class="signup-sub-title"><i class="fa fa-building-o"></i> {{ trans('user.work_experience') }}</h2>
                    <p class="signup-sub-description">{{ trans('user.msg_06') }}</p>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="work_list">
                    @foreach ($userExperiences as $item)
                        <div id="work_item">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-5">{{ Form::label('', trans('user.organisation_name').':', ['class' => 'margin-top-xs']) }}</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" id="name" name="organisation_name[]" type="text" value="{{ $item->name }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 padding-left-normal">
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-6">{{ Form::label('', trans('user.job_position').':', ['class' => 'margin-top-xs']) }}</label>
                                                    <div class="col-sm-6">
                                                        <input class="form-control" id="position" name="job_position[]" type="text" value="{{ $item->position }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.period').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-3">
                        						<input class="form-control" id="start" name="work_period_start[]" type="text" value="{{ $item->start }}">
                        					</div>
                        					<div class="col-sm-1">{{ Form::label('', '-', ['class' => 'margin-top-xs']) }}</div>
                        					<div class="col-sm-3">
                        						<input class="form-control" id="end" name="work_period_end[]" type="text" value="{{ $item->end }}">
                        					</div>

                        					<label class="col-sm-5 margin-top-sm">{{ Form::label('', trans('user.job_type').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7 margin-top-sm">
                        						{{ Form::select('work_job_type[]'
                                                	, $types->lists('name', 'id')
                                                    , $item->type_id
                                                    , array('class' => 'form-control', 'id' => 'type_id')) }}
                        					</div>
                        					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddWork('', '', '', '', '', '');"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_organisation') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<div class="col-sm-12">
                        						<textarea class="form-control" placeholder="Notes..." style="height: 104px;" id="notes" name="work_note[]" cols="50" rows="10">{{ $item->notes }}</textarea>
                        					</div>
                        					<div class="col-sm-12 margin-top-sm text-right">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteWork(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_organisation') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endforeach

                    @if (count($userExperiences) == 0)
                        <div id="work_item">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-5">{{ Form::label('', trans('user.organisation_name').':', ['class' => 'margin-top-xs']) }}</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" id="name" name="organisation_name[]" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 padding-left-normal">
                                            <div class="row margin-top-sm">
                                                <div class="form-group">
                                                    <label class="col-sm-6">{{ Form::label('', trans('user.job_position').':', ['class' => 'margin-top-xs']) }}</label>
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
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.period').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-3">
                        						<input class="form-control" id="start" name="work_period_start[]" type="text">
                        					</div>
                        					<div class="col-sm-1">{{ Form::label('', '-', ['class' => 'margin-top-xs']) }}</div>
                        					<div class="col-sm-3">
                        						<input class="form-control" id="end" name="work_period_end[]" type="text">
                        					</div>

                        					<label class="col-sm-5 margin-top-sm">{{ Form::label('', trans('user.job_type').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7 margin-top-sm">
                        						{{ Form::select('work_job_type[]'
                                                	, $types->lists('name', 'id')
                                                    , null
                                                    , array('class' => 'form-control', 'id' => 'type_id')) }}
                        					</div>
                        					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddWork('', '', '', '', '', '');"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_organisation') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<div class="col-sm-12">
                        						<textarea class="form-control" placeholder="Notes..." style="height: 104px;" id="notes" name="work_note[]" cols="50" rows="10"></textarea>
                        					</div>
                        					<div class="col-sm-12 margin-top-sm text-right">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteWork(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_organisation') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endif
                </div>


                <div id="worked_company_list">
                    @foreach ($userCompanies as $item)
                        <div id="workedCompany_item">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <div class="row margin-top-sm padding-left-sm">
                                            <div class="form-group">
                                                <label for="" class="margin-top-xs">{{ trans('user.company') }}:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="row margin-top-sm signup-long-input">
                                            <div class="form-group">
                                                <select class="form-control" name="worked_company_id[]" id="worked_company_id">
                                                    <option value="0">{{ trans('user.other') }}</option>
                                                    @foreach ($followCompanies as $fComapny)
                                                        <option value="{{ $fComapny->id }}" @if ($item->followCompany->id == $fComapny->id) selected @endif>{{ $fComapny->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <div class="row margin-top-sm signup-long-input">
                                            <div class="form-group margin-top-xs">
                                                <a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteWorkedCompany(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_company') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (count($userCompanies) == 0)
                        <div id="workedCompany_item">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">
                                        <div class="row margin-top-sm padding-left-sm">
                                            <div class="form-group">
                                                <label for="" class="margin-top-xs">{{ trans('user.company') }}:</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="row margin-top-sm signup-long-input">
                                            <div class="form-group">
                                                <select class="form-control" name="worked_company_id[]" id="worked_company_id">
                                                    <option value="0">{{ trans('user.other') }}</option>
                                                    @foreach ($followCompanies as $fComapny)
                                                        <option value="{{ $fComapny->id }}">{{ $fComapny->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <div class="row margin-top-sm signup-long-input">
                                            <div class="form-group margin-top-xs">
                                                <a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteWorkedCompany(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_company') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5 col-sm-offset-1">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-7 col-sm-offset-5">
                                        <a style="color: #2980b9; cursor: pointer;" onclick="onAddWorkedCompany(0);"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_company') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                    <h2 class="signup-sub-title"><i class="fa fa-comment"></i> {{ trans('user.testimonials') }}</h2>
                    <p class="signup-sub-description">{{ trans('user.msg_07') }}</p>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div id="testimonial_list">
                    @foreach ($userTestimonials as $item)
                        <div id="testimonial_item">
                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.full_name').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control" id="name" name="testimonial_name[]" type="text" value="{{ $item->name }}">
                        					</div>

                        					<label class="col-sm-5 margin-top-sm">{{ Form::label('', trans('user.organisation').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7 margin-top-sm">
                        						<input class="form-control" id="organisation" name="testimonial_organisation[]" type="text" value="{{ $item->organisation }}">
                        					</div>
                        					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddTestimonial();"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_testimonial') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<div class="col-sm-12">
                        						<textarea class="form-control" placeholder="Testimonial..." style="height: 104px;" id="notes" name="testimonial_note[]" cols="50" rows="10">{{ $item->notes }}</textarea>
                        					</div>
                        					<div class="col-sm-12 margin-top-sm text-right">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteTestimonial(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_testimonial') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endforeach

                    @if (count($userTestimonials) == 0)
                        <div id="testimonial_item">
                        	<div class="form-group">
                        		<div class="col-sm-6">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<label class="col-sm-5">{{ Form::label('', trans('user.full_name').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7">
                        						<input class="form-control" id="name" name="testimonial_name[]" type="text">
                        					</div>

                        					<label class="col-sm-5 margin-top-sm">{{ Form::label('', trans('user.organisation').':', ['class' => 'margin-top-xs']) }}</label>
                        					<div class="col-sm-7 margin-top-sm">
                        						<input class="form-control" id="organisation" name="testimonial_organisation[]" type="text">
                        					</div>
                        					<div class="col-sm-7 col-sm-offset-5 margin-top-sm">
                        						<a style="color: #2980b9; cursor: pointer;" onclick="onAddTestimonial();"><i class="fa fa-plus-circle"></i> {{ trans('user.add_new_testimonial') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        		<div class="col-sm-6 padding-left-normal">
                        			<div class="row margin-top-sm">
                        				<div class="form-group">
                        					<div class="col-sm-12">
                        						<textarea class="form-control" placeholder="Testimonial..." style="height: 104px;" id="notes" name="testimonial_note[]" cols="50" rows="10"></textarea>
                        					</div>
                        					<div class="col-sm-12 margin-top-sm text-right">
                        						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteTestimonial(this);"><i class="fa fa-trash"></i> {{ trans('user.delete_testimonial') }}</a>
                        					</div>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="form-group">
                        		<div class="col-sm-12 margin-top-sm">
                        			<hr/>
                        		</div>
                        	</div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="tab-pane row fade" id="tab-5">
                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div_salary">
                    <h2 class="signup-sub-title"><i class="fa fa-money"></i> {{ trans('user.salary_job_type') }}</h2>
                    <p class="signup-sub-description">{{ trans('user.msg_08') }}</p>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-5">{{ Form::label('', trans('user.remuneration_amount').':', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-7">
                                        {{ Form::text('renumeration_amount', $user->renumeration_amount, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-6">{{ Form::label('', trans('user.per').':', ['class' => 'margin-top-xs']) }}</label>
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
                        <div class="col-sm-2 margin-top-sm">
                            {{ Form::label('', trans('user.job_type').':', ['class' => '']) }}
                        </div>
                        <div class="col-sm-2 margin-top-sm">
                            {{ Form::checkbox('is_freelance', 0, $user->is_freelance, ['class' => 'checkbox-normal', 'id' => 'is_freelance']) }}
                            <label class="control-checkbox">{{ trans('user.freelance') }}</label>
                        </div>
                        <div class="col-sm-2 margin-top-sm">
                            {{ Form::checkbox('is_parttime', 0, $user->is_parttime, ['class' => 'checkbox-normal', 'id' => 'is_parttime']) }}
                            <label class="control-checkbox">{{ trans('user.part-time') }}</label>
                        </div>
                        <div class="col-sm-2 margin-top-sm">
                            {{ Form::checkbox('is_fulltime', 0, $user->is_fulltime, ['class' => 'checkbox-normal', 'id' => 'is_fulltime']) }}
                            <label class="control-checkbox">{{ trans('user.full-time') }}</label>
                        </div>
                        <div class="col-sm-2 margin-top-sm">
                            {{ Form::checkbox('is_internship', 0, $user->internship, ['class' => 'checkbox-normal', 'id' => 'is_internship']) }}
                            <label class="control-checkbox">{{ trans('user.internship') }}</label>
                        </div>
                        <div class="col-sm-2 margin-top-sm">
                            {{ Form::checkbox('is_volunteer', 0, $user->is_volunteer, ['class' => 'checkbox-normal', 'id' => 'is_volunteer']) }}
                            <label class="control-checkbox">{{ trans('user.volunteer') }}</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>
            </div>
            <div class="tab-pane row fade" id="tab-6">

                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-normal" id="div_contact">
                    <h2 class="signup-sub-title"><i class="fa fa-clipboard"></i> {{ trans('user.apply_detail') }}</h2>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <hr/>
                    </div>
                </div>

                <div id="div_apply">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-2">
                                {{ Form::label('', 'Pattern', ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-10">
                                <select class="form-control" onchange="changePattern(this);">
                                    @foreach($patterns as $pattern)
                                    <option value="{{ $pattern->name }}" data-description="{{ $pattern->description }}">{{ $pattern->name }}</option>
                                    @endforeach
                                    <option value="" data-descripton="">{{ trans('job.other') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-12">
                            <div class="col-sm-2">
                                {{ Form::label('', trans('job.title'), ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-10">
                                {{ Form::text('apply_title', $patterns[0]->name, ['class' => 'form-control', 'id' => 'title']) }}
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-12">
                            <div class="col-sm-2">
                                {{ Form::label('', trans('job.description'), ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-10">
                                {{ Form::textarea('apply_description', $patterns[0]->description, ['class' => 'form-control job-description', 'rows' => '5', 'id' => 'description']) }}
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-12">
                            <div class="col-sm-2">
                                {{ Form::label('', trans('job.add_attachments'), ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-10">
                                {{ Form::file('attachFile', ['class' => 'form-control', 'id' => 'file']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg" id="div_contact">
                    <h2 class="signup-sub-title"><i class="fa fa-bookmark"></i> {{ trans('user.contact_details') }}</h2>
                    <p class="signup-sub-description">{{ trans('user.msg_09') }}</p>
                </div>

                <div class="form-group">
                    <div class="col-sm-12 margin-top-sm">
                        <hr/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-4">{{ Form::label('',trans('user.address').':', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('address', $user->address, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-4">{{ Form::label('', trans('user.facebook').':', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('facebook', $user->facebook, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-4">{{ Form::label('', trans('user.phone_number').':', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('phone', $user->phone, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-4">{{ Form::label('', trans('user.linkedin').':', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('linkedin', $user->linkedin, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-4">{{ Form::label('', trans('user.website').':', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('website', $user->website, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-4">{{ Form::label('', trans('user.twitter').':', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('twitter', $user->twitter, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row margin-top-sm">
                                <div class="form-group margin-top-xs">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        {{ Form::checkbox('is_published', 0, $user->is_published, ['class' => 'checkbox-normal', 'id' => 'is_published']) }}
                                        <label class="control-checkbox">{{ trans('user.msg_10') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 padding-left-normal">
                            <div class="row margin-top-sm">
                                <div class="form-group">
                                    <label class="col-sm-4">{{ Form::label('', trans('user.google').'+:', ['class' => 'margin-top-xs']) }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('google', $user->google, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0px;">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="row margin-top-sm padding-left-sm">
                                <div class="form-group">
                                    {{ Form::label('', trans('user.google_maps_address').':', ['class' => 'margin-top-xs']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-10">
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
                        <div class="col-sm-12">
                            <div class="row signup-long-input">
                                <div id="mapdiv" style="height:200px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row padding-bottom-xl">
    <div class="col-sm-4 col-sm-offset-4 margin-top-normal text-center">
        <button class="btn btn-lg btn-primary text-uppercase disabled" id="js-btn-submit">{{ trans('job.submit') }} <span class="glyphicon glyphicon-ok-circle"></span></button>
        <a class="btn btn-lg btn-primary text-uppercase" onclick="showNextTab()" id="js-a-next">{{ trans('user.next') }} <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>