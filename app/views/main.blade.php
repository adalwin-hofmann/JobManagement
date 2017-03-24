<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/favicon.png">
    <title>
        @section('title')
            {{ SITE_NAME }}
        @show
    </title>
    
	{{ HTML::style('/assets/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/uniform/css/uniform.default.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
	
    {{ HTML::style('/assets/metronic/assets/global/css/components.css') }}
    {{ HTML::style('/assets/metronic/assets/global/css/plugins.css') }}
    
    {{ HTML::style('/assets/css/style_bootstrap.css') }}
    {{ HTML::style('/assets/css/style_common.css') }}

    @yield('styles')
    @yield('custom-styles')
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
        
</head>
<body>    
    @yield('header')
    
    @yield('body')
    
    @yield('footer')
</body>
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    {{ HTML::script('/assets/metronic/global/plugins/respond.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/excanvas.min.js') }}
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
    
    {{ HTML::script('/assets/js/alert.js') }}
    {{ HTML::script('/assets/js/bootbox.js') }}
    @yield('header-scripts')
    @yield('scripts')
    @yield('custom-scripts')
</html>
