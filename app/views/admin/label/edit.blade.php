@extends('admin.layout')

@section('custom-styles')

	{{ HTML::style('/assets/css/bootstrap-colorpicker.min.css') }}
	{{ HTML::style('/assets/css/docs.css') }}

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
		<h3 class="page-title">Country Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Label</span>
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
			<i class="fa fa-pencil-square-o"></i> Edit Label
		</div>
	</div>
    <div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.label.store') }}">
        
        	<input type="hidden" name="label_id" value="{{ $label->id }}">
        
            @foreach ([
                'name' => 'Name',
                'color' => 'Color',
                'created_at' => 'Created At',
                'updated_at' => 'Updated At',
            ] as $key => $value)
                @if ($key == 'color')
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-10">
                            <div class="input-group demo2">
                                <input type="text" value="{{ $label->{$key} }}" class="form-control" name="color" />
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-10">
                            @if ($key === 'created_at' || $key === 'updated_at')
                                <p class="form-control-static">{{ $label->{$key} }}</p>
                            @else
                            <input type="text" class="form-control" name="{{ $key }}" value="{{ $label->{$key} }}">
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
			
			<div class="form-group">
			  <div class="col-sm-offset-2 col-sm-10 text-center">
				  <button class="btn btn-success">
					  <span class="glyphicon glyphicon-ok-circle"></span> Save
				  </button>
				  <a href="{{ URL::route('admin.label') }}" class="btn btn-primary">
					  <span class="glyphicon glyphicon-share-alt"></span> Back
				  </a>
			  </div>
			</div>        

        </form>
    </div>
</div>
@stop


@section('custom-scripts')
    {{ HTML::script('/assets/js/bootstrap-colorpicker.js') }}
    {{ HTML::script('/assets/js/docs.js') }}

    @include('js.admin.label.create')
@stop

@stop
