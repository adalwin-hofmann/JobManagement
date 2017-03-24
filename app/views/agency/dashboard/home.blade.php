@extends('agency.layout')

@section('body')
<main class="bs-docs-masthead gray-container" role="main" style="min-height: 570px;">
	<div class="background-dashboard" style="z-index: 0; display: none;"></div>
    <div class="container">
    	<div class="row">

			<!-- Start for Dashboard Content -->
			<div class="col-sm-12 margin-top-xs dashboard-container padding-top-normal padding-bottom-sm">

			    <div class="col-sm-12 margin-bottom-normal">
                    <div class="row text-center margin-top-sm">
                        <h1 class="">{{ trans('job.dashboard') }}</h1>
                    </div>
                </div>

				<form class="form-horizontal" method="post" action="{{ URL::route('agency.dashboard') }}" id="search-form">
					<div class="form-group">
						<label class="col-sm-2 control-label">{{ trans('company.search_date') }}</label>
						<div class="col-sm-2">
							<input class="form-control form-control-inline  date-picker" data-date-format="yyyy-mm-dd"  type="text" value="{{ $startDate }}" name="startDate" id="startDate">
						</div>
						<div class="col-sm-2">
							<input class="form-control form-control-inline date-picker" data-date-format="yyyy-mm-dd" type="text" value="{{ $endDate }}" name="endDate" id="endDate">
						</div>
						<div class="col-sm-2">
							<button class="btn btn-primary" onclick="return onValidate();">{{ trans('company.search') }}</button>
						</div>
						<div class="col-sm-1">
							&nbsp;
						</div>                                
						<div class="col-sm-3">
							<select class="form-control" id="period" name="period">
								<option value="0" <?php if ($period == 0) {?> selected <?php }?>>Select Period</option>
								<option value="3" <?php if ($period == 3) {?> selected <?php }?>>Last 3 days</option>
								<option value="7" <?php if ($period == 7) {?> selected <?php }?>>Last 1 week</option>
								<option value="30" <?php if ($period == 30) {?> selected <?php }?>>Last 1 month</option>
								<option value="60" <?php if ($period == 60) {?> selected <?php }?>>Last 2 months</option>
								<option value="90" <?php if ($period == 90) {?> selected <?php }?>>Last 3 months</option>
								<option value="180" <?php if ($period == 180) {?> selected <?php }?>>Last 6 months</option>
								<option value="365" <?php if ($period == 365) {?> selected <?php }?>>Last 1 year</option>
							</select>
						</div>
					</div>                        
				</form> 
				
				<hr/>
				
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="col-md-4">
							<div class="dashboard-stat blue" style="margin-bottom: 0px;">
								<div class="visual">
									<i class="icon-diamond"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php 
											$apply_count = 0;
											$hint_count = 0;
											if ($agency->jobs()->get()->count() > 0) {
                                                foreach ($agency->jobs()->where('created_at', '>', $startDate.' 00:00:00')->where('created_at', '<=', $endDate.' 23:59:59')->where('is_active', 1)->where('is_finished', 1)->get() as $job) {
                                                    $apply_count = $apply_count + $job->applies()->count();
                                                    $hint_count = $hint_count + $job->hints->count();
                                                }
                                            }elseif ($agency->parent->companyApplies()->get()->count() > 0){
                                                $apply_count = $agency->parent->companyApplies()->where('created_at', '>', $startDate.' 00:00:00')->where('created_at', '<=', $endDate.' 23:59:59')->get()->count();
                                                $hint_count = 0;
                                            }
										?>
										{{ $apply_count }}										
									</div>
									<div class="desc">
										 {{ trans('company.application_received') }}
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="dashboard-stat blue" style="margin-bottom: 0px;">
								<div class="visual">
									<i class="fa fa-slack"></i>,
								</div>
								<div class="details">
									<div class="number">
										<?php 
											$views_count = 0;
											foreach ($agency->jobs()->where('created_at', '>', $startDate.' 00:00:00')->where('created_at', '<=', $endDate.' 23:59:59')->where('is_active', 1)->where('is_finished', 1)->get() as $job) {
												$views_count = $views_count + $job->views;
											}
										?>	
										{{ $views_count }}									
									</div>
									<div class="desc">
										 {{ trans('company.views_on_job_page') }}
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="dashboard-stat blue" style="margin-bottom: 0px;">
								<div class="visual">
									<i class="fa fa-globe"></i>
								</div>
								<div class="details">
									<div class="number">
										<?php 
											if ($views_count == 0) $views_count = 1;
										?>
										{{ number_format($apply_count / $views_count * 100, 2, '.', '') }}&nbsp;%
									</div>
									<div class="desc">
										 {{ trans('company.average_bid_rate_of_jobs_per_user') }}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<hr/>
				
				
				<!-- Div for Applications received -->
				<div class="row margin-top-lg">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-diamond"></i>{{ trans('company.application_received') }}
								</div>
								<div class="tools">
									<a href="javascript:;" class="collapse">
									</a>
								</div>
							</div>
							<div class="portlet-body">
								
								@if ($apply_count > 0)
								    @if ($agency->jobs()->get()->count() > 0)
                                        @foreach ($agency->jobs()->where('created_at', '>', $startDate.' 00:00:00')->where('created_at', '<=', $endDate.' 23:59:59')->where('is_active', 1)->where('is_finished', 1)->orderBy('created_at', 'DESC')->limit(10)->get() as $job)
                                            @foreach ($job->applies as $apply)
                                                <div id = "div_apply">
                                                    <div class="row">
                                                        <div class="col-sm-2 text-center">
                                                            <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$apply->user->profile_image }}" class="img-circle">

                                                            <div class="row margin-top-xs">
                                                                <a onclick="showUserView(this)" data-userId="{{ $apply->user->id }}" class="username">{{ $apply->user->name }}</a>@if ($apply->user->age($apply->user->id) != 0), <b>{{ $apply->user->age($apply->user->id) }}</b> @endif
                                                            </div>

                                                            <div class="row find-people-rating">
                                                                <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $apply->score }}" onchange="showSaveButton(this)" @if ($agency->is_admin != 1 && $job->company_id != $agency->id) disabled @endif>
                                                            </div>
                                                            <div class="row">
                                                                <div class="row">
                                                                    <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $apply->id }}" style="display: none;" onclick="saveApplyScore(this)">{{ trans('company.save') }}</a>
                                                                </div>
                                                            </div>

                                                            @if ($apply->user->labelIdsOfAgency($agency->id) != '')
                                                                <div class="col-sm-12 margin-top-xs margin-bottom-xs">
                                                                    <div class="row">
                                                                        @foreach($apply->user->labels()->where('company_id', $agency->id)->get() as $label)
                                                                            <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px; margin-bottom: 5px;">{{ $label->label->name }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="row margin-top-xs">
                                                                <p style="font-size: 11px;"><b>{{ trans('job.applied_job') }}</b></p>
                                                                <p><a href="{{ URL::route('agency.job.view', $apply->job->slug) }}">{{ $apply->job->name }}</a></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1 text-center" style="margin-left: -15px; margin-right: 15px;">
                                                            <div class="col-sm-12 margin-top-xs">
                                                                <p><b>{{ trans('user.skills') }}</b></p>
                                                                <?php
                                                                    $skillFlag = 0;
                                                                    $skillLength = 0;
                                                                    foreach($apply->user->skills()->orderBy('value', 'desc')->get() as $skill) {
                                                                        $skillLength += strlen($skill->name);
                                                                        if ($skillFlag >= 3) {
                                                                            break;
                                                                        }
                                                                        $skillFlag ++;
                                                                ?>
                                                                    <p style="font-size: 11px;">{{ $skill->name }} ({{ $skill->value }})</p>
                                                                <?php }?>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <div class="row">
                                                                <div class="col-sm-12 margin-top-xs">
                                                                    <p><b>{{ trans('user.jobs') }}</b></p>
                                                                    @foreach($apply->user->experiences()->orderBy('start', 'desc')->get() as $item)
                                                                        @if ($item->end == '0' || $item->end == '')
                                                                            <p style="font-size: 11px;"> {{ trans('user.current_job') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ trans('company.still_working') }}</p>
                                                                        @else
                                                                            <p style="font-size: 11px;">{{ trans('user.previous_jobs') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                                <div class="col-sm-12 margin-top-xs">
                                                                    @foreach ($apply->user->educations()->orderBy('start', 'desc')->get() as $item)
                                                                        <p style="font-size: 11px;">{{ trans('user.education_studied') }}: {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                                                        <?php break;?>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-2">
                                                            <div class="row">
                                                                <div class="col-sm-12 margin-top-xs company-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="left" data-image-url="{{ HTTP_PHOTO_PATH. $apply->user->profile_image }}" data-tag="{{ $apply->user->name }}" data-description="{{ nl2br($apply->user->about) }}">
                                                                    <?php
                                                                        $aboutString = $apply->user->about;
                                                                        if (preg_match('/^.{1,200}\b/s', $aboutString, $match))
                                                                        {
                                                                            if (strlen($aboutString) > 300) {
                                                                                $aboutString = $match[0].'...';
                                                                            }
                                                                        }
                                                                    ?>
                                                                    <p><b>{{ trans('user.about_me') }}</b></p>
                                                                    <p>{{ $aboutString }}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 text-center">

                                                            <div class="row margin-bottom-xs">
                                                                <div class="col-sm-10 margin-top-xs col-sm-offset-1">
                                                                    <?php $myNotes = '';?>
                                                                    @foreach ($apply->notes as $note)
                                                                        @if ($note->company->id != $agency->id)

                                                                        @else
                                                                            <?php
                                                                                $myNotes = $note->notes;
                                                                            ?>
                                                                        @endif
                                                                    @endforeach

                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="notes" rows="3" id="js-textarea-apply-notes" data-id="{{ $apply->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
                                                                        </div>
                                                                        <div class="col-sm-12 text-center margin-top-xs">
                                                                            <button class="btn btn-success btn-sm btn-home" onclick="saveApplyNotes(this)" style="display: none;" id="js-button-saveNote"><i class="fa fa-save"></i> Save Note</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @if ($apply->status != 3)
                                                                <a class="btn btn-sm blue" id="js-a-apply-process" data-id="{{ $apply->id }}">
                                                                    <i class="fa fa-toggle-right"></i> {{ trans('job.process') }}
                                                                </a>
                                                            @endif
                                                            @if ($apply->status != 4)
                                                                <button class="btn btn-sm green" id="js-btn-video-interview" data-id="{{ $apply->id }}" data-name="{{ $apply->user->name }}"  data-userId="{{ $apply->user->id }}" data-jobId="{{ $job->id }}">
                                                                    <i class="fa fa-video-camera"></i> Video
                                                                </button>
                                                                <button class="btn btn-sm green" id="js-btn-face-interview" data-name="{{ $apply->user->name }}"  data-userId="{{ $apply->user->id }}" data-jobId="{{ $job->id }}">
                                                                    <i class="fa fa-male"></i> Face
                                                                </button>     
                                                            @endif
                                                            <a class="btn btn-sm btn-danger disabled" id="js-a-apply-reject" data-id="{{ $apply->id }}">
                                                                <i class="fa fa-times"></i> {{ trans('job.reject') }}
                                                            </a>

                                                            <div class="row">
                                                                <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_apply" data-target="div_notes" other-data-target="div_proposal" onclick="showView(this)" data-id="{{ $apply->id }}">{{ trans('job.view_proposal') }}</button>|
                                                                <!--
                                                                <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_apply" data-target="div_proposal" other-data-target="div_notes" onclick="showView(this)" data-id="{{ $apply->id }}">View Proposal</button>
                                                                 -->
                                                                <button class="btn btn-link btn-sm btn-common" id="js-btn-open-message" super-data-target="div_apply">{{ trans('job.send_message') }}</button>
                                                                <!--
                                                                <button class="btn btn-link btn-sm btn-common @if ($agency->members()->get()->count() == 1) disabled @endif" id="js-btn-open-request" super-data-target="div_apply">{{ trans('job.request_feedback') }}</button>
                                                                -->
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Div for Notes -->
                                                    <div class="row  margin-top-sm" id="div_notes" style="display:none;">
                                                        <div class="col-sm-12">
                                                            <div class="col-sm-12">
                                                                <div class="alert alert-success alert-dismissibl fade in" style="margin-bottom: 0px;">
                                                                    <button type="button" class="close" data-target="div_notes" onclick="hideView(this)">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        <span class="sr-only">{{ trans('company.close') }}</span>
                                                                    </button>

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
                                                                            <span class="span-job-description-title">{{ trans('job.my_note') }}</span>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <textarea class="form-control" name="notes" rows="3" id="js-textarea-apply-notes" data-id="{{ $apply->id }}" placeholder="Note...">{{ nl2br($myNotes) }}</textarea>
                                                                        </div>
                                                                        <div class="col-sm-12 text-center margin-top-xs">
                                                                            <button class="btn btn-success btn-sm btn-home" onclick="saveApplyNotes(this)"><i class="fa fa-save"></i> {{ trans('job.save_note') }}</button>
                                                                            <button class="btn btn-success btn-sm" style="margin-left: 10px;" data-target="div_notes" onclick="hideView(this)"><i class="fa fa-close"></i> {{ trans('job.close') }}</button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End for Notes -->

                                                    <!-- Modal Div for Send Message -->
                                                    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                                                                    <h4 class="modal-title" id="msgModalLabel">{{ trans('job.send_message') }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group ">
                                                                        <textarea class="form-control" rows="8" id="txt_message"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $apply->id }}">{{ trans('job.send') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Div for Send Message -->


                                                    <!-- Modal Div for Send Message -->
                                                    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                                                                    <h4 class="modal-title" id="msgModalLabel">{{ trans('job.request_feedback') }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-1" style="margin-top: 9px;">
                                                                                {{ trans('job.to') }}:
                                                                            </div>
                                                                            <div class="col-sm-11">
                                                                                <select class="form-control" id="js-select-member">
                                                                                    @foreach ($members as $member)
                                                                                        @if ($member->id != $agency->id)
                                                                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-sm-12 margin-top-xs">
                                                                                <textarea class="form-control" rows="8" id="js-textarea-request-content"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary" id="js-btn-send-request" data-id="{{ $apply->user->id }}">{{ trans('job.send') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End Div for Send Message -->

                                                    <div class="col-sm-12">
                                                        <hr/>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @elseif  ($agency->parent->companyApplies()->get()->count() > 0)
                                        @foreach($agency->parent->companyApplies()->where('created_at', '>', $startDate.' 00:00:00')->where('created_at', '<=', $endDate.' 23:59:59')->get()  as $apply)
                                            <div id = "div_apply">
                                                <div class="row">
                                                    <div class="col-sm-2 text-center">
                                                        <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$apply->user->profile_image }}" class="img-circle">

                                                        <div class="row margin-top-xs">
                                                            <a onclick="showUserView(this)" data-userId="{{ $apply->user->id }}" class="username">{{ $apply->user->name }}</a>@if ($apply->user->age($apply->user->id) != 0), <b>{{ $apply->user->age($apply->user->id) }}</b> @endif
                                                        </div>

                                                        <div class="row find-people-rating">
                                                            <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $apply->score }}" onchange="showSaveButton(this)" @if ($agency->is_admin != 1 && $job->company_id != $agency->id) disabled @endif>
                                                        </div>
                                                        <div class="row">
                                                            <div class="row">
                                                                <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $apply->id }}" style="display: none;" onclick="saveCompanyApplyScore(this)">{{ trans('company.save') }}</a>
                                                            </div>
                                                        </div>

                                                        @if ($apply->user->labelIdsOfAgency($agency->id) != '')
                                                            <div class="col-sm-12 margin-top-xs">
                                                                <div class="row">
                                                                    @foreach($apply->user->labels()->where('company_id', $agency->id)->get() as $label)
                                                                        <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                    <div class="col-sm-1 text-center" style="margin-left: -15px; margin-right: 15px;">
                                                        <div class="col-sm-12 margin-top-xs">
                                                            <p><b>{{ trans('user.skills') }}</b></p>
                                                            <?php
                                                                $skillFlag = 0;
                                                                $skillLength = 0;
                                                                foreach($apply->user->skills()->orderBy('value', 'desc')->get() as $skill) {
                                                                    $skillLength += strlen($skill->name);
                                                                    if ($skillFlag >= 3) {
                                                                        break;
                                                                    }
                                                                    $skillFlag ++;
                                                            ?>
                                                                <p style="font-size: 11px;">{{ $skill->name }} ({{ $skill->value }})</p>
                                                            <?php }?>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <div class="row">
                                                            <div class="col-sm-12 margin-top-xs">
                                                                <p><b>{{ trans('user.jobs') }}</b></p>
                                                                @foreach($apply->user->experiences()->orderBy('start', 'desc')->get() as $item)
                                                                    @if ($item->end == '0' || $item->end == '')
                                                                        <p style="font-size: 11px;"> {{ trans('user.current_job') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ trans('company.still_working') }}</p>
                                                                    @else
                                                                        <p style="font-size: 11px;">{{ trans('user.previous_jobs') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                            <div class="col-sm-12 margin-top-xs">
                                                                @foreach ($apply->user->educations()->orderBy('start', 'desc')->get() as $item)
                                                                    <p style="font-size: 11px;">{{ trans('user.education_studied') }}: {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                                                    <?php break;?>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-2">
                                                        <div class="row">
                                                            <div class="col-sm-12 margin-top-xs company-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="left" data-image-url="{{ HTTP_PHOTO_PATH. $apply->user->profile_image }}" data-tag="{{ $apply->user->name }}" data-description="{{ nl2br($apply->user->about) }}">
                                                                <?php
                                                                    $aboutString = $apply->user->about;
                                                                    if (preg_match('/^.{1,200}\b/s', $aboutString, $match))
                                                                    {
                                                                        if (strlen($aboutString) > 300) {
                                                                            $aboutString = $match[0].'...';
                                                                        }
                                                                    }
                                                                ?>
                                                                <p><b>{{ trans('user.about_me') }}</b></p>
                                                                <p>{{ $aboutString }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 text-center">

                                                        <div class="row margin-bottom-xs">
                                                            <div class="col-sm-10 margin-top-xs col-sm-offset-1">
                                                                <?php $myNotes = '';?>
                                                                @foreach ($apply->notes as $note)
                                                                    @if ($note->company->id != $agency->id)

                                                                    @else
                                                                        <?php
                                                                            $myNotes = $note->notes;
                                                                        ?>
                                                                    @endif
                                                                @endforeach

                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="notes" rows="3" id="js-textarea-apply-notes" data-id="{{ $apply->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center margin-top-xs">
                                                                        <button class="btn btn-success btn-sm btn-home" onclick="saveCompanyApplyNotes(this)" style="display: none;" id="js-button-saveNote"><i class="fa fa-save"></i> Save Note</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_apply" data-target="div_notes" other-data-target="div_proposal" onclick="showView(this)" data-id="{{ $apply->id }}">{{ trans('job.view_proposal') }}</button>|
                                                            <!--
                                                            <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_apply" data-target="div_proposal" other-data-target="div_notes" onclick="showView(this)" data-id="{{ $apply->id }}">View Proposal</button>
                                                             -->
                                                            <button class="btn btn-link btn-sm btn-common" id="js-btn-open-message" super-data-target="div_apply">{{ trans('job.send_message') }}</button>|
                                                            <button class="btn btn-link btn-sm btn-common @if ($agency->members()->get()->count() == 1) disabled @endif" id="js-btn-open-request" super-data-target="div_apply">{{ trans('job.request_feedback') }}</button>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Div for Notes -->
                                                <div class="row  margin-top-sm" id="div_notes" style="display:none;">
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <div class="alert alert-success alert-dismissibl fade in" style="margin-bottom: 0px;">
                                                                <button type="button" class="close" data-target="div_notes" onclick="hideView(this)">
                                                                    <span aria-hidden="true">&times;</span>
                                                                    <span class="sr-only">{{ trans('company.close') }}</span>
                                                                </button>

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
                                                                        <span class="span-job-description-title">{{ trans('job.my_note') }}</span>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="notes" rows="3" id="js-textarea-apply-notes" data-id="{{ $apply->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center margin-top-xs">
                                                                        <button class="btn btn-success btn-sm btn-home" onclick="saveCompanyApplyNotes(this)"><i class="fa fa-save"></i> {{ trans('job.save_note') }}</button>
                                                                        <button class="btn btn-success btn-sm" style="margin-left: 10px;" data-target="div_notes" onclick="hideView(this)"><i class="fa fa-close"></i> {{ trans('job.close') }}</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End for Notes -->

                                                <!-- Modal Div for Send Message -->
                                                <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                                                                <h4 class="modal-title" id="msgModalLabel">{{ trans('job.send_message') }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group ">
                                                                    <textarea class="form-control" rows="8" id="txt_message"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary" id="js-btn-send-applyMessage" data-id="{{ $apply->id }}">{{ trans('job.send') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Div for Send Message -->

                                                <!-- Modal Div for Send Message -->
                                                <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                                                                <h4 class="modal-title" id="msgModalLabel">{{ trans('job.request_feedback') }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-1" style="margin-top: 9px;">
                                                                            {{ trans('job.to') }}:
                                                                        </div>
                                                                        <div class="col-sm-11">
                                                                            <select class="form-control" id="js-select-member">
                                                                                @foreach ($members as $member)
                                                                                    @if ($member->id != $agency->id)
                                                                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-sm-12 margin-top-xs">
                                                                            <textarea class="form-control" rows="8" id="js-textarea-request-content"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary" id="js-btn-send-request" data-id="{{ $apply->user->id }}">{{ trans('job.send') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Div for Send Message -->

                                                <div class="col-sm-12">
                                                    <hr/>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
						    	@else
						    	<div class="row">
						    		<div class="col-sm-12 text-center">
										{{ trans('company.msg_05') }}
						    		</div>
						    	</div>
						    	@endif
							</div>
						</div>					
					</div>
				</div>
				<!-- EOF for Applications received -->
				
				<!-- Div for Applications Recommend -->
				<div class="row margin-top-lg">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-globe"></i>{{ trans('company.applications_recommended') }}
								</div>
								<div class="tools">
									<a href="javascript:;" class="collapse">
									</a>
								</div>
							</div>
							<div class="portlet-body">
								
								@if ($hint_count > 0)
								<table class="table table-striped table-bordered table-hover dataTable no-footer">
						            <thead>
						                <tr>
						                    <th>#</th>
						                    <th>{{ trans('job.job_name') }}</th>
						                    <th>{{ trans('company.user_name') }}</th>
						                    <th>{{ trans('job.recommended_at') }}</th>
						                    <th style="width: 80px;">{{ trans('job.view') }}</th>
						                </tr>
						            </thead>

						            <tbody>
						            	<?php $count = 0?>
						            	@foreach ($agency->jobs()->where('created_at', '>', $startDate.' 00:00:00')->where('created_at', '<=', $endDate.' 23:59:59')->where('is_active', 1)->where('is_finished', 1)->get() as $job)
											@foreach ($job->hints as $hint)
											    <tr>
                                                    <?php $count ++;?>
                                                    <td>{{ $count }}</td>
                                                    <td>{{ $hint->job->name }}</td>
                                                    <td>{{ $hint->user->name }}</td>
                                                    <td>{{ $hint->created_at }}</td>
                                                    <td>
                                                        <a href="{{ URL::route('agency.job.view', $hint->job->slug)  }}" class="btn btn-sm btn-info">
                                                            <span class="glyphicon glyphicon-edit"></span> {{ trans('job.view') }}
                                                        </a>
                                                    </td>
											    </tr>
											@endforeach
						            	@endforeach
						            </tbody>
						    	</table>
						    	@else
						    	<div class="row">
						    		<div class="col-sm-12 text-center">
										{{ trans('job.msg_01') }}
						    		</div>
						    	</div>
						    	@endif
							</div>
						</div>					
					</div>
				</div>
				<!-- EOF for Applications recommend -->  	
			</div> 
			<!-- EOF for Dashboard Content -->  
    	</div>
    	
    	@if ($agency->is_admin == 1)
    	<div class="row margin-top-xs dashboard-container padding-bottom-sm margin-bottom-sm padding-top-xs">
			<div class="col-sm-12">
	    		<div class="portlet box">
	    			<div class="portlet-title">
	    				<div class="caption" style="color: black;">
	    					<i class="fa fa-users"></i>{{ trans('company.members') }}
	    				</div>
	    				<div class="actions">
	    					<a onclick="showAddModal();" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span>{{ trans('company.add_member') }}</a>
	    				</div>
	    			</div>
	    			<div class="portlet-body">
						<!-- Modal Div for Add Member -->
						<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
										<h4 class="modal-title" id="msgModalLabel">{{ trans('company.add_member') }}</h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-3 col-sm-offset-1">
												{{ Form::label('', 'Name', ['class' => 'margin-top-xs']) }}
											</div>
											<div class="col-sm-7">
												{{ Form::text('name', '', ['class' => 'form-control', 'id' => 'name']) }}
											</div>
										</div>
						
										<div class="row margin-top-xs">
											<div class="col-sm-3 col-sm-offset-1">
												{{ Form::label('', 'Email', ['class' => 'margin-top-xs']) }}
											</div>
											<div class="col-sm-7">
												{{ Form::text('email', '', ['class' => 'form-control', 'id' => 'email']) }}
											</div>
										</div>


										<div class="row margin-top-xs">
                                            <div class="col-sm-3 col-sm-offset-1">
                                                {{ Form::label('', 'Password', ['class' => 'margin-top-xs']) }}
                                            </div>
                                            <div class="col-sm-7">
                                                {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                                            </div>
                                        </div>


                                        <div class="row margin-top-xs">
                                            <div class="col-sm-3 col-sm-offset-1">
                                                {{ Form::label('', 'Password Confirmmation', ['class' => 'margin-top-xs']) }}
                                            </div>
                                            <div class="col-sm-7">
                                                {{ Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password']) }}
                                            </div>
                                        </div>
										
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
										<button type="button" class="btn btn-primary" id="js-btn-add-member">{{ trans('company.add') }}</button>
									</div>
								</div>
							</div>
						</div> 
						<!-- End Div for Add Member -->	
						
						<!-- Modal Div for Edit Member -->
						<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
										<h4 class="modal-title" id="msgModalLabel">{{ trans('company.add_member') }}</h4>
									</div>
									<div class="modal-body">
										<input type="hidden" name="edit_id" value="" id="edit_id" />
										<div class="row">
											<div class="col-sm-3 col-sm-offset-1">
												{{ Form::label('', trans('job.name'), ['class' => 'margin-top-xs']) }}
											</div>
											<div class="col-sm-7">
												{{ Form::text('name', '', ['class' => 'form-control', 'id' => 'edit_name']) }}
											</div>
										</div>
						
										<div class="row margin-top-xs">
											<div class="col-sm-3 col-sm-offset-1">
												{{ Form::label('', trans('job.email'), ['class' => 'margin-top-xs']) }}
											</div>
											<div class="col-sm-7">
												{{ Form::text('email', '', ['class' => 'form-control', 'id' => 'edit_email']) }}
											</div>
										</div>
										
										<div class="row margin-top-xs">
											<div class="col-sm-10 col-sm-offset-1">
										        <div class="alert alert-danger alert-dismissible" id="js-div-update-warnning" style="display: none;">
										            <button type="button" class="close" id="js-btn-modal-close">
										                <span aria-hidden="true">&times;</span>
										                <span class="sr-only">{{ trans('job.close') }}</span>
										            </button>
										            <p id="js-p-update-warnning">
										            </p>
										        </div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.close') }}</button>
										<button type="button" class="btn btn-primary" id="js-btn-update-member">{{ trans('company.update') }}</button>
									</div>
								</div>
							</div>
						</div> 
						<!-- End Div for Edit Member -->	
						
						<?php if (isset($alert)) { ?>
						<div class="alert alert-<?php echo $alert['type'];?> alert-dismissible fade in">
						    <button type="button" class="close" data-dismiss="alert">
						        <span aria-hidden="true">&times;</span>
						        <span class="sr-only">{{ trans('job.close') }}</span>
						    </button>
						    <p>
						        <?php echo $alert['msg'];?>
						    </p>
						</div>
						<?php } ?>    			
	    			
	    				@if ($agency->members()->count() == 0)
	    				<div class="row">
							<div class="col-sm-12 text-center">
								{{ trans('company.msg_35') }}
						    </div>
						</div>
						@else
				        <table class="table table-striped table-bordered table-hover dataTable no-footer">
				            <thead>
				                <tr>
				                    <th>#</th>
				                    <th>{{ trans('job.name') }}</th>
				                    <th>{{ trans('job.email') }}</th>
				                    <th>{{ trans('company.added_at') }}</th>
				                    <th style="width: 80px;">{{ trans('company.edit') }}</th>
				                    <th style="width: 80px;">{{ trans('company.delete') }}</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach ($agency->members as $key => $value)
				                    <tr>
				                        <td>{{ $key + 1 }}</td>
				                        <td>{{ $value->name }}</td>
				                        <td>{{ $value->email }}</td>
				                        <td>{{ $value->created_at }}</td>
				                        <td>
				                            <a class="btn btn-sm btn-info" data-name="{{ $value->name }}" data-email="{{ $value->email }}" data-id="{{ $value->id }}" onclick="showEditModal(this);">
				                                <span class="glyphicon glyphicon-edit"></span> {{ trans('company.edit') }}
				                            </a>
				                        </td>
				                        <td>
				                            <a data-url="{{ URL::route('agency.user.delete', $value->id)  }}" class="btn btn-sm btn-danger" id="js-a-delete">
				                                <span class="glyphicon glyphicon-trash"></span> {{ trans('company.delete') }}
				                            </a>
				                        </td>
				                    </tr>
				                @endforeach
				            </tbody>
				        </table>
	    				@endif
	    			</div>
	    		</div>
			</div>
    	</div>
    	@endif


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
                                <span>{{ trans('company.process') }}...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Div for Loading -->


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
                            <input type="hidden" id="js-input-vi-apply-id" value="">
                            <input type="hidden" id="js-input-vi-job-id" value="">
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
                                        <button class="btn default" type="button" style="padding-top: 7px; padding-bottom: 7px;"><i class="fa fa-calendar"></i></button>
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

        
        <!-- Modal Div for Face Interview -->
        <div class="modal fade" id="fiModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="msgModalLabel">Send Face To Face Interview</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button id="js-btn-schedule" class="btn btn-primary btn-block">Schedule</button>
                                </div>
                                <div class="col-sm-4">
                                    <button id="js-btn-invite" class="btn btn-default btn-block">Invite</button>
                                </div>
                            </div>
                            
                            <div class="margin-top-sm" id="js-div-schedule">
                                <div class="row">
                                    <div class="col-sm-3 text-right">
                                        <label class="form-control-static">
                                            Date & Time
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control text-center readonly" id="js-text-schedule-date" readonly/>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control text-center readonly" id="js-text-schedule-time" readonly/>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" id="js-select-schedule-duration">
                                            <?php $durations = [15, 30, 45, 60, 75, 90, 105, 120, 150, 180, 210, ];?>
                                            @foreach ($durations as $value)
                                                <option value="{{ $value }}"> {{ $value }} Min</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row margin-top-xs">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-control-static">
                                            Title
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="js-text-schedule-title"/>
                                    </div>
                                </div>
                                
                                <div class="row margin-top-xs">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-control-static">
                                            Description
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="9" id="js-text-schedule-description"></textarea>
                                    </div>
                                </div>                        
                            </div>
                            
                            <div class="margin-top-sm" id="js-div-invite" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-control-static">
                                            Title
                                        </label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="js-text-invite-title"/>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-control" id="js-select-invite-duration">
                                            @foreach ($durations as $value)
                                                <option value="{{ $value }}"> {{ $value }} Min</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row margin-top-xs">
                                    <div class="col-sm-2 text-right">
                                        <label class="form-control-static">
                                            Description
                                        </label>
                                    </div>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="9" id="js-text-invite-description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="sendFIInterview(this);">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Div for Face Interview -->           
    </div> 
</main>


<div id="js-div-userview" style="display: none;">
</div>

@stop

@section('custom-scripts')
    @include('js.agency.dashboard.home')
@stop