@extends('company.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
@stop

@section('body')
 
<main class="bs-docs-masthead gray-container padding-top-xs padding-bottom-xs" role="main">
    <div class="container padding-top-normal padding-bottom-normal" style="background: white;">
        @if ($company->companyShares()->get()->count() > 0)
            @if ($company->companyShares()->whereNotNull('job_id')->get()->count() > 0)
                <div class="portlet box grey-cascade">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Shared Jobs
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="job_table">
                            <thead>
                                <tr>
                                    <th>
                                         No
                                    </th>
                                    <th>
                                         Name
                                    </th>
                                    <th>
                                        Shared By
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($company->companyShares()->whereNotNull('job_id')->get() as $key => $value)
                                    <tr class="odd gradX">
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('company.job.view', $value->job->slug) }}" target="_blank">{{ $value->job->name }}</a>
                                        </td>
                                        <td>
                                            <a class="pointer" data-id="{{ $value->agency->id }}" onclick="showAgencyView(this);">
                                                {{ $value->agency->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('company.job.view', $value->job->slug) }}" class="btn btn-sm btn-success" target="_blank">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($company->companyShares()->whereNotNull('user_id')->get()->count() > 0)
                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i>Shared Users
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="user_table">
                            <thead>
                                <tr>
                                    <th>
                                         No
                                    </th>
                                    <th>
                                         Name
                                    </th>
                                    <th>
                                        Email
                                    </th>
                                    <th>
                                        Shared By
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($company->companyShares()->whereNotNull('user_id')->get() as $key => $value)
                                    <tr class="odd gradX">
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            <a onclick="showUserView(this)" data-userid="{{ $value->user->id }}" class="pointer">
                                                {{ $value->user->name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $value->user->email }}
                                        </td>
                                        <td>
                                            <a class="pointer" data-id="{{ $value->agency->id }}" onclick="showAgencyView(this);">
                                                {{ $value->agency->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a onclick="showUserView(this)" data-userid="{{ $value->user->id }}" class="btn btn-sm btn-success" target="_blank">
                                                View
                                            </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm btn-home" onclick="showMsgModal(this)" data-id="{{ $value->user->id }}" data-prefix="user">Send Message</button>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm green margin-top-xs" id="js-a-apply-interview" data-name="frank" data-userid="12">
                                                <i class="fa fa-comments-o"></i> Interview
                                            </a>
                                        </td>
                                    </tr>


                                    <!-- Modal Div for Send Message -->
                                    <div class="modal fade" id="msgModal_user_{{ $value->user->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
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
                                                        <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $value->user->id }}" data-prefix="user">Send</button>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Div for Send Message -->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($company->companyShares()->whereNotNull('interview_id')->get()->count() > 0)
                <div class="portlet box purple">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Shared Interviews
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="interview_table">
                            <thead>
                                <tr>
                                    <th>
                                         No
                                    </th>
                                    <th>
                                        Interview Name
                                    </th>
                                    <th>
                                         User Name
                                    </th>
                                    <th>
                                        User Email
                                    </th>
                                    <th>
                                        Shared By
                                    </th>
                                    <th>
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($company->companyShares()->whereNotNull('interview_id')->get() as $key => $value)
                                    <tr class="odd gradX">
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            {{ $value->interview->questionnaire->title }}
                                        </td>
                                        <td>
                                            <a onclick="showUserView(this)" data-userid="{{ $value->interview->user->id }}" class="pointer">
                                                {{ $value->interview->user->name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $value->interview->user->email }}
                                        </td>
                                        <td>
                                            <a class="pointer" data-id="{{ $value->agency->id }}" onclick="showAgencyView(this);">
                                                {{ $value->agency->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a onclick="showInterview(this)" data-id="{{ $value->interview->id }}" class="btn btn-sm btn-success" target="_blank">
                                                View
                                            </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-success btn-sm btn-home" onclick="showMsgModal(this)" data-id="{{ $value->interview->user->id }}" data-prefix="interview">Send Message</button>
                                        </td>
                                    </tr>

                                    <!-- Modal Div for Send Message -->
                                    <div class="modal fade" id="msgModal_interview_{{ $value->interview->user->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
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
                                                        <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $value->interview->user->id }}" data-prefix="interview">Send</button>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Div for Send Message -->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            <div class="row">
                <div class="col-sm-12 text-center">
                    There are no shares
                </div>
            </div>
        @endif
    </div>
</main>   


<div id="js-div-userview" style="display: none;">
</div>

<div id="js-div-agencyview" style="display: none;">
</div>


<!-- Modal Div for Sharing -->
<div class="modal fade" id="interviewModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('company.close') }}</span></button>
                <h4 class="modal-title" id="msgModalLabel">View Interview</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <div id="interview-modal-content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('company.close') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Div for Sharing -->



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
                                <button class="btn default" type="button" style="padding-top: 6px; padding-bottom: 6px;   border: 1px solid rgb(219, 219, 219);"><i class="fa fa-calendar"></i></button>
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
    @include('js.company.share.index')
@stop
