<script>
jQuery(document).ready(function() {     
  	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init() // init quick sidebar
  	Login.init();

   	// init background slide images
   	$.backstretch([
        "../../assets/metronic/assets/admin/pages/media/bg/1.jpg",
        ], {
          fade: 1000,
          duration: 8000
    	}
    );
});
</script>