@extends('agency.layout')

@section('custom-styles')
    {{ HTML::style('/assets/css/style_label_select_box.css') }}
	<style>
	    .btn {
	        font-size: 11px;
	    }
	</style>
@stop

@section('body')
<main class="bs-docs-masthead gray-container padding-top-xs" role="main" style="min-height: 0px;">
    <div class="container">
        <div class="row padding-top-normal padding-bottom-sm" style="background-color: rgba(255, 255, 255, 0.4);">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{ URL::route('agency.user.find') }}">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <label class="row">Registered Date</label>
                                    <div class="row">
                                        <input class="form-control form-control-inline  date-picker" data-date-format="yyyy-mm-dd"  type="text" value="{{$startDate}}" name="startDate" id="startDate">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <label class="row">Skill</label>
                                    <div class="row">
                                        <input class="form-control form-control-inline typeahead tt-query" type="text" value="{{$skill_name}}" name="skill_name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="col-sm-11 col-sm-offset-1">
                                    <label class="row">Filter By</label>
                                    <div class="row">
                                        {{ Form::select('filter_option'
                                           , array('0' => 'All', '1' => 'Applied', '2' => 'Matched', '3' => 'Followers')
                                           , $filter_option
                                           , array('class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <label class="row">Label</label>
                                    <div class="row">
                                        <select class="form-control" name="label_option">
                                            <option value="0" @if ($label_option == 0) selected @endif>All</option>
                                            @foreach ($labels as $label)
                                                <option value="{{ $label->id }}" @if ($label_option == $label->id) selected @endif>{{ $label->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-top-normal">
                            <div class="col-sm-2 col-sm-offset-5 text-center">
                                <button class="btn btn-primary btn-block"><i class="glyphicon glyphicon-search"></i>   Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<div class="gray-container">
    <div class="container" style="min-height: 320px;">
        <div class="row margin-bottom-sm">
			@foreach ($users as $user)
			<div class="row margin-top-xs" id="div_job">
				<div class="row table-job-row padding-top-sm padding-bottom-sm" id="user-detail-container-{{ $user->id }}" style="position: relative">
                    @if ($user->sharedBy($agency->id))
                        <img src="{{ HTTP_IMAGE_PATH.'shared-marker.png' }}" class="shared-marker"/>
                    @endif
                    <div class="col-sm-2 text-center">
                        <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH. $user->profile_image }}" class="img-circle">
                        <div class="col-sm-12 margin-top-xs">
                            <a onclick="showUserView(this)" data-userId="{{ $user->id }}" class="username">{{ $user->name }}</a>@if ($user->age($user->id) != 0), <b>{{ $user->age($user->id) }}</b> @endif
                        </div>

                        <div class="col-sm-12 find-people-rating">
                            <input id="input-rate-{{ $user->id }}" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($user->scores()->where('company_id', $agency->id)->get()) > 0 ? $user->scores()->where('company_id', $agency->id)->firstOrFail()->score : 0  }}" onchange="showSaveButton(this)">
                        </div>

                        <div class="col-sm-12">
                            <div class="row">
                                <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $user->id }}" style="display: none;" onclick="saveUserScore(this)">Save</a>
                            </div>
                        </div>

                        @if ($user->labelIdsOfAgency($agency->id) != '')
                            <div class="col-sm-12 margin-top-xs">
                                <div class="row">
                                    @foreach($user->labels()->where('company_id', $agency->id)->get() as $label)
                                        <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-2 text-center">
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

                    <div class="col-sm-2 margin-top-xs">
                        <div class="row">
                            <button class="btn btn-sm btn-primary" onclick="showMsgModal(this)" data-id="{{ $user->id }}"><i class="fa fa-envelope-o"></i> Send Message</button>
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
                        <div class="row">
                            @if (!$user->isCandidate($agency->id))
                            <button class="btn btn-sm btn-danger margin-top-xxs" data-id="{{ $user->id }}" id="js-btn-addToCandidates"><i class="fa fa-save"></i> {{ trans('user.add_to_candidates') }}</button>
                            @endif
                        </div>
                        <div class="row">
                            <button class="btn btn-sm margin-top-xxs" onclick="showLabelBox(this)" data-id="{{ $user->id }}" data-labelids="{{ $user->labelIdsOfAgency($agency->id) }}"><i class="fa fa-gear"></i> Labels</button>
                            <div id="label-box-container"></div>
                        </div>
                    </div>
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
			
			<?php if (count($users) == 0) {?>
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


<!--Div for Label Box-->
<div class="select-menu-modal hidden" id="js-div-label-box">
    <input type="hidden" id="js-input-user-id" value=""/>
    <div class="select-menu-header">
        <span class="select-menu-title">Apply labels to this User</span>
        <span class="octicon octicon-x js-menu-close" role="button" aria-label="Close" onclick="closeLabelBox(this);"><i class="fa fa-times"></i></span>
    </div>

    <div class="select-menu-filters">
        <div class="select-menu-text-filter">
            <input type="text" id="label-filter-field" class="js-filterable-field js-navigation-enable" placeholder="Filter labels" autocomplete="off">
        </div>
    </div>

    <div class="select-menu-list" data-filterable-for="label-filter-field" data-filterable-type="substring">
        <input hidden="checkbox" name="issue[labels][]" value="">

        @foreach($labels as $label)
        <div class="select-menu-item js-navigation-item" data-id="{{ $label->id }}" id="label-item-{{ $label->id }}" data-name="{{ $label->name }}">
            <span class="select-menu-item-icon octicon octicon-check"><i class="fa fa-check"></i></span>
            <div class="select-menu-item-text">
                <div class="color-label-wrapper">
                    <div data-name="bug" class="color-label">
                        <input style="display:none" type="checkbox" value="bug" name="issue[labels][]">
                        <span class="color" style="background-color: {{ $label->color }}"></span>
                        <span class="name">{{ $label->name }}</span>
                        <span class="octicon octicon-x"><i class="fa fa-times"></i></span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="select-menu-no-results">Nothing to show</div>
    </div>
</div>
<!-- EOF for Label Box -->

@stop

@section('custom-scripts')
	{{ HTML::script('/assets/js/typeahead.min.js') }}
	@include('js.agency.user.find')
	@include('js.agency.user.labelBox')
@stop