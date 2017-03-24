<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<script>

var map;
var myLatLng;
var marker;

$("button#js-btn-review").click(function() {
    var companyId = $(this).attr('data-id');
    var description = $('textarea#description').val();
    var score = $('input#input-rate').val();

    if (description == '') {
		bootbox.alert('{{ trans('company.msg_22') }}', function() {

		});
		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
		return;
    }
    
    
    $.ajax({
        url: "{{ URL::route('user.company.async.addReview') }}",
        dataType : "json",
        type : "POST",
        data : {company_id : companyId, score : score, description : description},
        success : function(data) {
        	location.reload();
        }
    });
});


$(document).ready(function() {
	
	var lat = '<?php echo $company->lat?>';
	var lng = '<?php echo $company->long?>';

	lat = lat * 1.0;
	lng = lng * 1.0; 

	var opts = {'center': new google.maps.LatLng(lat, lng), 'zoom':11, 'mapTypeId': google.maps.MapTypeId.ROADMAP } 
	map = new google.maps.Map(document.getElementById('mapdiv'),opts); 

	google.maps.event.addListener(map,'click',function(event) { 
 		document.getElementById('latlng').value = event.latLng.lat() + ', ' + event.latLng.lng();
 		document.getElementById('lat').value = event.latLng.lat();
 		document.getElementById('lng').value = event.latLng.lng();
 		myLatLng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
 		if (marker) {
 			marker.setMap(null);
 		}
 		marker = new google.maps.Marker({position:myLatLng});
 		marker.setMap(map); 
	}) 
	
	google.maps.event.addListener(map,'mousemove',function(event) { 
	});	
	
	myLatLng = new google.maps.LatLng(lat, lng);
 	if (marker) {
 		marker.setMap(null);
 	}
 	marker = new google.maps.Marker({position:myLatLng});
 	marker.setMap(map);	
});





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

function showView(obj) {
	var target = "div#" + obj.getAttribute('data-target');

	if ($(obj).parents("div#div_job").eq(0).find(target).eq(0).css('display') == "none") {
	
		var other_target = "div#" + obj.getAttribute('other-target');
		var other_target_second = "div#" + obj.getAttribute('other-target-second');
		var other_target_third = "div#" + obj.getAttribute('other-target-third');
	
		var userId = '<?php echo Session::get('user_id');?>';
	
		if (target == 'div#div_hint' || target== 'div#div_apply') {
			if (userId == '') {
				bootbox.alert('{{ trans('company.msg_23') }}', function() {

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

$('button#js-btn-follow').click(function() {
    var companyId = $(this).attr('data-id');
    $('button#js-btn-processing').css('display', 'block');
    $('button#js-btn-follow').css('display', 'none');
    $.ajax({
        url: "{{ URL::route('user.async.followCompany') }}",
        dataType : "json",
        type : "POST",
        data : {company_id: companyId},
        success : function(data) {
           if (data.result == 'success') {
        	   $('button#js-btn-follow').css('display', 'none');
        	   $('button#js-btn-following').css('display', 'block');
        	   $('button#js-btn-processing').css('display', 'none');
           } else {
               $('button#js-btn-follow').css('display', 'block');
        	   bootbox.alert(data.msg, function() {

        	   });
        	   window.setTimeout(function(){
                  bootbox.hideAll();
               }, 1000);
           }
        }
    });
});

$('button#js-btn-following').click(function() {
    var companyId = $(this).attr('data-id');
    $('button#js-btn-processing').css('display', 'block');
    $('button#js-btn-following').css('display', 'none');
    $.ajax({
        url: "{{ URL::route('user.async.unfollowCompany') }}",
        dataType : "json",
        type : "POST",
        data : {company_id: companyId},
        success : function(data) {
           if (data.result == 'success') {
        	   $('button#js-btn-follow').css('display', 'block');
        	   $('button#js-btn-following').css('display', 'none');
        	   $('button#js-btn-processing').css('display', 'none');
           } else {
               $('button#js-btn-following').css('display', 'block');
        	   bootbox.alert(data.msg, function(){

        	   });
        	   window.setTimeout(function(){
                  bootbox.hideAll();
               }, 1000);
           }
        }
    });
});


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
         	   bootbox.alert(data.msg, function() {

         	   });
         	   window.setTimeout(function(){
                  bootbox.hideAll();
               }, 1000);
            }
        }
    });
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
		bootbox.alert ("{{ trans('company.msg_27') }}", function() {

		});
		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
		return;
   	}

   	if (phonenumber == '' && phoneflag == '1') {
   		bootbox.alert ("{{ trans('company.msg_26') }}", function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

   	if (emailflag == '1') {
		if (!isValidEmailAddress(email)) {
	   		bootbox.alert ("{{ trans('company.msg_28') }}", function() {

	   		});
       		window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
	   		return;
		}
   	}

   	if (currentJob == '' && currentjobflag == '1') {
   		bootbox.alert ("{{ trans('company.msg_29') }}", function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

   	if (previousJobs == '' && previousjobsflag == '1') {
   		bootbox.alert ("{{ trans('company.msg_30') }}", function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

   	if (description == '' && descriptionflag == '1') {
   		bootbox.alert ("{{ trans('company.msg_31') }}", function() {

   		});
   		window.setTimeout(function(){
            bootbox.hideAll();
        }, 1000);
   		return;
   	}

    var hintButton = $(this);

    hintButton.addClass('disabled');
   	hintButton.html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');
   	    
    $.ajax({
        url: "{{ URL::route('user.job.async.addHint') }}",
        dataType : "json",
        type : "POST",
        data : {job_id : jobId, name : name, phonenumber: phonenumber, email: email, currentJob: currentJob, previousJobs: previousJobs, description: description},
        success : function(data) {
            hintButton.removeClass('disabled');
            hintButton.html('{{ trans('company.submit') }}');
        	bootbox.alert(data.msg, function() {
        	});
       		window.setTimeout(function(){
                bootbox.hideAll();
            }, 1000);
        }
    });
});


function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};


function showApplyModal() {
    $('div#applyModal').modal();
}


function changePattern(sel) {
	var title = sel.value;
	var description = sel.options[sel.selectedIndex].getAttribute('data-description');

	$(sel).parents('div#div_apply').eq(0).find('input#title').eq(0).val(title);
	$(sel).parents('div#div_apply').eq(0).find('textarea#description').eq(0).html(description);
}


</script>