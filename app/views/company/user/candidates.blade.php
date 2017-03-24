@extends('company.layout')

@section('custom-styles')
	{{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
@stop

@section('body')
<div class="gray-container padding-top-xs padding-bottom-xs">
    <div class="container" style="min-height: 490px; background: white;">
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home">{{ trans('user.candidates_management') }}</h2>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i> {{ trans('user.candidates_table') }}
                        </div>
                        <div class="actions">
                            <a onclick="showAddModal()" class="btn btn-default btn-sm">
                                <i class="fa fa-plus"></i> Add
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                                <tr>
                                    <th>
                                         Username
                                    </th>
                                    <th>
                                         Email
                                    </th>
                                    <th>
                                         Phone Number
                                    </th>
                                    <th>
                                        Created By
                                    </th>
                                    <th class="text-center">
                                    </th>
                                    <th class="text-center">
                                    </th>
                                    <th class="text-center">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $value)
                                    <tr class="odd gradX">
                                        <td>
                                            {{ $value->user->name }}
                                        </td>
                                        <td>
                                            {{ $value->user->email }}
                                        </td>
                                        <td>
                                            {{ $value->user->phone }}
                                        </td>
                                        <td>
                                            @if ($value->company->is_admin == 1) Admin @else {{ $value->company->name }} @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm btn-home" onclick="showMsgModal(this)" data-id="{{ $value->user->id }}">Send Message</button>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm green" id="js-a-apply-interview" data-name="{{ $value->user->name }}" data-userid="{{ $value->user->id }}">
                                                <i class="fa fa-comments-o"></i> Interview
                                            </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm btn-home" onclick="showMoveModal(this)" data-id="{{ $value->user->id }}">Move</button>
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
                <h4 class="modal-title" id="msgModalLabel">{{ trans('user.add_candidate') }}</h4>
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

                    <div class="row margin-top-xs">
                        <div class="col-sm-3 col-sm-offset-1">
                            {{ Form::label('', 'Phone Number', ['class' => 'margin-top-xs']) }}
                        </div>
                        <div class="col-sm-7">
                            {{ Form::text('phone', '', ['class' => 'form-control', 'id' => 'phone']) }}
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-3 col-sm-offset-1">
                            {{ Form::label('', 'Note', ['class' => 'margin-top-xs']) }}
                        </div>
                        <div class="col-sm-7">
                            {{ Form::textarea('note', '', ['class' => 'form-control', 'id' => 'note']) }}
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
                <button type="button" class="btn btn-primary" id="js-btn-add-candidate">{{ trans('company.add') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- EOF for Add Candidate -->


<!-- Modal Div for Send Message -->
<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" id="msg_userId" value="" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="msgModalLabel">Send Message</h4>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <textarea class="form-control" rows="8" id="txt_message"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="js-btn-send-message">Send</button>
            </div>
        </div>
    </div>
</div>
<!-- End Div for Send Message -->


<!-- Modal Div for Move To Jobs -->
<div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" id="msg_userId" value="" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="msgModalLabel">Move to Jobs</h4>
            </div>
            <div class="modal-body">
                <div id="js-div-move-job-container">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Div for Move To Jobs -->




<!-- Modal Div for Video Interview -->
<div class="modal fade" id="viModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="msgModalLabel">{{ trans('job.send_video_interview') }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="js-input-vi-userid" value="">
                    @if (count($viTemplates) == 0 || count($questionnaires) == 0)
                    <div class="row margin-bottom-xs">
                        <div class="col-sm-12">
                            <div class="alert alert-danger alert-dismissibl fade in" style="margin-bottom: 0px;">
                                <button type="button" class="close" onclick="hideQuestionnaireQuestionsAlert(this);">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">{{ trans('company.close') }}</span>
                                </button>
                                <p>{{ trans('company.msg_41') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="margin-top-xs">{{ trans('job.to') }}</label>
                        </div>
                        <div class="col-sm-9">
                            <label id="js-label-vi-username" class="margin-top-xs"></label>
                        </div>
                    </div>
                    <div class="row margin-top-xs">
                        <div class="col-sm-3">
                            <label class="margin-top-xs"> {{ trans('job.questionnaire') }}</label>
                        </div>
                        <div class="col-sm-9">
                            {{ Form::select('questionnaire_id'
                               , $questionnaires->lists('title', 'id')
                               , null
                               , array('class' => 'form-control', 'id' => 'js-vi-questionnaire-id')) }}
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-3">
                            <label class="margin-top-xs">{{ trans('job.expiration_date') }}</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" data-date-viewmode="years" data-date-minviewmode="months">
                                <input type="text" class="form-control" readonly="" name="vi-expiration" value="{{ date("Y-m-d",strtotime("+1 week")) }}" id="vi-expiration">
                                <span class="input-group-btn">
                                <button class="btn default" type="button" style="padding-top: 7px; padding-bottom: 8px;   border: 1px solid rgb(219, 219, 219);"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-3">
                            <label class="margin-top-xs">{{ trans('job.template') }}</label>
                        </div>
                        <div class="col-sm-9">
                            {{ Form::select('vi_template_id'
                               , $viTemplates->lists('title', 'id')
                               , null
                               , array('class' => 'form-control', 'id' => "js-vi-template-id")) }}
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-3">
                            <label class="margin-top-xs">{{ trans('job.subject') }}</label>
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control" id="js-vi-template-title" value="@if (count($viTemplates) > 0) {{ $viTemplates[0]->title }} @endif"/>
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-12">
                            <textarea class="form-control" id="js-vi-template-description">@if (count($viTemplates) > 0){{ $viTemplates[0]->description }}@endif</textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.cancel') }}</button>
                <button type="button" class="btn btn-primary @if (count($viTemplates) == 0 || count($questionnaires) == 0) disabled @endif" onclick="sendVIInterview(this);">{{ trans('job.send') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Div for Video Interview -->

@stop

@section('custom-scripts')

	<!-- BEGIN PAGE LEVEL PLUGINS -->
	{{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}
	<!-- END PAGE LEVEL SCRIPTS -->
    @include('js.company.user.candidates')
	<script>
        jQuery(document).ready(function() {
          	TableManaged.init();
          	FormValidation.init();
        });
    </script>
@stop