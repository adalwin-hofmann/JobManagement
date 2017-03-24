@extends('agency.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
@stop

@section('body')
<main class="auth gray-container padding-top-xs padding-bottom-xs">
    
    <div class="container" style="background: white;">
	    <div class="row">
		    <div class="col-sm-4 col-sm-offset-4 margin-top-lg">
		        @if ($errors->has())
		        <div class="alert alert-danger alert-dismissibl fade in">
		            <button type="button" class="close" data-dismiss="alert">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">{{ trans('job.close') }}</span>
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
		                <span class="sr-only">{{ trans('job.close') }}</span>
		            </button>
		            <p>
		                <?php echo $alert['msg'];?>
		            </p>
		        </div>
		        <?php } ?>
		    </div>    
	    </div>

	    <div class="row">
            <div class="col-sm-5 col-sm-offset-1">
                <div class="form-group">
                    <label class="col-sm-5">{{ Form::label('', 'Job Template:', ['class' => 'margin-top-xs']) }}</label>
                    <div class="col-sm-7">
                        <select class="form-control" id="js-select-jobTemp" onchange="fillBlanks(this);">
                            <option value="0">{{ trans('company.other') }}</option>
                            @foreach ($jobTemps as $job)
                                <option value="{{ $job->id }}">{{ $job->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-5 margin-top-xs text-center">
                <span>({{ trans('company.msg_07') }})</span>
            </div>
	    </div>

	    
	    <form method="POST" action="{{ URL::route('agency.job.doAddJob') }}" role="form" class="form-login margin-top-lg" id="js-form-addJob" enctype="multipart/form-data">
	    
	    	<input type="hidden" name="agency_id" value="{{ $agency_id }}">
	    
	    	<div class="text-center">
	    		<h2 class="signup-sub-title"><i class="fa fa-file-text-o"></i> {{ trans('company.add_job_offer') }}</h2>
	    		<p class="signup-sub-description">{{ trans('company.msg_08') }}</p>
	    	</div>
	    	
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-1 margin-top-sm">
					<hr/>
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-5 col-sm-offset-1">
				        @foreach ([
				            'name' => trans('company.job_title').':',
				            'level_id' => trans('company.career_level').':',
				            'presence_id' => trans('company.presence').':',
				            'year' => trans('company.years_of_experience').':',
				            'p_category_id' => trans('company.industry').':',
				            'category_id' => trans('company.sub-industry').':',
				            'city_id' => trans('company.location').':',
				        ] as $key => $value)
				            <div class="row margin-top-sm">
			                    <div class="form-group">
			                        <label class="col-sm-5">{{ Form::label($key, $value, ['class' => 'margin-top-xs']) }}</label>
			                        <div class="col-sm-7">
										@if ($key == 'city_id')
				                            {{ Form::select($key
				                               , $cities->lists('name', 'id')
				                               , null
				                               , array('class' => 'form-control')) }}   
				                        @elseif ($key == 'p_category_id')
				                            {{ Form::select($key
				                               , $categories->lists('name', 'id')
				                               , null
				                               , array('class' => 'form-control')) }}
                                        @elseif ($key == 'category_id')
                                            <div id="sub-category-box"></div>
										@elseif ($key == 'presence_id')
				                            {{ Form::select($key
				                               , $presences->lists('name', 'id')
				                               , null
				                               , array('class' => 'form-control')) }}
				                        @elseif ($key == 'level_id')
				                            {{ Form::select($key
				                               , $levels->lists('name', 'id')
				                               , null
				                               , array('class' => 'form-control')) }}				                        													                        	                          		                            
				                        @else
				                            {{ Form::text($key, null, ['class' => 'form-control']) }}
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
		                    			<label>{{ Form::label('about', trans('company.job_description').':') }}</label>
		                    		</div>	                    		
		                    	</div>
		                        <div>
									{{ Form::textarea('description', null, ['class' => 'form-control job-description', 'id' => 'description']) }}
		                        </div>
		                    </div>
			            </div> 				
					</div>
				</div>
			</div>
			
			
	    	<div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
	    		<h2 class="signup-sub-title"><i class="fa fa-bar-chart-o"></i> {{ trans('company.required_skills') }}</h2>
	    		<p class="signup-sub-description">{{ trans('company.msg_09') }}</p>
	    	</div>
	    	
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-1 margin-top-sm">
					<hr/>
				</div>
			</div>
						
			<div id="skill_list"></div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-5 col-sm-offset-1">
			            <div class="row margin-top-sm">
		                    <div class="form-group">
		                        <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', 'Native Language:', ['class' => 'margin-top-xs']) }}</label>
		                        <div class="col-sm-7">
		                            {{ Form::select('native_language_id'
		                               , $languages->lists('name', 'id')
		                               , null
		                               , array('class' => 'form-control')) }} 
		                        </div>
		                    </div>
			            </div>        				
					</div>
					<div class="col-sm-5 padding-left-normal">
			            <div class="row margin-top-sm">
		                    <div class="form-group">
								<div class="col-sm-12 margin-top-xs">
									<a style="color: #2980b9; cursor: pointer;" onclick="onAddForeignLanguage('','','','');"><i class="fa fa-plus-circle"></i> Add Foreign Language</a>
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
						<div class="row margin-top-sm padding-left-xs">
							<div class="form-group">
								{{ Form::label('', trans('company.additional_requirements').':', ['class' => 'margin-top-xs']) }}
							</div>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row margin-top-sm signup-long-input">
							<div class="form-group">
								{{ Form::text('requirements', null, ['class' => 'form-control', 'id' => 'requirements']) }}
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

			
	    	<div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
	    		<h2 class="signup-sub-title"><i class="fa fa-envelope-o"></i> {{ trans('company.recruitment_bonus') }}</h2>
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
								<label class="col-sm-5">{{ Form::label('', trans('company.bonus').':', ['class' => 'margin-top-xs']) }}</label>
								<div class="col-sm-7">
									{{ Form::text('bonus', null, ['class' => 'form-control', 'id' => 'bonus']) }}
								</div>
							</div>
						</div>        				
					</div>
					<div class="col-sm-5 padding-left-normal">
						<div class="row margin-top-sm">
							<div class="form-group">
								<label class="col-sm-6">{{ Form::label('', trans('company.bonus_paid_after').':', ['class' => 'margin-top-xs']) }}</label>
								<div class="col-sm-6">
									{{ Form::text('paid_after', null, ['class' => 'form-control', 'id' => 'paid_after']) }}
								</div>
							</div>
						</div> 				
					</div>				
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="row margin-top-sm signup-long-input text-center">
							<div class="form-group margin-top-xs" style="display: inline-block">
								<div class="checkbox-container">
				                    {{ Form::checkbox('is_verified', 1, null, ['class' => 'checkbox-normal', 'id' => 'is_verified', 'checked']) }}
				                    <label class="control-checkbox">{{ trans('company.verified') }}</label>
								</div>
								<div class="checkbox-container">
				                    {{ Form::checkbox('is_name', 1, null, ['class' => 'checkbox-normal', 'id' => 'is_name', 'checked']) }}
				                    <label class="control-checkbox">{{ trans('company.name') }}</label>
								</div>
								<div class="checkbox-container" style="padding-left: 0px; padding-right: 0px;">
				                    {{ Form::checkbox('is_phonenumber', 0, null, ['class' => 'checkbox-normal', 'id' => 'is_phonenumber']) }}
				                    <label class="control-checkbox">{{ trans('company.phone_number') }}</label>
								</div>
								<div class="checkbox-container">
				                    {{ Form::checkbox('is_email', 1, null, ['class' => 'checkbox-normal', 'id' => 'is_email', 'checked']) }}
				                    <label class="control-checkbox">{{ trans('company.email') }}</label>
								</div>
								<div class="checkbox-container">
				                    {{ Form::checkbox('is_currentjob', 0, null, ['class' => 'checkbox-normal', 'id' => 'is_currentjob']) }}
				                    <label class="control-checkbox">{{ trans('company.current_job') }}</label>
								</div>
								<div class="checkbox-container">
				                    {{ Form::checkbox('is_previousjobs', 0, null, ['class' => 'checkbox-normal', 'id' => 'is_previousjobs']) }}
				                    <label class="control-checkbox">{{ trans('company.previous_jobs') }}</label>
								</div>
								<div class="checkbox-container">
				                    {{ Form::checkbox('is_description', 1, null, ['class' => 'checkbox-normal', 'id' => 'is_description', 'checked']) }}
				                    <label class="control-checkbox">{{ trans('company.description') }}</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2 col-sm-offset-1">
						<div class="row margin-top-sm padding-left-xs">
							<div class="form-group">
								{{ Form::label('', trans('company.description').':', ['class' => 'margin-top-xs']) }}
							</div>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="row margin-top-sm signup-long-input">
							<div class="form-group">
								{{ Form::text('bonus_description', null, ['class' => 'form-control', 'id' => 'bonus_description']) }}
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
	    		<h2 class="signup-sub-title"><i class="fa fa-money"></i> {{ trans('company.salary_benefits') }}</h2>
	    		<p class="signup-sub-description">{{ trans('company.msg_12') }}</p>
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
								<label class="col-sm-5">{{ Form::label('', trans('company.job_type').':', ['class' => 'margin-top-xs']) }}</label>
								<div class="col-sm-7">
		                            {{ Form::select('type_id'
		                               , $types->lists('name', 'id')
		                               , null
		                               , array('class' => 'form-control', 'id' => 'type_id')) }}
								</div>
							</div>
						</div>        				
					</div>
					<div class="col-sm-5 padding-left-normal">
						<div class="row margin-top-sm">
							<div class="form-group">
								<label class="col-sm-4">{{ Form::label('', trans('company.salary').':', ['class' => 'margin-top-xs']) }}</label>
								<div class="col-sm-4">
									{{ Form::text('salary', null, ['class' => 'form-control', 'id' => 'salary']) }}
								</div>
								<div class="col-sm-4">
									{{ Form::label('', '/ Month', ['class' => 'margin-top-xs']) }}
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
			
			<div id="benefit_list"></div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-5 col-sm-offset-1">
						<div class="row">
							<div class="form-group">
								<div class="col-sm-7 col-sm-offset-5">
									<a style="color: #2980b9; cursor: pointer;" onclick="onAddBenefit('');"><i class="fa fa-plus-circle"></i> {{ trans('company.add_new_benefit') }}</a>
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
								<label class="col-sm-5">{{ Form::label('', trans('company.phone_number').':', ['class' => 'margin-top-xs']) }}</label>
								<div class="col-sm-7">
									{{ Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone']) }}
								</div>
							</div>
						</div>        				
					</div>
					<div class="col-sm-5 padding-left-normal">
						<div class="row margin-top-sm">
							<div class="form-group">
								<label class="col-sm-6">{{ Form::label('', trans('company.contact_email').':', ['class' => 'margin-top-xs', 'id' => 'email']) }}</label>
								<div class="col-sm-6">
									{{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}
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
				                    {{ Form::checkbox('is_published', 0, null, ['class' => 'checkbox-normal', 'id' => 'is_published']) }}
				                    <label class="control-checkbox">{{ trans('company.msg_14') }}</label>
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
								{{ Form::text('latlng', null, ['class' => 'form-control', 'readonly', 'id' => 'latlng']) }}
							</div>
						</div>
					</div>
				</div>
			</div>
			
	        <input type="hidden" name="lat" value="" id="lat">
	        <input type="hidden" name="lng" value="" id="lng">
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


            <div class="text-center col-sm-10 col-sm-offset-1 margin-top-lg">
                <h2 class="signup-sub-title"><i class="fa fa-send"></i> {{ trans('company.share_management') }}</h2>
                <p class="signup-sub-description">{{ trans('company.msg_45') }}</p>
            </div>


			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-1 margin-top-sm">
					<hr/>
				</div>
			</div>

			<div class="margin-top-lg">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <table class="table table-striped table-bordered table-hover" id="client_company_table">
                            <thead>
                                <tr>
                                    <th class="table-checkbox">
                                        <input type="checkbox" class="group-checkable" data-set="#client_company_table .checkboxes"/>
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agency->clients as $item)
                                    <tr class="odd gradeX">
                                        <td>
                                            <input type="checkbox" class="checkboxes" value="0" data-id="{{ $item->company->id }}"/>
                                        </td>
                                        <td>
                                             <div class="white-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="right" data-image-url="{{ HTTP_LOGO_PATH.$item->company->logo }}" data-tag="{{ $item->company->tag }}" data-description="{{ nl2br($item->company->description) }}">
                                                 <div style="display: inline-block; margin-top: 5px;">
                                                     <span><a href="{{ URL::route('user.company.view', $item->company->slug) }}">{{ $item->company->name }}</a></span>
                                                 </div>
                                             </div>
                                        </td>
                                        <td>
                                            {{ $item->company->email }}
                                        </td>
                                        <td>
                                             <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ round($item->company->parent->reviews()->avg('score')) }}" readonly>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

	        <input type="hidden" name="share_companies" id="share_companies" value=""/>
	        
	        <div class="row padding-bottom-xl">
	            <div class="col-sm-3 col-sm-offset-4 margin-top-normal">
	                <a class="btn btn-lg btn-primary text-uppercase btn-block btn-home" id="js-a-save-template" onclick="saveAsTemplate()"><span class="glyphicon glyphicon-floppy-disk text-uppercase"></span> {{ trans('company.save_as_a_template') }}</a>
	            </div>
	            <div class="col-sm-2 margin-top-normal">
	                <button class="btn btn-lg btn-primary text-uppercase btn-block btn-home"><span class="glyphicon glyphicon-ok-circle text-uppercase"></span> {{ trans('company.submit') }}</button>
	            </div>
	        </div>
	    </form>    
    </div>
           
</main>


<!-- Model Div for Skill -->
<div id="clone_div_skill" class="hidden row">
	<div class="form-group">
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5">{{ Form::label('', trans('company.skill_name').':', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-7">
						<input class="form-control" name="skill_name[]" id="skill_name" type="text">
					</div>
				</div>
			</div>        				
		</div>
		<div class="col-sm-5">
			<div class="row margin-top-sm">
				<div class="form-group">
					<div class="col-sm-3">
						<input class="form-control" name="skill_value[]" id="skill_value" type="text">
					</div>							
					<label class="col-sm-4">{{ Form::label('', '% (1 to 100 value)', ['class' => 'margin-top-xs']) }}</label>
					<div class="col-sm-5 margin-top-xs">
						<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteSkill(this);"><i class="fa fa-trash"></i> {{ trans('company.delete_skill') }}</a>
					</div>
				</div>
			</div> 				
		</div>
		<div class="col-sm-5 col-sm-offset-1">
			<div class="row margin-top-sm">
				<div class="form-group">
					<label class="col-sm-5"></label>
					<div class="col-sm-7">
						<a style="color: #2980b9; cursor: pointer;" onclick="onAddSkill('', '');"><i class="fa fa-plus-circle"></i> {{ trans('company.add_new_skill') }}</a>
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
                        <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', trans('company.foreign_language').':', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7">
                            {{ Form::select('foreign_language_id[]'
                               , $languages->lists('name', 'id')
                               , null
                               , array('class' => 'form-control', 'id' => 'foreign_language_id')) }}
                        </div>
                        <div class="col-sm-5"></div>
                        <div class="col-sm-7 margin-top-xs">
                        	<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteForeignLanguage(this);"><i class="fa fa-trash"></i> {{ trans('company.delete_foreign_language') }}</a>
                        </div>		           
                    </div>
	            </div>        				
			</div>
			<div class="col-sm-5 padding-left-normal">
	            <div class="row margin-top-sm">
                    <div class="form-group">
                        <label class="col-sm-5" style="color:#34495e;">{{ Form::label('', trans('company.understanding').':', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7">
							<select class="form-control" id="understanding" name="understanding[]">
								<option value="1">{{ trans('company.very_bad') }}</option>
								<option value="2">{{ trans('company.bad') }}</option>
								<option value="3">{{ trans('company.normal') }}</option>
								<option value="4">{{ trans('company.good') }}</option>
								<option value="5">{{ trans('company.best') }}</option>
							</select>
                        </div>
                        <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', trans('company.speaking').':', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7 margin-top-sm">
							<select class="form-control" id="speaking" name="speaking[]">
								<option value="1">{{ trans('company.very_bad') }}</option>
								<option value="2">{{ trans('company.bad') }}</option>
								<option value="3">{{ trans('company.normal') }}</option>
								<option value="4">{{ trans('company.good') }}</option>
								<option value="5">{{ trans('company.best') }}</option>
							</select>
                        </div>
                        <label class="col-sm-5 margin-top-sm" style="color:#34495e;">{{ Form::label('', trans('company.writing').':', ['class' => 'margin-top-xs']) }}</label>
                        <div class="col-sm-7 margin-top-sm">
							<select class="form-control" id="writing" name="writing[]">
								<option value="1">{{ trans('company.very_bad') }}</option>
								<option value="2">{{ trans('company.bad') }}</option>
								<option value="3">{{ trans('company.normal') }}</option>
								<option value="4">{{ trans('company.good') }}</option>
								<option value="5">{{ trans('company.best') }}</option>
							</select>
                        </div>
                    </div>
	            </div> 				
			</div>
		</div>
	</div>
</div>
<!--  -->

<!-- Model Div for Benefit -->
<div id="clone_div_benefit" class="hidden">
	<div class="form-group">
		<div class="row">
			<div class="col-sm-5 col-sm-offset-1">
				<div class="row margin-top-sm">
					<div class="form-group">
						<label class="col-sm-5">{{ Form::label('', trans('company.benefit_name').':', ['class' => 'margin-top-xs']) }}</label>
						<div class="col-sm-7">
							<input class="form-control" name="benefit_name[]" id="benefit_name" type="text">
						</div>
						<div class="col-sm-7 col-sm-offset-5 margin-top-xs">
							<a style="color: #e74c3c; cursor: pointer;" onclick="onDeleteBenefit(this);"><i class="fa fa-trash"></i> {{ trans('company.delete_benefit') }}</a>
						</div>
					</div>
				</div>        				
			</div>											
		</div>
	</div>
</div>
<!--  -->


<!--Select Boxs for Sub-Categories -->
@foreach ($categories as $category)
    <select class="form-control hidden" id="select-sub-{{$category->id}}" name="sub_category_id">
        @foreach($category->child as $sCategory)
            <option value="{{ $sCategory->id }}">{{ $sCategory->name }}</option>
        @endforeach
    </select>
@endforeach
<!-- -->

@stop

@stop

@section('custom-scripts')
	{{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
    @include('js.agency.job.add');
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>
@stop