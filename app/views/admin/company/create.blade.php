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
				<span>Add</span>
			</li>
		</ul>
	</div>
</div>

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Create Company
		</div>
	</div>
    <div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.company.store') }}" enctype="multipart/form-data">
	        
	        <input type="hidden" name="finished" value = "1">
	        <input type="hidden" name="lat" value="" id="lat">
	        <input type="hidden" name="lng" value="" id="lng">
	        
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
                        {{ Form::select($key
                           , $cities->lists('name', 'id')
                           , null
                           , array('class' => 'form-control')) }} 
                    @elseif ($key == 'category_id') 
                        {{ Form::select($key
                           , $categories->lists('name', 'id')
                           , null
                           , array('class' => 'form-control')) }}   
                    @elseif ($key == 'teamsize_id')
                    	<select class="form-control" name="teamsize_id" id="teamsize_id">
                    		@foreach ($teamsizes as $item)
                    		<option value="{{ $item->id }}">{{ $item->min." ~ ".$item->max }}</option>
                    		@endforeach
                    	</select>
                    @elseif ($key == 'is_published')
                    	{{ Form::checkbox($key, 0, null, ['class' => 'is_published']) }}
                    	<label class="control-checkbox">{{ Form::label($key, $value) }}</label>
                    @elseif ($key == 'logo')
                        {{ Form::file($key, ['class' => 'form-control']) }}  
                    @elseif ($key == 'description') 
                    	{{ Form::textarea($key, null, ['class' => 'form-control']) }}
                    @elseif ($key == 'password')
                    	{{ Form::password($key, array('class' => 'form-control')) }}       
                    @else
                        {{ Form::text($key, null, ['class' => 'form-control']) }}
                    @endif
                </div>

            </div>
            @endforeach
            
            <div class="form-group  map-container">
            	<label class="col-sm-2 control-label">Google Maps Address</label>
				<div class="col-sm-10">
                	<input class="form-control" type="text" id="latlng" disabled="disabled">
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
			<button class="btn btn-primary btn-sm" onclick="onAddService('', '', '')" type="button">Add</button>
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

@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    @include('js.admin.company.create')
@stop

@stop
