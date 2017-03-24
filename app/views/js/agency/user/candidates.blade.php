<script>

var TableManaged = function () {

    var initTable1 = function () {

        var table = $('#sample_1');

        // begin first table
        table.dataTable({
            "columns": [{
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": true
            }, {
                "orderable": false
            }, {
                "orderable": false
            }, {
                "orderable": false
            }],
            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,
            "pagingType": "bootstrap_full_number",
            "language": {
                "lengthMenu": "  _MENU_ records",
                "paginate": {
                    "previous":"Prev",
                    "next": "Next",
                    "last": "Last",
                    "first": "First"
                }
            },
            "columnDefs": [{  // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }],
            "order": [
                [1, "asc"]
            ] // set first column as a default sort by asc
        });

        var tableWrapper = jQuery('#sample_1_wrapper');

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).attr("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).attr("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            jQuery.uniform.update(set);
        });

        table.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
        });

        tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
    }

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            initTable1();
        }

    };

}();


var FormValidation = function () {
    // basic validation
    var handleValidation1 = function() {
        // for more info visit the official plugin documentation:
            // http://docs.jquery.com/Plugins/Validation

            var form1 = $('#form_sample_1');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                messages: {
                    select_multi: {
                        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                        minlength: jQuery.validator.format("At least {0} items must be selected")
                    }
                },
                rules: {
                    name: {
                        minlength: 2,
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    url: {
                        required: true,
                        url: true
                    },
                    number: {
                        required: true,
                        number: true
                    },
                    digits: {
                        required: true,
                        digits: true
                    },
                    creditcard: {
                        required: true,
                        creditcard: true
                    },
                    occupation: {
                        minlength: 5,
                    },
                    select: {
                        required: true
                    },
                    select_multi: {
                        required: true,
                        minlength: 1,
                        maxlength: 3
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.parent(".input-group").size() > 0) {
                        error.insertAfter(element.parent(".input-group"));
                    } else if (element.attr("data-error-container")) {
                        error.appendTo(element.attr("data-error-container"));
                    } else if (element.parents('.radio-list').size() > 0) {
                        error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                    } else if (element.parents('.radio-inline').size() > 0) {
                        error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                    } else if (element.parents('.checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                    } else if (element.parents('.checkbox-inline').size() > 0) {
                        error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit
                    success1.hide();
                    error1.show();
                    Metronic.scrollTo(error1, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                }
            });


    }


    return {
        //main function to initiate the module
        init: function () {

            handleValidation1();

        }

    };
}();

function showMsgModal(obj) {
    var userId = $(obj).attr('data-id');
    $('input#msg_userId').val(userId);

    $('div#msgModal').modal();
}


function showAddModal() {

    $('input#name').val('');
    $('input#email').val('');
    $('input#phone').val('');
    $('textarea#note').val('');

    $('div#addModal').modal();
    FormValidation.init();
}


$('button#js-btn-add-candidate').click(function() {
    var name = $('input#name').val();
    var email = $('input#email').val();
    var phone = $('input#phone').val();
    var note = $('textarea#note').val();

    var buttonObj = $(this);

    $(this).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');
    $.ajax({
        url: "{{ URL::route('agency.user.async.addCandidate') }}",
        dataType : "json",
        type : "POST",
        data : {name: name, email: email, phone: phone, note: note},
        success : function(data) {
            buttonObj.html('Add');
            if (data.result == 'success') {
                $('div#addModal').modal('hide');
                var table = $('#sample_1').DataTable();
                var sendButton = '<button class="btn btn-success btn-sm btn-home" onclick="showMsgModal(this)" data-id="'+ data.userId +'">Send Message</button>';
                var interviewButton = '<a class="btn btn-sm green" id="js-a-apply-interview" data-name="' + data.userName + '" data-userid="' + data.userId + '"><i class="fa fa-comments-o"></i> Interview</a>';
                var moveButton = '<button class="btn btn-success btn-sm btn-home" onclick="showMoveModal(this)" data-id="'+ data.userId +'">Move</button>'
                table.row.add([
                    name,
                    email,
                    phone,
                    data.createdBy,
                    sendButton,
                    interviewButton,
                    moveButton
                ]).draw();
            }else {
                bootbox.alert (data.msg);
            }
        }
    });
});



$("button#js-btn-send-message").click(function() {

	var userId = $('input#msg_userId').val();
	var target = 'div#msgModal';
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


$("a#js-a-apply-interview").click(function() {

    var userName = $(this).attr('data-name');
    var userId = $(this).attr('data-userId');

    //insert value to modal
    $('label#js-label-vi-username').html(userName);
    $('input#js-input-vi-userid').val(userId);


    $('div#viModal').modal();

});



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
                });
            }
        }
    });
};


function showMoveModal(obj) {
    var userId = $(obj).attr('data-id');

    $.ajax({
        url: "{{ URL::route('agency.user.async.checkAvailableJobs') }}",
        dataType : "json",
        type : "POST",
        data : {user_id: userId},
        success : function(data) {
            if (data.result == 'fail') {
                bootbox.alert(data.msg);
            }else {
                $('div#js-div-move-job-container').empty();
                $('div#js-div-move-job-container').html(data.jobsView);
                $('div#moveModal').modal();
            }
        }
    });


}

function moveToJob(obj) {
    var userId = $(obj).attr('data-userId');
    var jobId = $(obj).attr('data-jobId');

    $.ajax({
        url: "{{ URL::route('agency.user.async.moveToJob') }}",
        dataType : "json",
        type : "POST",
        data : {user_id: userId, job_id: jobId},
        success : function(data) {
            if (data.result == 'success') {
                $(obj).html('Moved');
                $(obj).addClass('disabled');
            }
        }
    });
}


</script>