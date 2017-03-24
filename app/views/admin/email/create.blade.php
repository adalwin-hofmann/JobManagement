@extends('admin.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-summernote/summernote.css') }}
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
		<h3 class="page-title">Email Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Email</span>
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
			<i class="fa fa-pencil-square-o"></i> Create Email
		</div>
	</div>
    <div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('admin.email.store') }}">
            @foreach ([
                'code' => 'Code',
                'name' => 'Name',
                'subject' => 'Subject',
                'body' => 'Body',
                'reply_name' => 'Reply Name',
                'reply_email' => 'Reply Email',
            ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-10">
                    @if ($key === 'body')
                        <div id="js-div-body"></div>
                    @else
                        <input type="text" class="form-control" name="{{ $key }}">
                    @endif
                </div>
            </div>
            @endforeach
            <input type="hidden" name="body"/>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success" onclick="return validate();">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('admin.email') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}
{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}
{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-markdown/lib/markdown.js') }}
{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}
{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-summernote/summernote.min.js') }}

<script>
$(document).ready(function() {
    $('#js-div-body').summernote({
        height: 300,
        tabsize: 4,
        toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['insert', ['link']],
                ['view', ['codeview']],
            ]
    });
});

function validate() {
    $("input[name='body']").val($('#js-div-body').code());
    return true;
}
</script>

@stop

@stop
