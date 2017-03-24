<script type="text/javascript">
	$(document).ready(function() {
			$('.nav .dropdown').hover(function() {
				$(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
			}, function() {
				$(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
			});
		});	
</script>