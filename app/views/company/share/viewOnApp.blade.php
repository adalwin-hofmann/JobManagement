@extends('company.layout')

@section ('body')
<div class="background-face-interview"  style="background:url('{{ HTTP_COMPANY_PHOTO_PATH.$companyBackground }}'); background-size: cover;"></div>

<div class="container margin-top-normal margin-bottom-normal background-calendar padding-bottom-normal">
    <div class="row margin-top-normal">
        <div class="col-sm-2 col-sm-offset-1">
            <img src="{{ HTTP_LOGO_PATH.$companyLogo}}" style="width: 100%;">
        </div>
        <div class="col-sm-8">
            <h3><b>{{ $agency->name }}</b></h3>
            <p><i>You can see the shared items and leave the note for agency.</i></p>
            <div class="pull-left">
                <a href="{{ URL::route('user.company.view', $agency->slug) }}" target="_blank">
                    <i class="fa fa-building"></i> View Agency Profile
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    @if ($share->job_id != NULL)
    <div class="row margin-top-normal">
        <div class="col-sm-12">
            <div class="col-sm-12 padding-xs" id="div_job"   style="background: white;">
                <div class="col-sm-12 margin-top-xs">
                    <div class="row">
                        <h2 class="color-gray-dark"><b>{{ $share->job->name }}</b></h2>
                    </div>

                    @if ($share->job->is_crawled == 0)
                        <div class="row job-info-bar">
                            <div class="form-group">
                                <div class="col-sm-3 text-center">
                                    <label class="job-info-bar-company-name">{{ $share->job->company->name }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <label class="job-info-bar-type">{{ $share->job->presence->name }}</label>
                                    <label class="job-info-bar-address" style="margin-left: 30px;"><i class="fa fa-map-marker"></i> {{ $share->job->city->name }}</label>
                                    <label class="job-info-bar-created-time">{{ $share->job->created_at }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-top-sm">
                            <div class="col-sm-4 text-center">
                                <div class="row">
                                    <span class="job-span-title-normal text-uppercase">{{ trans('job.job_type') }}</span>
                                </div>
                                <div class="row">
                                    <span class="job-span-value-normal">{{ $share->job->type->name }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center">
                                <div class="row">
                                    <span class="job-span-title-normal text-uppercase">{{ trans('job.salary') }}</span>
                                </div>
                                <div class="row">
                                    <span class="job-span-value-normal">${{ $share->job->salary }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center" style="padding-right: 0px;">
                                <div class="row">
                                    <span class="job-span-title-normal text-uppercase">{{ trans('job.recruitment_bonus') }}</span>
                                </div>
                                <div class="row">
                                    <span class="job-span-value-normal">${{ $share->job->bonus }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($share->job->is_crawled == 0)
                        <div class="row margin-top-sm">
                            <span class="span-job-description-title">{{ trans('job.required_skills') }}:</span>
                        </div>
                        <div class="row">
                            <?php foreach ($share->job->skills as $jobSkill) {?>
                                <label class="job-skill-label">{{ $jobSkill->name }}</label>
                            <?php }?>
                        </div>
                    @endif


                    <div class="row margin-top-normal">
                        <span class="span-job-description-title">{{ trans('job.job_description') }}:</span>
                    </div>
                    <div class="row">
                        <span class="span-job-descripton-note">{{ nl2br($share->job->description) }}</span>
                    </div>

                    @if ($share->job->is_crawled == 0)
                        <div class="row margin-top-normal">
                            <span class="span-job-description-title">{{ trans('job.additional_requirements') }}:</span>
                        </div>
                        <div class="row">
                            <span class="span-job-descripton-note">{{ $share->job->requirements }}</span>
                        </div>

                        <div class="row margin-top-normal">
                            <span class="span-job-description-title">{{ trans('job.languages') }}:</span>
                        </div>
                        <div class="row">
                            <span class="span-job-descripton-note">{{ $share->job->language->name }} </span><span style="color: #B8B5B5;">({{ trans('job.native') }})</span>
                            @foreach ($share->job->foreignLanguages as $language)
                            <span class="span-job-descripton-note">, {{ $language->language->name }}</span>
                            @endforeach
                        </div>

                        @if (count($share->job->benefits) > 0)
                        <div class="row margin-top-normal">
                            <span class="span-job-description-title">{{ trans('job.benefits') }}:</span>
                        </div>
                        @foreach ($share->job->benefits as $benefit)
                        <div class="row">
                            <span class="span-job-descripton-note">{{ $benefit->name }}</span>
                        </div>
                        @endforeach
                        @endif

                        <div class="row margin-top-normal">
                            <div class="col-sm-6" style="padding-left: 0px;">
                                <span class="span-job-description-title">{{ trans('job.phone_number') }}: </span>
                                <span class="span-job-descripton-note"> {{ $share->job->phone }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="span-job-description-title">{{ trans('job.email') }}: </span>
                                @if ($share->job->is_published)
                                <span class="span-job-descripton-note"> {{ $share->job->email }}</span>
                                @else
                                <span class="span-job-descripton-note"> <i class="fa fa-warning"></i>{{ trans('job.not_published_by_company') }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($share->user_id != NULL)
    <div class="row margin-top-normal">
        <div class="col-sm-12">
            <div class="col-sm-12 padding-xs" style="background: white;">
                <div class="col-sm-12">
                    <div class="row" id="div_job">
                        <div class="row table-job-row padding-top-sm padding-bottom-sm" style="border: 0px;">
                            <div class="col-sm-2 text-center">
                                <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH. $share->user->profile_image }}" class="img-circle">
                                <div class="col-sm-12 margin-top-xs">
                                    <a onclick="showUserView(this)" data-userId="{{ $share->user->id }}" class="username">{{ $share->user->name }}</a>@if ($share->user->age($share->user->id) != 0), <b>{{ $share->user->age($share->user->id) }}</b> @endif
                                </div>

                                <div class="col-sm-12 find-people-rating">
                                    <input id="input-rate-{{ $share->user->id }}" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($share->user->scores()->where('company_id', $company->id)->get()) > 0 ? $share->user->scores()->where('company_id', $company->id)->firstOrFail()->score : 0  }}" onchange="showSaveButton(this)">
                                </div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $share->user->id }}" style="display: none;" onclick="saveUserScore(this)">Save</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2 text-center">
                                <div class="col-sm-12 margin-top-xs">
                                    <?php
                                        $skillFlag = 0;
                                        $skillLength = 0;
                                        foreach($share->user->skills()->orderBy('value', 'desc')->get() as $skill) {
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
                                        @foreach($share->user->experiences()->orderBy('start', 'desc')->get() as $item)
                                            @if ($item->end == '0' || $item->end == '')
                                                <p>{{ trans('user.current_job') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ trans('company.still_working') }}</p>
                                            @else
                                                <p>{{ trans('user.previous_jobs') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-sm-12 margin-top-xs">
                                        @foreach ($share->user->educations()->orderBy('start', 'desc')->get() as $item)
                                            <p>{{ trans('user.education_studied') }}: {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                            <?php break;?>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="row">
                                    <div class="col-sm-12 margin-top-xs company-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="left" data-image-url="{{ HTTP_PHOTO_PATH. $share->user->profile_image }}" data-tag="{{ $share->user->name }}" data-description="{{ nl2br($share->user->about) }}">
                                        <?php
                                            $aboutString = $share->user->about;
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


                            <div class="col-sm-2">
                                <button class="btn btn-sm btn-primary" onclick="showMsgModal(this)" data-id="{{ $share->user->id }}"><i class="fa fa-envelope-o"></i> Send Message</button>
                                <button class="btn btn-sm green margin-top-xxs" id="js-btn-video-interview" data-name="{{ $share->user->name }}"  data-userId="{{ $share->user->id }}">
                                    <i class="fa fa-video-camera"></i> Video Interview
                                </button>
                                <button class="btn btn-sm green margin-top-xxs" id="js-btn-face-interview" data-name="{{ $share->user->name }}"  data-userId="{{ $share->user->id }}">
                                    <i class="fa fa-male"></i> Face Interview
                                </button>
                                @if (!$share->user->isCandidate($company->id))
                                <button class="btn btn-sm btn-danger margin-top-xxs" data-id="{{ $share->user->id }}" id="js-btn-addToCandidates"><i class="fa fa-save"></i> {{ trans('user.add_to_candidates') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Div for Send Message -->
    <div class="modal fade" id="msgModal{{ $share->user->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $share->user->id }}">Send</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Div for Send Message -->
    @endif


    @if ($share->interview_id != NULL)
    <div class="row margin-top-normal">
        <div class="col-sm-12">
            <div class="col-sm-12 padding-xs" style="background: white">;
                <div id="div_user" style="border: 0px;">
                    <div class="row padding-bottom-xs">
                        <div class="col-sm-2 text-center">
                            <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$share->interview->user->profile_image }}" class="img-circle">
                        </div>
                        <div class="col-sm-3" style="padding-top: 2px;">
                            <div class="row">
                                <a onclick="showUserView(this)" data-userId="{{ $share->interview->user->id }}" class="username" id="user_name">{{ $share->interview->user->name }}</a>
                            </div>
                            <div class="row" style="position: relative;">
                                <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($share->interview->user->scores()->where('company_id', $company->id)->get()) > 0 ? $share->interview->user->scores()->where('company_id', $company->id)->firstOrFail()->score : 0  }}" onchange="showSaveButton(this)" @if ($company->is_admin != 1 && $share->interview->company_id != $company->id) disabled @endif>
                                <a class="btn btn-sm blue btn-save-rate" id="js-a-save-rate" data-id="{{ $share->interview->user->id }}" style="display: none;" onclick="saveUserScore(this)">{{ trans('company.save') }}</a>
                            </div>
                        </div>
                        <div class="col-sm-3" style="padding-top: 2px;">
                            <div class="row">
                                <?php
                                    $skillFlag = 0;
                                    $skillLength = 0;
                                    foreach($share->interview->user->skills as $skill) {
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
                                @if (count($share->interview->user->skills) == 0)
                                <label>&nbsp</label>
                                @endif
                            </div>
                            <div class="row">
                                <button class="btn btn-link btn-sm btn-common" super-data-target="div_user" data-target="div_appliedJobs" onclick="showJobView(this)">{{ trans('company.view_interview') }}</button>
                            </div>
                        </div>
                        <div class="col-sm-3 col-sm-offset-1 text-center margin-top-xs">
                            <button class="btn btn-success btn-sm btn-home" data-id="{{ $share->interview->user->id }}" onclick="showMsgModal(this)">{{ trans('company.send_message') }}</button>
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
                                                @foreach($share->interview->questionnaire->questions as $key => $value)
                                                    <li @if ($key == 0) class="active" @endif id="li-tab-{{$key}}"><a href="#tab-{{ $share->interview->id }}-{{ $key + 1 }}" data-toggle="tab">{{ trans('company.question').' '.($key+1) }}</a></li>
                                                @endforeach
                                            </ul>

                                            <div class="tab-content">
                                                @foreach($share->interview->questionnaire->questions as $key => $value)
                                                    <div class="tab-pane row fade interview-question-tab @if ($key == 0) in active @endif" id="tab-{{ $share->interview->id }}-{{ $key+1 }}">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <video id="preview" controls class="video-interview-preview">
                                                                    <source src="{{ HTTP_VIDEO_PATH.$share->interview->responses[$key]->file_name }}" type="video/webm">
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
                                                                        if ($share->interview->responses[$key]->notes()->where('company_id', $company->id)->get()->count() > 0) {
                                                                            $myNotes = $share->interview->responses[$key]->notes()->where('company_id', $company->id)->firstOrFail()->notes;
                                                                        }
                                                                    ?>
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" name="notes" rows="8" id="js-textarea-interview-notes-{{ $share->interview->responses[$key]->id }}" data-id="{{ $share->interview->responses[$key]->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
                                                                    </div>
                                                                    <div class="col-sm-12 margin-top-xs">
                                                                        <button class="btn btn-success btn-sm btn-home" data-id="{{ $share->interview->responses[$key]->id }}" onclick="saveInterviewNotes(this)"><i class="fa fa-save"></i> Save Note</button>
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
                <div class="modal fade" id="msgModal{{ $share->interview->user->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
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
                                <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $share->interview->user_id }}">{{ trans('job.send') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Div for Send Message -->
            </div>
        </div>
    </div>
    @endif

    <div class="row margin-top-xs">
        <div class="col-sm-12">
            <div class="col-sm-12" style="background: white;">
                <div class="row">
                    <div class="col-sm-12 margin-top-xs">
                        <label><b>My note for agency</b></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <textarea class="form-control" id="company-share-note" rows="5">{{ $share->noteByCompany($company->id) }}</textarea>
                    </div>
                </div>
                <div class="row margin-top-xs margin-bottom-sm">
                    <div class="col-sm-2 col-sm-offset-5">
                        <button class="btn btn-sm blue form-control" onclick="saveShareNote(this)" data-id="{{ $share->id }}"><i class="fa fa-save"></i> Save Note</button>
                    </div>
                </div>
            </div>
        </div>
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
<div class="modal fade" id="fiModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
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


<div id="js-div-userview" style="display: none;"></div>

@stop

@section('scripts')
    @include('js.company.share.viewOnApp');
@stop

@stop