@extends('company.layout')

@section('custom-styles')
	{{ HTML::style('/assets/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}

	<style>
	    .btn {
	        font-size: 11px;
	    }
	</style>

@stop

@section('body')
<main class="bs-docs-masthead gray-container padding-top-xs" role="main" style="min-height: 0px;">
    <div class="container">
        <div class="row padding-top-normal padding-bottom-normal" style="background-color: rgba(255, 255, 255, 0.4);">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ URL::route('company.user.applied') }}">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <div class="col-sm-11 col-sm-offset-1">
                                <label class="row">Registered Date</label>
                                <div class="row">
                                    <input class="form-control form-control-inline  date-picker" data-date-format="yyyy-mm-dd"  type="text" value="{{$startDate}}" name="startDate" id="startDate">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="col-sm-11 col-sm-offset-1">
                                <label class="row">Skill</label>
                                <div class="row">
                                    <input class="form-control form-control-inline typeahead tt-query" type="text" value="{{$skill_name}}" name="skill_name">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="col-sm-11 col-sm-offset-1">
                                <label class="row">{{ trans('user.previous_position_titles_companies') }}</label>
                                <div class="row">
                                    <input class="form-control form-control-inline" type="text" value="{{$previous_position}}" name="previous_position">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3  padding-top-sm text-center">
                            <button class="btn btn-primary btn-home"><i class="glyphicon glyphicon-search"></i>   Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<div class="gray-container padding-bottom-xs">
    <div class="container" style="min-height: 320px;">
        <div class="row margin-bottom-sm">
            @if (count($users) > 0)
                @foreach ($users as $user)
                <div class="row margin-top-xs" id="div_job">
                    <div class="row table-job-row padding-top-sm padding-bottom-xs" id="div_user" style="position: relative;">
                        @if ($user->shares()->count() > 0)
                            <img src="{{ HTTP_IMAGE_PATH.'shared-marker.png' }}" class="shared-marker" />
                        @endif
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH. $user->profile_image }}" class="img-circle">
                                    <div class="col-sm-12 margin-top-xs">
                                        <a onclick="showUserView(this)" data-userId="{{ $user->id }}" class="username">{{ $user->name }}</a>@if ($user->age($user->id) != 0), <b>{{ $user->age($user->id) }}</b> @endif
                                    </div>

                                    <div class="col-sm-12 find-people-rating">
                                        <input id="input-rate-{{ $user->id }}" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($user->scores()->where('company_id', $company->id)->get()) > 0 ? $user->scores()->where('company_id', $company->id)->firstOrFail()->score : 0  }}" onchange="showSaveButton(this)">
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $user->id }}" style="display: none;" onclick="saveUserScore(this)">Save</a>
                                        </div>
                                    </div>

                                    <div class="row margin-top-xs">
                                        <p style="font-size: 11px;"><b>{{ trans('job.applied_jobs') }}</b></p>
                                        <?php $count = 0; ?>
                                        @foreach ($user->applies as $apply)
                                            @if ($apply->job->company_id == $company->id)
                                                <?php $count ++; ?>
                                                <?php if ($count == 3) break;?>
                                                <p style="font-size: 11px;"><a href="{{ URL::route('company.job.view', $apply->job->slug) }}">{{ $apply->job->name }}</a></p>
                                            @endif
                                        @endforeach
                                        <a class="btn btn-link btn-sm text-uppercase btn-job-table" onclick="showOverView(this);">{{ trans('job.overview') }}</a>
                                    </div>
                                </div>

                                <div class="col-sm-1 text-center" style="margin-left: -15px; margin-right: 15px;">
                                    <div class="col-sm-12 margin-top-xs">
                                        <?php
                                            $skillFlag = 0;
                                            $skillLength = 0;
                                            foreach($user->skills()->orderBy('value', 'desc')->get() as $skill) {
                                                $skillLength += strlen($skill->name);
                                                if ($skillFlag >= 3) {
                                                    break;
                                                }
                                                $skillFlag ++;
                                        ?>
                                            <p>{{ $skill->name }} ({{ $skill->value }})</p>
                                        <?php }
                                            if ($skillFlag == 3) {
                                        ?>
                                            <p>...</p>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="row">
                                        <div class="col-sm-12 margin-top-xs">
                                            @foreach($user->experiences()->orderBy('start', 'desc')->get() as $item)
                                                @if ($item->end == '0' || $item->end == '')
                                                    <p>{{ trans('user.current_job') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ trans('company.still_working') }}</p>
                                                @else
                                                    <p>{{ trans('user.previous_jobs') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="col-sm-12 margin-top-xs">
                                            @foreach ($user->educations()->orderBy('start', 'desc')->get() as $item)
                                                <p>{{ trans('user.education_studied') }}: {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                                <?php break;?>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="row">
                                        <div class="col-sm-12 margin-top-xs company-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="left" data-image-url="{{ HTTP_PHOTO_PATH. $user->profile_image }}" data-tag="{{ $user->name }}" data-description="{{ nl2br($user->about) }}">
                                            <?php
                                                $aboutString = $user->about;
                                                if (preg_match('/^.{1,300}\b/s', $aboutString, $match))
                                                {
                                                    if (strlen($aboutString) > 300) {
                                                        $aboutString = $match[0].'...';
                                                    }
                                                }
                                            ?>
                                            <p>{{ trans('user.about_me') }}: {{ $aboutString }}</p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-3 margin-top-xs text-center">

                                    <div class="row margin-bottom-xs">
                                        <div class="col-sm-12">
                                            <?php $myNotes = '';?>
                                            @foreach ($user->notes as $note)
                                                @if ($note->company->id != $company->id)

                                                @else
                                                    <?php
                                                        $myNotes = $note->notes;
                                                    ?>
                                                @endif
                                            @endforeach

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <textarea class="form-control" name="notes" rows="3" id="js-textarea-user-notes" data-id="{{ $user->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
                                                </div>
                                                <div class="col-sm-12 text-center margin-top-xs">
                                                    <button class="btn btn-success btn-sm btn-home" onclick="saveUserNotes(this)" style="display: none;" id="js-button-saveNote"><i class="fa fa-save"></i> Save Note</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <button class="btn btn-success btn-sm btn-home" onclick="showMsgModal(this)" data-id="{{ $user->id }}">Send Message</button>
                                    </div>

                                    <div class="row">
                                        <button class="btn btn-sm green margin-top-xxs" id="js-btn-video-interview" data-name="{{ $user->name }}"  data-userId="{{ $user->id }}">
                                            <i class="fa fa-video-camera"></i> Video Interview
                                        </button>
                                    </div>
                                    
                                    <div class="row">
                                        <button class="btn btn-sm green margin-top-xxs" id="js-btn-face-interview" data-name="{{ $user->name }}"  data-userId="{{ $user->id }}">
                                            <i class="fa fa-male"></i> Face Interview
                                        </button>                                    
                                    </div>

                                    @if (!$user->isCandidate($company->id))
                                    <div class="row">
                                        <button class="btn btn-sm btn-danger margin-top-xxs" data-id="{{ $user->id }}" id="js-btn-addToCandidates"><i class="fa fa-save"></i> {{ trans('user.add_to_candidates') }}</button>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>


                        <!-- Div for More -->
                        <div class="row" id="div_more" style="display: none;">
                            <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <div class="alert alert-success alert-dismissibl fade in">
                                        <button type="button" class="close" data-target="div_more" onclick="hideView(this)">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">{{ trans('job.close') }}</span>
                                        </button>
                                        <p>
                                            <span class="span-job-description-title">{{ trans('user.for_our_company') }}:</span>
                                        </p>
                                        @foreach ($user->applies as $apply)
                                            @if ($apply->job->company_id == $company->id)
                                                <p><a href="{{ URL::route('company.job.view', $apply->job->slug) }}">{{ $apply->job->name }}</a></p>
                                            @endif
                                        @endforeach
                                        <br/>
                                        <p>
                                            <span class="span-job-description-title">{{ trans('user.for_other_company') }}:</span>
                                        </p>
                                        @foreach ($user->applies as $apply)
                                            @if ($apply->job->company_id != $company->id)
                                                <p><a href="{{ URL::route('user.dashboard.viewJob', $apply->job->slug) }}">{{ $apply->job->name }}</a></p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End for More -->
                    </div>

                </div>

                <!-- Modal Div for Send Message -->
                <div class="modal fade" id="msgModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $user->id }}">Send</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Div for Send Message -->
                @endforeach
            @elseif ($company->parent->companyApplies()->count() > 0)
                @foreach ($company->parent->companyApplies as $apply)
                    <div class="row margin-top-xs">
                        <div id = "div_apply" class="table-job-row padding-top-xs padding-bottom-xs">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                    <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$apply->user->profile_image }}" class="img-circle">

                                    <div class="row margin-top-xs">
                                        <a onclick="showUserView(this)" data-userId="{{ $apply->user->id }}" class="username">{{ $apply->user->name }}</a>@if ($apply->user->age($apply->user->id) != 0), <b>{{ $apply->user->age($apply->user->id) }}</b> @endif
                                    </div>

                                    <div class="row find-people-rating">
                                        <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $apply->score }}" onchange="showSaveButton(this)" @if ($company->is_admin != 1 && $job->company_id != $company->id) disabled @endif>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $apply->id }}" style="display: none;" onclick="saveCompanyApplyScore(this)">{{ trans('company.save') }}</a>
                                        </div>
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
                                        <div class="col-sm-12 margin-top-xs about-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="left" data-image-url="{{ HTTP_PHOTO_PATH. $apply->user->profile_image }}" data-tag="{{ $apply->user->name }}" data-description="{{ nl2br($apply->user->about) }}">
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
                                                @if ($note->company->id != $company->id)

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
                                        <button class="btn btn-link btn-sm btn-common @if ($company->members()->get()->count() == 1) disabled @endif" id="js-btn-open-request" super-data-target="div_apply">{{ trans('job.request_feedback') }}</button>
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
                                                @if ($note->company->id != $company->id)
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
                                                                @if ($member->id != $company->id)
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
                    </div>

                @endforeach
			@endif
			<?php if (count($users) == 0 && $company->parent->companyApplies()->count() == 0) {?>
			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12 padding-top-sm padding-bottom-sm text-center" style="background-color: white;">
						There are no peoples.
					</div>
				</div>
			</div>
			<?php }?>

			<div class="pull-right margin-top-xs">{{ $users->links() }}</div>
        </div>
    </div>

    <div class="container" style="background: white;">
        <div class="col-sm-12">
            <div class="row">
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
                                        @foreach ($candidates as $key => $value)
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
                                                    <button class="btn btn-success btn-sm btn-home" onclick="showCandidatesMsgModal(this)" data-id="{{ $value->user->id }}">Send Message</button>
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
                            <span>Sending...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Div for Send Message -->


    <div id="js-div-userview" style="display: none;">

    </div>

</div>



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
                <button type="button" class="btn btn-primary" id="js-btn-candidate-send-message">Send</button>
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

@stop

@section('custom-scripts')
	{{ HTML::script('/assets/js/typeahead.min.js') }}
    @include('js.company.user.applied')
@stop