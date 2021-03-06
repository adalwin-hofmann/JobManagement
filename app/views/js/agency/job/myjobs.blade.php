<script>

function reloadResult(obj) {
    var searchword = $(obj).val().toLowerCase();
    $('table.table-store-list').find('tbody').find('tr').each(function() {
        var title  = $(this).find('td').eq(0).find('a').html().toLowerCase();
        if (title.indexOf(searchword) < 0) {
            $(this).css('display', 'none');
        }else {
            $(this).css('display', 'table-row');
        }
    });
}

function reloadUser(obj) {
    var searchword = $(obj).val().toLowerCase();
    $('div#div_user').each(function() {
        var title  = $(this).find('div').eq(0).find('a#user_name').html().toLowerCase();
        if (title.indexOf(searchword) < 0) {
            $(this).css('display', 'none');
        }else {
            $(this).css('display', 'block');
        }
    });

    var count = 0;
    $('div#div_user').each(function() {
        if ($(this).css('display') == 'block') {
            count ++;
        }
    });

    if (count == 0) {
        $('div#div_no_candidates').css('display', 'block');
    }else {
        $('div#div_no_candidates').css('display', 'none');
    }
}

function showSaveButton(obj) {
    $(obj).parents('div.row').eq(0).find('a#js-a-save-rate').fadeIn("normal");
}


