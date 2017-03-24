<script>

$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		title: function() {
				return "<img src='" + $(this).attr('data-image-url') + "' style='width:180px; margin-top: 5px;'><br/>&quot;" + $(this).attr('data-tag') + "&quot;<br/><br/>" + $(this).attr('data-description');
			}
	})
});


function hideView(obj) {
	var target = "div#" + obj.getAttribute('data-target');
	
	$(obj).parents(target).eq(0).slideUp( "fast", function() {
		// Animation complete.
	});
}

function showView(obj) {
	var target = "div#" + obj.getAttribute('data-target');

	if ($(obj).parents("div#div_job").eq(0).find(target).eq(0).css('display') == "none") {
	
		var other_target = "div#" + obj.getAttribute('other-target');
		var other_target_second = "div#" + obj.getAttribute('other-target-second');
		var other_target_third = "div#" + obj.getAttribute('other-target-third');
	
		var userId = '<?php echo Session::get('user_id');?>';
	
		if (target == 'div#div_hint' || target== 'div#div_apply') {
			if (userId == '') {
				bootbox.alert('You have to login first.', function() {

				});
           		window.setTimeout(function(){
                    bootbox.hideAll();
                }, 1000);
				return;
			}
		}
		
		$(obj).parents("div#div_job").eq(0).find(target).eq(0).slideDown( "fast", function() {
			// Animation complete.
		});
		$(obj).parents("div#div_job").eq(0).find(other_target).eq(0).slideUp( "fast", function() {
			// Animation complete.
		});		
		$(obj).parents("div#div_job").eq(0).find(other_target_second).eq(0).slideUp( "fast", function() {
			// Animation complete.
		});
		$(obj).parents("div#div_job").eq(0).find(other_target_third).eq(0).slideUp( "fast", function() {
			// Animation complete.
		});
			
	}else {
		
		$(obj).parents("div#div_job").eq(0).find(target).eq(0).slideUp( "fast", function() {
			// Animation complete.
		});
	}
}

$("button#js-btn-send-message").click(function() {

	var jobId = $(this).attr('data-id');
	var companyId = $(this).attr('data-company-id');
    var message = $(this).parents('div#div_message').eq(0).find('textarea#message_content').eq(0).val();

    $.ajax({
        url:"{{ URL::route('user.job.async.sendMessage') }}",
        dataType : "json",
        type : "POST",
        data : {job_id : jobId, message : message, company_id: companyId},
        success : function(data){
        	bootbox.alert(data.msg, function() {

        	});
       		window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });       
});



</script>