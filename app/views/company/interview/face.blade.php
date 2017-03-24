@extends('company.layout')

@section('custom-styles')
    <!-- BEGIN JENI -->
    {{ HTML::style('/assets/fullcalendar/fullcalendar.css') }}
    <style>
        #js-div-calendar {
        	max-width: 900px;
        	margin: 0 auto;
        	background: #FFF;
        	padding: 10px;
        }
        
        .fc-event{
            cursor: pointer;
        }        
    </style>
    <!-- END JENI --> 
@stop

@section('body')
<div class="container margin-top-lg margin-bottom-lg">
    <div class="row margin-top-normal">
        <div class="col-sm-12">
            <div id='js-div-calendar'></div>
        </div>
    </div>
    
    <!-- div class="row margin-top-normal">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-block btn-lg">Sync with gCalendar</button>
                </div>            
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-block btn-lg">Sync with iCal</button>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-block btn-lg">Sync with Outlook Calendar</button>
                </div>                                
            </div>
        </div>
    </div -->
</div>


<div id="js-div-userview" style="display: none;"></div>

<div class="modal fade" id="js-div-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Face Interview</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <span><b>Name : </b></span>
                        <a class="pointer" id="js-a-name" data-userId="" onclick="showUserView(this)"></a>
                    </div>                    
                    <div class="col-sm-6">
                        <span><b>Email : </b></span>
                        <span id="js-span-email"></span>
                    </div>
                </div>
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


@section('custom-scripts')
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
		                         , end : '{{ $value->interview_date." ".date("H:i:00", strtotime($value->start_at) + $value->duration * 60) }}'
	                             , backgroundColor: "{{ ($company->parent->setting) ? $company->parent->setting->slot_background : DEFAULT_SLOT_BACKGROUND }}" };
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
			events: events,
			eventClick: function(calEvent) {
		        $.ajax({
		            url: "{{ URL::route('company.interview.async.face.loadBookingInfo') }}",
		            dataType : "json",
		            type : "POST",
		            data : {id : calEvent.id},
		            success : function(data) {
			            // user_id = data.id
			            $("#js-a-name").html(data.name);
			            $("#js-a-name").attr('data-userId', data.id);
			            $("#js-span-email").text(data.email);
			            $("#js-span-title").text(data.title);
			            $("#js-span-start-at").html(data.interview_date + " " + data.start_at);
			            $("#js-p-description").html(data.description);
		                $("#js-div-modal").modal();
		            }
		        });
		        				
			}
		});
	});



    function showUserView(obj) {
        var userId = $(obj).attr('data-userId');
        $("#js-div-modal").modal('hide');
        $.ajax({
            url:"{{ URL::route('company.user.async.view') }}",
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
            url:"{{ URL::route('company.user.async.sendInvite') }}",
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
