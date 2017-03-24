
<script>


var viTempTitle = [];
var viTempDesc = [];

<?php
    foreach ($viTemplates as $item) {
        ?>
            viTempTitle['{{ $item->id }}'] = '{{ $item->title }}';
            viTempDesc['{{ $item->id }}'] = '{{ substr(json_encode($item->description), 1, strlen(json_encode($item->description)) - 2) }}';
        <?php
    }
?>


function showAddModal() {

	$('div#addModal').modal();
	
}

function showEditModal(obj) {
	var name = $(obj).attr('data-name');
	var email = $(obj).attr('data-email');
	var userId = $(obj).attr('data-id');

	$('input#edit_name').val(name);
	$('input#edit_email').val(email);
	$('input#edit_id').val(userId);
	
	$('div#editModal').modal();
}

$('button#js-btn-modal-close').click(function() {
	$('div#js-div-add-warnning').fadeOut("normal");
});

$('button#js-btn-update-member').click(function(event) {
	event.preventDefault();

	var name = $('input#edit_name').val();
	var email = $('input#edit_email').val();
	var userId = $('input#edit_id').val();

	if (name == '' || email == '') {
		$('p#js-p-update-warnning').html('Please fill the fields.');
		$('div#js-div-update-warnning').fadeIn("normal");
	}else if (!validateEmail(email)) {
		$('p#js-p-update-warnning').html('Please input the valid email address.');
		$('div#js-div-update-warnning').fadeIn("normal");
	}else {
        $.ajax({
            url: "{{ URL::route('company.user.async.updateMember') }}",
            dataType : "json",
            type : "POST",
            data : {name : name, email : email, memberId : userId},
            success : function(data) {
               if (data.result == 'success') {
            	   message = data.msg;
				   $('div#updateModal').modal('hide');
				   location.reload();
               }else {
	           		$('p#js-p-update-warnning').html(data.msg);
	        		$('div#js-div-update-warnning').fadeIn("normal");
               }
            }
        });		
	}	
});

$('button#js-btn-add-member').click(function() {
	var name = $('input#name').val();
	var email = $('input#email').val();
	var password = $('input#password').val();
	var confirm_password = $('input#confirm_password').val();

	if (name == '' || email == '') {
		$('p#js-p-add-warnning').html('Please fill the fields.');
		$('div#js-div-add-warnning').fadeIn("normal");
	}else if (!validateEmail(email)) {
		$('p#js-p-add-warnning').html('Please input the valid email address.');
		$('div#js-div-add-warnning').fadeIn("normal");
    }else if (password == '') {
		$('p#js-p-add-warnning').html('Please input the password.');
		$('div#js-div-add-warnning').fadeIn("normal");
    }else if (password != confirm_password) {
		$('p#js-p-add-warnning').html('Password does not match.');
		$('div#js-div-add-warnning').fadeIn("normal");
	}else {
        $.ajax({
            url: "{{ URL::route('company.user.async.addMember') }}",
            dataType : "json",
            type : "POST",
            data : {name : name, email : email, password: password},
            success : function(data) {
               if (data.result == 'success') {
            	   message = data.msg;
				   $('div#addModal').modal('hide');
				   location.reload();
               }else {
	           		$('p#js-p-add-warnning').html(data.msg);
	        		$('div#js-div-add-warnning').fadeIn("normal");
               }
            }
        });		
	}
});


$("a#js-a-delete").click(function(event) {
    event.preventDefault();
    var url = $(this).attr('data-url');
    bootbox.confirm("Are you sure?", function(result) {
        if (result) {
            window.location.href = url;
        }
    });
});


function validateEmail(email){
	var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	var valid = emailReg.test(email);

	if(!valid) {
        return false;
    } else {
    	return true;
    }
}
$(document).ready(function() {
	$('div#js-div-loading').css('margin-top', (window.innerHeight - 89) / 2 - 50 + 'px');
});