function saveUserScore(obj) {
    var userId = $(obj).attr('data-id');
    var score = $(obj).parents('div.row').eq(0).find('input#input-rate').eq(0).val();

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


function showJobView(obj) {
	var target = "div#" + obj.getAttribute('data-target');

	if ($(obj).parents("div#div_user").eq(0).find(target).eq(0).css('display') == "none") {
		$(obj).parents("div#div_user").eq(0).find(target).eq(0).slideDown( "fast", function() {
			// Animation complete.
		});
	}else {

		$(obj).parents("div#div_user").eq(0).find(target).eq(0).slideUp( "fast", function() {
			// Animation complete.
		});
	}
}

function hideView(obj) {
	var target = "div#" + obj.getAttribute('data-target');

    $(obj).parents("div#div_user").eq(0).find(target).eq(0).slideUp( "fast", function() {
        // Animation complete.
    });

}

function showMsgModal(obj) {
    var userId = $(obj).attr('data-id');
    var target = 'div#msgModal' + userId;

    $(target).modal();
}

$("button#js-btn-send-message").click(function() {

	var userId = $(this).attr('data-id');
	var target = 'div#msgModal' + userId;
    var message = $(this).parents(target).eq(0).find('textarea#txt_message').eq(0).val();
    $("div#loadingModal").modal('show');
    $.ajax({
        url:"{{ URL::route('agency.user.async.sendMessage') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, message : message},
        success : function(data){
            $("div#loadingModal").modal('hide');
        	bootbox.alert(data.msg, function(){

        	});
        	window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
    $(target).modal('hide');
});

$("button#js-btn-apply-send-message").click(function() {

	var applyId = $(this).attr('data-id');
	var target = 'div#msgModal' + applyId;
    var message = $(this).parents(target).eq(0).find('textarea#txt_message').eq(0).val();
    $("div#loadingModal").modal('show');
    $.ajax({
        url:"{{ URL::route('agency.job.async.sendMessage') }}",
        dataType : "json",
        type : "POST",
        data : {apply_id : applyId, message : message},
        success : function(data){
            $("div#loadingModal").modal('hide');
        	bootbox.alert(data.msg, function(){

        	});
        	window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
    $(target).modal('hide');
});

function hideModal(obj) {
	var userId = $(obj).attr('data-id');
	var target = 'div#msgModal' + userId;
	$(target).modal('hide');
}



$(document).ready(function() {
    $('div#js-div-loading').css('margin-top', (window.innerHeight - 89) / 2 - 50 + 'px');
});


function updateStatus(obj) {
    var jobId = $(obj).attr('data-id');
    var status = $(obj).attr('data-status');

    status = status * 1;

    if (status == 4) {
        status = 0;
    }else {
        status = status + 1;
        if (status == 3) status = 4;
    }

    $.ajax({
        url:"{{ URL::route('agency.job.async.updateStatus') }}",
        dataType : "json",
        type : "POST",
        data : {job_id : jobId, status : status},
        success : function(data){
        }
    });

    if (status == 0) {
        $(obj).attr('class', 'job-open');
        $(obj).html('OPEN');
        $(obj).attr('data-status', '0');
    }else if (status == 1) {
        $(obj).attr('class', 'job-pending');
        $(obj).html('PENDING');
        $(obj).attr('data-status', '1');
    }else if (status == 2) {
        $(obj).attr('class', 'job-closed');
        $(obj).html('CLOSED');
        $(obj).attr('data-status', '2');
    }else {
        $(obj).attr('class', 'job-deactive');
        $(obj).html('DEACTIVE');
        $(obj).attr('data-status', '4');
    }
}


function showView(obj) {
    var target = 'tr#'+$(obj).attr('data-target');
    var other_target = 'tr#' + $(obj).attr('other-target');
    var other_second_target = 'tr#' + $(obj).attr('other-second-target');

    var real_other_target = '';

    if ($(other_target).css('display') != 'none') {
        real_other_target = other_target;
    }else {
        real_other_target = other_second_target;
    }

    $(real_other_target).fadeOut('fast', function() {
        if ($(target).css('display') == 'none') {
            $(target).fadeIn('normal');
        }else {
            $(target).fadeOut('normal');
        }
    });
}



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


$("a#js-a-apply-reject").click(function() {
	var applyId = $(this).attr('data-id');

	bootbox.confirm("Are you sure?", function(result) {
        if (result) {

        	$("div#loadingModal").delay(1500).modal('show');

            $.ajax({
                url: "{{ URL::route('agency.job.async.rejectApply') }}",
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


function showProposal(obj) {
    var target ='div#' + $(obj).attr('data-target');

    if ($(target).css('display') == 'none') {
        $(target).slideDown('fast');
    }else {
        $(target).slideUp('fast');
    }

}

function saveApplyNotes(obj) {
    var notes = $(obj).parents('div').eq(1).find('textarea').eq(0).val();
    var applyId = $(obj).parents('div').eq(1).find('textarea').eq(0).attr('data-id');

    $.ajax({
        url: "{{ URL::route('agency.job.async.saveNotes') }}",
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


function saveInterviewNotes(obj) {
    var cvrId = $(obj).attr('data-id');
    var target = 'textarea#js-textarea-interview-notes-'+cvrId;
    var notes = $(target).val();

    $.ajax({
        url: "{{ URL::route('agency.job.async.saveInterviewNote') }}",
        dataType : "json",
        type : "POST",
        data : {cvr_id : cvrId, notes : notes},
        success : function(data) {
            bootbox.alert(data.msg);
            window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });

}


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


// funtions for share

function cleanShareModal() {
    $('input#share_job_id').val('');
    $('input#share_user_id').val('');
    $('input#share_interview_id').val('');
}

function showJobShareModal(obj) {

    cleanShareModal();

    var jobId = $(obj).attr('data-id');
    $('input#share_job_id').val(jobId);

    $.ajax({
        url:"{{ URL::route('agency.share.async.getCompanyListByJob') }}",
        dataType : "json",
        type : "POST",
        data : {job_id: jobId},
        success : function(data){
            if (data.result == 'success') {
                $('div#share-modal-content').empty();
                $('div#share-modal-content').html(data.companyView);
                $('div#shareModal').modal();
            }else {
                bootbox.alert(data.msg);
            }
        }
    });
}

function showUserShareModal(obj) {

    cleanShareModal();

    var userId = $(obj).attr('data-id');
    $('input#share_user_id').val(userId);

    $.ajax({
        url:"{{ URL::route('agency.share.async.getCompanyListByUser') }}",
        dataType : "json",
        type : "POST",
        data : {user_id: userId},
        success : function(data){
            if (data.result == 'success') {
                $('div#share-modal-content').empty();
                $('div#share-modal-content').html(data.companyView);
                $('div#shareModal').modal();
            }else {
                bootbox.alert(data.msg);
            }
        }
    });
}

function showInterviewShareModal(obj) {

    cleanShareModal();

    var interviewId = $(obj).attr('data-id');
    $('input#share_interview_id').val(interviewId);

    $.ajax({
        url:"{{ URL::route('agency.share.async.getCompanyListByInterview') }}",
        dataType : "json",
        type : "POST",
        data : {interview_id: interviewId},
        success : function(data){
            if (data.result == 'success') {
                $('div#share-modal-content').empty();
                $('div#share-modal-content').html(data.companyView);
                $('div#shareModal').modal();
            }else {
                bootbox.alert(data.msg);
            }
        }
    });
}


function shareToCompany(obj) {

    var jobId = $('input#share_job_id').val();
    var userId = $('input#share_user_id').val();
    var interviewId = $('input#share_interview_id').val();
    var companyId = $(obj).attr('data-id');

    $(obj).html('Sharing...');

    $.ajax({
        url:"{{ URL::route('agency.share.async.shareToCompany') }}",
        dataType : "json",
        type : "POST",
        data : {job_id: jobId, user_id: userId, interview_id: interviewId, company_id: companyId},
        success : function(data){
            if (data.result == 'success') {
                $(obj).html('Share');
                $(obj).addClass('disabled');
                $('div#shareModal').modal('hide');
            }else {
                bootbox.alert(data.msg);
            }
        }
    });
}


// add hint functions

function showHintMsgModal(obj) {
    var hintId = $(obj).attr('data-id');
    var target = 'div#msgHintModal' + hintId;

    $(target).modal();
}



function hideHintModal(obj) {
	var hintId = $(obj).attr('data-id');
	var target = 'div#msgHintModal' + hintId;
	$(target).modal('hide');
}


$("button#js-btn-hint-send-message").click(function() {

	var userId = $(this).attr('data-id');
	var hintId = $(this).attr('data-hintId');
	var target = 'div#msgHintModal' + hintId;
    var message = $(this).parents(target).eq(0).find('textarea#txt_message').eq(0).val();
    $("div#loadingModal").modal('show');
    $.ajax({
        url:"{{ URL::route('agency.user.async.sendMessage') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, message : message},
        success : function(data){
            $("div#loadingModal").modal('hide');
        	bootbox.alert(data.msg, function(){

        	});
        	window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
    $(target).modal('hide');
});


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


function saveHintNotes(obj) {
    var notes = $(obj).parents('div').eq(1).find('textarea').eq(0).val();
    var userId = $(obj).parents('div').eq(1).find('textarea').eq(0).attr('data-id');

    $.ajax({
        url: "{{ URL::route('agency.user.async.saveNotes') }}",
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



$("a#js-a-hint-reject").click(function() {

	var hintId = $(this).attr('data-id');

	bootbox.confirm("Are you sure?", function(result) {
        if (result) {

        	$("div#loadingModal").delay(1500).modal('show');

            $.ajax({
                url: "{{ URL::route('agency.job.async.rejectHint') }}",
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

</script>