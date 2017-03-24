@extends('main')

@section('styles')
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/style.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/style-responsive.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/themes/blue.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/custom.css') }}
	
    {{ HTML::style('/assets/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css') }}	
	
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}
	
	{{ HTML::style('/assets/css/star-rating.min.css') }}
	
    {{ HTML::style('/assets/css/style_user.css') }}
    {{ HTML::style('/assets/css/style_company.css') }}
@stop

@section('header')

    <?php if (!isset($pageNo)) { $pageNo = 0; } ?>
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                <div class="col-md-6 col-sm-6 additional-shop-info">
                    <a class="company-site-logo" href="/"><img src="/assets/img/logo.png"/></a>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-6 col-sm-6 additional-nav">

                    @if (Session::has('company_id'))
                        <ul class="nav navbar-nav pull-right" style="margin-left: 20px;">
                            <li class="setting-menu dropdown dropdown-user">
                                <a class="dropdown-toggle" dropdown-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img src="{{ HTTP_LOGO_PATH.$company->logo}}">  <span><b>{{ $company->name }}</b></span> <i class="fa fa-angle-down"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    @if (Session::get('company_is_admin') == 1)
                                        <li class="{{ ($pageNo == 4) ? 'active' : ''}}">
                                            <a href="{{ URL::route('company.profile') }}">
                                            <i class="fa fa-user"></i> {{ trans('menu.profile') }} </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ URL::route('company.auth.doLogout') }}">
                                        <i class="fa fa-key"></i> {{ trans('menu.sign_out') }} </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif

                    <ul class="list-unstyled list-inline pull-right" style="margin-top: 13px;">
                        <li><a class="color-blue {{ Session::get('locale') != 'fi' ? 'font-weight-bold' : '' }}" href="{{ URL::route('language-chooser', 'en') }}">English</a></li>
                        <li><a class="color-blue {{ Session::get('locale') != 'en' ? 'font-weight-bold' : '' }}" href="{{ URL::route('language-chooser', 'fi') }}">Finnish</a></li>
                        <li><a class="color-blue {{ Session::get('locale') != 'lv' ? 'font-weight-bold' : '' }}" href="{{ URL::route('language-chooser', 'lv') }}">Latvian</a></li>
                    </ul>
                    <i class="fa fa-globe pull-right color-blue" style="margin-top: 13px;"></i>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
        </div>
    </div>

    <div class="header">
        <div class="container">
        	
			<a href="javascript:void(0);" class="mobi-toggler"><i class="fa fa-bars"></i></a>
			
            <div class="header-navigation pull-right font-transform-bitter company-menu-bar">
                <ul class="nav nav-pills nav-top">
                    @if (Session::has('company_id'))
                    	<li class="{{ ($pageNo == 3) ? 'active' : ''}}"><a href="{{ URL::route('company.dashboard') }}">{{ trans('menu.dashboard') }}</a></li>
                    	<li class="setting-menu dropdown dropdown-user pointer {{ ($pageNo == 1 || $pageNo == 2) ? 'active' : ''}}">
                    	    <a class="dropdown-toggle" dropdown-toggle = "dropdown" data-hover="dropdown" data-close-others="true">{{ trans('menu.jobs') }}</a>
                    	    <ul class="dropdown-menu">
                    	        <li class="{{ ($pageNo == 1) ? 'active' : ''}}"><a href="{{ URL::route('company.job.add') }}">{{ trans('menu.post_job') }}</a></li>
                                <li class="{{ ($pageNo == 2) ? 'active' : ''}}"><a href="{{ URL::route('company.job.myjobs' )}}">{{ trans('menu.my_jobs') }}</a></li>
                    	    </ul>
                    	</li>
                        <li class="setting-menu dropdown dropdown-user pointer {{ ($pageNo == 5 || $pageNo == 6 || $pageNo == 8) ? 'active' : ''}}">
                            <a class="dropdown-toggle" dropdown-toggle = "dropdown" data-hover="dropdown" data-close-others="true">People</a>
                            <ul class="dropdown-menu">
                                <li class="{{ ($pageNo == 5) ? 'active' : ''}}"><a href="{{ URL::route('company.user.find' )}}">{{ trans('menu.find_people') }}</a></li>
                                <li class="{{ ($pageNo == 6) ? 'active' : ''}}"><a href="{{ URL::route('company.user.applied' )}}">{{ trans('menu.applied_people') }}</a></li>
                                <li class="{{ ($pageNo == 8) ? 'active' : ''}}"><a href="{{ URL::route('company.user.shared' )}}">Shared People</a></li>
                            </ul>
                        </li>
                        <li class="{{ ($pageNo == 7) ? 'active' : ''}}"><a href="{{ URL::route('company.message.list' )}}">{{ trans('menu.messages') }}</a></li>
                        <li class="setting-menu dropdown dropdown-user pointer {{ ($pageNo == 10 || $pageNo == 11 || $pageNo == 12) ? 'active' : ''}}">
                            <a class="dropdown-toggle" dropdown-toggle = "dropdown" data-hover="dropdown" data-close-others="true">Interviews</a>
                            <ul class="dropdown-menu">
                                <li class="{{ ($pageNo == 10) ? 'active' : ''}}"><a href="{{ URL::route('company.interview.face') }}">Face Interview</a></li>
                                <li class="{{ ($pageNo == 11) ? 'active' : ''}}"><a href="{{ URL::route('company.interview.video') }}">Video Interview</a></li>
                                <li class="{{ ($pageNo == 12) ? 'active' : ''}}"><a href="{{ URL::route('company.interview.shared') }}">Shared Interview</a></li>
                            </ul>
                        </li>
                        @if (Session::get('company_is_admin') == 1)
                        <li class="{{ ($pageNo == 13) ? 'active' : ''}}"><a href="{{ URL::route('company.setting' )}}">Setting</a></li>
                        @endif
                    @else
                        <li class="{{ ($pageNo == 98) ? 'active' : ''}}">
                        	<a href ="{{ URL::route('company.auth.login') }}">{{ trans('menu.sign_in') }}</a>
                        </li>
                        <li class="{{ ($pageNo == 99) ? 'active' : ''}}">
                        	<a href="{{ URL::route('company.auth.signup') }}">{{ trans('menu.register') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@stop

@yield('content')

@section('footer')
<div class="footer-container">
    <div class="container footer-menu">
        <div class="row color-white">
            <div class="col-sm-3">
                <p class="text-uppercase margin-bottom-20"><b>{{ trans('footer.company_info') }}</b></p>
                <ul>
                    <li><a href="#">{{ trans('footer.about_us') }}</a></li>
                    <li><a href="#">{{ trans('footer.blog') }}</a></li>
                    <li><a href="#">{{ trans('footer.careers') }}</a></li>
                    <li><a href="#">{{ trans('footer.terms_of_service') }}</a></li>
                    <li><a href="#">{{ trans('footer.privacy_policy') }}</a></li>
                    <li><a href="#">{{ trans('footer.contact_support') }}</a></li>
                </ul>
            </div>
            <div class="col-sm-3 color-white">
                <p class="text-uppercase margin-bottom-20 text-uppercase"><b>{{ trans('footer.how_it_works') }}</b></p>
                <ul>
                    <li><a href="#">{{ trans('footer.how_it_works') }}?</a></li>
                    <li><a href="#">{{ trans('footer.media_solutions') }}</a></li>
                    <li><a href="#">{{ trans('footer.partnerships') }}</a></li>
                </ul>
            </div>
            <div class="col-sm-3 color-white">
                <p class="text-uppercase margin-bottom-20"><b>{{ trans('footer.follow_us') }}</b></p>
                <ul>
                    <li><a href="#"><i class="fa fa-facebook" style="width: 18px;"></i>&nbsp;{{ trans('footer.facebook') }}</a></li>
                    <li><a href="#"><i class="fa fa-twitter" style="width: 18px;"></i>&nbsp;{{ trans('footer.twitter') }}</a></li>
                    <li><a href="#"><i class="fa fa-google-plus" style="width: 18px;"></i>&nbsp;{{ trans('footer.google_plus') }}</a></li>
                </ul>            
            </div>
            <div class="col-sm-3 color-white">
                <p class="text-uppercase margin-bottom-20 text-uppercase"><b>{{ trans('footer.news_letters') }}</b></p>
                <input type="text" class="form-control" placeholder="Email"/>

            </div>                                    
        </div>
    </div>
    <footer class="footer-area">
        <div class="container">
            <div class="footer-logo pull-left">
                <a href="/">{{ trans('footer.social_headhunter') }}</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </footer>
</div>
@stop

@section('scripts')	
	{{ HTML::script('/assets/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js') }}
    {{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/back-to-top.js') }}
    {{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/layout.js') }}
    
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/scripts/metronic.js') }}
    {{ HTML::script('/assets/metronic/assets/admin/pages/scripts/components-pickers.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}

    {{ HTML::script('/assets/js/star-rating.min.js') }}
        
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();
            Layout.initUniform();
            Layout.initTwitter();
            Metronic.init();
            ComponentsPickers.init();
        });
    </script>
@stop

@stop