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

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">Group Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Group</span>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<span>Marketing</span>
			</li>
		</ul>
	</div>
</div> 

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Create Marketing
		</div>
	</div>
	<div class="portlet-body form">
        <form class="form-horizontal form-bordered form-row-stripped" role="form" method="post" action="{{ URL::route('admin.group.doMarketing') }}">
            <input type="hidden" name="group_id" value="{{ $group->id }}"/>
            <div class="form-body">
                @foreach ([
                    'subject' => 'Subject',
                    'name' => 'Name',
                    'body' => 'body',
                    'reply_name' => 'Reply Name',
                    'reply_email' => 'Reply Email',
                ] as $key => $value)
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
                    <div class="col-sm-9">
                        @if ($key == 'body')
                        <textarea class="form-control" name="{{ $key }}" rows="7"></textarea>
                        @elseif ($key == 'subject')
                        <input type="text" class="form-control" name="{{ $key }}" value="{{ SITE_NAME }}">
                        @elseif ($key == 'name')
                        <input type="text" class="form-control" name="{{ $key }}" value="{{ $group->name }}">
                        @elseif ($key == 'reply_name')
                        <input type="text" class="form-control" name="{{ $key }}" value="{{ REPLY_NAME }}">
                        @elseif ($key == 'reply_email')
                        <input type="text" class="form-control" name="{{ $key }}" value="{{ REPLY_EMAIL }}">
                        @else
                        <input type="text" class="form-control" name="{{ $key }}"> 
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="form-actions fluid">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Send Message
                    </button>
                    <a href="{{ URL::route('admin.group') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>            
        </form>
    </div>
</div>
@stop

@stop
