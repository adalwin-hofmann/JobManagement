<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>

var jobTemps = [];

<?php
    foreach ($jobTemps as $job) {
        ?>

        var tempKey = '{{ $job->id }}';

        jobTemps[tempKey] = [];

        jobTemps[tempKey]['name'] = '{{ $job->name }}';
        jobTemps[tempKey]['description'] = '{{ nl2br(substr(json_encode($job->description), 1, strlen(json_encode($job->description)) - 2)) }}';
        jobTemps[tempKey]['level_id'] = '{{ $job->level_id }}';
        jobTemps[tempKey]['category_id'] = '{{ $job->category_id }}';
        jobTemps[tempKey]['presence_id'] = '{{ $job->presence_id }}';
        jobTemps[tempKey]['year'] = '{{ $job->year }}';
        jobTemps[tempKey]['city_id'] = '{{ $job->city_id }}';
        jobTemps[tempKey]['native_language_id'] = '{{ $job->native_language_id }}';
        jobTemps[tempKey]['requirements'] = '{{ $job->requirements }}';
        jobTemps[tempKey]['is_name'] = '{{ $job->is_name }}';
        jobTemps[tempKey]['is_phonenumber'] = '{{ $job->is_phonenumber }}';
        jobTemps[tempKey]['is_email'] = '{{ $job->is_email }}';
        jobTemps[tempKey]['is_description'] = '{{ $job->is_description }}';
        jobTemps[tempKey]['is_previousjobs'] = '{{ $job->is_previousjobs }}';
        jobTemps[tempKey]['is_currentjob'] = '{{ $job->is_currentjob }}';
        jobTemps[tempKey]['bonus'] = '{{ $job->bonus }}';
        jobTemps[tempKey]['paid_after'] = '{{ $job->paid_after }}';
        jobTemps[tempKey]['bonus_description'] = '{{ $job->bonus_description }}';
        jobTemps[tempKey]['type_id'] = '{{ $job->type_id }}';
        jobTemps[tempKey]['salary'] = '{{ $job->salary }}';
        jobTemps[tempKey]['email'] = '{{ $job->email }}';
        jobTemps[tempKey]['phone'] = '{{ $job->phone }}';
        jobTemps[tempKey]['lat'] = '{{ $job->lat }}';
        jobTemps[tempKey]['long'] = '{{ $job->long }}';
        jobTemps[tempKey]['is_published'] = '{{ $job->is_published }}';

        jobTemps[tempKey]['is_name'] = jobTemps[tempKey]['is_name'] * 1;
        jobTemps[tempKey]['is_phonenumber'] = jobTemps[tempKey]['is_phonenumber'] * 1;
        jobTemps[tempKey]['is_email'] = jobTemps[tempKey]['is_email'] * 1;
        jobTemps[tempKey]['is_description'] = jobTemps[tempKey]['is_description'] * 1;
        jobTemps[tempKey]['is_previousjobs'] = jobTemps[tempKey]['is_previousjobs'] * 1;
        jobTemps[tempKey]['is_currentjob'] = jobTemps[tempKey]['is_currentjob'] * 1;
        jobTemps[tempKey]['is_published'] = jobTemps[tempKey]['is_published'] * 1;
        jobTemps[tempKey]['lat'] = jobTemps[tempKey]['lat'] * 1.0;
        jobTemps[tempKey]['long'] = jobTemps[tempKey]['long'] * 1.0;


        var languageCount = 0;
        <?php
            foreach($job->foreignLanguages as $language) {
                ?>

                jobTemps[tempKey]['language'] = [];
                jobTemps[tempKey]['language'][languageCount] = [];

                jobTemps[tempKey]['language'][languageCount]['language_id'] = '{{ $language->language_id }}';
                jobTemps[tempKey]['language'][languageCount]['understanding'] = '{{ $language->understanding }}';
                jobTemps[tempKey]['language'][languageCount]['speaking'] = '{{ $language->speaking }}';
                jobTemps[tempKey]['language'][languageCount]['writing'] = '{{ $language->writing }}';
                languageCount ++;
                <?php
            }
        ?>

        jobTemps[tempKey]['languageCount'] = languageCount;

        var skillCount = 0;
        <?php
            foreach($job->skills as $skill) {
                ?>

                jobTemps[tempKey]['skill'] = [];
                jobTemps[tempKey]['skill'][skillCount] = [];

                jobTemps[tempKey]['skill'][skillCount]['name'] = '{{ $skill->name }}';
                jobTemps[tempKey]['skill'][skillCount]['value'] = '{{ $skill->value }}';
                skillCount ++;
                <?php
            }
        ?>

        jobTemps[tempKey]['skillCount'] = languageCount;

        var benefitCount = 0;
        <?php
            foreach($job->benefits as $benefit) {
                ?>
                    jobTemps[tempKey]['benefit'] = [];
                    jobTemps[tempKey]['benefit'][benefitCount] = [];

                    jobTemps[tempKey]['benefit'][benefitCount]['name'] = '{{ $benefit->name }}';
                <?php
            }
        ?>



        jobTemps[tempKey]['benefitCount'] = benefitCount;

        <?php
    }