function hideView(obj) {
	var target = "div#" + obj.getAttribute('data-target');

	$(obj).parents(target).eq(0).slideUp( "fast", function() {
		// Animation complete.
	});
}

function showView(obj) {
	var target = "div#" + obj.getAttribute('data-target');
	var super_target = "div#" + obj.getAttribute('super-data-target');
	var other_target = "div#" + obj.getAttribute('other-data-target');

	if (super_target == "div#div_apply" && target == "div#div_notes") {
		var applyId = obj.getAttribute('data-id');
	    $.ajax({
	        url: "{{ URL::route('company.user.async.updateStatus') }}",
	        dataType : "json",
	        type : "POST",
	        data : {apply_id : applyId, status_value: 1},
	        success : function(data) {
	        }
	    });
	}else if (super_target == "div#div_hint" && target == "div#div_notes") {
		var hintId = obj.getAttribute('data-id');
	    $.ajax({
	        url: "{{ URL::route('company.user.async.updateHintStatus') }}",
	        dataType : "json",
	        type : "POST",
	        data : {hint_id : hintId},
	        success : function(data) {
	        }
	    });
	}

	$(obj).parents(super_target).eq(0).find(target).eq(0).slideDown( "fast", function() {
		// Animation complete.
	});

	$(obj).parents(super_target).eq(0).find(other_target).eq(0).slideUp( "fast", function() {
		// Animation complete.
	});
}


