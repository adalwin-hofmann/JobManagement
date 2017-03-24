<script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>
    function resetPassword() {
        $('div#reset-success-alert-box').fadeOut('fast');
        $('div#reset-alert-box').fadeOut('fast');
        $('div#forgotModal').modal();
    }

    function sendEmailToReset() {
        var email = $('input#txt_email').val();

        if (!IsEmail(email)) {
            $('div#reset-alert-box').fadeIn('normal');
            return;
        }else {
            $('div#reset-alert-box').fadeOut('normal');
        }

        $.ajax({
            url: "{{ URL::route('agency.auth.async.resetPassword') }}",
            dataType : "json",
            type : "POST",
            data : {email: email},
            success : function(data) {
                if (data.result == 'success') {
                    $('div#reset-success-alert-box').fadeIn('normal');
                }else {
                    $('p#js-p-reset-alert').html(data.msg);
                    $('div#reset-alert-box').fadeIn('normal');
                }
            }
        });
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function hideResetAlert(obj) {
        $('div#reset-alert-box').fadeOut('normal');
    }

    function hideResetSuccessAlert() {
        $('div#reset-success-alert-box').fadeOut('normal');
    }

</script>