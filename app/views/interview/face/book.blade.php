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
    	
    	.fc-bg, .fc-title, .fc-time {
    	    cursor: pointer;
    	}
    	
    	.fc-event {
    	    background: {{ ($company->parent->setting) ? $company->parent->setting->slot_background : DEFAULT_SLOT_BACKGROUND }}
    	}
    </style>
    <!-- END JENI -->    
@stop

@section ('body')
<div class="background-face-interview"></div>

<div class="container margin-top-normal margin-bottom-normal background-calendar">
    <div class="row margin-top-normal">
        <div class="col-sm-2 col-sm-offset-1">
            <img src="{{ HTTP_LOGO_PATH.$faceInterview->company->logo}}" style="width: 100%;">
        </div>
        <div class="col-sm-8">
            <h3><b>{{ $faceInterview->company->name }}</b></h3>
            <p><i>You can book the interview here. Don't miss the chance to get the job.</i></p>
            <div class="pull-right">
                <a href="{{ URL::route('user.company.view', $faceInterview->company->slug) }}" target="_blank">
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
    <input type="hidden" id="js-hidden-face-interview-id" value="{{ $faceInterview->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Face To Face Interview Booking</h4>
            </div>
            <div class="modal-body">
                <div class="row margin-top-sm">
                    <div class="col-sm-2">
                        <label class="form-control-static">
                            <b>Title : </b>
                        </label>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="js-text-title" placeholder="Enter Title" value="{{ $faceInterview->title }}">
                        <input type="hidden" id="js-hidden-name" value="{{ $faceInterview->user->name }}">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control readonly" id="js-text-duration" readonly placeholder="Enter Duration" value="{{ $faceInterview->duration }} Min">
                        <input type="hidden" id="js-hidden-duration" value="{{ $faceInterview->duration }}">
                    </div>
                </div>
                <div class="row margin-top-sm">
                    <div class="col-sm-12">
                        <textarea class="form-control" id="js-textarea-description" placeholder="Enter Description Here..." rows="12">{{ $faceInterview->description }}</textarea>
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

<div class="modal fade" id="js-div-event-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Face To Face Interview</h4>
            </div>
            <div class="modal-body">
                <div class="row margin-top-sm">
                    <div class="col-sm-6">
                        <span><b>Title : </b></span>
                        <span id="js-span-title"></span>
                    </div>
                    <div class="col-sm-6">
                        <span><b>Start At : </b></span>
                        <span id="js-span-start-at" class="font-size-sm"></span>
                    </div>
                </div>
                <div class="row margin-top-sm">
                    <div class="col-sm-12">
                        <p><b>Description : </b></p>
                        <p id="js-p-description"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
		                         , title : '{{ $value->user->name."-".$value->title }}'
		                         , start : '{{ $value->interview_date." ".$value->start_at }}'
		                         , end : '{{ $value->interview_date." ".date("H:i:00", strtotime($value->start_at) + $value->duration * 60) }}'};
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
			minTime: "{{ ($company->parent->setting) ? $company->parent->setting->start_at : DEFAULT_START_AT }}",
			maxTime: "{{ ($company->parent->setting) ? $company->parent->setting->end_at : DEFAULT_END_AT }}",
			slotDuration: "{{ \SH\Models\Setting::findByCode('CD11')->value }}",
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				$("#js-hidden-start").val(start);
				$("div#js-div-modal").modal();
			},
			events: events,
			eventClick: function(calEvent) {
		        $.ajax({
		            url: "{{ URL::route('company.interview.async.face.loadBookingInfo') }}",
		            dataType : "json",
		            type : "POST",
		            data : {id : calEvent.id},
		            success : function(data) {
			            $("#js-span-title").text(data.title);
			            $("#js-span-start-at").html(data.interview_date + " " + data.start_at);
			            $("#js-p-description").html(data.description);
		                $("#js-div-event-modal").modal();
		            }
		        });
		        				
			}			
		});
		
		$("#js-btn-submit").click(function() {
			var start = new Date($("#js-hidden-start").val());
			start = start.toISOString();
			var interview_date = start.substr(0, 10);
			start = start.substr(11, 8);

			var duration = $("#js-hidden-duration").val()

			var end = new Date($("#js-hidden-start").val());
			end.setTime(end.getTime() + duration * 60 * 1000);
			var end_at = end.toUTCString();
		    
			var title = $("#js-hidden-name").val() + "-" + $("#js-text-title").val();

			var face_interview_id = $("#js-hidden-face-interview-id").val();

	        $.ajax({
	            url: "{{ URL::route('interview.face.async.createBooking') }}",
	            dataType : "json",
	            type : "POST",
	            data : {start_at: start, interview_date: interview_date, face_interview_id: face_interview_id, title : $("#js-text-title").val(), description : $("#js-textarea-description").val()},
	            success : function(data) {
	    			var eventData = { title: title, start: $("#js-hidden-start").val(), end: end_at };
	    			$('#js-div-calendar').fullCalendar('renderEvent', eventData, true);
	    			$('#js-div-calendar').fullCalendar('unselect');
	    			$("div#js-div-modal").modal('hide');
	            }
	        });
		});
	});
</script>
@stop

@stop