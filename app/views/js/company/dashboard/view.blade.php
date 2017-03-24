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
		bootbox.alert('Please leave a message.');
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

</script>