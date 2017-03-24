<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>

var selectedCount = 0;

/* add event for checkbox */
$("input#job_select_checkbox").change(function() {

	var jobId = $(this).attr('data-id');
	
    if(this.checked) {
        //Do stuff
    	selectedCount ++;
    }else {
    	selectedCount --;		
    }
});
/*  */

$('button#js-btn-pattern-create').click(function() {
	var title = $('input#create_title').val();
	var description = $('textarea#create_description').val();

	if (title == '' || description == '') {
		$('p#js-p-create-warnning').html('Please fill the fields.');
		$('div#js-div-create-warnning').fadeIn("normal");
	}else {
        $.ajax({
            url: "{{ URL::route('user.dashboard.async.createTemplate') }}",
            dataType : "json",
            type : "POST",
            data : {create_title : title, create_description : description},
            success : function(data) {
               if (data.result == 'success') {
            	   message = data.msg;
            	   bootbox.alert(message, function() {
						location.reload();
                   });
               }
            }
        });		
        $("div#createModal").modal('hide');
	}
});

$('button#js-btn-modal-close').click(function() {
	$('div#js-div-create-warnning').fadeOut("normal");
});


function showCreateModal() {

	$('div#createModal').modal();
	
}

function changePattern(sel) {
	var title = sel.value;
	var description = sel.options[sel.selectedIndex].getAttribute('data-description');

	$(sel).parents('div#apply-div').eq(0).find('input#title').eq(0).val(title);
	$(sel).parents('div#apply-div').eq(0).find('textarea#description').eq(0).html(description);
}

function removeThisJob(obj) {
	var cartId = obj.getAttribute('data-id');
	
    bootbox.confirm("Are you sure?", function(result) {
        if (result) {
	        $.ajax({
	            url: "{{ URL::route('user.job.async.removeFromCart') }}",
	            dataType : "json",
	            type : "POST",
	            data : {cart_id : cartId},
	            success : function(data) {
	               if (data.result == 'success') {
	            	   location.reload();	
	               } else {
	            	   message = data.msg;
	            	   bootbox.alert(message, function() {

	            	   });
                  		window.setTimeout(function(){
                           bootbox.hideAll();
                       }, 1000);
	               }
	            }
	        });	
        }
    });
}


function checkAll() {
	$('input#job_select_checkbox').each(function() {

			var jobId = $(this).attr('data-id');

			if ($(this).attr('checked')) {
				$(this).removeAttr('checked');
				selectedCount --;
			}else {
				$(this).attr('checked', true);
				$(this).prop('checked', true);
				selectedCount ++;
			}
		});
}


$(document).ready(function () {
    $("button#js-btn-apply").click(function() {
        if ($("div#apply-div").hasClass('hidden')) {
            if (selectedCount > 0){
    			$("div#apply-div").removeClass('hidden');
            }else {
				bootbox.alert ('Please select at least one job.', function() {

				});
           		window.setTimeout(function(){
                    bootbox.hideAll();
                }, 1000);
            }
        }else {
        	$("div#apply-div").addClass('hidden')
        }
    });	

    $("button#js-btn-submit").click(function() {
        var flag = 0;
        var message = '';

    	$('input#job_select_checkbox').each(function() {

        	if (this.checked) {
    			var jobId = $(this).attr('data-id');

    	        var name = $('input#title').val();
    	        var description = $('textarea#description').val();
    	        
    	        $.ajax({
    	            url: "{{ URL::route('user.job.async.apply') }}",
    	            dataType : "json",
    	            type : "POST",
    	            data : {job_id : jobId, name : name, description : description},
    	            success : function(data) {
    	               if (data.result == 'success') {
    		               
    	               } else {
    	            	   message = data.msg;
    	            	   flag = 1;
    	               }
    	            }
    	        });	
        	}
		});

		if (flag == 0) {
			bootbox.alert('Jobs are applied successfully.', function () {
				location.reload();
				});
		}else {
			bootbox.alert(message);
		}
    });	
});


$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		title: function() {
				return "<img src='" + $(this).attr('data-image-url') + "' style='width:180px; margin-top: 5px;'><br/>&quot;" + $(this).attr('data-tag') + "&quot;<br/><br/>" + $(this).attr('data-description');
			}
	})
});


function showEditModal(obj) {
    var patternId = $(obj).attr('data-id');
    var target = 'div#editModal' + patternId;
    $(target).modal();
}

function savePattern(obj) {

    var patternId = $(obj).attr('data-id');
    var title = $(obj).parents('div.modal-dialog').eq(0).find('input#edit_title').val();
    var description = $(obj).parents('div.modal-dialog').eq(0).find('textarea#edit_description').val();
    var target = 'div#editModal' + patternId;

    $.ajax({
        url: "{{ URL::route('user.dashboard.async.editTemplate') }}",
        dataType : "json",
        type : "POST",
        data : {edit_title : title, edit_description : description, pattern_id: patternId},
        success : function(data) {
           if (data.result == 'success') {
               message = data.msg;
               bootbox.alert(message, function() {
                    location.reload();
               });
           }
        }
    });
    $(target).modal('hide');
}

function removePattern(obj) {
    var patternId = $(obj).attr('data-id');

    bootbox.confirm("{{ trans('job.are_you_sure') }}?", function(result) {
        if (result) {
	        $.ajax({
                url: "{{ URL::route('user.dashboard.async.deleteTemplate') }}",
                dataType : "json",
                type : "POST",
                data : {pattern_id: patternId},
                success : function(data) {
                    if (data.result == 'success') {
                        message = data.msg;
                        bootbox.alert(message, function() {
                            location.reload();
                        });
                    }
                }
            });
        }
    });
}

</script>