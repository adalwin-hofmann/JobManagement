<script>

var skills = [];


<?php
	$i = 0;
    foreach ($skills as $skill) {?>
    	skills[<?php echo $i++;?>] = '<?php echo $skill->name;?>';
<?php } ?>

function showMsgModal(obj) {
    var userId = $(obj).attr('data-id');
    var target = 'div#msgModal' + userId;

    $(target).modal();
}

$("button#js-btn-send-message").click(function() {

	var userId = $(this).attr('data-id');
	var target = 'div#msgModal' + userId
    var message = $(this).parents(target).eq(0).find('textarea#txt_message').eq(0).val();

    $("div#loadingModal").modal('show');
    $.ajax({
        url:"{{ URL::route('agency.user.async.sendMessage') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, message : message},
        success : function(data){
            $("div#loadingModal").modal('hide');
        	bootbox.alert('Message has been sent successfully.', function(){

        	});
        	window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
    $(target).modal('hide');
});

$(document).ready(function() {
    $('input.typeahead').typeahead({
                                    name: 'skill_name',
                                    local: skills
                                });

    $('div#js-div-loading').css('margin-top', (window.innerHeight - 89) / 2 - 50 + 'px');

});



function showSaveButton(obj) {
    $(obj).parents('div.row').eq(0).find('a#js-a-save-rate').fadeIn("normal");
}


function saveUserScore(obj) {
    var userId = $(obj).attr('data-id');
    var target = 'input#input-rate-' + userId;
    var score = $(target).val();

    $.ajax({
        url:"{{ URL::route('agency.user.async.saveRate') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, score : score},
        success : function(data){
            bootbox.alert (data.msg);
            window.setTimeout(function(){
                bootbox.hideAll();
                $(obj).parents('div.row').eq(0).find('a#js-a-save-rate').fadeOut("normal");
            }, 2000);
        }
    });
}


$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		title: function() {
				return $(this).attr('data-description');
			}
	})
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



$("button#js-btn-video-interview").click(function() {
    var userName = $(this).attr('data-name');
    var userId = $(this).attr('data-userId');

    //insert value to modal
    $('label#js-label-vi-username').html(userName);
    $('input#js-input-vi-userid').val(userId);
    $('div#viModal').modal();
});

$("button#js-btn-face-interview").click(function() {
    // Reset Value
    $("div#fiModal").attr('data-user-id', $(this).attr('data-userId'));
    $("#js-text-schedule-title, #js-text-schedule-description").val("");
    $("#js-text-invite-title, #js-text-invite-description").val("");
    $("#js-text-schedule-date").datepicker("setDate", "0");
    $("#js-select-invite-duration, #js-select-schedule-duration").val(15);
    
    $("button#js-btn-schedule").click();
    $('div#fiModal').modal();
});

$("button#js-btn-schedule").click(function() {
    $(this).addClass('btn-primary').removeClass('btn-default');
    $("button#js-btn-invite").removeClass('btn-primary').addClass('btn-default');
    $("div#js-div-invite").fadeOut();
    $("div#js-div-schedule").fadeIn();
    
});

$("button#js-btn-invite").click(function() {
    $(this).addClass('btn-primary').removeClass('btn-default');
    $("button#js-btn-schedule").removeClass('btn-primary').addClass('btn-default');
    $("div#js-div-schedule").fadeOut();
    $("div#js-div-invite").fadeIn();    
});

$(document).ready(function() {
    $('#js-text-schedule-date').datepicker({format: 'yyyy-mm-dd', setDate: new Date()}).datepicker("setDate", "0");
    $('#js-text-schedule-time').timepicker({autoclose: true, minuteStep: 5, showSeconds: false, showMeridian: false});
    $('#js-text-schedule-time').on("focus", function() {
        $(this).timepicker('showWidget');
    });
});

function sendFIInterview(obj) {
    var userId = $("div#fiModal").attr('data-user-id');
    if ($("#js-btn-schedule").hasClass('btn-primary')) {
        var date = $("#js-text-schedule-date").val();
        var time = $("#js-text-schedule-time").val();
        var duration = $("#js-select-schedule-duration").val();
        var title = $("#js-text-schedule-title").val();
        var description = $("#js-text-schedule-description").val();

        if (time == '') {
            bootbox.alert('Please select the time.');
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);            
            return;
        }

        if (title == '') {
            bootbox.alert('Please enter the title.');
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);
            return;
        }

        $.ajax({
            url: "{{ URL::route('company.interview.async.face.doSchedule') }}",
            dataType : "json",
            type : "POST",
            data : {user_id : userId, date : date, time : time, title : title, duration : duration, description : description},
            success : function(data) {
                if (data.result == 'success') {
                    bootbox.alert(data.msg);
                    window.setTimeout(function(){
                        bootbox.hideAll();
                        $('div#fiModal').modal('hide');
                    }, 2000); 
                }
            }
        });
    } else {
        var duration = $("#js-select-invite-duration").val();
        var title = $("#js-text-invite-title").val();
        var description = $("#js-text-invite-description").val();

        if (title == '') {
            bootbox.alert('Please enter the title.');
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);             
            return;
        }        
        
        $.ajax({
            url: "{{ URL::route('company.interview.async.face.doInvite') }}",
            dataType : "json",
            type : "POST",
            data : {user_id : userId, title : title, duration : duration, description : description},
            success : function(data) {
                if (data.result == 'success') {
                    bootbox.alert(data.msg);
                    window.setTimeout(function(){
                        bootbox.hideAll();
                        $('div#fiModal').modal('hide');
                    }, 2000);                     
                }
            }
        });        
    }
}

function sendVIInterview(obj) {

    $(obj).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');

    var userId = $('input#js-input-vi-userid').val();
    var templateId = $('select#js-vi-template-id').val();
    var questionnaireId = $('select#js-vi-questionnaire-id').val();
    var expireAt = $('input#vi-expiration').val();
    var subject = $('input#js-vi-template-title').val();
    var description = $('textarea#js-vi-template-description').val();

    $.ajax({
        url: "{{ URL::route('agency.user.async.sendInterview') }}",
        dataType : "json",
        type : "POST",
        data : {user_id: userId, template_id: templateId, questionnaire_id: questionnaireId, expire_at: expireAt, subject: subject, description: description},
        success : function(data) {
            $(obj).html('Send');
            if (data.result == 'success') {
                bootbox.alert (data.msg, function() {
                    location.reload();
                });
            }
        }
    });
};

$('button#js-btn-addToCandidates').click(function() {
    var userId = $(this).attr('data-id');
    $(this).addClass('disabled');

    $.ajax({
        url: "{{ URL::route('agency.user.async.addToCandidate') }}",
        dataType : "json",
        type : "POST",
        data : {user_id: userId},
        success : function(data) {
            if (data.result == 'success') {

            }
        }
    });
});

</script>