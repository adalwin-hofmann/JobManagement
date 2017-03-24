<script src="http://maps.google.com/maps/api/js?v=3.exp&signed_in=true"></script>

<script>

var skills = [];


<?php
	$i = 0;
    foreach ($skills as $skill) {?>
    	skills[<?php echo $i++;?>] = '<?php echo $skill->name;?>';
<?php } ?>


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


<?php 
	foreach ($userSkills as $userSkill) {?>
		onAddSkill('<?php echo $userSkill->id;?>', '<?php echo $userSkill->name;?>', '<?php echo $userSkill->value;?>')
<?php }?>

<?php 
	foreach ($userLanguages as $userLanguage) {?>
		onAddForeignLanguage('<?php echo $userLanguage->language_id;?>', '<?php echo $userLanguage->understanding;?>', '<?php echo $userLanguage->speaking;?>', '<?php echo $userLanguage->writing;?>');
<?php }?>

<?php 
	foreach ($userEducations as $userEducation) {?>
		onAddInstitution('<?php echo $userEducation->name;?>', '<?php echo $userEducation->start;?>', '<?php echo $userEducation->end;?>', '<?php echo $userEducation->location;?>', '<?php echo $userEducation->faculty;?>', "<?php echo substr(json_encode($userEducation->notes), 1, strlen(json_encode($userEducation->notes)) - 2);?>");
<?php }?>

<?php 
	foreach ($userAwards as $userAward) {?>
		onAddAward('<?php echo $userAward->name;?>', '<?php echo $userAward->prize;?>', '<?php echo $userAward->year;?>', '<?php echo $userAward->location;?>');
<?php }?>

<?php 
	foreach ($userExperiences as $userExperience) {?>
		onAddWork('<?php echo $userExperience->name;?>', '<?php echo $userExperience->position;?>', '<?php echo $userExperience->type_id;?>', "<?php echo substr(json_encode($userExperience->notes), 1, strlen(json_encode($userExperience->notes)) - 2);?>", '<?php echo $userExperience->start;?>', '<?php echo $userExperience->end;?>');
<?php }?>

<?php 
	foreach ($userTestimonials as $userTestimonial) {?>
		onAddTestimonial('<?php echo $userTestimonial->name;?>', '<?php echo $userTestimonial->organisation;?>', "<?php echo substr(json_encode($userTestimonial->notes), 1, strlen(json_encode($userTestimonial->notes)) - 2);?>");
<?php }?>

<?php
    foreach ($userCompanies as $fCompany) {?>
        onAddWorkedCompany('<?php echo $fCompany->followCompany->id; ?>');
<?php }?>


function onAddWorkedCompany(fid) {
    var objClone = $("div#clone_div_workedCompany").clone().removeClass('hidden');
    objClone.find('#worked_company_id').val(fid);
    objClone.attr("id", "worked_company_item");
    $('div#worked_company_list').eq(0).append(objClone);
}

function onDeleteWorkedCompany(obj) {
    $(obj).parents('div#worked_company_item').eq(0).remove();
}
		
var map;
var myLatLng;
var marker;

$(function () {
	$('#datetimepicker5').datetimepicker({
		pickTime: false
	});
});


function preview(obj) {
	$('form#js_user_profile_form').find('input#js_user_preview').eq(0).val(1);
	$('form#js_user_profile_form').submit();
}

function onAddSkill(id, name, value) {
	var objClone = $("div#clone_div_skill").clone().removeClass('hidden');
	objClone.attr("id", "skill_item");
	objClone.find("input#skill_name").val(name);
	objClone.find("input#skill_value").val(value);
	objClone.find("input#skill_id").val(id);

    objClone.find("input#skill_name").typeahead({
                                     		name: 'skill_name',
                                     		local: skills
                                     	});

	$("div#skill_list").eq(0).append(objClone);
}

