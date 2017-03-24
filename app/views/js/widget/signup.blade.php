<script>

function saveUserInfo(obj) {
    var name = $('input#name').val();
    var email = $('input#email').val();
    var city_id = $('select#city_id').val();
    var token = $('input#token').val();

    $.ajax({
        url: "{{ URL::route('widget.async.user.save') }}",
        dataType : "json",
        type : "POST",
        data : {name: name, email: email, city_id: city_id, token: token},
        success : function(data) {
           if (data.result == 'success') {
           } else {
           }
        }
    });
}

</script>