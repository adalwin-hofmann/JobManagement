@extends('user.layout')

@section('body')
<main class="bs-docs-masthead" role="main" style="min-height: 560px;">
	<div class="background-dashboard"></div>        
    <div class="container">
    	<div class="margin-top-lg"></div>
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home color-white"> {{ trans('user.my_recommendations') }}</h2>
        </div>

        <div class="col-sm-3">
            <ul class="tabbable faq-tabbable margin-bottom-normal custom-left-menu">
                <li class="active">
                    <a href="{{ URL::route('user.dashboard.recommendations') }}">
                        <i class="fa fa-ticket"></i> {{ trans('user.recommendations') }}
                    </a>
                </li>
                <li class="">
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
                            <i class="fa fa-navicon"></i> {{ trans('user.recommendations_list') }}
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- Div for Job Recommend sent -->
                        <div class="row">
                            <div class="col-sm-12">
                                @if (count($hints) > 0)
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ trans('user.job_name') }}</th>
                                                <th>{{ trans('user.name') }}</th>
                                                <th>{{ trans('user.state') }}</th>
                                                <th>{{ trans('user.recommended_at') }}</th>
                                                <th style="width: 80px;">{{ trans('user.view') }}</th>
                                                <th style="width: 80px;">{{ trans('user.delete') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($hints as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><a href="{{ URL::route('user.dashboard.viewJob', $value->job->slug) }}">{{ $value->job->name }}</a></td>
                                                <td>{{ $value->name }}</td>
                                                <td>
                                                    @if ($value->is_verified == 0)
                                                    {{ trans('user.sent') }}
                                                    @elseif ($value->status == 0)
                                                    {{ trans('user.verified') }}
                                                    @elseif ($value->status == 1)
                                                    {{ trans('user.viewed') }}
                                                    @elseif ($value->status == 2)
                                                    {{ trans('user.rejected') }}
                                                    @elseif ($value->status == 3)
                                                    {{ trans('user.processing') }}
                                                    @else
                                                    {{ trans('user.interview') }}
                                                    @endif
                                                </td>
                                                <td>{{ $value->created_at }}</td>
                                                <td>
                                                    <a data-id="{{ $value->id }}" onclick="viewHint(this);" class="btn btn-sm btn-info">
                                                        <span class="glyphicon glyphicon-edit"></span> {{ trans('user.view') }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a data-id="{{ $value->id }}" onclick="deleteHint(this)" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash-o"></i> {{ trans('user.delete') }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="pull-right">{{ $hints->links() }}</div>
                                        </div>
                                    </div>


                                    @foreach ($hints as $hint)
                                        <!-- Modal Div for View Recommendation -->
                                        <div class="modal fade" id="viewHintModal{{ $hint->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('user.close') }}</span></button>
                                                        <h4 class="modal-title" id="msgModalLabel">{{ trans('user.view_recommendation') }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-3">
                                                                <span class="span-job-description-title">{{ trans('user.job_name') }}:</span>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <span class="span-job-descripton-note">{{ $hint->job->name }}</span>
                                                            </div>
                                                        </div>

                                                        <?php if ($hint->name != '') {?>
                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-3">
                                                                <span class="span-job-description-title">{{ trans('user.name') }}:</span>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <span class="span-job-descripton-note">{{ $hint->name }}</span>
                                                            </div>
                                                        </div>
                                                        <?php }?>

                                                        <?php if ($hint->email != '') {?>
                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-3">
                                                                <span class="span-job-description-title">{{ trans('user.email') }}:</span>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <span class="span-job-descripton-note">{{ $hint->email }}</span>
                                                            </div>
                                                        </div>
                                                        <?php }?>

                                                        <?php if ($hint->phone != '') {?>
                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-3">
                                                                <span class="span-job-description-title">{{ trans('user.phone_number') }}:</span>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <span class="span-job-descripton-note">{{ $hint->phone }}</span>
                                                            </div>
                                                        </div>
                                                        <?php }?>

                                                        <?php if ($hint->currentJob != '') {?>
                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-3">
                                                                <span class="span-job-description-title">{{ trans('user.current_job') }}:</span>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <span class="span-job-descripton-note">{{ $hint->currentJob }}</span>
                                                            </div>
                                                        </div>
                                                        <?php }?>

                                                        <?php if ($hint->previousJobs != '') {?>
                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-3">
                                                                <span class="span-job-description-title">{{ trans('user.previous_jobs') }}:</span>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <span class="span-job-descripton-note">{{ $hint->previousJobs }}</span>
                                                            </div>
                                                        </div>
                                                        <?php }?>

                                                        <?php if ($hint->description != '') {?>
                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-3">
                                                                <span class="span-job-description-title">{{ trans('user.description') }}:</span>
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <span class="span-job-descripton-note">{{ nl2br($hint->description) }}</span>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('user.close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Div for View Recommendation -->
                                    @endforeach

                                @else
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            {{ trans('user.msg_29') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- EOF for Job recommend sent -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@stop

@section('custom-scripts')
	@include('js.user.dashboard.recommendations')
@stop