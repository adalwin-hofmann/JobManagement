<script>

<?php if (isset($contacts)) { ?>
var contactCount = '<?php echo count($contacts); ?>';
contactCount = contactCount * 1;

var contacts = new Array();
var count = 0;

<?php
	foreach ($contacts as $contact) {
?>
		contacts[count] = new Array();
		contacts[count]['id'] = '<?php echo $contact->id; ?>';
		contacts[count]['name'] = '<?php echo $contact->name; ?>';
		contacts[count]['email'] = '<?php echo $contact->email; ?>';
		contacts[count]['phone'] = '<?php echo $contact->phone; ?>';
		contacts[count]['currentJob'] = '<?php echo $contact->currentJob; ?>';
		contacts[count]['previousJobs'] = '<?php echo $contact->previousJobs; ?>';
		contacts[count]['description'] = '<?php echo substr(json_encode($contact->description), 1, strlen(json_encode($contact->description)) - 2); ?>';
		count ++;
<?php
	}
?>


function fillInput(obj) {

	var i;
	
	for (i = 0; i < count; i ++) {
		if (contacts[i]['id'] == obj.value)	break;
	}

    $(obj).parents('div#div_hint').eq(0).find('input#name').eq(0).val(contacts[i]['name']);
    $(obj).parents('div#div_hint').eq(0).find('input#email').eq(0).val(contacts[i]['email']);
    $(obj).parents('div#div_hint').eq(0).find('input#phone').eq(0).val(contacts[i]['phone']);
    $(obj).parents('div#div_hint').eq(0).find('input#previousJobs').eq(0).val(contacts[i]['previousJobs']);
}

<?php }?>

function changePattern(sel) {
	var title = sel.value;
	var description = sel.options[sel.selectedIndex].getAttribute('data-description');

	$(sel).parents('div#div_apply').eq(0).find('input#title').eq(0).val(title);
	$(sel).parents('div#div_apply').eq(0).find('textarea#description').eq(0).html(description);
}

function hideView(obj) {
	var target = "div#" + obj.getAttribute('data-target');
	
	$(obj).parents(target).eq(0).slideUp( "fast", function() {
		// Animation complete.
	});
}