?>


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


function onAddSkill(name, value) {
	var objClone = $("div#clone_div_skill").clone().removeClass('hidden');
	objClone.find('input#skill_name').val(name);
	objClone.find('input#skill_value').val(value);
	objClone.attr("id", "skill_item");
	$("div#skill_list").eq(0).append(objClone);
}

function onAddForeignLanguage(id, understanding, speaking, writing) {
	var objClone = $("div#clone_div_language").clone().removeClass('hidden');
    objClone.find('select#foreign_language_id').val(id);
    objClone.find('select#understanding').val(understanding);
    objClone.find('select#speaking').val(speaking);
    objClone.find('select#writing').val(writing);
	objClone.attr("id", "language_item");
	$('div#language_list').eq(0).append(objClone);
}

function onAddBenefit(name) {
	var objClone = $("div#clone_div_benefit").clone().removeClass('hidden');
	objClone.find('benefit_name').val(name);
	objClone.attr("id", "benefit_item");
	$('div#benefit_list').eq(0).append(objClone);
}

function onDeleteSkill(obj) {
	$(obj).parents('div#skill_item').eq(0).remove();
}

function onDeleteForeignLanguage(obj) {
	$(obj).parents('div#language_item').eq(0).remove();
}

function onDeleteBenefit(obj) {
	$(obj).parents('div#benefit_item').eq(0).remove();
}

function saveAsTemplate() {
    $("input#is_finished").val(0);
    $("form#js-form-addJob").submit();
}

