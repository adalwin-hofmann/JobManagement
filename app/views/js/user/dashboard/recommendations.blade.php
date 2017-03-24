
<script>

    function viewHint(obj) {

        var hintId = $(obj).attr('data-id');
        var target = 'div#viewHintModal' + hintId;

        $(target).modal();

    }

    function deleteHint(obj) {

        var hintId = $(obj).attr('data-id');

        bootbox.confirm("Are you sure?", function(result) {
            if (result) {
                $.ajax({
                    url: "{{ URL::route('user.dashboard.async.deleteHint') }}",
                    dataType : "json",
                    type : "POST",
                    data : {hint_id: hintId},
                    success : function(data) {
                        if (data.result == 'success') {
                            message = data.msg;
                            bootbox.alert(message, function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    }

</script>