<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>

function scrollToDiv(obj) {
    event.preventDefault();
    var target = "#" + obj.getAttribute('data-target');
    $('html, body').animate({
        scrollTop: $(target).offset().top - 110
    }, 2000);	
}


$('textarea#js-textarea-note').change(function() {

	var notes = $(this).val();
	var userId = "{{ $user->id }}"
	
    $.ajax({
        url: "{{ URL::route('company.user.async.saveNotes') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, notes : notes},
        success : function(data) {
        }
    });
});


function saveNotes(obj) {

	var notes = $('textarea#js-textarea-note').val();
	var userId = "{{ $user->id }}"

    $.ajax({
        url: "{{ URL::route('company.user.async.saveNotes') }}",
        dataType : "json",
        type : "POST",
        data : {user_id : userId, notes : notes},
        success : function(data) {
            if (data.result == 'success') {
                bootbox.alert ("{{ trans('user.msg_31') }}");
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 1000);
            }
        }
    });

}

</script>