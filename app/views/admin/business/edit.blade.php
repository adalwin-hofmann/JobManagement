@extends('admin.layout')

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

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Edit Company</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.business.store') }}">
        
        	<input type="hidden" name="business_id" value="{{ $business->id }}">
        
            @foreach ([
                'name' => 'Name',
                'is_approved' => 'Is Approved',
            ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-10">
					@if ($key === 'is_approved')
                        {{ Form::select($key
                           , array('No', 'Yes')
                           , $business->{$key}
                           , array('class' => 'form-control')) }}                    	        
                    @else
                        {{ Form::text($key, $business->{$key}, ['class' => 'form-control']) }}
                    @endif
                </div>
            </div>
            @endforeach
			
			<div class="form-group">
			  <div class="col-sm-offset-2 col-sm-10 text-center">
				  <button class="btn btn-success">
					  <span class="glyphicon glyphicon-ok-circle"></span> Save
				  </button>
				  <a href="{{ URL::route('admin.business') }}" class="btn btn-primary">
					  <span class="glyphicon glyphicon-share-alt"></span> Back
				  </a>
			  </div>
			</div>        

        </form>
    </div>
</div>
@stop

@stop
