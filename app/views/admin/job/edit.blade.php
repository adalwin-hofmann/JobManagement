@extends('admin.layout')

@section('custom_styles')
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
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
		<h3 class="page-title">Job Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Job</span>
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
			<i class="fa fa-pencil-square-o"></i> Edit Job
		</div>
	</div>
    <div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.job.store') }}">
        
        	<input type="hidden" name="job_id" value="{{ $job->id }}">
	        <input type="hidden" name="lat" id="lat" value="{{ $job->lat }}">
	        <input type="hidden" name="lng" id="lng" value="{{ $job->long }}">
	        <input type="hidden" name="requestURL" value="{{ Route::current()->getName() }}">
	        	        
            @foreach ([
                'company_id' => 'Company',
                'name' => 'Name',
                'level_id' => 'Career Level',
                'description' => 'Description',
                'category_id' => 'Category',
                'presence_id' => 'Presence',
                'year' => 'Years of experience',
                'city_id' => 'Location',
				                
            ] as $key => $value)
            <div class="form-group">
            
            	@if ($key != 'is_published')
                	<label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                @else 
                	<label class="col-sm-3 control-label"></label>
                @endif
                
                <div class="col-sm-9">
                    @if ($key == 'city_id')                        
                        {{ Form::select($key
                           , $cities->lists('name', 'id')
                           , $job->city_id
                           , array('class' => 'form-control')) }} 
                    @elseif ($key == 'category_id') 
                        {{ Form::select($key
                           , $categories->lists('name', 'id')
                           , $job->category_id
                           , array('class' => 'form-control')) }}   
                    @elseif ($key == 'level_id')
                        {{ Form::select($key
                           , $levels->lists('name', 'id')
                           , $job->level_id
                           , array('class' => 'form-control')) }}  
                    @elseif ($key == 'presence_id')
                        {{ Form::select($key
                           , $presences->lists('name', 'id')
                           , $job->presence_id
                           , array('class' => 'form-control')) }}
                    @elseif ($key == 'company_id')
                        {{ Form::select($key
                           , $companies->lists('name', 'id')
                           , $job->company_id
                           , array('class' => 'form-control')) }}   
                    @elseif ($key == 'is_published')
                    	{{ Form::checkbox($key, $job->is_published, ($job->is_published == '1') ? true:null, ['class' => 'is_published']) }}
                    	<label class="control-checkbox">{{ Form::label($key, $value) }}</label>
                    @elseif ($key == 'description') 
                    	{{ Form::textarea($key, $job->description, ['class' => 'form-control']) }}     
                    @else
                        {{ Form::text($key, $job->{$key}, ['class' => 'form-control']) }}
                    @endif
                </div>

            </div>
            @endforeach
            
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<hr/>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ Form::label('native_language_id', 'Native Language') }}</label>
	            <div class="col-sm-6">
					{{ Form::select('native_language_id'
					   , $languages->lists('name', 'id')
					   , $job->language_id
					   , array('class' => 'form-control')) }}
	            </div>	
	            <div class="col-sm-3 text-right">
					<button class="btn btn-primary btn-sm" onclick="onAddForeignLanguage('', '', '', '')" type="button">Add Foreign Language</button>
				</div>		
			</div>            
	        
	        <div id="foreign_language_list"></div>	        
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<hr/>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ Form::label('requirements', 'Additional Requirements') }}</label>
	            <div class="col-sm-9">
					{{ Form::text('requirements', $job->requirements, ['class' => 'form-control']) }}
	            </div>		
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ Form::label('bonus', 'Bonus') }}</label>
	            <div class="col-sm-9">
					{{ Form::text('bonus', $job->bonus, ['class' => 'form-control']) }}
	            </div>					
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ Form::label('paid_after', 'Bonus paid after') }}</label>
	            <div class="col-sm-9">
					{{ Form::text('paid_after', $job->paid_after, ['class' => 'form-control']) }}
	            </div>					
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label"></label>
				<div class="col-sm-3">
                    {{ Form::checkbox('is_verified', $job->is_verified, $job->is_verified, ['class' => 'is_published', 'id' => 'is_verified']) }}
                    <label class="control-checkbox">{{ Form::label('is_verified', 'Verified') }}</label>
                </div>
				<div class="col-sm-3">
                    {{ Form::checkbox('is_name', $job->is_name, $job->is_name, ['class' => 'is_published', 'id' => 'is_name']) }}
                    <label class="control-checkbox">{{ Form::label('is_name', 'Name') }}</label>
				</div>
				<div class="col-sm-3">
                    {{ Form::checkbox('is_phonenumber', $job->is_phonenumber, $job->is_phonenumber, ['class' => 'is_published', 'id' => 'is_phonenumber']) }}
                    <label class="control-checkbox">{{ Form::label('is_phonenumber', 'Phonenumber') }}</label>
				</div>
				<label class="col-sm-3 control-label"></label>
				<div class="col-sm-3">
                    {{ Form::checkbox('is_email', $job->is_email, $job->is_email, ['class' => 'is_published', 'id' => 'is_email']) }}
                    <label class="control-checkbox">{{ Form::label('is_email', 'Email') }}</label>
				</div>
				<div class="col-sm-3">
                    {{ Form::checkbox('is_currentjob', $job->is_currentjob, $job->is_currentjob, ['class' => 'is_published', 'id' => 'is_currentjob']) }}
                    <label class="control-checkbox">{{ Form::label('is_currentjob', 'Current job') }}</label>
				</div>
				<div class="col-sm-3">
                    {{ Form::checkbox('is_previousjobs', $job->is_previousjobs, $job->is_previousjobs, ['class' => 'is_published', 'id' => 'is_previousjobs']) }}
                    <label class="control-checkbox">{{ Form::label('is_previousjobs', 'Previous jobs') }}</label>
				</div>
				<label class="col-sm-3 control-label"></label>
				<div class="col-sm-3">
                    {{ Form::checkbox('is_description', $job->is_description, $job->is_description, ['class' => 'is_published', 'id' => 'is_description']) }}
                    <label class="control-checkbox">{{ Form::label('is_description', 'Description') }}</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ Form::label('bonus_description', 'Bonus Description') }}</label>
	            <div class="col-sm-9">
					{{ Form::text('bonus_description', $job->bonus_description, ['class' => 'form-control']) }}
	            </div>					
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ Form::label('type_id', 'Job type') }}</label>
	            <div class="col-sm-9">
                	{{ Form::select('type_id'
               			, $types->lists('name', 'id')
                        , $job->type_id
                        , array('class' => 'form-control')) }}
	            </div>					
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Salary</label>
				<div class="col-sm-6">
					{{ Form::text('salary', $job->salary, ['class' => 'form-control']) }}
				</div>
				<label class="col-sm-3 control-label"> / Month</label>
			</div>
			
			<div id="benefit_list"></div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Phone number</label>
				<div class="col-sm-9">
					{{ Form::text('phone', $job->phone, ['class' => 'form-control']) }}
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Contact Email</label>
				<div class="col-sm-9">
					{{ Form::text('email', $job->email, ['class' => 'form-control']) }}
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label"></label>
				<div class="col-sm-9">
                	{{ Form::checkbox('is_published', 0, null, ['class' => 'is_published', 'id' => 'is_published']) }}
                    <label class="control-checkbox">{{ Form::label('is_published', 'Publish my email address') }}</label>
				</div>
			</div>
			
            <div class="form-group">
            	<label class="col-sm-3 control-label">Google Maps Address</label>
				<div class="col-sm-9">
                	<input class="form-control" type="text" id="latlng" disabled="disabled" value="{{ $job->lat.', '.$job->long }}">
                </div>            	
            </div>
           
            <div class="form-group">
            	<label class="col-sm-3 control-label"></label>
				<div class="col-sm-9">
					<div id="mapdiv"></div>
				</div>            	
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Apply Link</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="link_address" value="{{ $job->link_address }}">
                </div>
            </div>
            
            <div class="form-group">
				<label class="col-sm-3 control-label">Active</label>
				<div class="col-sm-9">
                	{{ Form::select('is_active'
               			, array('0' => 'No', '1' => 'Yes')
                        , $job->is_active
                        , array('class' => 'form-control')) }}
				</div>            
            </div>
            
			
			<div class="form-group">
			  <div class="col-sm-offset-2 col-sm-10 text-center">
				  <button class="btn btn-success">
					  <span class="glyphicon glyphicon-ok-circle"></span> Save
				  </button>
				  <a href="{{ URL::route('admin.job') }}" class="btn btn-primary">
					  <span class="glyphicon glyphicon-share-alt"></span> Back
				  </a>
			  </div>
			</div>        

        </form>
    </div>
