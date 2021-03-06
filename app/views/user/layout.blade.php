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
                    @if (Session::has('company_id') || Session::has('agency_id'))
                    <a class="company-site-logo" href="/"><img src="/assets/img/logo.jpg"/></a>
                    @endif
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-6 col-sm-6 additional-nav">
                    @if (Session::has('user_id'))
                        <ul class="nav navbar-nav pull-right" style="margin-left: 20px;">
                            <li class="setting-menu dropdown dropdown-user">
                                <a class="dropdown-toggle" dropdown-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img class="img-circle" src="{{ HTTP_PHOTO_PATH.$user->profile_image }}">  <span><b>{{ $user->name }}</b></span> <i class="fa fa-angle-down"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li class="{{ ($pageNo == 4) ? 'active' : ''}}">
                                        <a href="{{ URL::route('user.dashboard.profile') }}">
                                        <i class="fa fa-user"></i> {{ trans('menu.profile') }} </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::route('user.auth.doLogout') }}">
                                        <i class="fa fa-key"></i> {{ trans('menu.sign_out') }} </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @elseif (Session::has('company_id'))
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
                    @elseif (Session::has('agency_id'))
                        <ul class="nav navbar-nav pull-right" style="margin-left: 20px;">
                            <li class="setting-menu dropdown dropdown-user">
                                <a class="dropdown-toggle" dropdown-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img src="{{ HTTP_LOGO_PATH.$agency->logo}}">  <span><b>{{ $agency->name }}</b></span> <i class="fa fa-angle-down"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    @if (Session::get('agency_is_admin') == 1)
                                        <li class="{{ ($pageNo == 4) ? 'active' : ''}}">
                                            <a href="{{ URL::route('agency.profile') }}">
                                            <i class="fa fa-user"></i> {{ trans('menu.profile') }} </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ URL::route('agency.auth.doLogout') }}">
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
            @if (!Session::has('company_id') && !Session::has('agency_id'))
        	<a class="site-logo" href="/"><img src="/assets/img/logo.png"/></a>
        	@endif
			<a href="javascript:void(0);" class="mobi-toggler"><i class="fa fa-bars"></i></a>
			
            <div class="header-navigation pull-right font-transform-bitter @if (Session::has('company_id') || Session::has('agency_id')) company-menu-bar @else user-menu-bar @endif">
                <ul class="nav nav-pills nav-top">
                    @if (Session::has('user_id'))
                    	<li class="{{ ($pageNo == 3) ? 'active' : ''}}"><a href="{{ URL::route('user.dashboard') }}">{{ trans('menu.dashboard') }}</a></li>
                    	<li class="{{ ($pageNo == 1) ? 'active' : ''}}"><a href="{{ URL::route('user.job.search') }}">{{ trans('menu.find_job') }}</a></li>
                    	<li class="{{ ($pageNo == 8) ? 'active' : ''}}"><a href="{{ URL::route('user.company.search') }}">{{ trans('menu.find_company') }}</a></li>
                        <li class="{{ ($pageNo == 2) ? 'active' : ''}}"><a href="{{ URL::route('user.dashboard.cart') }}">{{ trans('menu.application_cart') }}</a></li>
                        <li class="{{ ($pageNo == 6) ? 'active' : ''}}"><a href="{{ URL::route('user.dashboard.recommendations') }}">{{ trans('menu.recommendations') }}</a></li>
                        <li class="{{ ($pageNo == 7) ? 'active' : ''}}"><a href="{{ URL::route('user.message.list') }}">Messages</a></li>
                        <!-- li class="{{ ($pageNo == 5) ? 'active' : ''}}"><a href="{{ URL::route('user.dashboard.appliedJobs') }}">My Apply</a></li -->
                    @elseif (Session::has('company_id'))
                    	<li class="{{ ($pageNo == 3) ? 'active' : ''}}"><a href="{{ URL::route('company.dashboard') }}">{{ trans('menu.dashboard') }}</a></li>
                    	<li class="{{ ($pageNo == 1) ? 'active' : ''}}"><a href="{{ URL::route('company.job.add') }}">{{ trans('menu.post_job') }}</a></li>
                        <li class="{{ ($pageNo == 2) ? 'active' : ''}}"><a href="{{ URL::route('company.job.myjobs' )}}">{{ trans('menu.my_jobs') }}</a></li>
                        <li class="{{ ($pageNo == 5) ? 'active' : ''}}"><a href="{{ URL::route('company.user.find' )}}">{{ trans('menu.find_people') }}</a></li>
                        <li class="{{ ($pageNo == 6) ? 'active' : ''}}"><a href="{{ URL::route('company.user.applied' )}}">{{ trans('menu.applied_people') }}</a></li>
                        <li class="{{ ($pageNo == 7) ? 'active' : ''}}"><a href="{{ URL::route('company.message.list' )}}">{{ trans('menu.messages') }}</a></li>
                        <li class="{{ ($pageNo == 9) ? 'active' : ''}}"><a href="{{ URL::route('company.share') }}">{{ trans('menu.share_management') }}</a></li>
                        <li class="{{ ($pageNo == 10) ? 'active' : ''}}"><a href="{{ URL::route('company.interview.face') }}">Face Interview</a></li>
                    @elseif (Session::has('agency_id'))
                        <li class="{{ ($pageNo == 3) ? 'active' : ''}}"><a href="{{ URL::route('agency.dashboard') }}">{{ trans('menu.dashboard') }}</a></li>
                        <li class="{{ ($pageNo == 1) ? 'active' : ''}}"><a href="{{ URL::route('agency.job.add') }}">{{ trans('menu.post_job') }}</a></li>
                        <li class="{{ ($pageNo == 2) ? 'active' : ''}}"><a href="{{ URL::route('agency.job.myjobs' )}}">{{ trans('menu.my_jobs') }}</a></li>
                        <li class="{{ ($pageNo == 5) ? 'active' : ''}}"><a href="{{ URL::route('agency.user.find' )}}">{{ trans('menu.find_people') }}</a></li>
                        <li class="{{ ($pageNo == 6) ? 'active' : ''}}"><a href="{{ URL::route('agency.user.applied' )}}">{{ trans('menu.applied_people') }}</a></li>
                        <li class="{{ ($pageNo == 7) ? 'active' : ''}}"><a href="{{ URL::route('agency.message.list' )}}">{{ trans('menu.messages') }}</a></li>
                        <li class="{{ ($pageNo == 8) ? 'active' : ''}}"><a href="{{ URL::route('agency.user.candidates' )}}">{{ trans('menu.candidates') }}</a></li>
                        <li class="{{ ($pageNo == 9) ? 'active' : ''}}"><a href="{{ URL::route('agency.share') }}">{{ trans('menu.share_management') }}</a></li>
                    @else
                    	<li class="{{ ($pageNo == 1) ? 'active' : ''}}"><a href="{{ URL::route('user.job.search') }}">{{ trans('menu.find_job') }}</a></li>
                        <li class="{{ ($pageNo == 98) ? 'active' : ''}}">
                        	<a href="{{ URL::route('user.auth.login') }}">{{ trans('menu.sign_in') }}</a>
                        </li>
                        <li class="{{ ($pageNo == 99) ? 'active' : ''}}">
                        	<a href="{{ URL::route('user.auth.signup') }}">{{ trans('menu.register') }}</a>
                        </li>
                        <li class="menu-search">
                            <span class="sep"></span>
                        </li>
                        <li><a href="{{ URL::route('agency.dashboard') }}" id="menu-for-company">{{ trans('menu.for_agency') }}</a></li>
                        <li><a href="{{ URL::route('company.dashboard') }}" id="menu-for-company">{{ trans('menu.for_company') }}</a></li>
                    @endif
                </ul>
            </div>
            <div class="clearfix"></div>
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
                    <li><a href="{{ URL::route('user.aboutUs') }}">{{ trans('footer.about_us') }}</a></li>
                    <li><a href="#">{{ trans('footer.blog') }}</a></li>
                    <li><a href="#">{{ trans('footer.careers') }}</a></li>
                    <li><a href="#">{{ trans('footer.terms_of_service') }}</a></li>
                    <li><a href="#">{{ trans('footer.privacy_policy') }}</a></li>
                    <li><a href="#">{{ trans('footer.contact_support') }}</a></li>
                </ul>
            </div>
            <div class="col-sm-3 color-white">
                <p class="text-uppercase margin-bottom-20"><b>{{ trans('footer.how_it_works') }}</b></p>
                <ul>
                    <li><a href="#">{{ trans('footer.how_it_works') }}?</a></li>
                    <li><a href="#">{{ trans('footer.media_solutions') }}</a></li>
                    <li><a href="#">{{ trans('footer.partnerships') }}</a></li>
                    <li><a href="{{ URL::route('user.consumerBasic') }}">{{ trans('footer.consumer_basic') }}</a></li>
                    <li><a href="{{ URL::route('user.consumers') }}">{{ trans('footer.consumers') }}</a></li>
                    <li><a href="{{ URL::route('user.featureBusinessSmall') }}">{{ trans('footer.feature_business') }}</a></li>
                    <li><a href="{{ URL::route('user.featureBusiness') }}">{{ trans('footer.for_business') }}</a></li>
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
                <p class="text-uppercase margin-bottom-20"><b>{{ trans('footer.news_letters') }}</b></p>
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
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-62451673-1', 'auto');
      ga('send', 'pageview');
    </script>
    
	{{ HTML::script('/assets/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js') }}
    {{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/back-to-top.js') }}
    {{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/layout.js') }}
    
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}

    {{ HTML::script('/assets/js/star-rating.min.js') }}
        
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();
            Layout.initUniform();
            Layout.initTwitter();
        });
    </script>
    @include('js.user.layout')
@stop

@stop