@extends('user.layout')

@section('body')
<main class="bs-docs-masthead" role="main" style="min-height: 560px;">
	<div class="background-dashboard"></div>        
    <div class="container">
    	<div class="margin-top-lg"></div>
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home color-white"> Message Center</h2>
        </div>

        <div class="col-sm-10 col-sm-offset-1 margin-bottom-sm" style="background: #FAFAFA; border: 1px solid #EEE; padding: 15px;" >
    	    <textarea class="form-control" rows="5" id="txt_message" placeholder="Please enter message here..."></textarea>
    	    <button class="btn btn-primary margin-top-sm pull-right" id="js-btn-send-message" data-job-id="{{ $jobId }}" data-company-id="{{ $companyId }}">{{ trans('job.send') }}</button>
    	    <div class="clearfix"></div>
    	    <hr/>        
            @foreach ($messages as $key => $value)
    		<div class="row margin-top-sm">
    			@if ($value->is_company_sent)
    			<div class="col-sm-2 text-center">
    			    <img src="{{ HTTP_LOGO_PATH.$value->company->logo }}" style="width: 50%;" class="img-rounded"/>
    		        <div class="margin-top-xs">{{ $value->company->name }}</div>
    			</div>
    			<div class="col-sm-10">
    				<p>
    				    {{ $value->description}}
                        <span class="color-gray-dark font-size-xs">
    					    <i>( {{ $value->created_at }} )</i>
    				    </span>				    
    			    </p>
    			</div>
    			@else
    			<div class="col-sm-10 text-right">
    				<p>{{ $value->description }}</p>
                    <span class="color-gray-dark font-size-xs">
    				    <i>( {{ $value->created_at }} )</i>
    			    </span>
    			</div>			
    			<div class="col-sm-2 text-center">
    			    <img src="{{ HTTP_PHOTO_PATH.$value->user->profile_image }}" style="width: 50%;" class="img-rounded"/>
    				<div class="margin-top-xs">
    				    <a href="{{ URL::route('user.view', $value->user->slug) }}">
    				        {{ $value->user->name }}
    				    </a>
    			    </div>
    			</div>
    			@endif
    		</div>
    		@endforeach
        </div>

    </div>
</main>

@section('custom-scripts')
<script>
$(document).ready(function() {    
    $("button#js-btn-send-message").click(function() {
        var message = $('textarea#txt_message').val();
        var jobId = $(this).attr('data-job-id');
        var companyId = $(this).attr('data-company-id');
        if (message == "") {
            bootbox.alert("Please enter the message.");
            return;
        }

        $(this).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');
        
        $.ajax({
            url:"{{ URL::route('user.job.async.sendMessage') }}",
            dataType : "json",
            type : "POST",
            data : {message : message, job_id : jobId, company_id: companyId},
            success : function(data){
                $(this).html('Send');
            	bootbox.alert(data.msg, function(){
                	window.location.reload();
        	    });
            }
        });        
    });

});
</script>
@stop

@stop

