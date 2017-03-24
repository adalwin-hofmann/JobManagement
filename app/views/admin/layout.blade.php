@extends('main')

@section('styles')
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
	
	{{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}

	{{ HTML::style('/assets/metronic/assets/admin/layout/css/layout.css') }}
	{{ HTML::style('/assets/metronic/assets/admin/layout/css/themes/blue.css') }}
	{{ HTML::style('/assets/metronic/assets/admin/layout/css/custom.css') }}
    
    {{ HTML::style('/assets/css/style_bootstrap.css') }}
    {{ HTML::style('/assets/css/style_common.css') }}	
	
    {{ HTML::style('/assets/css/style_admin.css') }}
    
    @yield('custom_styles')
@stop

@section('header')
    <header class="header">        
		<div class="page-header navbar navbar-fixed-top">
			<!-- BEGIN HEADER INNER -->
			<div class="page-header-inner">
				<!-- BEGIN LOGO -->
				<div class="page-logo" style="padding-top: 10px;">
					<a href="{{ URL::route('admin.dashboard') }}">
					    <span class="admin-logo-title">Social Headhunter</span>
					</a>
				</div>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
				</a>
				<!-- END RESPONSIVE MENU TOGGLER -->
				<!-- BEGIN TOP NAVIGATION MENU -->
				<div class="top-menu">
				    <?php if (Session::has('admin_id')) {?>
		    			<ul class="nav navbar-nav pull-right">
		    				<li class="dropdown dropdown-quick-sidebar-toggler">
		    					<a href="{{ URL::route('admin.auth.doLogout') }}" class="dropdown-toggle">
		    					    <i class="icon-logout"></i> Logout
		    					</a>
		    				</li>
		    			</ul>
					<?php }?>
				</div>
				<!-- END TOP NAVIGATION MENU -->
			</div>
			<!-- END HEADER INNER -->
		</div>
		
		<div class="clearfix"></div>

    </header>
@stop

@section('body')
    <div class="page-container">
    
        <?php if (!isset($pageNo)) { $pageNo = 0; } ?> 	
		<div class="page-sidebar-wrapper">
			<div class="page-sidebar navbar-collapse collapse">
				<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
					<li class="sidebar-toggler-wrapper">
						<div class="sidebar-toggler"></div>
					</li>
	
					<li class="start <?php echo ($pageNo == 0) ? "active" : "";?>">
						<a href="{{ URL::route('admin.dashboard') }}">
						    <i class="icon-bar-chart"></i>
						    <span class="title">Dashboard</span>
						</a>
					</li>

					<li class="<?php echo ($pageNo == 23) ? "active" : "";?>">
                        <a href="{{ URL::route('admin.job.news') }}">
                            <i class="fa fa-rocket"></i>
                            <span class="title">Today's New Jobs</span>
                        </a>
                    </li>
					

					<li class="<?php echo ($pageNo == 21) ? "active" : "";?>">
						<a href="{{ URL::route('admin.user') }}">
						    <i class="icon-users"></i>
						    <span class="title">User Management</span>
						</a>
					</li>

                    <li class="<?php echo ($pageNo == 22) ? "active" : "";?>">
                        <a href="{{ URL::route('admin.user.collected') }}">
                            <i class="fa fa-child"></i>
                            <span class="title">Collected User</span>
                        </a>
                    </li>

					
					<li class="<?php echo ($pageNo == 10) ? "active" : "";?>">
						<a href="{{ URL::route('admin.company') }}">
						    <i class="fa fa-building"></i>
						    <span class="title">Company Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 11) ? "active" : "";?>">
						<a href="{{ URL::route('admin.job') }}">
						    <i class="icon-diamond"></i>
						    <span class="title">Job Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 6) ? "active" : "";?>">
						<a href="{{ URL::route('admin.category') }}">
						    <i class="fa fa-tag"></i>
						    <span class="title">Category Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 1) ? "active" : "";?>">
						<a href="{{ URL::route('admin.country') }}">
						    <i class="fa fa-globe"></i>
						    <span class="title">Country Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 2) ? "active" : "";?>">
						<a href="{{ URL::route('admin.city') }}">
						    <i class="fa fa-map-marker"></i>
						    <span class="title">City Management</span>
						</a>
					</li>
					
					<li class="{{ ($pageNo == 14) ? 'active' : '' }}">
						<a href="{{ URL::route('admin.email') }}">
						    <i class="fa fa-envelope-o"></i>
						    <span class="title">Email Management</span>
						</a>
					</li>
					
					<li class="{{ ($pageNo == 15) ? 'active' : '' }}">
						<a href="{{ URL::route('admin.setting') }}">
						    <i class="fa fa-gear"></i>
						    <span class="title">Setting Management</span>
						</a>
					</li>

					<li class="{{ ($pageNo == 17) ? 'active' : '' }}">
						<a href="{{ URL::route('admin.label') }}">
						    <i class="fa fa-bookmark"></i>
						    <span class="title">Label Management</span>
						</a>
					</li>

					<li class="{{ ($pageNo == 16) ? 'active' : '' }}">
						<a href="{{ URL::route('admin.group') }}">
						    <i class="fa fa-sitemap"></i>
						    <span class="title">Group Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 3) ? "active" : "";?>">
						<a href="{{ URL::route('admin.service') }}">
						    <i class="fa fa-glass"></i>
						    <span class="title">Service Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 4) ? "active" : "";?>">
						<a href="{{ URL::route('admin.teamsize') }}">
						    <i class="fa fa-users"></i>
						    <span class="title">Teamsize Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 5) ? "active" : "";?>">
						<a href="{{ URL::route('admin.language') }}">
						    <i class="fa fa-language"></i>
						    <span class="title">Language Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 7) ? "active" : "";?>">
						<a href="{{ URL::route('admin.level') }}">
						    <i class="fa fa-signal"></i>
						    <span class="title">Level Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 8) ? "active" : "";?>">
						<a href="{{ URL::route('admin.type') }}">
						    <i class="fa fa-star"></i>
						    <span class="title">Type Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 9) ? "active" : "";?>">
						<a href="{{ URL::route('admin.presence') }}">
						    <i class="fa fa-tags"></i>
						    <span class="title">Presence Management</span>
						</a>
					</li>
					
					<li class="<?php echo ($pageNo == 12) ? "active" : "";?>">
						<a href="{{ URL::route('admin.pattern') }}">
						    <i class="fa fa-rocket"></i>
						    <span class="title">Pattern Management</span>
						</a>
					</li>

                    <li class="<?php echo ($pageNo == 13) ? "active" : "";?>">
                        <a href="{{ URL::route('admin.skill') }}">
                            <i class="fa fa-cubes"></i>
                            <span class="title">Skill Management</span>
                        </a>
                    </li>

                    <li class="<?php echo ($pageNo == 24) ? "active" : "";?>">
                        <a href="{{ URL::route('admin.other') }}">
                            <i class="fa fa-asterisk"></i>
                            <span class="title">Other Management</span>
                        </a>
                    </li>
												
				</ul>
				<!-- END SIDEBAR MENU -->
			</div>
		</div>
        <div class="page-content-wrapper">
        	<div class="page-content" style="min-height: 554px;">
	            <div class="pull-right top-buttons">
	                @yield('top-buttons')
	            </div>
	            <div class="clearfix"></div>
	            
	            @yield('content')       	
        	</div>
        </div>
    </div>
@stop

@section('footer')
	<div class="page-footer">
		<div class="page-footer-inner">
			 &copy; Copyright 2015 | All Rights Reserved | Powered by Finternet-Group
		</div>
		<div class="page-footer-tools">
			<span class="go-top">
			<i class="fa fa-angle-up"></i>
			</span>
		</div>
	</div>
@stop

@section('scripts')
	{{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
	
	{{ HTML::script('/assets/metronic/assets/global/scripts/metronic.js') }}
	{{ HTML::script('/assets/metronic/assets/admin/layout/scripts/layout.js') }}
	{{ HTML::script('/assets/metronic/assets/admin/layout/scripts/quick-sidebar.js') }}
	{{ HTML::script('/assets/metronic/assets/admin/pages/scripts/table-managed.js') }}
	<script>
	jQuery(document).ready(function() {       
	    Metronic.init();
	    Layout.init();
	    QuickSidebar.init();
	});
	</script>    

	@yield('custom_scripts')
@stop

@stop