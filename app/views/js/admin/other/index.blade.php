<script>
    function updateScore() {
        var score = $('input#level_score').val();
        var shareScore = $('input#share_score').val();
        var applyScore = $('input#apply_score').val();
        var recruitVerifyScore = $('input#recruit_verify_score').val();
        var recruitSuccessScore = $('input#recruit_success_score').val();
        var inviteScore = $('input#invite_score').val();
        var recruitScore = $('input#recruit_score').val();

        $.ajax({
            url: "{{ URL::route('admin.other.async.updateLevelScore') }}",
            dataType : "json",
            type : "POST",
            data : {level_score: score, share_score: shareScore, apply_score: applyScore, recruit_verify_score: recruitVerifyScore, recruit_success_score: recruitSuccessScore, invite_score: inviteScore, recruit_score: recruitScore},
            success : function(data) {
                if (data.result == 'success') {
                    bootbox.alert('Score updated successfully!');
                }
            }
        });
    }
</script>