</div>




<!-- Model Div for skill -->
<div id="clone_div_skill" class="hidden">
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<hr/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Skill</label>
		<label class="col-sm-1 control-label">Name</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="skill_name[]" id="service_name">
		</div>
		<div class="col-sm-2">
			<input class="form-control" type="text" name="skill_value[]" id="icon_code">
		</div>
		<label class="col-sm-3 control-label text-left-important">%(1 to 100 value)</label>                    
	</div>  
	<div class="form-group">
		<div class="col-sm-12 text-right">
			<button class="btn btn-primary btn-sm" onclick="onAddSkill('', '')" type="button">Add</button>
			<button class="btn btn-danger btn-sm" onclick="onDeleteSkill(this)">Delete</button>
		</div>
	</div>
	            
</div>
<!--  -->



<!-- Model Div for Foreign Language -->
<div id="clone_div_language" class="hidden">
	<div class="form-group">
		<div class="col-sm-6">
			<div class="form-group">
				<label class="col-sm-6 control-label">Foreign Language</label>
				<div class="col-sm-6">
					{{ Form::select('foreign_language_id[]'
					   , $languages->lists('name', 'id')
					   , null
					   , array('class' => 'form-control', 'id' => 'foreign_language_id')) }}
	            </div>				
			</div>
	        <div class="col-sm-12 text-right">
				<button class="btn btn-danger btn-sm" onclick="onDeleteForeignLanguage(this)">Delete Foreign Language</button>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label class="col-sm-6 control-label">Understanding</label>
				<div class="col-sm-6">
					<select class="form-control" name="understanding[]" id="understanding">
						<option value="5">5</option>	
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>	
					</select>
	            </div>		
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Speaking</label>
				<div class="col-sm-6">
					<select class="form-control" name="speaking[]" id="speaking">
						<option value="5">5</option>	
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>	
					</select>
	            </div>				
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Writting</label>
				<div class="col-sm-6">
					<select class="form-control" name="writting[]" id="writting">
						<option value="5">5</option>	
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>	
					</select>
	            </div>				
			</div>
		</div>                  
	</div>        
</div>
<!--  -->

<!-- Model Div for Benefit -->
<div id="clone_div_benefit" class="hidden">
	<div class="form-group">
		<label class="col-sm-3 control-label">Benefit Name</label>
		<div class="col-sm-6">
			<input class="form-control" type="text" name="benefit_name[]" id="benefit_name">
		</div>
		<div class="col-sm-3 text-right">
			<button class="btn btn-primary btn-sm" onclick="onAddBenefit('')" type="button">Add</button>
			<button class="btn btn-danger btn-sm" onclick="onDeleteBenefit(this)">Delete</button>
		</div>	         	
	</div>
</div>
<!--  -->


@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    @include('js.admin.job.edit')
@stop

@stop