function getResult() {
    $('form#search_form').submit();
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
				bootbox.alert('{{ trans('job.msg_10') }}');
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

function reloadResult(obj) {
    var searchword = $(obj).val().toLowerCase();
    var count = 0;

    $(document).find('div#div_job').each(function() {

        if (searchword == '') {
            count ++;
            $(this).css('display', 'block');
            return;
        }

        var jobTitle = $(this).find('a#a-job-title').html().toLowerCase();
        var companyName = $(this).find('a#a-company-name').html().toLowerCase();
        var skillName = $(this).find('input#skill_name').val().toLowerCase();



        if (jobTitle.indexOf(searchword) >= 0 || companyName.indexOf(searchword) >= 0 || skillName.indexOf(searchword) >= 0) {
            $(this).css('display', 'block');
            count ++;
        }else {
            $(this).css('display', 'none');
        }

    });

    if (count > 0) {
        $('div#div-no-job').css('display', 'none');
    }else {
        $('div#div-no-job').css('display', 'block');
    }
}


$("button#js-btn-addToCart").click(function() {
    var jobId = $(this).attr('data-id');
    
    $.ajax({
        url: "{{ URL::route('user.job.async.addToCart') }}",
        dataType : "json",
        type : "POST",
        data : {job_id : jobId},
        success : function(data) {
            if (data.result == 'success') {
         	   bootbox.alert(data.msg, function() {
         		   location.reload();
             	   });
            } else {
         	   bootbox.alert(data.msg);
            }
        }
    });
});

$('#category_select').on("change",function() {
	//Your code here

    var target = 'select#select-sub-' + $(this).val();
    var objClone = $(target).clone().removeClass('hidden');
    objClone.attr("id", "sub_category_item");
    $("div#sub-category-box").empty();
    $("div#sub-category-box").eq(0).append(objClone);

	$('#search_form').submit();
});



$('#type_select').on("change",function() {
	//Your code here
	$('#search_form').submit();
});



$("a#js-a-hint").click(function() {
    var jobId = $(this).attr('data-id');
    var name = '';
    var phonenumber = '';
    var email = '';
    var currentJob = '';
    var previousJobs = '';
    var description = '';

    if ($(this).parents('div#div_hint').find('input#is_name').eq(0) !== null) {
		nameflag = $(this).parents('div#div_hint').find('input#is_name').eq(0).val();
   	}
   	
    if ($(this).parents('div#div_hint').find('input#is_phonenumber').eq(0) !== null) {
    	phoneflag = $(this).parents('div#div_hint').find('input#is_phonenumber').eq(0).val();
   	}

    if ($(this).parents('div#div_hint').find('input#is_email').eq(0) !== null) {
    	emailflag = $(this).parents('div#div_hint').find('input#is_email').eq(0).val();
   	}  

    if ($(this).parents('div#div_hint').find('input#is_currentjob').eq(0) !== null) {
    	currentjobflag = $(this).parents('div#div_hint').find('input#is_currentjob').eq(0).val();
   	}

    if ($(this).parents('div#div_hint').find('input#is_previousjobs').eq(0) !== null) {
    	previousjobsflag = $(this).parents('div#div_hint').find('input#is_previousjobs').eq(0).val();
   	}

    if ($(this).parents('div#div_hint').find('textarea#is_description').eq(0) !== null) {
    	descriptionflag = $(this).parents('div#div_hint').find('textarea#is_description').eq(0).val();
   	}
 
   	    

    if ($(this).parents('div#div_hint').find('input#name').eq(0) !== null) {
		name = $(this).parents('div#div_hint').find('input#name').eq(0).val();
   	}

    if ($(this).parents('div#div_hint').find('input#phonenumber').eq(0) !== null) {
    	phonenumber = $(this).parents('div#div_hint').find('input#phonenumber').eq(0).val();
   	}

    if ($(this).parents('div#div_hint').find('input#email').eq(0) !== null) {
    	email = $(this).parents('div#div_hint').find('input#email').eq(0).val();
   	}   	

    if ($(this).parents('div#div_hint').find('input#currentJob').eq(0) !== null) {
    	currentJob = $(this).parents('div#div_hint').find('input#currentJob').eq(0).val();
   	}

    if ($(this).parents('div#div_hint').find('input#previousJobs').eq(0) !== null) {
    	previousJobs = $(this).parents('div#div_hint').find('input#previousJobs').eq(0).val();
   	}

    if ($(this).parents('div#div_hint').find('textarea#description').eq(0) !== null) {
    	description = $(this).parents('div#div_hint').find('textarea#description').eq(0).val();
   	}

   	if (name == '' && nameflag == '1') {
		bootbox.alert ('{{ trans('job.msg_12') }}', function() {

		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
		return;
   	}

   	if (phonenumber == '' && phoneflag == '1') {
   		bootbox.alert ('{{ trans('job.msg_13') }}', function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

   	if (emailflag == '1') {
		if (!isValidEmailAddress(email)) {
	   		bootbox.alert ('{{ trans('job.msg_14') }}', function() {

	   		});
       		window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
	   		return;
		}
   	}

   	if (currentJob == '' && currentjobflag == '1') {
   		bootbox.alert ('{{ trans('job.msg_15') }}', function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

   	if (previousJobs == '' && previousjobsflag == '1') {
   		bootbox.alert ('{{ trans('job.msg_16') }}', function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

   	if (description == '' && descriptionflag == '1') {
   		bootbox.alert ('{{ trans('job.msg_17') }}', function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

    $("div#loadingModal").modal('show');
    $.ajax({
        url: "{{ URL::route('user.job.async.addHint') }}",
        dataType : "json",
        type : "POST",
        data : {job_id : jobId, name : name, phonenumber: phonenumber, email: email, currentJob: currentJob, previousJobs: previousJobs, description: description},
        success : function(data) {
        	bootbox.alert(data.msg, function() {
                var target = "div#div_hint";
                $("div#loadingModal").modal('hide');
                $(target).slideUp( "fast", function() {
                    // Animation complete.
                });
        	});
        }
    });
});


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};



var objSlider;
$(document).ready(function() {
    $('#js-slider-waiting-time').slider({});
    
    objSlider = $('#js-slider-waiting-time').on('slide', function(obj) {
        $("div#js-div-range-waiting-min").text("$" + obj.value[0] + " : " + "$" + obj.value[1]);
        $("#js-waiting-time-min").val(obj.value[0]);
        $("#js-waiting-time-max").val(obj.value[1]);
    });
    
});

$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		title: function() {
				return "<img src='" + $(this).attr('data-image-url') + "' style='width:180px; margin-top: 5px;'><br/>&quot;" + $(this).attr('data-tag') + "&quot;<br/><br/>" + $(this).attr('data-description');
			}
	})
});

$(document).ready(function() {
	$('div#js-div-loading').css('margin-top', (window.innerHeight - 89) / 2 - 50 + 'px');


    var target = 'select#select-sub-' + $('select#category_select').val();
    var objClone = $(target).clone().removeClass('hidden');
    objClone.attr("id", "sub_category_item");
    $("div#sub-category-box").eq(0).append(objClone);


    $('#sub_category_item').change(function() {
        $('#search_form').submit();
    });
});




</script>