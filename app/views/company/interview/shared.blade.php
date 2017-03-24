@extends('company.layout')

@section('body')
 
<main class="bs-docs-masthead gray-container padding-top-xs padding-bottom-xs" role="main">
	<div class="background-dashboard" style="display: none;"></div>
    <div class="container" style="background: white;">
    	<div class="margin-top-50"></div>
        <div class="row text-center margin-top-normal margin-bottom-normal">
            <h2 class="">Shared Video Interviews</h2>
        </div>

        <div class="col-sm-10 col-sm-offset-1">
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
                    @endforeach
                </tbody>
            </table>
        </div>
        
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


<!-- Modal Div for Send Message -->
<div class="modal fade" id="msgModal_interview" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" name="interview_send_message_user_id" id="interview_user_id" />
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


@stop


@section('custom-scripts')
    @include('js.company.interview.shared')
@stop