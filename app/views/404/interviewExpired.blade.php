<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>{{ SITE_NAME }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>

    {{ HTML::style('/assets/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/uniform/css/uniform.default.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    {{ HTML::style('/assets/metronic/assets/admin/pages/css/error.css') }}
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    {{ HTML::style('/assets/metronic/assets/global/css/components.css') }}
    {{ HTML::style('/assets/metronic/assets/global/css/plugins.css') }}
    {{ HTML::style('/assets/metronic/assets/admin/layout/css/layout.css') }}
    {{ HTML::style('/assets/metronic/assets/admin/layout/css/themes/default.css') }}
    {{ HTML::style('/assets/metronic/assets/admin/layout/css/custom.css') }}
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
</head>

<body class="page-404-3">
    <div class="page-inner">
        <img src="/assets/img/earth.jpg" class="img-responsive" alt="">
    </div>
    <div class="container error-404">
        <h1>404</h1>
        <h2>Houston, we have a problem.</h2>
        <p>
             Actually, your interview had expired.
        </p>
        <p>
            <a href="{{ HTTP_PATH }}">
            Return home </a>
            <br>
        </p>
    </div>

    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    {{ HTML::script('/assets/metronic/assets/global/plugins/respond.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/excanvas.min.js') }}
    <![endif]-->
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery-1.11.0.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js') }}
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery.blockui.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery.cokie.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/uniform/jquery.uniform.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}
    <!-- END CORE PLUGINS -->
    {{ HTML::script('/assets/metronic/assets/global/scripts/metronic.js') }}
    {{ HTML::script('/assets/metronic/assets/admin/layout/scripts/layout.js') }}
    {{ HTML::script('/assets/metronic/assets/admin/layout/scripts/quick-sidebar.js') }}
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            QuickSidebar.init() // init quick sidebar
        });
    </script>
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>