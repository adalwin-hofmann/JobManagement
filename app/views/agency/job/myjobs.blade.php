@extends('agency.layout')

@section('body')
 
<main class="bs-docs-masthead gray-container padding-top-xs padding-bottom-xs" role="main">
	<div class="background-dashboard" style="display: none;"></div>
    <div class="container padding-bottom-sm" style="background: white;">
    	<div class="margin-top-50"></div>
        <div class="row text-center margin-top-normal margin-bottom-normal">
            <h2 class="home">{{ trans('company.job_management') }}</h2>
        </div>

        <div class="col-sm-3">
            @include("agency.job.leftMyJobs")
        </div>
        
        <div class="col-sm-9">
            @if ($statusType < 5)
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-6">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Job Title" name="keyword" value="" onkeyup="reloadResult(this)">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-sm margin-bottom-sm">
                    <table class="table table-store-list" style="width: 100%;">
                        <thead style="background-color: #F7F7F7">
                            <tr>
                                <th class="text-center text-uppercase">{{ trans('company.title') }}</th>
                                <th class="text-center text-uppercase">{{ trans('company.by') }}</th>
                                <th class="text-center text-uppercase">{{ trans('company.status') }}</th>
                                <th class="text-center text-uppercase">{{ trans('company.views') }}</th>
                                <th class="text-center text-uppercase">{{ trans('company.bids') }}</th>
                                <th class="text-center text-uppercase">{{ trans('company.budget') }}</th>
                                <th class="text-center text-uppercase">{{ trans('company.posted_date') }}</th>
                                <th class="text-center text-uppercase"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jobs as $item)
                            <tr>
                                <td class="text-center" style="position: relative">
                                    <a href="{{ URL::route('agency.job.view', $item->slug) }}"><b>{{ $item->name }}</b></a>
                                    <br>
                                    <button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="tr-applications-{{ $item->id }}"  data-target="tr-overview-{{ $item->id }}" other-second-target="tr-hints-{{$item->id}}" onclick="showView(this)"> {{ trans('company.overview') }}</button>
                                    <button class="btn btn-link btn-sm text-uppercase btn-job-table @if ($item->applies()->where('status', 0)->get()->count() == 0) disabled @endif" other-target="tr-overview-{{ $item->id }}"  other-second-target="tr-hints-{{$item->id}}" data-target="tr-applications-{{ $item->id }}" onclick="showView(this)"> {{ trans('company.applications') }}</button>
                                    <button class="btn btn-link btn-sm text-uppercase btn-job-table @if ($item->hints()->where('status', 0)->get()->count() == 0) disabled @endif" other-target="tr-overview-{{ $item->id }}"  data-target="tr-hints-{{ $item->id }}"  other-second-target="tr-applications-{{$item->id}}" onclick="showView(this)"> {{ trans('company.hints') }}</button>
                                    @if ($item->sharedBy($agency->id))
                                        <img src="{{ HTTP_IMAGE_PATH.'shared-marker.png' }}" class="shared-marker"/>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->company->is_admin == 1)
                                        {{ 'Admin' }}
                                    @else
                                        {{ $item->company->name }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->is_active == 0)
                                        <label class="job-deactive text-uppercase" title="Click to change the status." data-status="4" data-id="{{ $item->id }}" onclick="updateStatus(this)">{{ trans('company.deactive') }}</label>
                                    @elseif ($item->status == 0)
                                        <label class="job-open text-uppercase" title="Click to change the status." data-status="0" data-id="{{ $item->id }}" onclick="updateStatus(this)">{{ trans('company.open') }}</label>
                                    @elseif ($item->status == 1)
                                        <label class="job-pending text-uppercase" title="Click to change the status." data-stauts="1" data-id="{{ $item->id }}" onclick="updateStatus(this)">{{ trans('company.pending') }}</label>
                                    @elseif ($item->status == 2)
                                        <label class="job-closed text-uppercase" title="Click to change the status." data-stauts="2" data-id="{{ $item->id }}" onclick="updateStatus(this)">{{ trans('company.closed') }}</label>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->views }}</td>
                                <td class="text-center">{{ count($item->applies) }}</td>
                                <td class="text-center">{{ '$'.$item->salary }}</td>
                                <td class="text-center">
                                    <?php
                                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $item->created_at);
                                    ?>
                                    {{ $date->format('d-m-Y') }}
                                </td>
                                <td class="text-center">
                                    <div class="row">
                                        <a href="{{ URL::route('agency.job.view', $item->slug) }}" class="btn btn-success btn-sm btn-home">{{ trans('job.view') }}</a>
                                    </div>
                                    <div class="row margin-top-xs">
                                        <a data-id="{{ $item->id }}" class="btn btn-success btn-sm btn-green" onclick="showJobShareModal(this)">{{ trans('job.share') }}</a>
                                    </div>
                                </td>
                            </tr>

                            <tr id="tr-overview-{{ $item->id }}" style="display: none;">
                                <td colspan="8" class="td-other-info">
                                    <!-- Div for Overview -->
                                    <div class="row" id="div_overview">
                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="alert alert-success alert-dismissibl fade in">
                                                    <button type="button" class="close" other-target="tr-applications-{{ $item->id }}"  data-target="tr-overview-{{ $item->id }}" onclick="showView(this)">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">{{ trans('company.close') }}</span>
                                                    </button>
                                                    <p>
                                                        <span class="span-job-description-title">{{ trans('company.job_description') }}:</span>
                                                    </p>
                                                    <p>
                                                        <span class="span-job-descripton-note">{{ nl2br($item->description) }}</span>
                                                    </p>
                                                    <p>&nbsp</p>
                                                    <p>
                                                        <span class="span-job-description-title">{{ trans('company.additional_requirements') }}:</span>
                                                    </p>
                                                    <p>
                                                        <span class="span-job-descripton-note">{{ $item->requirements }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End for Overview -->
                                </td>
                            </tr>

                            <tr id="tr-applications-{{ $item->id }}" style="display: none;">
                                <td colspan="8" class="td-other-info">
                                    <!-- Div for Applications -->
                                    <div class="row" id="div_overview">
                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="alert alert-success alert-dismissibl fade in">
                                                    <button type="button" class="close" other-target="tr-overview-{{ $item->id }}"  data-target="tr-applications-{{ $item->id }}" onclick="showView(this)">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">{{ trans('company.close') }}</span>
                                                    </button>
                                                    @foreach($item->applies as $apply)
                                                        @if ($apply->status == 0)
                                                        <div class="row padding-top-xxs padding-bottom-xxs">
                                                            <div class="col-sm-2 text-center" style="position: relative;">
                                                                <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$apply->user->profile_image }}" class="img-circle">
                                                                @if (count($item->company->newMessages($apply->job_id, $apply->user_id)->get()) > 0)
                                                                <a href="{{ URL::route('company.message.detail', ['id' => $apply->job_id, 'id2' => $apply->user_id]) }}">
                                                                    <span style="position: absolute; left: 60%; top: 0px;" class="badge badge-danger">{{ count($item->company->newMessages($apply->job_id, $apply->user_id)->get()) }}</span>
                                                                </a>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <a href="{{ URL::route('user.view', $apply->user->slug) }}">{{ $apply->user->name }}</a><br>
                                                                <label class="label-other-info">{{ $apply->user->email }}</label>
                                                                @if ($apply->user->phone != '')
                                                                    <br><label class="label-other-info">{{ $apply->user->phone }}</label>
                                                                @endif
                                                                @if ($apply->user->labelIdsOfAgency($agency->id) != '')
                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            @foreach($apply->user->labels()->where('company_id', $agency->id)->get() as $label)
                                                                                <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-4 row" style="position: relative;">
                                                                <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $apply->score }}" onchange="showSaveButton(this)" @if ($agency->is_admin != 1 && $item->company_id != $agency->id) disabled @endif>
                                                                <a class="btn btn-sm blue btn-save-rate" id="js-a-save-rate" data-id="{{ $apply->id }}" style="display: none; margin-top: 5px;" onclick="saveApplyScore(this)">Save</a>
                                                            </div>
                                                            <div class="col-sm-3 padding-top-xs text-right" style="padding-right: 0px;">
                                                                <a class="btn btn-xs blue" id="js-a-open-message" data-id="{{ $apply->id }}" onclick="showMsgModal(this)">Send Message</a>
                                                                <a class="btn btn-xs btn-danger " id="js-a-apply-reject" data-id="{{ $apply->id }}" @if ($apply->status == 2) disabled @endif>
                                                                    @if ($apply->status == 2)
                                                                        {{ trans('job.rejected') }}
                                                                    @else
                                                                        <i class="fa fa-times"></i> {{ trans('job.reject') }}
                                                                    @endif
                                                                </a>
                                                                <button class="btn btn-link btn-sm btn-common" data-target="div_proposal_{{ $apply->id }}"  onclick="showProposal(this)">{{ trans('company.view_proposal') }}</button>
                                                            </div>

                                                            <div class="col-sm-10 col-sm-offset-2" id="div_proposal_{{ $apply->id }}" style="display: none;">
                                                                <p>
                                                                    <span class="span-job-description-title">{{ $apply->name }}</span>
                                                                </p>
                                                                <p>
                                                                    <span class="span-job-descripton-note">{{ nl2br($apply->description) }}</span>
                                                                </p>

                                                                <div class="row">
                                                                    <hr/>
                                                                </div>

                                                                <?php $myNotes = '';?>
                                                                @foreach ($apply->notes as $note)
                                                                    @if ($note->company->id != $agency->id)
                                                                        <div class="row margin-bottom-xs">
                                                                            <div class="col-sm-12">
                                                                                @if ($note->company->is_admin == 1)
                                                                                    <span class="span-job-description-title">{{ 'Admin: ' }}</span>
                                                                                @else
                                                                                    <span class="span-job-description-title">{{ $note->company->name }}</span>
                                                                                @endif
                                                                                <span class="span-job-descripton-note">{{ nl2br($note->notes).': ' }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <?php
                                                                            $myNotes = $note->notes;
                                                                        ?>
                                                                    @endif
                                                                @endforeach

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <span class="span-job-description-title">My Note</span>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="notes" rows="3" id="js-textarea-apply-notes" data-id="{{ $apply->id }}" placeholder="Note...">{{ nl2br($myNotes) }}</textarea>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center margin-top-xs">
                                                                        <button class="btn btn-success btn-sm btn-home" onclick="saveApplyNotes(this)"><i class="fa fa-save"></i> Save Note</button>
                                                                        <button class="btn btn-success btn-sm" style="margin-left: 10px;" data-target="div_proposal_{{ $apply->id }}"  onclick="showProposal(this)"><i class="fa fa-close"></i> {{ trans('company.close') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal Div for Send Message -->
                                                        <div class="modal fade" id="msgModal{{ $apply->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-id="{{ $apply->user->id }}" onclick="hideModal(this)"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('company.close') }}</span></button>
                                                                        <h4 class="modal-title" id="msgModalLabel">{{ trans('company.send_message') }}</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group ">
                                                                            <textarea class="form-control" rows="8" id="txt_message"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-id="{{ $apply->id }}" onclick="hideModal(this)">{{ trans('company.close') }}</button>
                                                                        <button type="button" class="btn btn-primary" id="js-btn-apply-send-message" data-id="{{ $apply->id }}">{{ trans('job.send') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Div for Send Message -->
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End for Applications -->
                                </td>
                            </tr>

                            <tr id="tr-hints-{{ $item->id }}" style="display: none;">
                                <td colspan="8" class="td-other-info">
                                    <!-- Div for Applications -->
                                    <div class="row" id="div_overview">
                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="alert alert-success alert-dismissibl fade in">
                                                    <button type="button" class="close" other-target="tr-overview-{{ $item->id }}"  data-target="tr-applications-{{ $item->id }}" onclick="showView(this)">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">{{ trans('company.close') }}</span>
                                                    </button>
                                                    @foreach($item->hints as $hint)

                                                        @if ($hint->status == 0)
                                                        <div class="row padding-top-xxs padding-bottom-xxs">
                                                            <div class="col-sm-2 text-center" style="position: relative;">
                                                                <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$hint->user->profile_image }}" class="img-circle">
                                                                @if (count($item->company->newMessages($hint->job_id, $hint->user_id)->get()) > 0)
                                                                <a href="{{ URL::route('company.message.detail', ['id' => $hint->job_id, 'id2' => $hint->user_id]) }}">
                                                                    <span style="position: absolute; left: 60%; top: 0px;" class="badge badge-danger">{{ count($item->company->newMessages($hint->job_id, $hint->user_id)->get()) }}</span>
                                                                </a>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <a href="{{ URL::route('user.view', $hint->user->slug) }}">{{ $hint->user->name }}</a><br>
                                                                <label class="label-other-info">{{ $hint->user->email }}</label>
                                                                @if ($hint->user->phone != '')
                                                                    <br><label class="label-other-info">{{ $hint->user->phone }}</label>
                                                                @endif
                                                                @if ($hint->user->labelIdsOfAgency($agency->id) != '')
                                                                    <div class="col-sm-12">
                                                                        <div class="row">
                                                                            @foreach($hint->user->labels()->where('company_id', $agency->id)->get() as $label)
                                                                                <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-4 row" style="position: relative;">
                                                                <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $hint->score }}" onchange="showSaveButton(this)" @if ($agency->is_admin != 1 && $item->company_id != $agency->id) disabled @endif>
                                                                <a class="btn btn-sm blue btn-save-rate" id="js-a-save-rate" data-id="{{ $hint->id }}" style="display: none; margin-top: 5px;" onclick="saveHintScore(this)">Save</a>
                                                            </div>
                                                            <div class="col-sm-3 padding-top-xs text-right" style="padding-right: 0px;">
                                                                <a class="btn btn-xs blue" id="js-a-open-message" data-id="{{ $hint->id }}" onclick="showHintMsgModal(this)">Send Message</a>
                                                                <a class="btn btn-xs btn-danger " id="js-a-hint-reject" data-id="{{ $hint->id }}" @if ($hint->status == 2) disabled @endif>
                                                                    @if ($hint->status == 2)
                                                                        {{ trans('job.rejected') }}
                                                                    @else
                                                                        <i class="fa fa-times"></i> {{ trans('job.reject') }}
                                                                    @endif
                                                                </a>
                                                                <button class="btn btn-link btn-sm btn-common" data-target="div_proposal_{{ $hint->id }}"  onclick="showProposal(this)">{{ trans('company.view_hint') }}</button>
                                                            </div>

                                                            <div class="col-sm-10 col-sm-offset-2" id="div_proposal_{{ $hint->id }}" style="display: none;">
                                                                <p>
                                                                    <span class="span-job-description-title">{{ $hint->name }}</span>
                                                                </p>
                                                                <p>
                                                                    <span class="span-job-descripton-note">{{ nl2br($hint->description) }}</span>
                                                                </p>

                                                                <div class="row">
                                                                    <hr/>
                                                                </div>

                                                                <?php $myNotes = '';?>
                                                                @foreach ($hint->notes as $note)
                                                                    @if ($note->company->id != $agency->id)
                                                                        <div class="row margin-bottom-xs">
                                                                            <div class="col-sm-12">
                                                                                @if ($note->company->is_admin == 1)
                                                                                    <span class="span-job-description-title">{{ 'Admin: ' }}</span>
                                                                                @else
                                                                                    <span class="span-job-description-title">{{ $note->company->name }}</span>
                                                                                @endif
                                                                                <span class="span-job-descripton-note">{{ nl2br($note->notes).': ' }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <?php
                                                                            $myNotes = $note->notes;
                                                                        ?>
                                                                    @endif
                                                                @endforeach

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <span class="span-job-description-title">My Note</span>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="notes" rows="3" id="js-textarea-hint-notes" data-id="{{ $hint->user->id }}" placeholder="Note...">{{ nl2br($myNotes) }}</textarea>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center margin-top-xs">
                                                                        <button class="btn btn-success btn-sm btn-home" onclick="saveHintNotes(this)"><i class="fa fa-save"></i> Save Note</button>
                                                                        <button class="btn btn-success btn-sm" style="margin-left: 10px;" data-target="div_proposal_{{ $hint->id }}"  onclick="showProposal(this)"><i class="fa fa-close"></i> {{ trans('company.close') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal Div for Send Message -->
                                                        <div class="modal fade" id="msgHintModal{{ $hint->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-id="{{ $hint->user->id }}" onclick="hideModal(this)"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('company.close') }}</span></button>
                                                                        <h4 class="modal-title" id="msgModalLabel">{{ trans('company.send_message') }}</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group ">
                                                                            <textarea class="form-control" rows="8" id="txt_message"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-id="{{ $hint->id }}" onclick="hideHintModal(this)">{{ trans('company.close') }}</button>
                                                                        <button type="button" class="btn btn-primary" id="js-btn-hint-send-message" data-id="{{ $hint->user->id }}" data-hintId="{{ $hint->id }}">{{ trans('job.send') }}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Div for Send Message -->
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End for Applications -->
                                </td>
                            </tr>

                            <tr class="table-divider">
                                <td colspan="8" ></td>
                            </tr>
                            @endforeach
                            @if (count($jobs) == 0)
                            <tr>
                                <td colspan="8" class="text-center">{{ trans('company.msg_33') }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pull-right">{{ $jobs->links() }}</div>
                </div>
            @elseif ($statusType == 5)
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-6">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by User Name" name="keyword" value="" onkeyup="reloadUser(this)">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-sm">
                    @foreach ($applies as $item)
                        <?php
                            $user = $item->user;
                        ?>
                        <div id="div_user">
                            <div class="row padding-top-xs padding-bottom-xs" style="position: relative;">
                                @if ($user->sharedBy($agency->id))
                                    <img src="{{ HTTP_IMAGE_PATH.'shared-marker.png' }}" class="shared-marker" style="left: 15px;"/>
                                @endif
                                <div class="col-sm-2 text-center">
                                    <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$user->profile_image }}" class="img-circle">
                                </div>
                                <div class="col-sm-3" style="padding-top: 2px;">
                                    <div class="row">
                                        <a onclick="showUserView(this)" id="user_name" data-userId="{{ $user->id }}" class="username">{{ $user->name }}</a>
                                    </div>
                                    <div class="row" style="position: relative;">
                                        <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($user->scores()->where('company_id', $agency->id)->get()) > 0 ? $user->scores()->where('company_id', $agency->id)->firstOrFail()->score : 0  }}" onchange="showSaveButton(this)" @if ($agency->is_admin != 1 && $item->company_id != $agency->id) disabled @endif>
                                        <a class="btn btn-sm blue btn-save-rate" id="js-a-save-rate" data-id="{{ $user->id }}" style="display: none;" onclick="saveUserScore(this)">{{ trans('company.save') }}</a>
                                    </div>
                                    @if ($user->labelIdsOfAgency($agency->id) != '')
                                        <div class="row margin-top-xxs">
                                            @foreach($user->labels()->where('company_id', $agency->id)->get() as $label)
                                                <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-3" style="padding-top: 2px;">
                                    <div class="row">
                                        <?php
                                            $skillFlag = 0;
                                            $skillLength = 0;
                                            foreach($user->skills as $skill) {
                                                $skillLength += strlen($skill->name) + 5;
                                                if ($skillLength > 18) {
                                                    $skillFlag = 1;
                                                    break;
                                                }
                                        ?>
                                            <label class="job-skill-label">{{ $skill->name }}</label>
                                        <?php }
                                            if ($skillFlag == 1) {
                                        ?>
                                            <label class="job-skill-label">...</label>
                                        <?php }?>
                                        @if (count($user->skills) == 0)
                                        <label>&nbsp</label>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <button class="btn btn-link btn-sm btn-common" super-data-target="div_user" data-target="div_appliedJobs" onclick="showJobView(this)">{{ trans('company.view_applied_jobs') }}</button>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-sm-offset-1 text-center margin-top-xs">
                                    <button class="btn btn-success btn-sm btn-home" data-id="{{ $user->id }}" onclick="showMsgModal(this)">{{ trans('company.send_message') }}</button>
                                    <button class="btn btn-success btn-sm btn-green" data-id="{{ $user->id }}" onclick="showUserShareModal(this)" style="font-size: 11px">{{ trans('job.share') }}</button>
                                </div>
                            </div>

                            {{-- Div for Applied Jobs--}}
                            <div class="row" id="div_appliedJobs" style="display: none;">
                                <div class="col-sm-12">
                                    <div class="col-sm-12">
                                        <div class="alert alert-success alert-dismissibl fade in">
                                            <button type="button" class="close" data-target="div_appliedJobs" onclick="hideView(this)">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">{{ trans('company.close') }}</span>
                                            </button>
                                            @foreach($user->applies as $apply)
                                                @if ($apply->job->company->id == $agency->id)
                                                <p>
                                                    <span class="span-job-descripton-note"><a href="{{ URL::route('user.dashboard.viewJob', $apply->job->slug) }}">{{ $apply->job->name }}</a></span>
                                                </p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--EOF for Applied Jobs--}}
                        </div>

                        <!-- Modal Div for Send Message -->
                        <div class="modal fade" id="msgModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('company.close') }}</span></button>
                                        <h4 class="modal-title" id="msgModalLabel">{{ trans('company.send_message') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group ">
                                            <textarea class="form-control" rows="8" id="txt_message"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('company.close') }}</button>
                                        <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $user->id }}">{{ trans('job.send') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Div for Send Message -->
                    @endforeach

                    <div class="text-center" id="div_no_candidates" @if(count($applies) > 0) style="display: none;" @endif>
                        <label>{{ trans('company.msg_34') }}</label>
                    </div>

                    <div class="pull-right">{{ $applies->links() }}</div>
                </div>
            @elseif ($statusType == 6)
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-6">
                        <div class="row">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by User Name" name="keyword" value="" onkeyup="reloadUser(this)">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-sm">
                    @foreach ($interviews as $item)
                        <div id="div_user">
                            <div class="row padding-top-xs padding-bottom-xs" style="position: relative">
                                @if ($item->sharedBy($agency->id))
                                    <img src="{{ HTTP_IMAGE_PATH.'shared-marker.png' }}" class="shared-marker" style="left: 15px;"/>
                                @endif
                                <div class="col-sm-2 text-center">
                                    <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$item->user->profile_image }}" class="img-circle">
                                </div>
                                <div class="col-sm-3" style="padding-top: 2px;">
                                    <div class="row">
                                        <a onclick="showUserView(this)" data-userId="{{ $item->user->id }}" class="username" id="user_name">{{ $item->user->name }}</a>
                                    </div>
                                    <div class="row" style="position: relative;">
                                        <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($item->user->scores()->where('company_id', $agency->id)->get()) > 0 ? $item->user->scores()->where('company_id', $agency->id)->firstOrFail()->score : 0  }}" onchange="showSaveButton(this)" @if ($agency->is_admin != 1 && $item->company_id != $agency->id) disabled @endif>
                                        <a class="btn btn-sm blue btn-save-rate" id="js-a-save-rate" data-id="{{ $item->user->id }}" style="display: none;" onclick="saveUserScore(this)">{{ trans('company.save') }}</a>
                                    </div>
                                    @if ($item->user->labelIdsOfAgency($agency->id) != '')
                                        <div class="row margin-top-xxs">
                                            @foreach($item->user->labels()->where('company_id', $agency->id)->get() as $label)
                                                <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-3" style="padding-top: 2px;">
                                    <div class="row">
                                        <?php
                                            $skillFlag = 0;
                                            $skillLength = 0;
                                            foreach($item->user->skills as $skill) {
                                                $skillLength += strlen($skill->name) + 5;
                                                if ($skillLength > 18) {
                                                    $skillFlag = 1;
                                                    break;
                                                }
                                        ?>
                                            <label class="job-skill-label">{{ $skill->name }}</label>
                                        <?php }
                                            if ($skillFlag == 1) {
                                        ?>
                                            <label class="job-skill-label">...</label>
                                        <?php }?>
                                        @if (count($item->user->skills) == 0)
                                        <label>&nbsp</label>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <button class="btn btn-link btn-sm btn-common" super-data-target="div_user" data-target="div_appliedJobs" onclick="showJobView(this)">{{ trans('company.view_interview') }}</button>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-sm-offset-1 text-center margin-top-xs">
                                    <button class="btn btn-success btn-sm btn-home" data-id="{{ $item->user->id }}" onclick="showMsgModal(this)">{{ trans('company.send_message') }}</button>
                                    <button class="btn btn-success btn-sm btn-green" data-id="{{ $item->id }}" onclick="showInterviewShareModal(this)" style="font-size: 11px">{{ trans('job.share') }}</button>
                                </div>
                            </div>

                            {{-- Div for Applied Jobs--}}
                            <div class="row" id="div_appliedJobs" style="display: none;">
                                <div class="col-sm-12">
                                    <div class="col-sm-12">
                                        <div class="alert alert-success alert-dismissibl fade in">
                                            <button type="button" class="close" data-target="div_appliedJobs" onclick="hideView(this)">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">{{ trans('company.close') }}</span>
                                            </button>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <ul class="nav nav-tabs interview-nav-tabs">
                                                        @foreach($item->questionnaire->questions as $key => $value)
                                                            <li @if ($key == 0) class="active" @endif id="li-tab-{{$key}}"><a href="#tab-{{ $item->id }}-{{ $key + 1 }}" data-toggle="tab">{{ trans('company.question').' '.($key+1) }}</a></li>
                                                        @endforeach
                                                    </ul>

                                                    <div class="tab-content">
                                                        @foreach($item->questionnaire->questions as $key => $value)
                                                            <div class="tab-pane row fade interview-question-tab @if ($key == 0) in active @endif" id="tab-{{ $item->id }}-{{ $key+1 }}">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <video id="preview" controls class="video-interview-preview">
                                                                            <source src="{{ HTTP_VIDEO_PATH.$item->responses[$key]->file_name }}" type="video/webm">
                                                                        </video>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <p style="color: #A39D9D;">Interview Questions</p>
                                                                        <p class="p-interview-question">{{ $value->questions->question }}</p>

                                                                        <hr/>
                                                                        <div class="row margin-top-xs">
                                                                            <div class="col-sm-12">
                                                                                <span class="span-job-description-title">My Note</span>
                                                                            </div>

                                                                            <?php
                                                                                $myNotes = '';
                                                                                if ($item->responses[$key]->notes()->where('company_id', $agency->id)->get()->count() > 0) {
                                                                                    $myNotes = $item->responses[$key]->notes()->where('company_id', $agency->id)->firstOrFail()->notes;
                                                                                }
                                                                            ?>
                                                                            <div class="col-sm-12">
                                                                                <textarea class="form-control" name="notes" rows="8" id="js-textarea-interview-notes-{{ $item->responses[$key]->id }}" data-id="{{ $item->responses[$key]->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
                                                                            </div>
                                                                            <div class="col-sm-12 margin-top-xs">
                                                                                <button class="btn btn-success btn-sm btn-home" data-id="{{ $item->responses[$key]->id }}" onclick="saveInterviewNotes(this)"><i class="fa fa-save"></i> Save Note</button>
                                                                                <button class="btn btn-success btn-sm" style="margin-left: 10px;" data-target="div_appliedJobs" onclick="hideView(this)"><i class="fa fa-close"></i> Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--EOF for Applied Jobs--}}
                        </div>

                        <!-- Modal Div for Send Message -->
                        <div class="modal fade" id="msgModal{{ $item->user->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('company.close') }}</span></button>
                                        <h4 class="modal-title" id="msgModalLabel">{{ trans('company.send_message') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group ">
                                            <textarea class="form-control" rows="8" id="txt_message"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('company.close') }}</button>
                                        <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $item->user_id }}">{{ trans('job.send') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Div for Send Message -->
                    @endforeach

                    <div class="text-center" id="div_no_candidates" @if(count($interviews) > 0) style="display: none;" @endif>
                        <label>{{ trans('company.msg_42') }}</label>
                    </div>

                    <div class="pull-right">{{ $interviews->links() }}</div>
                </div>

            @endif
        </div>
    </div>


    <!-- Modal Div for Loading -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="js-div-loading" style="width: 110px;">
            <div class="modal-content" style="border-radius: 5px !important;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="/assets/img/ajax-loader.gif">
                        </div>
                        <div class="col-sm-12 margin-top-xs">
                            <span>{{ trans('company.processing') }}...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Div for Send Message -->


    <!-- Modal Div for Sharing -->
    <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('company.close') }}</span></button>
                    <h4 class="modal-title" id="msgModalLabel">{{ trans('job.share') }}</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="share_job_id" value=""/>
                    <input type="hidden" id="share_user_id" value=""/>
                    <input type="hidden" id="share_interview_id" value=""/>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12">
                                <div id="share-modal-content">

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



    <div id="js-div-userview" style="display: none;">

    </div>
</main>   

@stop

@section('custom-scripts')
    @include('js.agency.job.myjobs')
@stop