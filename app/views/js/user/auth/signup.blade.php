<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>


/* add event for checkbox */
$("#is_published").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_published").val(1);
    }else {
    	$("input#is_published").val(0);
    }
});

$("#is_freelance").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_freelance").val(1);
    }else {
    	$("input#is_freelance").val(0);
    }
});

$("#is_parttime").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_parttime").val(1);
    }else {
    	$("input#is_parttime").val(0);
    }
});

$("#is_fulltime").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_fulltime").val(1);
    }else {
    	$("input#is_fulltime").val(0);
    }
});

$("#is_internship").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_internship").val(1);
    }else {
    	$("input#is_internship").val(0);
    }
});

$("#is_volunteer").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_volunteer").val(1);
    }else {
    	$("input#is_volunteer").val(0);
    }
});

/*  */

var map;
var myLatLng;
var marker;


if (navigator.geolocation) {
	 navigator.geolocation.getCurrentPosition(success, error);
} else {
	 error('not supported');
}



function success(position) {
	var opts = {'center': new google.maps.LatLng(position.coords.latitude, position.coords.longitude), 'zoom':11, 'mapTypeId': google.maps.MapTypeId.ROADMAP } 
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
	
	myLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
 	if (marker) {
 		marker.setMap(null);
 	}
 	marker = new google.maps.Marker({position:myLatLng});
 	marker.setMap(map);	


	document.getElementById('latlng').value = position.coords.latitude + ', ' + position.coords.longitude;
 	document.getElementById('lat').value = position.coords.latitude;
 	document.getElementById('lng').value = position.coords.longitude;
}


function error(msg) {
	
}



$(function () {
	$('#datetimepicker5').datetimepicker({
		pickTime: false
	});
});


function onAddSkill() {
	var objClone = $("div#clone_div_skill").clone().removeClass('hidden');
	objClone.attr("id", "skill_item");
	$("div#skill_list").eq(0).append(objClone);
}

function onAddForeignLanguage() {
	var objClone = $("div#clone_div_language").clone().removeClass('hidden');
	objClone.attr("id", "language_item");
	$('div#language_list').eq(0).append(objClone);
}

function onAddInstitution() {
	var objClone = $("div#clone_div_institution").clone().removeClass('hidden');
	objClone.attr("id", "institution_item");
	$('div#institution_list').eq(0).append(objClone);
}

function onAddAward() {
	var objClone = $("div#clone_div_award").clone().removeClass('hidden');
	objClone.attr("id", "award_item");
	$('div#award_list').eq(0).append(objClone);
}

function onAddWork() {
	var objClone = $("div#clone_div_work").clone().removeClass('hidden');
	objClone.attr("id", "work_item");
	$('div#work_list').eq(0).append(objClone);
}

function onAddTestimonial() {
	var objClone = $("div#clone_div_testimonial").clone().removeClass('hidden');
	objClone.attr("id", "testimonial_item");
	$('div#testimonial_list').eq(0).append(objClone);
}

function onDeleteSkill(obj) {
	$(obj).parents('div#skill_item').eq(0).remove();
}

function onDeleteForeignLanguage(obj) {
	$(obj).parents('div#language_item').eq(0).remove();
}

function onDeleteInstitution(obj) {
	$(obj).parents('div#institution_item').eq(0).remove();
}

function onDeleteAward(obj) {
	$(obj).parents('div#award_item').eq(0).remove();
}

function onDeleteWork(obj) {
	$(obj).parents('div#work_item').eq(0).remove();
}

function onDeleteTestimonial(obj) {
	$(obj).parents('div#testimonial_item').eq(0).remove();
}

$(document).ready(function() {
	onAddSkill();
	onAddForeignLanguage();
	onAddInstitution();
	onAddAward();
	onAddWork();
	onAddTestimonial();
});
</script>