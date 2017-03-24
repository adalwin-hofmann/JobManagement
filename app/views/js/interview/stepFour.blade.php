<script type="text/javascript">
	$(document).ready(function() {
        var wHeight = window.innerHeight;
        var newBodyHeight = wHeight - 75 - 102 - 10;
        var newImageHeight = wHeight - 75 - 102 - 40;
        var newContentHeight = newBodyHeight -  10;
        $('div.interview-body').css('height', newBodyHeight + 'px');
        $('div.interview-body-content').css('height', newContentHeight + 'px');
        $('img.interview-company-image').css('height', newImageHeight + 'px');
    });


</script>