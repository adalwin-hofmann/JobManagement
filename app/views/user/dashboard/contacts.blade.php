@extends('user.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.css') }}
@stop

@section('body')
<main class="bs-docs-masthead" role="main" style="min-height: 560px;">
	<div class="background-dashboard"></div>        
    <div class="container">
        <div class="margin-top-lg"></div>
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home color-white"> {{ trans('user.my_contacts') }}</h2>
        </div>

        <div class="col-sm-3">
            <ul class="tabbable faq-tabbable margin-bottom-normal custom-left-menu">
                <li class="">
                    <a href="{{ URL::route('user.dashboard.recommendations') }}">
                        <i class="fa fa-ticket"></i> {{ trans('user.recommendations') }}
                    </a>
                </li>
                <li class="active">
                    <a href="{{ URL::route('user.dashboard.contacts') }}">
                        <i class="fa fa-book"></i> {{ trans('user.contacts') }}</a>
                    </a>
                </li>
            </ul>
        </div>


        <div class="col-sm-9">
            <div class="row">
                <div class="portlet box gray">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-navicon"></i> {{ trans('user.contact_list') }}
                        </div>
                        <div class="actions">
                            <a onclick="showAddContact();" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;{{ trans('user.add_contact') }}
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- Div for Job Recommend sent -->
                        <div class="row">
                            <!-- Div for Job Recommend sent -->
                            <div class="col-sm-12">
                                @if (count($contacts) > 0)
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ trans('user.name') }}</th>
                                                <th>{{ trans('user.email') }}</th>
                                                <th>{{ trans('user.phone') }}</th>
                                                <th>{{ trans('user.previous_jobs') }}</th>
                                                <th style="width: 80px;">{{ trans('user.edit') }}</th>
                                                <th style="width: 80px;">{{ trans('user.delete') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($contacts as $key => $value)
                                            <tr id="tr_{{ $value->id }}">
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <a href="#" id="username" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="Required" data-original-title="Enter username" class="editable editable-click editable-open" data-id="{{ $value->id }}">{{ $value->name }}</a>
                                                </td>
                                                <td>
                                                    <a href="#" id="useremail" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="Required" data-original-title="Enter email address" class="editable editable-click editable-open" data-id="{{ $value->id }}">{{ $value->email }}</a>
                                                </td>
                                                <td>
                                                    <a href="#" id="userphone" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="(Optional)" data-original-title="Enter phone number" class="editable editable-click editable-open" data-id="{{ $value->id }}">{{ $value->phone }}</a>
                                                </td>
                                                <td>
                                                    <a href="#" id="userpreviousjobs" data-type="text" data-pk="1" data-placement="bottom" data-placeholder="(Optional)" data-original-title="Enter previous jobs" class="editable editable-click editable-open" data-id="{{ $value->id }}">{{ $value->previousJobs }}</a>
                                                </td>
                                                <td>
                                                    <a data-id="{{ $value->id }}" onclick="editContact(this);" class="btn btn-sm btn-info">
                                                        <span class="glyphicon glyphicon-edit"></span> {{ trans('user.edit') }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a data-id="{{ $value->id }}" onclick="deleteContact(this)" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash-o"></i> {{ trans('user.delete') }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-right">{{ $contacts->links() }}</div>
                                        </div>
                                    </div>


                                    @foreach ($contacts as $contact)
                                        <!-- Modal Div for Edit Contact -->
                                        <div class="modal fade" id="editContactModal{{ $contact->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('user.close') }}</span></button>
                                                        <h4 class="modal-title" id="msgModalLabel">{{ trans('user.edit_contact') }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                                    <span class="span-job-description-title">{{ trans('user.name') }}:</span>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" value="{{ $contact->name }}" name="contact_name" id="contact_name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                                    <span class="span-job-description-title">{{ trans('user.email') }}:</span>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" value="{{ $contact->email }}" name="contact_email" id="contact_email">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                                    <span class="span-job-description-title">{{ trans('user.phone') }}:</span>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" value="{{ $contact->phone }}" name="contact_phone" id="contact_phone">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                                    <span class="span-job-description-title">{{ trans('user.previous_jobs') }}:</span>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" value="{{ $contact->previousJobs }}" name="contact_previousJobs" id="contact_previousJobs">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('user.close') }}</button>
                                                        <button type="button" class="btn btn-primary" data-id="{{ $contact->id }}" onclick="saveContact(this)">{{ trans('user.save') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Div for Edit Contact -->
                                    @endforeach
                                @else
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            {{ trans('user.msg_28') }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Modal Div for Edit Contact -->
                                <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('user.close') }}</span></button>
                                                <h4 class="modal-title" id="msgModalLabel">{{ trans('user.add_contact') }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                            <span class="span-job-description-title">{{ trans('user.name') }}:</span>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" value="" name="contact_name" id="contact_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                            <span class="span-job-description-title">{{ trans('user.email') }}:</span>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" value="" name="contact_email" id="contact_email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                            <span class="span-job-description-title">{{ trans('user.phone') }}:</span>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" value="" name="contact_phone" id="contact_phone">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-2 col-sm-offset-1 padding-top-xxs">
                                                            <span class="span-job-description-title">{{ trans('user.previous_jobs') }}:</span>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" value="" name="contact_previousJobs" id="contact_previousJobs">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('user.close') }}</button>
                                                <button type="button" class="btn btn-primary" data-id="" onclick="saveContact(this)">{{ trans('user.save') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Div for Edit Contact -->
                            </div>
                            <!-- EOF for Job recommend sent -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@stop

@section('scripts')

    <!-- BEGIN PLUGINS USED BY X-EDITABLE -->
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery.mockjax.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js') }}
    <!-- END X-EDITABLE PLUGIN -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->
    {{ HTML::script('/assets/metronic/assets/global/scripts/metronic.js') }}
    {{ HTML::script('/assets/metronic/assets/admin/layout/scripts/quick-sidebar.js') }}
	{{ HTML::script('/assets/metronic/assets/admin/pages/scripts/form-editable.js') }}

    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            QuickSidebar.init() // init quick sidebar
            FormEditable.init();
        });
    </script>

	@include('js.user.dashboard.contacts')
@stop