function fillBlanks(obj) {
    var selValue = obj.value;

    if (selValue != '0') {
        $('input#name').val( jobTemps[selValue]['name'] );
        $('select#level_id').val( jobTemps[selValue]['level_id'] );
        $('select#presence_id').val( jobTemps[selValue]['presence_id'] );
        $('input#year').val( jobTemps[selValue]['year'] );
        $('select#category_id').val( jobTemps[selValue]['category_id'] );
        $('select#city_id').val( jobTemps[selValue]['city_id'] );
        $('textarea#description').html( jobTemps[selValue]['description'] );

        $('div#skill_list').empty();
        if (jobTemps[selValue]['skillCount'] > 0) {
            for (var i = 0; i < jobTemps[selValue]['skillCount']; i ++) {
                onAddSkill(jobTemps[selValue]['skill'][i]['name'], jobTemps[selValue]['skill'][i]['value']);
            }
        }else {
            onAddSkill('', '');
        }

        $('select#native_language_id').val( jobTemps[selValue]['native_language_id'] );

        $('div#language_list').empty();
        if (jobTemps[selValue]['languageCount'] > 0) {
            for (var i = 0; i < jobTemps[selValue]['languageCount']; i ++) {
                onAddForeignLanguage(jobTemps[selValue]['language'][i]['language_id'], jobTemps[selValue]['language'][i]['understanding'], jobTemps[selValue]['language'][i]['speaking'], jobTemps[selValue]['language'][i]['writing']);
            }
        }else {
            onAddForeignLanguage('', '', '', '');
        }

        $('input#requirements').val( jobTemps[selValue]['requirements'] );
        $('input#bonus').val( jobTemps[selValue]['bonus'] );
        $('input#paid_after').val( jobTemps[selValue]['paid_after'] );

        $('input#is_name').prop('checked', jobTemps[selValue]['is_name']);
        $('input#is_phonenumber').prop('checked', jobTemps[selValue]['is_phonenumber']);
        $('input#is_email').prop('checked', jobTemps[selValue]['is_email']);
        $('input#is_currentjob').prop('checked', jobTemps[selValue]['is_currentjob']);
        $('input#is_previousjobs').prop('checked', jobTemps[selValue]['is_previousjobs']);
        $('input#is_description').prop('checked', jobTemps[selValue]['is_description']);

        $('input#is_name').val(jobTemps[selValue]['is_name']);
        $('input#is_phonenumber').val(jobTemps[selValue]['is_phonenumber']);
        $('input#is_email').val(jobTemps[selValue]['is_email']);
        $('input#is_currentjob').val(jobTemps[selValue]['is_currentjob']);
        $('input#is_previousjobs').val(jobTemps[selValue]['is_previousjobs']);
        $('input#is_description').val(jobTemps[selValue]['is_description']);

        $('input#bonus_description').val( jobTemps[selValue]['bonus_description'] );

        $('select#type_id').val( jobTemps[selValue]['type_id'] );
        $('input#salary').val( jobTemps[selValue]['salary'] );

        $('div#benefit_list').empty();
        if (jobTemps[selValue]['benefitCount'] > 0) {
            for (var i = 0; i < jobTemps[selValue]['benefitCount']; i ++ ) {
                onAddBenefit(jobTemps[selValue]['benefit'][i]['name']);
            }
        }else {
            onAddBenefit('');
        }

        $('input#phone').val( jobTemps[selValue]['phone'] );
        $('input#email').val( jobTemps[selValue]['email'] );

        $('input#is_published').prop('checked', jobTemps[tempKey]['is_published']);
        $('input#is_published').val( jobTemps[tempKey]['is_published'] );

        myLatLng = new google.maps.LatLng(jobTemps[selValue]['lat'], jobTemps[selValue]['long']);
        if (marker) {
            marker.setMap(null);
        }
        marker = new google.maps.Marker({position:myLatLng});
        marker.setMap(map);

        $('input#latlng').val( jobTemps[selValue]['lat'] + ', ' + jobTemps[selValue]['long'] );
    }else {
        $('input#name').val('');
        $('input#year').val('');
        $('textarea#description').html('');

        $('div#skill_list').empty();
        onAddSkill('', '');


        $('div#language_list').empty();
        onAddForeignLanguage('', '', '', '');

        $('input#requirements').val('');
        $('input#bonus').val('');
        $('input#paid_after').val('');

        $('input#is_name').prop('checked', 1);
        $('input#is_phonenumber').prop('checked', 0);
        $('input#is_email').prop('checked', 1);
        $('input#is_currentjob').prop('checked', 0);
        $('input#is_previousjobs').prop('checked', 0);
        $('input#is_description').prop('checked', 1);

        $('input#is_name').val(1);
        $('input#is_phonenumber').val(0);
        $('input#is_email').val(1);
        $('input#is_currentjob').val(0);
        $('input#is_previousjobs').val(0);
        $('input#is_description').val(1);

        $('input#bonus_description').val('');
        $('input#salary').val('');

        $('div#benefit_list').empty();
        onAddBenefit('');


        $('input#phone').val('');
        $('input#email').val('');

        $('input#is_published').prop('checked', 0);
        $('input#is_published').val( 0 );

    }

}


$(document).ready(function() {
	onAddSkill('', '');
	onAddForeignLanguage('', '', '', '');
	onAddBenefit('');

	var target = 'select#select-sub-' + $('select#p_category_id').val();
	var objClone = $(target).clone().removeClass('hidden');
    objClone.attr("id", "sub_category_item");
    $("div#sub-category-box").eq(0).append(objClone);


});


$('select#p_category_id').change(function() {
	var target = 'select#select-sub-' + $(this).val();
	var objClone = $(target).clone().removeClass('hidden');
    objClone.attr("id", "sub_category_item");
    $("div#sub-category-box").empty();
    $("div#sub-category-box").eq(0).append(objClone);
});

</script>