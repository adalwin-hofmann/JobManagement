<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>


<script>
var map;
var myLatLng;
var marker;

<?php 
	foreach ($job_languages as $language) {?>
		onAddForeignLanguage('<?php echo $language->language_id;?>', '<?php echo $language->understanding;?>', '<?php echo $language->writing;?>', '<?php echo $language->speaking;?>')
<?php }?>		

<?php 
	foreach ($job_skills as $skill) {?>
		onAddSkill('<?php echo $skill->name;?>', '<?php echo $skill->value;?>')
<?php }?>

<?php 
	foreach ($job_benefits as $benefit) {?>
		onAddBenefit('<?php echo $skill->name;?>')
<?php }?>

/* add event for checkbox */
$("#is_published").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_published").val(1);
    }else {
    	$("input#is_published").val(0);
    }
});

$("#is_name").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_name").val(1);
    }else {
    	$("input#is_name").val(0);
    }
});

$("#is_phonenumber").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_phonenumber").val(1);
    }else {
    	$("input#is_phonenumber").val(0);
    }
});

$("#is_verified").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_verified").val(1);
    }else {
    	$("input#is_verified").val(0);
    }
});

$("#is_email").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_email").val(1);
    }else {
    	$("input#is_email").val(0);
    }
});

$("#is_currentjob").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_currentjob").val(1);
    }else {
    	$("input#is_currentjob").val(0);
    }
});

$("#is_previousjobs").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_previousjobs").val(1);
    }else {
    	$("input#is_previousjobs").val(0);
    }
});

$("#is_description").change(function() {
    if(this.checked) {
        //Do stuff
        $("input#is_description").val(1);
    }else {
    	$("input#is_description").val(0);
    }
});

/*  */

$(document).ready(function() {

	var lat = '<?php echo $job->lat?>';
	var lng = '<?php echo $job->long?>';

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

function onAddForeignLanguage(language_id, understanding, writting, speaking) {
	var objClone = $("div#clone_div_language").clone().removeClass('hidden');
	objClone.attr('id', 'language_item');
	objClone.find('select#foreign_language_id').val(language_id);
	objClone.find('select#understanding').val(understanding);
	objClone.find('select#writting').val(writting);
	objClone.find('select#speaking').val(speaking);
	$('div#foreign_language_list').eq(0).append(objClone);
}

function onDeleteForeignLanguage(obj) {
	$(obj).parents('div#foreign_language_list').eq(0).remove();
}

function onAddSkill(name, value) {
	var objClone = $("div#clone_div_skill").clone().removeClass('hidden');
	objClone.attr("id", "skill_item");
	objClone.find("input#skill_name").val(name);
	objClone.find("input#skill_value").val(value);
	$("div#skill_list").eq(0).append(objClone);
}

function onDeleteSkill(obj) {
	$(obj).parents('div#skill_item').eq(0).remove();
}

function onAddBenefit(name) {
	var objClone = $("div#clone_div_benefit").clone().removeClass('hidden');
	objClone.attr("id", "benefit_item");
	objClone.find("input#benefit_name").val(name);
	$("div#benefit_list").eq(0).append(objClone);
}

function onDeleteBenefit(obj) {
	$(obj).parents('div#benefit_item').eq(0).remove();
}
</script>