function onAddForeignLanguage(id, understanding, speaking, writing) {
	var objClone = $("div#clone_div_language").clone().removeClass('hidden');
	objClone.attr("id", "language_item");
	objClone.find("select#understanding").val(understanding);
	objClone.find("select#speaking").val(speaking);
	objClone.find("select#writing").val(writing);
	objClone.find("select#foreign_language_id").val(id);
	$('div#language_list').eq(0).append(objClone);
}

function onAddInstitution(name, start, end, location, faculty, note) {
	var objClone = $("div#clone_div_institution").clone().removeClass('hidden');
	objClone.attr("id", "institution_item");
	objClone.find("input#institution_name").val(name);
	objClone.find("input#period_start").val(start);
	objClone.find("input#period_end").val(end);
	objClone.find("input#location").val(location);
	objClone.find("input#qualification").val(faculty);
	objClone.find("textarea#institution_note").html(note);
	$('div#institution_list').eq(0).append(objClone);
}

function onAddAward(name, prize, year, location) {
	var objClone = $("div#clone_div_award").clone().removeClass('hidden');
	objClone.attr("id", "award_item");
	objClone.find("input#competition_name").val(name);
	objClone.find("input#prize").val(prize);
	objClone.find("input#competition_year").val(year);
	objClone.find("input#competition_location").val(location);
	$('div#award_list').eq(0).append(objClone);
}

function onAddWork(name, position, type_id, notes, start, end) {
	var objClone = $("div#clone_div_work").clone().removeClass('hidden');
	objClone.attr("id", "work_item");
	objClone.find("input#name").val(name);
	objClone.find("input#position").val(position);
	objClone.find("select#type_id").val(type_id);
	objClone.find("textarea#notes").html(notes);
	objClone.find("input#start").val(start);
	objClone.find("input#end").val(end);
	$('div#work_list').eq(0).append(objClone);
}

function onAddTestimonial(name, organisation, notes) {
	var objClone = $("div#clone_div_testimonial").clone().removeClass('hidden');
	objClone.attr("id", "testimonial_item");
	objClone.find("input#name").val(name);
	objClone.find("input#organisation").val(organisation);
	objClone.find("textarea#notes").html(notes);
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

	<?php if (count($userSkills) == 0){?>
	onAddSkill('', '', '');
	<?php }?>

	<?php if (count($userLanguages) == 0) {?>
	onAddForeignLanguage('', '', '', '');
	<?php }?>

	<?php if (count($userEducations) == 0) {?> 
	onAddInstitution('', '', '', '', '', '');
	<?php }?>

	<?php if (count($userAwards) == 0) {?>
	onAddAward('', '', '', '');
	<?php }?>

	<?php if (count($userExperiences) == 0) {?>
	onAddWork('', '', '', '', '', '');
	<?php }?>

	<?php if (count($userTestimonials) == 0) {?>
	onAddTestimonial('', '', '');
	<?php }?>

	<?php if (count($userCompanies) == 0) {?>
	onAddWorkedCompany(0);
	<?php }?>

	var lat = '<?php echo $user->lat?>';
	var lng = '<?php echo $user->lng?>';

	lat = lat * 1.0;
	lng = lng * 1.0; 

	var opts = {'center': new google.maps.LatLng(lat, lng), 'zoom':15}
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

function scrollToDiv(obj) {
    event.preventDefault();
    var target = "#" + obj.getAttribute('data-target');
    $('html, body').animate({
        scrollTop: $(target).offset().top - 110
    }, 2000);
}


function reloadProfileImage(obj) {
    if (obj.files && obj.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img-profile').attr('src', e.target.result);
        }

        reader.readAsDataURL(obj.files[0]);
    }
}

function reloadCoverImage(obj) {
    if (obj.files && obj.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img-cover').css('display', 'block');
            $('#img-cover').attr('src', e.target.result);
        }

        reader.readAsDataURL(obj.files[0]);
    }
}

function reloadMap() {
    setTimeout(function(){
        google.maps.event.trigger(map, 'resize');
        }, 500);
}

</script>