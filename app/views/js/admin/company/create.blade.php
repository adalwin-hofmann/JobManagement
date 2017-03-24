<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>


<script>
var map;
var myLatLng;
var marker;
var services = [];


<?php
	$i = 0; 
    foreach ($services as $service) {?>
    	services[<?php echo $i++;?>] = {
    	    	label: '<?php echo $service->name;?>',
    	    	value: '<?php echo $service->name?>',
    	    	desc: '<?php echo $service->icon_code?>',
    	    	serviceId: <?php echo $service->id?>
    	};
<?php } ?>


if (navigator.geolocation) {
	 navigator.geolocation.getCurrentPosition(success, error);
} else {
	 error('not supported');
}


$(".is_published").change(function() {
    if(this.checked) {
        //Do stuff
        $("input.is_published").val(1);
    }else {
    	$("input.is_published").val(0);
    }
});


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



$(document).ready(function() {
	onAddService('', '', '');
});

function onAddService(name, code, id) {
	var objClone = $("div#clone_div_service").clone().removeClass('hidden');
	objClone.attr("id", "service_item");
	objClone.find("input#service_name").val(name);
	objClone.find("input#icon_code").val(code);
	objClone.find("input#service_id").val(id);
 	objClone.find("input#service_name").autocomplete({
 	 	source: services,
 	 	focus: function(event, ui) {
 	 			objClone.find("input#service_name").val(ui.item.label);	
 	 	 	},
 	 	select: function (event, ui) {
				objClone.find("input#icon_code").val(ui.item.desc);	
				objClone.find("input#service_id").val(ui.item.serviceId);
 	 	 	}
 		});
	$("div#service_list").eq(0).append(objClone);
}

function onDeleteService(obj) {
	$(obj).parents('div#service_item').eq(0).remove();
}

</script>