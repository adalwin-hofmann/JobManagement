@extends('agency.layout')

@section('custom-styles')
	{{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
@stop

@section('body')
<div class="gray-container padding-top-xs padding-bottom-xs">
    <div class="container" style="min-height: 490px; background: white;">
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home">{{ trans('company.company_list') }}</h2>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i> {{ trans('company.companies_table') }}
                        </div>
                        <div class="actions">
                            <a onclick="showAddModal()" class="btn btn-default btn-sm">
                                <i class="fa fa-plus"></i> Add
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body" id="table-content">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                                <tr>
                                    <th>
                                         Name
                                    </th>
                                    <th>
                                         Email
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                    <th class="text-center">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $key => $value)
                                    <tr class="odd gradX">
                                        <td>
                                            {{ $value->name }}
                                        </td>
                                        <td>
                                            {{ $value->email }}
                                        </td>
                                        <td>
                                            @if ($value->isClient($agency->id))
                                                YES
                                            @else
                                                NO
                                            @endif
                                        </td>
                                        <td>
                                            @if ($value->isClient($agency->id))
                                                <button class="btn btn-sm btn-danger margin-top-xs" data-id="{{ $value->id }}" onclick="removeClient(this)">Remove from Client</button>
                                            @else
                                                <button class="btn btn-sm btn-primary" data-id="{{ $value->id }}" onclick="setClient(this)">Set as Client</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Div for Add Candidate -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                <h4 class="modal-title" id="msgModalLabel">{{ trans('company.add_company') }}</h4>
            </div>
            <div class="modal-body">

                <form action="#" id="form_sample_1" class="form-horizontal" novalidate="novalidate">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        {{ trans('user.msg_32') }}
                    </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button>
                        {{ trans('user.msg_33') }}
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-1">
                            {{ Form::label('', 'Name', ['class' => 'margin-top-xs']) }} <span class="required">*</span>
                        </div>
                        <div class="col-sm-7">
                            {{ Form::text('name', '', ['class' => 'form-control', 'id' => 'name', 'data-required' => '1']) }}
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-3 col-sm-offset-1">
                            {{ Form::label('', 'Email', ['class' => 'margin-top-xs']) }} <span class="required">*</span>
                        </div>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control" placeholder="Email Address" id="email">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row margin-top-xs">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="alert alert-danger alert-dismissibl" id="js-div-add-warnning" style="display: none;">
                            <button type="button" class="close" id="js-btn-modal-close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">{{ trans('job.close') }}</span>
                            </button>
                            <p id="js-p-add-warnning">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="addCompany(this)">{{ trans('company.add') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- EOF for Add Candidate -->

@stop

@section('custom-scripts')

	{{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}

    @include('js.agency.company.index')

	<script>
        jQuery(document).ready(function() {
          	TableManaged.init();
          	FormValidation.init();
        });
    </script>


@stop