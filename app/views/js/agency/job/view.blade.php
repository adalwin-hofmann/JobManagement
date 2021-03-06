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

/*
$('textarea#js-textarea-apply-notes').change(function() {

	var notes = $(this).val();
	var applyId = $(this).attr('data-id');
	
    $.ajax({
        url: "{{ URL::route('company.job.async.saveNotes') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, notes : notes},
        success : function(data) {
        }
    });
});
*/


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

/*
$('textarea#js-textarea-hint-notes').change(function() {

	var notes = $(this).val();
	var hintId = $(this).attr('data-id');
	
    $.ajax({
        url: "{{ URL::route('company.job.async.saveHintNotes') }}",
        dataType : "json",
        type : "POST",
        data : {hint_id : hintId, notes : notes},
        success : function(data) {
        }
    });
});
*/

function saveHintNotes(obj) {
    var notes = $(obj).parents('div').eq(1).find('textarea').eq(0).val();
    var userId = $(obj).parents('div').eq(1).find('textarea').eq(0).attr('data-id');

    $.ajax({
        url: "{{ URL::route('company.user.async.saveNotes') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, notes : notes},
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

$("a#js-a-apply-interview").click(function() {
/*	var applyId = $(this).attr('data-id');

    $.ajax({
        url: "URL::route('company.user.async.updateStatus')",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, status_value: 4},
        success : function(data) {
            if (data.result == 'success') {
                bootbox.alert ("Status updated successfully.", function() {
                    location.reload();
                });
            }
        }
    });*/

    var userName = $(this).attr('data-name');
    var userId = $(this).attr('data-userId');
    var applyId = $(this).attr('data-id');

    //insert value to modal
    $('label#js-label-vi-username').html(userName);
    $('input#js-input-vi-userid').val(userId);
    $('input#js-input-vi-apply-id').val(applyId);


    $('div#viModal').modal();

});


$('select#js-vi-template-id').on('change', function() {
    var index = this.value;

    $('input#js-vi-template-title').val(viTempTitle[index]);
    $('textarea#js-vi-template-description').val(viTempDesc[index]);

});

$('textarea#js-textarea-apply-notes').bind('input propertychange', function() {
    if ($(this).val() != '') {
        $(this).parents('div.row').eq(0).find('button#js-button-saveNote').fadeIn("normal");
    }else {
        $(this).parents('div.row').eq(0).find('button#js-button-saveNote').fadeOut("normal");
    }

});

function sendVIInterview(obj) {

    $(obj).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');

    var jobId = '{{ $job->id }}';
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
        data : {apply_id: applyId, message : message},
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
        url:"{{ URL::route('agency.user.async.requestFeedback') }}",
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
        url:"{{ URL::route('agency.job.async.sendHintMessage') }}",
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

$(document).ready(function() {
	$('div#js-div-loading').css('margin-top', (window.innerHeight - 89) / 2 - 50 + 'px');
});

function showSaveButton(obj) {
    $(obj).parents('div.row').eq(0).find('a#js-a-save-rate').fadeIn("normal");
}

function saveApplyScore(obj) {
    var applyId = $(obj).attr('data-id');
    var score = $(obj).parents('div.row').eq(0).find('input#input-rate').eq(0).val();

    $.ajax({
        url:"{{ URL::route('agency.job.async.saveApplyRate') }}",
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
        url:"{{ URL::route('agency.job.async.saveHintRate') }}",
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
        url:"{{ URL::route('agency.job.async.updateStatus') }}",
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

function updateBonus() {
    var jobId = $('input#jobId').val();
    var bonus = $('input#bonus').val();

    $("div#loadingModal").modal('show');
    $.ajax({
        url:"{{ URL::route('agency.job.async.updateBonus') }}",
        dataType : "json",
        type : "POST",
        data : {job_id : jobId, bonus : bonus},
        success : function(data){
            $("div#loadingModal").modal('hide');
            bootbox.alert (data.msg);
            window.setTimeout(function(){
                bootbox.hideAll();
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

</script>