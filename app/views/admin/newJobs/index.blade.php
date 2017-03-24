@extends('admin.layout')

@section('custom-styles')

    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css') }}

@stop

@section('content')
<?php if (isset($alert)) { ?>
<div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <p>
        <?php echo $alert['msg'];?>
    </p>
</div>
<?php } ?>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">Job Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Job</span>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<span>List</span>
			</li>
		</ul>

	</div>
</div>

<div class="row margin-top-sm">
    <div class="col-sm-12">
        <ul class="nav nav-tabs nav-justified ul-project-category" role="tablist">
            <li class="@if ($sid == 0) active @endif">
                <a href="{{ URL::route('admin.job.news', 0) }}">mol.fi</a>
            </li>
            <li class="@if ($sid == 1) active @endif">
                <a href="{{ URL::route('admin.job.news', 1) }}">monster.fi</a>
            </li>
            <li class="@if ($sid == 2) active @endif">
                <a href="{{ URL::route('admin.job.news', 2) }}">cvonline.ee</a>
            </li>
            <li class="@if ($sid == 3) active @endif">
                <a href="{{ URL::route('admin.job.news', 3) }}">cv.lv</a>
            </li>
            <li class="@if ($sid == 4) active @endif">
                <a href="{{ URL::route('admin.job.news', 4) }}">cv.lt</a>
            </li>
        </ul>
    </div>
</div>

<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Job List
		</div>

		<div class="actions">
            <a href="{{ URL::route('admin.job.newsActive') }}" class="btn btn-default btn-sm" @if (count($jobs) == 0) disabled @endif>
                Active All Jobs
            </a>
        </div>
	</div>


    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact Address</th>
                    <th>Apply Link</th>
                    <th>Link</th>
                    <th>Category</th>
                    <th>Company Name</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td style="word-break: break-word;">{{ $value->name }}</td>
                        <td style="word-break: break-all;">
                            <a href="#" id="contactemail" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="Required" data-original-title="Enter contact address" class="editable editable-click editable-open" data-id="{{ $value->id }}" origin-valu="{{ $value->email }}">
                                {{ $value->email }}
                            </a>
                        </td>
                        <td style="word-break: break-word;">
                            <a href="#" id="applylink" data-type="textarea" data-pk="1" data-placement="bottom" data-placeholder="http://www.example.com" data-original-title="Enter apply link" data-id="{{ $value->id }}" origin-value="{{ trim($value->link_address) }}">{{ trim($value->link_address) }}</a>
                        </td>
                        <td>
                            <a href="{{ $value->job_link }}" class="btn btn-sm blue-hoki" target="_blank">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                        <td style="word-break: break-all;">
                            <a href="#" id="categoryname" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="Required" data-original-title="Enter category name" class="editable editable-click editable-open" data-id="{{ $value->category->id }}" orgin-value="{{ $value->category->name }}">
                                {{ $value->category->name }}
                            </a>
                        </td>
                        <td>
                            <a href="#" id="companyname" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="Required" data-original-title="Enter company name" class="editable editable-click editable-open" data-id="{{ $value->company->id }}" origin-value="{{ $value->company->name }}">
                                {{ $value->company->name }}
                            </a>
                        </td>
                        <td>
                            <a href="#" id="cityname" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="Required" data-original-title="Enter city name" class="editable editable-click editable-open" data-id="{{ $value->city->id }}" origin-value="{{ $value->city->name }}">
                                {{ $value->city->name }}
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-sm @if ($value->is_active == 0) default @else green @endif" data-id="{{ $value->id }}" onclick="changeStatus(this);">
                                {{ ($value->is_active == 0) ? 'Deactive':'Active' }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('admin.job.newsEdit', $value->id)  }}" class="btn btn-sm btn-info" alt="Edit">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('admin.job.delete', $value->id)  }}" class="btn btn-sm btn-danger" id="js-a-delete" alt="Delete">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
        	<div class="col-sm-12">
        		<div class="pull-right">{{ $jobs->links() }}</div>
        	</div>
        </div>
    </div>
</div>
@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}


    <!-- BEGIN CORE PLUGINS -->
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/uniform/jquery.uniform.min.js') }}
    <!-- END CORE PLUGINS -->


    <!-- BEGIN PLUGINS USED BY X-EDITABLE -->
    {{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/moment.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/jquery.mockjax.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js') }}
    <!-- END X-EDITABLE PLUGIN -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {{ HTML::script('/assets/metronic/assets/global/scripts/metronic.js') }}
    {{ HTML::script('/assets/metronic/assets/admin/layout/scripts/layout.js') }}
    {{ HTML::script('/assets/metronic/assets/admin/layout/scripts/quick-sidebar.js') }}
    {{ HTML::script('/assets/metronic/assets/admin/pages/scripts/form-editable.js') }}
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>
        jQuery(document).ready(function() {
            FormEditable.init();
        });
    </script>

    @include('js.admin.newJobs.index')
@stop

@stop
