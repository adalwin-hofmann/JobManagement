@extends('agency.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
@stop

@section('body')
 
<main class="bs-docs-masthead gray-container padding-top-xs padding-bottom-xs" role="main">
    <div class="container padding-top-normal padding-bottom-normal" style="background: white;">
        @if ($agency->agencyShares()->get()->count() > 0)
            @if ($agency->agencyShares()->whereNotNull('job_id')->get()->count() > 0)
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
                                        Share To
                                    </th>
                                    <th>
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agency->agencyShares()->whereNotNull('job_id')->get() as $key => $value)
                                    <tr class="odd gradX">
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('agency.job.view', $value->job->slug) }}" target="_blank">{{ $value->job->name }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('user.company.view', $value->company->slug) }}" target="_blank">{{ $value->company->name }}</a>
                                        </td>
                                        <td>
                                            <a onclick="disableSharing(this)" data-id="{{ $value->id }}" class="btn btn-sm blue-hoki" target="_blank">
                                                Disable
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('agency.job.view', $value->job->slug) }}" class="btn btn-sm btn-success" target="_blank">
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

            @if ($agency->agencyShares()->whereNotNull('user_id')->get()->count() > 0)
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
                                        Share To
                                    </th>
                                    <th>
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agency->agencyShares()->whereNotNull('user_id')->get() as $key => $value)
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
                                            <a href="{{ URL::route('user.company.view', $value->company->slug) }}" target="_blank">{{ $value->company->name }}</a>
                                        </td>
                                        <td>
                                            <a onclick="disableSharing(this)" data-id="{{ $value->id }}" class="btn btn-sm blue-hoki" target="_blank">
                                                Disable
                                            </a>
                                        </td>
                                        <td>
                                            <a onclick="showUserView(this)" data-userid="{{ $value->user->id }}" class="btn btn-sm btn-success">
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

            @if ($agency->agencyShares()->whereNotNull('interview_id')->get()->count() > 0)
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
                                        Share To
                                    </th>
                                    <th>
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agency->agencyShares()->whereNotNull('interview_id')->get() as $key => $value)
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
                                            <a href="{{ URL::route('user.company.view', $value->company->slug) }}" target="_blank">{{ $value->company->name }}</a>
                                        </td>
                                        <td>
                                            <a onclick="disableSharing(this)" data-id="{{ $value->id }}" class="btn btn-sm blue-hoki" target="_blank">
                                                Disable
                                            </a>
                                        </td>
                                        <td>
                                            <a onclick="showInterview(this)" data-id="{{ $value->interview->id }}" class="btn btn-sm btn-success" target="_blank">
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

@stop


@section('custom-scripts')
    {{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
    {{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
    @include('js.agency.share.index')

@stop
