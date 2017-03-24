@extends('main')

@section('styles')
    {{ HTML::style('/assets/css/style_bootstrap.css') }}
    {{ HTML::style('/assets/css/style_common.css') }}
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    
	{{ HTML::style('/assets/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN THEME STYLES -->
	{{ HTML::style('/assets/metronic/assets/global/css/components.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/style.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/pages/css/style-revolution-slider.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/style-responsive.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/themes/blue.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/custom.css') }}
	<!-- END THEME STYLES -->
	{{ HTML::style('/assets/css/style_interview.css') }}
    
    <!-- BEGIN JENI -->
    {{ HTML::style('/assets/fullcalendar/fullcalendar.css') }}
    <style>
    	body {
    		margin: 40px 10px;
    		padding: 0;
    		font-family: "Lucida Grande", Helvetica,Arial, Verdana,sans-serif;
    		font-size: 14px;
    	}
    </style>
    <!-- END JENI -->    
@stop

@section ('body')
<div class="background-face-interview"></div>

<div class="container margin-top-normal margin-bottom-normal background-calendar">
    <div class="row margin-top-normal">
        <div class="col-sm-2 col-sm-offset-1">
            <img src="{{ HTTP_LOGO_PATH.$company->logo}}" style="width: 100%;">
        </div>
        <div class="col-sm-8">
            <h3><b>{{ $company->name }}</b></h3>
            <p><i>You can book the interview here. Don't miss the chance to get the job.</i></p>
            <div class="pull-right">
                <a href="{{ URL::route('user.company.view', $company->slug) }}" target="_blank">
                    <i class="fa fa-building"></i> View Company Profile
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 margin-top-lg margin-bottom-lg">
            <div id='js-div-calendar'></div>
        </div>
    </div>
</div>
    
<div class="modal fade" id="js-div-modal">
    <input type="hidden" id="js-hidden-start">
    <input type="hidden" id="js-hidden-company-id" value="{{ $company->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Booking Interview</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="js-text-name" placeholder="Enter Your Name">
                    </div>                    
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="js-text-email" placeholder="Your Email Address">
                    </div>
                </div>
                <div class="row margin-top-sm">
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="js-text-title" placeholder="Enter Title">
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" id="js-select-duration">
                            <option value=15>15 Min</option>
                            <option value=30>30 Min</option>
                            <option value=45>45 Min</option>
                            <option value=60>60 Min</option>
                        </select>
                    </div>
                </div>
                <div class="row margin-top-sm">
                    <div class="col-sm-12">
                        <textarea class="form-control" id="js-textarea-description" placeholder="Enter Description Here..." rows="12"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="js-btn-submit">Submit</button>
            </div>
        </div>
    </div>
</div>    
    
@stop

@section('scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-1.11.0.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}
	
	<!-- BEGIN JENI -->
	{{ HTML::script('/assets/fullcalendar/lib/moment.min.js') }}
    {{ HTML::script('/assets/fullcalendar/fullcalendar.min.js') }}
    <!-- END JENI -->
<script>
	$(document).ready(function() {
	    var events = [];
		@foreach ($faceInterviews as $key => $value)
		    events[{{ $key }}] = { id : '{{ $value->id }}'
		                         , title : '{{ $value->title }}'
		                         , start : '{{ $value->interview_date." ".$value->start_at }}'
		                         , end : '{{ $value->interview_date." ".$value->end_at }}' };
		@endforeach
		
		
		$('#js-div-calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'agendaWeek, agendaDay'
			},
			allDaySlot: false,
			eventLimit: true,
			defaultView: 'agendaWeek',
			minTime: "{{ \SH\Models\Setting::findByCode('CD09')->value }}",
			maxTime: "{{ \SH\Models\Setting::findByCode('CD10')->value }}",
			slotDuration: "{{ \SH\Models\Setting::findByCode('CD11')->value }}",
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				$("#js-hidden-start").val(start);
				
    			$("#js-select-duration").val(15);
    		    $("#js-text-title").val("");
    			$("#js-text-name").val("");
    			$("#js-text-email").val("");
    			$("#js-textarea-description").val("");
    							
				$("div#js-div-modal").modal();
			},
			events: events			
		});
		
		$("#js-btn-submit").click(function() {
			var duration = $("#js-select-duration").val() * 1;
		    var title = $("#js-text-title").val();
			var name = $("#js-text-name").val();
			var email = $("#js-text-email").val();
			var description = $("#js-textarea-description").val();
			
			var start = new Date($("#js-hidden-start").val());
			start = start.toISOString();
			var interview_date = start.substr(0, 10);
			start = start.substr(11, 8);

			var end = new Date($("#js-hidden-start").val());
			end.setMinutes(end.getMinutes() + duration);
			
			end = end.toISOString();
			end = end.substr(11, 8);

			var end = new Date($("#js-hidden-start").val());
			end.setMinutes(end.getMinutes() + duration);
			end_at = end.toUTCString()
			
			end = end.toISOString();
			end = end.substr(11, 8);			
			
			var company_id = $("#js-hidden-company-id").val();

			if (name == "") {
				bootbox.alert("Please enter name");
				return;
			}

			if (!validateEmail(email)) {
				bootbox.alert("Email address is invalid");
				return;				
			}

			if (email == "") {
				bootbox.alert("Please enter email");
				return;
			}

			if (title == "") {
				bootbox.alert("Please enter title");
				return;
			}			

			if (description == "") {
				bootbox.alert("Please enter description");
				return;
			}
	        $.ajax({
	            url: "{{ URL::route('interview.face.async.createBooking') }}",
	            dataType : "json",
	            type : "POST",
	            data : {name: name, email: email, title: title, description: description, start_at: start, end_at: end, interview_date: interview_date, company_id: company_id},
	            success : function(data) {
	    			var eventData = { title: title, start: $("#js-hidden-start").val(), end: end_at };
	    			$('#js-div-calendar').fullCalendar('renderEvent', eventData, true);
	    			$('#js-div-calendar').fullCalendar('unselect');
	    			$("div#js-div-modal").modal('hide');
	            }
	        });
		});
	});
    function validateEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }	
</script>
@stop

@stop