function saveApplyNotes(obj) {
    var notes = $(obj).parents('div').eq(1).find('textarea').eq(0).val();
    var applyId = $(obj).parents('div').eq(1).find('textarea').eq(0).attr('data-id');

    $.ajax({
        url: "{{ URL::route('company.job.async.saveNotes') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, notes : notes},
        success : function(data) {
            bootbox.alert('Note has been saved successfully!');
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
}




function saveHintNotes(obj) {
    var notes = $(obj).parents('div').eq(1).find('textarea').eq(0).val();
    var hintId = $(obj).parents('div').eq(1).find('textarea').eq(0).attr('data-id');

    $.ajax({
        url: "{{ URL::route('company.job.async.saveHintNotes') }}",
        dataType : "json",
        type : "POST",
        data : {hint_id : hintId, notes : notes},
        success : function(data) {
            bootbox.alert('Note has been saved successfully!');
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
}

$("a#js-btn-hint-notes").click(function() {
    var hintId = $(this).attr('data-id');
    var notes = $(this).parents('div#div_notes').eq(0).find('textarea#notes').eq(0).val();

    $.ajax({
        url: "{{ URL::route('company.job.async.saveHintNotes') }}",
        dataType : "json",
        type : "POST",
        data : {hint_id : hintId, notes : notes},
        success : function(data) {
        	bootbox.alert(data.msg);
        	window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
});



$("a#js-a-apply-reject").click(function() {
	var applyId = $(this).attr('data-id');

	bootbox.confirm("Are you sure?", function(result) {
        if (result) {

        	$("div#loadingModal").delay(1500).modal('show');

            $.ajax({
                url: "{{ URL::route('company.job.async.rejectApply') }}",
                dataType : "json",
                type : "POST",
                data : {apply_id: applyId},
                success : function(data) {
                	$("div#loadingModal").modal('hide');
                	bootbox.alert(data.msg, function() {
							location.reload();
                    	});
                }
            })
        }
    });

});

$("a#js-a-apply-process").click(function() {
	var applyId = $(this).attr('data-id');

    $.ajax({
        url: "{{ URL::route('company.user.async.updateStatus') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, status_value: 3},
        success : function(data) {
            if (data.result == 'success') {
                bootbox.alert ("Status updated successfully.", function() {
                    location.reload();
                });
            }
        }
    });

});


$("a#js-a-hint-reject").click(function() {

	var hintId = $(this).attr('data-id');

	bootbox.confirm("Are you sure?", function(result) {
        if (result) {

        	$("div#loadingModal").delay(1500).modal('show');

            $.ajax({
                url: "{{ URL::route('company.job.async.rejectHint') }}",
                dataType : "json",
                type : "POST",
                data : {hint_id: hintId},
                success : function(data) {
                	$("div#loadingModal").modal('hide');
                	bootbox.alert(data.msg, function() {
							location.reload();
                    	});
                }
            })
        }
    });
});


$('#msgModal').on('shown.bs.modal', function () {
    $('#txt_message').focus();
});



$("button#js-btn-open-message").click(function() {

	var super_target = "div#" + $(this).attr('super-data-target');

	$(this).parents(super_target).eq(0).find('div#msgModal').eq(0).modal();
	$(this).parents(super_target).eq(0).find('div#msgModal').eq(0).find("#txt_message").eq(0).val("");
	$(this).parents(super_target).eq(0).find('div#msgModal').eq(0).find("textarea#txt_message").eq(0).focus();
});

$("button#js-btn-open-request").click(function() {

	var super_target = "div#" + $(this).attr('super-data-target');

	$(this).parents(super_target).eq(0).find('div#requestModal').eq(0).modal();
	$(this).parents(super_target).eq(0).find('div#msgModal').eq(0).find("#js-textarea-request-content").eq(0).val("");
});


$("button#js-btn-send-message").click(function() {

	var applyId = $(this).attr('data-id');
    var message = $(this).parents('div#msgModal').eq(0).find('textarea#txt_message').eq(0).val();

    $.ajax({
        url:"{{ URL::route('company.job.async.sendMessage') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, message : message},
        success : function(data){
            if (data.result = 'success') {
                bootbox.alert ("Message has been sent successfully.");
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 1000);
            }
        }
    });
    $("div#msgModal").modal('hide');
});

$("button#js-btn-send-request").click(function() {

	var userId = $(this).attr('data-id');
	var memberId = $(this).parents('div#requestModal').eq(0).find('select#js-select-member').eq(0).val();
    var message = $(this).parents('div#requestModal').eq(0).find('textarea#js-textarea-request-content').eq(0).val();

    $.ajax({
        url:"{{ URL::route('company.user.async.requestFeedback') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, member_id : memberId, message : message},
        success : function(data){
        }
    });
    $("div#requestModal").modal('hide');
});


$("button#js-btn-send-message-hint").click(function() {

	var hintId = $(this).attr('data-id');
    var message = $(this).parents('div#msgModal').eq(0).find('textarea#txt_message').eq(0).val();

    $.ajax({
        url:"{{ URL::route('company.job.async.sendHintMessage') }}",
        dataType : "json",
        type : "POST",
        data : {hint_id : hintId, message : message},
        success : function(data){
            if (data.result = 'success') {
                bootbox.alert ("Message has been sent successfully.");
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 1000);
            }
        }
    });
    $("div#msgModal").modal('hide');
});



function showSaveButton(obj) {
    $(obj).parents('div.row').eq(0).find('a#js-a-save-rate').fadeIn("normal");
}

function saveApplyScore(obj) {
    var applyId = $(obj).attr('data-id');
    var score = $(obj).parents('div.row').eq(0).find('input#input-rate').eq(0).val();

    $.ajax({
        url:"{{ URL::route('company.job.async.saveApplyRate') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, score : score},
        success : function(data){
            bootbox.alert (data.msg);
            window.setTimeout(function(){
                bootbox.hideAll();
                $(obj).parents('div.row').eq(0).find('a#js-a-save-rate').fadeOut("normal");
            }, 2000);
        }
    });
}


function saveHintScore(obj) {
    var hintId = $(obj).attr('data-id');
    var score = $(obj).parents('div.row').eq(0).find('input#input-rate').eq(0).val();

    $.ajax({
        url:"{{ URL::route('company.job.async.saveHintRate') }}",
        dataType : "json",
        type : "POST",
        data : {hint_id : hintId, score : score},
        success : function(data){
            bootbox.alert (data.msg);
            window.setTimeout(function(){
                bootbox.hideAll();
                $(obj).parents('div.row').eq(0).find('a#js-a-save-rate').fadeOut("normal");
            }, 2000);
        }
    });
}


function updateStatus() {
    var jobId = $('input#jobId').val();
    var status = $('select#status').val();

    $("div#loadingModal").modal('show');
    $.ajax({
        url:"{{ URL::route('company.job.async.updateStatus') }}",
        dataType : "json",
        type : "POST",
        data : {job_id : jobId, status : status},
        success : function(data){
            $("div#loadingModal").modal('hide');
            bootbox.alert (data.msg);
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 2000);
        }
    });
}



$("button#js-btn-video-interview").click(function() {
    var userName = $(this).attr('data-name');
    var userId = $(this).attr('data-userId');
    var applyId = $(this).attr('data-id');
    var jobId = $(this).attr('data-jobId');

    //insert value to modal
    $('label#js-label-vi-username').html(userName);
    $('input#js-input-vi-userid').val(userId);
    $('input#js-input-vi-apply-id').val(applyId);
    $('input#js-input-vi-job-id').val(jobId);


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

    var jobId = $('input#js-input-vi-job-id').val();
    var userId = $('input#js-input-vi-userid').val();
    var templateId = $('select#js-vi-template-id').val();
    var questionnaireId = $('select#js-vi-questionnaire-id').val();
    var expireAt = $('input#vi-expiration').val();
    var applyId = $('input#js-input-vi-apply-id').val();
    var subject = $('input#js-vi-template-title').val();
    var description = $('textarea#js-vi-template-description').val();

    $.ajax({
        url: "{{ URL::route('company.job.async.sendInterview') }}",
        dataType : "json",
        type : "POST",
        data : {job_id: jobId, user_id: userId, template_id: templateId, questionnaire_id: questionnaireId, expire_at: expireAt, apply_id : applyId, status_value: 4, subject: subject, description: description},
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

$('textarea#js-textarea-apply-notes').bind('input propertychange', function() {
    if ($(this).val() != '') {
        $(this).parents('div.row').eq(0).find('button#js-button-saveNote').fadeIn("normal");
    }else {
        $(this).parents('div.row').eq(0).find('button#js-button-saveNote').fadeOut("normal");
    }

});


$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		title: function() {
				return $(this).attr('data-description');
			}
	})
});


$('select#js-vi-template-id').on('change', function() {
    var index = this.value;

    $('input#js-vi-template-title').val(viTempTitle[index]);
    $('textarea#js-vi-template-description').val(viTempDesc[index]);

});

$('select#period').on('change', function() {
    $('form#search-form').submit();
});




function showUserView(obj) {
    var userId = $(obj).attr('data-userId');
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


function saveCompanyApplyNotes(obj) {
    var notes = $(obj).parents('div').eq(1).find('textarea').eq(0).val();
    var applyId = $(obj).parents('div').eq(1).find('textarea').eq(0).attr('data-id');

    $.ajax({
        url: "{{ URL::route('company.profile.async.saveApplyNote') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, notes : notes},
        success : function(data) {
            bootbox.alert('Note has been saved successfully!');
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
}

$("button#js-btn-send-applyMessage").click(function() {

	var applyId = $(this).attr('data-id');
    var message = $(this).parents('div#msgModal').eq(0).find('textarea#txt_message').eq(0).val();

    $.ajax({
        url:"{{ URL::route('company.profile.async.sendMessage') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, message : message},
        success : function(data){
            if (data.result = 'success') {
                bootbox.alert ("Message has been sent successfully.");
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 1000);
            }
        }
    });
    $("div#msgModal").modal('hide');
});



</script>