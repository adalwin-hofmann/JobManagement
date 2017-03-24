@extends('main')    
    @section('styles')

        {{ HTML::style('/assets/css/style_bootstrap.css') }}
        {{ HTML::style('/assets/css/style_common.css') }}

        {{ HTML::style('/assets/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
        {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
        {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN PAGE LEVEL STYLES -->
        {{ HTML::style('/assets/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css') }}
        {{ HTML::style('/assets/metronic/assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.css') }}
        {{ HTML::style('/assets/metronic/assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css') }}
        <!-- END PAGE LEVEL STYLES -->

        <!-- BEGIN THEME STYLES -->
        {{ HTML::style('/assets/metronic/assets/global/css/components.css') }}
        {{ HTML::style('/assets/metronic/assets/frontend/layout/css/style.css') }}
        {{ HTML::style('/assets/metronic/assets/frontend/pages/css/style-revolution-slider.css') }}
        {{ HTML::style('/assets/metronic/assets/frontend/layout/css/style-responsive.css') }}
        {{ HTML::style('/assets/metronic/assets/frontend/layout/css/themes/blue.css') }}
        {{ HTML::style('/assets/metronic/assets/frontend/layout/css/custom.css') }}

        {{ HTML::style('/assets/css/style_user.css') }}
        {{ HTML::style('/assets/css/style_widget.css') }}
    @stop

    @section('body')
        <body class="corporate">
            @section('main')

            @stop
        </body>
    @stop

    @section('scripts')
        {{ HTML::script('/assets/js/alert.js') }}
        {{ HTML::script('/assets/js/bootbox.js') }}


    	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-1.11.0.min.js') }}
    	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js') }}
    	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}
    	{{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/back-to-top.js') }}
    	<!-- END CORE PLUGINS -->

    	<!-- BEGIN PAGE LEVEL PLUGINS -->
    	{{ HTML::script('/assets/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js') }}
    	{{ HTML::script('/assets/metronic/assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.min.js') }}
    	<!-- END PAGE LEVEL PLUGINS -->


    	<!-- BEGIN PAGE LEVEL SCRIPTS -->
    	{{ HTML::script('/assets/metronic/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.plugins.min.js') }}
    	{{ HTML::script('/assets/metronic/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js') }}
    	{{ HTML::script('/assets/metronic/assets/frontend/pages/scripts/revo-slider-init.js') }}
    	{{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/layout.js') }}
    	<!-- END PAGE LEVEL SCRIPTS -->


        <script type="text/javascript">
            jQuery(document).ready(function() {
                Layout.init();
                Layout.initOWL();
                RevosliderInit.initRevoSlider();
                Layout.initTwitter();
                Layout.initFixHeaderWithPreHeader(); /* Switch On Header Fixing (only if you have pre-header) */
                Layout.initNavScrolling();
            });
        </script>
    @stop
@stop