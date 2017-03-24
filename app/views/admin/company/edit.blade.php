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
		<h3 class="page-title">Company Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Company</span>
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
			<i class="fa fa-pencil-square-o"></i> Edit Company
		</div>
	</div>
    <div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.company.store') }}">
        
        	<input type="hidden" name="company_id" value="{{ $company->id }}">
	        <input type="hidden" name="lat" id="lat" value="{{ $company->lat }}">
	        <input type="hidden" name="lng" id="lng" value="{{ $company->long }}">
	        
            @foreach ([
                'email' => 'Email',
                'password' => 'Password',
                'name' => 'Name',
                'tag' => 'Tag Line',
                'year' => 'Foundation Year',
                'teamsize_id' => 'Team size',
                'category_id' => 'Category',
                'city_id' => 'Location',
                'description' => 'Description',
                'logo' => 'Logo',
                'expertise' => 'Expertise',
                'address' => 'Address',
                'phone' => 'Phone number',
                'website' => 'Website',
                'facebook' => 'Facebook',
                'linkedin' => 'Linkedin',
                'twitter' => 'Twitter',
                'google' => 'Google+',
                'is_published' => "Publish company's email",
            ] as $key => $value)
            <div class="form-group">
            
            	@if ($key != 'is_published')
                	<label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                @else 
                	<label class="col-sm-2 control-label"></label>
                @endif
                
                <div class="col-sm-10">
                    @if ($key == 'city_id')                        
		                <select class="form-control" name="city_id">
						    @foreach ($cities as $city)
								<option value="{{ $city->id }}" {{ ($company->city_id == $city->id) ? 'selected':'' }}>{{ $city->name }}</option>		
							@endforeach
						</select>
                    @elseif ($key == 'category_id') 
		                <select class="form-control" name="category_id">
						    @foreach ($categories as $category)
								<option value="{{ $category->id }}" {{ ($company->category_id == $category->id) ? 'selected':'' }}>{{ $category->name }}</option>		
							@endforeach
						</select>  
                    @elseif ($key == 'teamsize_id')
                    	<select class="form-control" name="teamsize_id" id="teamsize_id">
                    		@foreach ($teamsizes as $item)
                    		<option value="{{ $item->id }}" {{ ($company->teamsize_id == $item->id) ? 'selected':'' }}>{{ $item->min." ~ ".$item->max }}</option>
                    		@endforeach
                    	</select>
                    @elseif ($key == 'is_published')
                    	{{ Form::checkbox($key, $company->is_published, $company->is_published ,['class' => 'is_published']) }}
                    	<label class="control-checkbox">{{ Form::label($key, $value) }}</label>
                    @elseif ($key == 'logo')
                        {{ Form::file($key, ['class' => 'form-control']) }}  
                    @elseif ($key == 'description') 
                    	{{ Form::textarea($key, $company->description, ['class' => 'form-control']) }}
                    @elseif ($key == 'password')
                    	{{ Form::password($key, array('class' => 'form-control')) }}       
                    @else
                        {{ Form::text($key, $company->{$key}, ['class' => 'form-control']) }}
                    @endif
                </div>

            </div>
            @endforeach
            
            <div id="skill_list"></div>
            
            <div class="form-group  map-container">
            	<label class="col-sm-2 control-label">Google Maps Address</label>
				<div class="col-sm-10">
                	<input class="form-control" type="text" id="latlng" disabled="disabled" value="{{ $company->lat.', '.$company->long }}">
                </div>            	
            </div>
           
            <div class="form-group">
            	<label class="col-sm-2 control-label"></label>
				<div class="col-sm-10">
					<div id="mapdiv"></div>
				</div>            	
            </div>
            
            
            <div id="service_list">
            
            </div>


            <div id="follow_company_list">

            </div>

            
            <div class="form-group">
				<label class="col-sm-2 control-label">Active</label>
				<div class="col-sm-10">
                	{{ Form::select('is_active'
               			, array('0' => 'No', '1' => 'Yes')
                        , $company->is_active
                        , array('class' => 'form-control')) }}
				</div>            
            </div>
            
			
			<div class="form-group">
			  <div class="col-sm-offset-2 col-sm-10 text-center">
				  <button class="btn btn-success">
					  <span class="glyphicon glyphicon-ok-circle"></span> Save
				  </button>
				  <a href="{{ URL::route('admin.company') }}" class="btn btn-primary">
					  <span class="glyphicon glyphicon-share-alt"></span> Back
				  </a>
			  </div>
			</div>        

        </form>
    </div>
</div>




<!-- Model div of service -->
<div id="clone_div_service" class="hidden">
	<input type="hidden" name="service_id[]" id="service_id">
	<input type="hidden" name="company_service_id[]" id="company_service_id">
	<div class="form-group">
		<label class="col-sm-2 control-label">Service</label>
		<label class="col-sm-2 control-label">Name</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="service_name[]" id="service_name">
		</div>
		<label class="col-sm-2 control-label">Icon Code</label>
		<div class="col-sm-2">
			<input class="form-control" type="text" name="icon_code[]" readonly id="icon_code">
		</div>                    
	</div>  
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<label class="col-sm-2 control-label">Description</label>
		<div class="col-sm-8">
			<textarea class="form-control" name="service_description[]" id="service_description"></textarea>
		</div>            	
	</div>
	<div class="form-group">
		<div class="col-sm-12 text-right">
			<button class="btn btn-primary btn-sm" onclick="onAddService('', '', '', '')" type="button">Add</button>
			<button class="btn btn-danger btn-sm" onclick="onDeleteService(this)">Delete</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<hr/>
		</div>
	</div>	            
</div>
<!--  -->

<!-- Model Div for Follow Company -->
<div id="clone_div_followCompany" class="hidden">
    <div class="row margin-bottom-sm">
        <div class="col-sm-2 col-sm-offset-1">
            <label for="" class="control-label">Follow Company:</label>
        </div>
        <div class="col-sm-5">
            <input class="form-control" name="follow_company_name[]" type="text" value="" id="follow_company_name">
        </div>
        <div class="col-sm-3 col-sm-offset-1 text-right">
            <button class="btn btn-primary btn-sm" onclick="onAddFollowCompany('')" type="button">Add</button>
            <button class="btn btn-danger btn-sm" onclick="onDeleteFollowCompany(this)">Delete</button>
        </div>
    </div>
</div>
<!-- EOF for Follow Company -->

@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    @include('js.admin.company.edit')
@stop

@stop
