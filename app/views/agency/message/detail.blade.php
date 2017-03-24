@extends('agency.layout')

@section('body')

<main class="bs-docs-masthead" role="main">
<div class="background-dashboard"></div>
<div class="container">
	<div class="margin-top-50"></div>
	<div class="row text-center margin-top-normal margin-bottom-normal">
		<h2 class="home color-white">Message Center</h2>
	</div>
	
	<div class="col-sm-10 col-sm-offset-1 margin-bottom-sm" style="background: #FAFAFA; border: 1px solid #EEE; padding: 15px;" >
	    <textarea class="form-control" rows="5" id="txt_message" placeholder="Please enter message here..."></textarea>
	    <button class="btn btn-primary margin-top-sm pull-right" id="js-btn-send-message" data-user-id="{{ $userId }}" data-job-id="{{ $jobId }}">{{ trans('job.send') }}</button>
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
				    <a onclick="showUserView(this)" data-userId="{{ $value->user->id }}" class="username">
				        {{ $value->user->name }}
				    </a>
			    </div>
			</div>
			@endif
		</div>
		@endforeach
	</div>
</div>

<div id="js-div-userview" style="display: none;">
</div>

</main>

@section('custom-scripts')
<script>
$(document).ready(function() {    
    $("button#js-btn-send-message").click(function() {
    	var userId = $(this).attr('data-user-id');
        var message = $('textarea#txt_message').val();
        var jobId = $(this).attr('data-job-id');
        if (message == "") {
            bootbox.alert("Please enter the message.");
            return;
        }

        $(this).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');
        $.ajax({
            url:"{{ URL::route('agency.job.async.sendMessage') }}",
            dataType : "json",
            type : "POST",
            data : {user_id : userId, message : message, job_id : jobId},
            success : function(data){
                $(this).html('Send');
            	bootbox.alert(data.msg, function(){
                	
        	    });
            	window.setTimeout(function(){
                    window.location.reload();
                }, 1000);
            }
        });        
    });

});


function showUserView(obj) {
    var userId = $(obj).attr('data-userId');
    $.ajax({
        url:"{{ URL::route('agency.user.async.view') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId},
        success : function(data){
            if (data.result == 'success') {
                $('div#js-div-userview').empty();
                $('div#js-div-userview').html(data.userView);
                $('div#js-div-userview').fadeIn('normal');
            }
        }
    });
}

function hideUserView() {
    $('div#js-div-userview').fadeOut('normal');
}

function sendInvite(obj) {
    var userId = $(obj).attr('data-userid');
    var jobId = $(obj).attr('data-jobid');

    $(obj).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');

    $.ajax({
        url:"{{ URL::route('agency.user.async.sendInvite') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, job_id: jobId},
        success : function(data){
            if (data.result == 'success') {
                $(obj).html('Invited');
                $(obj).addClass('disabled');
            }
        }
    });
}


$(document).mouseup(function (e)
{
    var container = $("div#js-div-user-detail-view");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        hideUserView();
    }
});


</script>
@stop

@stop
