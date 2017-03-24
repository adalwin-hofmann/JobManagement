@extends('user.layout')

@section('body')
<main class="bs-docs-masthead" role="main" style="min-height: 560px;">
	<div class="background-dashboard"></div>        
    <div class="container">
    	<div class="margin-top-lg"></div>
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home color-white"> {{ trans('job.my_application_cart') }}</h2>
        </div>

        <div class="col-sm-3">
            <ul class="tabbable faq-tabbable margin-bottom-normal custom-left-menu">
                <li class="@if ($statusType == 0) active @endif">
                    <a href="{{ URL::route('user.dashboard.cart', 0) }}">
                        {{ trans('job.job_list') }}
                    </a>
                </li>
                <li class="@if ($statusType == 1) active @endif">
                    <a href="{{ URL::route('user.dashboard.cart', 1) }}">
                        {{ trans('job.template_list') }}
                    </a>
                </li>
            </ul>
        </div>


        <div class="col-sm-9">
            @if ($statusType == 0)
                <div class="row">
                    <div class="portlet box gray">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-navicon"></i> {{ trans('job.job_list') }}
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-store-list" style="border-bottom: 1px solid #DDDDDD;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-uppercase">{{ trans('job.project_title') }}</th>
                                        <th class="text-center text-uppercase">{{ trans('job.by') }}</th>
                                        <th class="text-center text-uppercase">{{ trans('job.applies') }}</th>
                                        <th class="text-center text-uppercase">{{ trans('job.posted_date') }}</th>
                                        <th class="text-center text-uppercase">{{ trans('job.budget') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->carts as $cart)
                                    <tr>
                                        <td>{{ Form::checkbox(null, 0, null, ['class' => 'checkbox-normal', 'id' => 'job_select_checkbox', 'data-id' => $cart->job->id]) }}</td>
                                        <td><a href="{{ URL::route('user.dashboard.viewJob', $cart->job->slug) }}">{{ $cart->job->name }}</a></td>
                                        <td class="text-center"><a href="{{ URL::route('user.company.view', $cart->job->company->parent->slug) }}" class="white-tooltip" data-toggle="tooltip" data-html="true" data-placement="bottom" data-image-url="{{ HTTP_LOGO_PATH.$cart->job->company->parent->logo }}" data-tag="{{ $cart->job->company->parent->tag }}" data-description="{{ nl2br($cart->job->company->parent->description) }}">{{ $cart->job->company->parent->name }}</a></td>
                                        <td class="text-center">{{ count($cart->job->applies) }}</td>
                                        <td class="text-center">{{ $cart->job->created_at }}</td>
                                        <td class="text-center">{{ '$'.$cart->job->salary }}</td>
                                        <td class="text-center">
                                            <a data-id="{{ $cart->id }}" onclick="removeThisJob(this);" class="btn btn-danger btn-sm">{{ trans('job.delete') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if (count($user->carts) == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">{{ trans('job.there_is_no_jobs') }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <?php if (count($user->carts) != 0) {?>
                            <div class="margin-top-xs" style="margin-left: 10px;">
                                <a style="cursor:pointer;" onclick="checkAll()">{{ trans('job.select_all') }}</a>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <?php if (count($user->carts) != 0) {?>
                <div class="row margin-bottom-normal">
                    <div class="col-sm-2 col-sm-offset-5">
                        <button class="btn btn-success btn-sm btn-job-apply" id="js-btn-apply">{{ trans('job.apply') }}</button>
                    </div>
                </div>
                <?php }?>

                <div id="apply-div" class="hidden">
                    <div class="row margin-bottom-normal">
                        <div class="col-sm-12 jop-apply-div">

                            <div class="row">
                                <div class="col-sm-2 col-sm-offset-1">
                                    {{ Form::label('', 'Pattern', ['class' => 'margin-top-xs']) }}
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control" onchange="changePattern(this);">
                                        @foreach($patterns as $pattern)
                                        <option value="{{ $pattern->name }}" data-description="{{ $pattern->description }}">{{ $pattern->name }}</option>
                                        @endforeach
                                        <option value="" data-descripton="">{{ trans('job.other') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row margin-top-xs">
                                <div class="col-sm-2 col-sm-offset-1">
                                    {{ Form::label('', trans('job.title'), ['class' => 'margin-top-xs']) }}
                                </div>
                                <div class="col-sm-8">
                                    {{ Form::text('title', $patterns[0]->name, ['class' => 'form-control', 'id' => 'title']) }}
                                </div>
                            </div>

                            <div class="row margin-top-xs">
                                <div class="col-sm-2 col-sm-offset-1">
                                    {{ Form::label('', trans('job.description'), ['class' => 'margin-top-xs']) }}
                                </div>
                                <div class="col-sm-8">
                                    {{ Form::textarea('description', $patterns[0]->description, ['class' => 'form-control job-description', 'rows' => '5', 'id' => 'description']) }}
                                </div>
                            </div>

                            <div class="row margin-top-sm">
                                <div class="col-sm-8 col-sm-offset-3 text-right">
                                    <div class="col-sm-4 col-sm-offset-8 text-center">
                                        <button class="btn btn-sm btn-primary text-uppercase btn-block" id="js-btn-submit">{{ trans('job.submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="portlet box gray">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-navicon"></i> {{ trans('job.template_list') }}
                            </div>
                            <div class="actions">
                                <a onclick="showCreateModal();" class="btn btn-default btn-sm">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp;{{ trans('job.create_application_template') }}
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!-- Modal Div for Create Template -->
                            <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title" id="msgModalLabel">{{ trans('job.create_application_template') }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-2 col-sm-offset-1">
                                                    {{ Form::label('', 'Title', ['class' => 'margin-top-xs']) }}
                                                </div>
                                                <div class="col-sm-8">
                                                    {{ Form::text('create_title', '', ['class' => 'form-control', 'id' => 'create_title']) }}
                                                </div>
                                            </div>

                                            <div class="row margin-top-xs">
                                                <div class="col-sm-2 col-sm-offset-1">
                                                    {{ Form::label('', 'Description', ['class' => 'margin-top-xs']) }}
                                                </div>
                                                <div class="col-sm-8">
                                                    {{ Form::textarea('create_description', '', ['class' => 'form-control', 'rows' => '5', 'id' => 'create_description']) }}
                                                </div>
                                            </div>

                                            <div class="row margin-top-xs">
                                                <div class="col-sm-10 col-sm-offset-1">
                                                    <div class="alert alert-danger alert-dismissibl" id="js-div-create-warnning" style="display: none;">
                                                        <button type="button" class="close" id="js-btn-modal-close">
                                                            <span aria-hidden="true">&times;</span>
                                                            <span class="sr-only">{{ trans('job.close') }}</span>
                                                        </button>
                                                        <p id="js-p-create-warnning">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.close') }}</button>
                                            <button type="button" class="btn btn-primary" id="js-btn-pattern-create">{{ trans('job.create') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Div for Create Template -->

                            <table class="table table-store-list" style="border-bottom: 1px solid #DDDDDD;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Title</th>
                                        <th class="text-center">{{ trans('job.created_at') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($myPatterns as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td class="text-center">{{ $value->created_at }}</td>
                                        <td class="text-right">
                                            <a data-id="{{ $value->id }}" onclick="showEditModal(this);" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> {{ trans('job.edit') }}</a>
                                            <a data-id="{{ $value->id }}" onclick="removePattern(this);" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> {{ trans('job.delete') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if (count($myPatterns) == 0)
                                    <tr>
                                        <td colspan="7" class="text-center">{{ trans('job.msg_20') }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>


                            @foreach ($myPatterns as $pattern)
                                <!-- Modal Div for Create Template -->
                                <div class="modal fade" id="editModal{{ $pattern->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                                                <h4 class="modal-title" id="msgModalLabel">{{ trans('job.edit_application_template') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-2 col-sm-offset-1">
                                                        {{ Form::label('', trans('job.title'), ['class' => 'margin-top-xs']) }}
                                                    </div>
                                                    <div class="col-sm-8">
                                                        {{ Form::text('edit_title', $pattern->name, ['class' => 'form-control', 'id' => 'edit_title']) }}
                                                    </div>
                                                </div>

                                                <div class="row margin-top-xs">
                                                    <div class="col-sm-2 col-sm-offset-1">
                                                        {{ Form::label('', trans('job.description'), ['class' => 'margin-top-xs']) }}
                                                    </div>
                                                    <div class="col-sm-8">
                                                        {{ Form::textarea('edit_description', $pattern->description, ['class' => 'form-control', 'rows' => '5', 'id' => 'edit_description']) }}
                                                    </div>
                                                </div>

                                                <div class="row margin-top-xs">
                                                    <div class="col-sm-10 col-sm-offset-1">
                                                        <div class="alert alert-danger alert-dismissibl" id="js-div-create-warnning" style="display: none;">
                                                            <button type="button" class="close" id="js-btn-modal-close">
                                                                <span aria-hidden="true">&times;</span>
                                                                <span class="sr-only">{{ trans('job.close') }}</span>
                                                            </button>
                                                            <p id="js-p-create-warnning">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.close') }}</button>
                                                <button type="button" class="btn btn-primary" id="js-btn-pattern-save" data-id="{{ $pattern->id }}" onclick="savePattern(this)">{{ trans('job.save') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Div for Create Template -->
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</main>
@stop

@section('custom-scripts')
	@include('js.user.dashboard.myApply')
@stop