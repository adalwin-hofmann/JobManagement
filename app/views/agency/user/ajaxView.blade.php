    {{ HTML::style('/assets/css/star-rating.min.css') }}
    {{ HTML::script('/assets/js/star-rating.min.js') }}

    <style>
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
            color: black;
        }

    </style>

    <?php  $myNote = ''; ?>
    @if ($user->notes()->count() > 0)
        @foreach ($user->notes as $note)
            <?php
                if ($note->company->id == $company->id) $myNote = $note->notes;
            ?>
        @endforeach
    @endif

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-6" id="js-div-user-detail-view">
                <div class="alert alert-userview alert-dismissable" style="background-color: white; box-shadow: 0 0 8px #989898;">
                    <button type="button" class="close" onclick="hideUserView()" aria-hidden="true"></button>

                    <div class="row">
                        <div class="col-sm-3">
                            <img style="width:100px; height:100px; border: 2px solid #FFF;" src="{{ HTTP_PHOTO_PATH.$user->profile_image }}">
                        </div>

                        <div class="col-sm-9">
                            <p class="ajax-username">{{ $user->name }} @if ($user->age($user->id) != 0) {{ $user->age($user->id) }} @endif &nbsp @if ($user->gender == 0) <i class="fa fa-male"></i> @else <i class="fa fa-female"></i> @endif</p>
                            <p style="margin-bottom: 5px;">{{ $user->professional_title }}</p>

                            <input id="input-rate-{{ $user->id }}" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($user->scores()->where('company_id', $company->id)->get()) > 0 ? $user->scores()->where('company_id', $company->id)->firstOrFail()->score : 0  }}" readonly>

                            @if ($user->labelIdsOfAgency($company->id) != '')
                                <div class="col-sm-12 margin-top-xs">
                                    <div class="row">
                                        @foreach($user->labels()->where('company_id', $company->id)->get() as $label)
                                            <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row margin-top-sm">
                        <div class="col-sm-12">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_15_1" data-toggle="tab">
                                        {{ trans('user.profile') }} </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_15_2" data-toggle="tab">
                                        {{ trans('user.applied_jobs') }} </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_15_3" data-toggle="tab">
                                        {{ trans('user.notes') }} </a>
                                    </li>
                                    @if ($user->viCreated($company->id)->get()->count() > 0)
                                    <li class="">
                                        <a href="#tab_15_4" data-toggle="tab">
                                        {{ trans('user.interviews') }} </a>
                                    </li>
                                    @endif
                                    @if ($company->availableJobs($user->id)->count() > 0)
                                    <li class="">
                                        <a href="#tab_15_5" data-toggle="tab">
                                        {{ trans('user.invites') }}
                                        </a>
                                    </li>
                                    @endif
                                    <li class="">
                                        <a href="#tab_15_6" data-toggle="tab">
                                        {{ trans('user.messages') }}
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_15_1">

                                        <div id="note-box">
                                            <div class="alert alert-success alert-dismissable" id="js-div-userForm-note-alert" style="display: none;">
                                                <button type="button" class="close" onclick="hideAlert(this);" aria-hidden="true" ></button>
                                                Note has been saved successfully.
                                            </div>

                                            <p>
                                                <b>{{ trans('user.company_notes') }}:</b>
                                            </p>

                                            <div class="row margin-bottom-xs">
                                                <div class="col-sm-12">
                                                    <textarea style="width: 100%;" rows="5" id="js-textarea-user-note">{{ $myNote }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row text-center">
                                                <button type="button" class="btn btn-primary" onclick="saveUserNote(this)" data-userid="{{ $user->id }}">Save Note</button>
                                            </div>
                                        </div>

                                        <p>
                                            <b>{{ trans('user.about_me') }}</b>
                                        </p>
                                        <p>
                                             {{ nl2br($user->about) }}
                                        </p>

                                        <div class="panel-group accordion margin-top-normal" id="accordion2">
                                            @if ($user->experiences()->count() > 0)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_4">
                                                    {{ trans('user.work_experience') }} </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_2_4" class="panel-collapse collapse" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <?php $t = 0;?>
                                                        <?php foreach($user->experiences as $experience){?>
                                                        <?php $t ++;?>
                                                        <div class="row">
                                                            <div class="col-sm-3 padding-bottom-xs margin-top-sm">
                                                                <div class="col-sm-12">
                                                                    <span class="user-view-experiencce-name">{{ $experience->name }}</span>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <span class="user-view-experience-position">{{ $experience->position }}</span>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-4 padding-bottom-xs margin-top-sm">
                                                                <div class="row">
                                                                    <div class="col-sm-2">
                                                                        <div class="user-view-experience-circle-mark"></div>
                                                                    </div>
                                                                    <div class="col-sm-9">
                                                                        <div class="row">
                                                                            <span class="user-view-bold-text">{{ $experience->start.' - '.$experience->end }}</span>
                                                                        </div>
                                                                        <div class="row">
                                                                            <span style="color: #16a085;">{{ $experience->type->name }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <span class="margin-top-sm user-view-text">{{ $experience->notes }}</span>
                                                            </div>
                                                        </div>

                                                        <?php if (count($user->experiences) != $t) {?>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="col-sm-12">
                                                                    <hr style="border-top: 1px solid #16a085;"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-sm-offset-3">
                                                                <div class="col-sm-12">
                                                                    <hr/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_1">
                                                    {{ trans('user.skills') }} </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_2_1" class="panel-collapse collapse" style="">
                                                    <div class="panel-body">
                                                        <?php foreach ($user->skills as $skill) {?>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <span class="user-view-small-title text-uppercase" style="color: #999999;">{{ $skill->name }}</span>
                                                            </div>
                                                            <div class="col-sm-6" style="margin-top: 2px;">
                                                                <span class="user-view-text" style="color: #999999;">{{ $skill->value.' '. trans('user.years') }} </span>
                                                            </div>
                                                        </div>
                                                        <?php }?>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <hr/>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <span class="user-view-small-title text-uppercase">{{ count($user->languages)+1 }} {{ trans('user.languages') }}</span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <span class="text-uppercase" style="color: #666666;">{{ $user->language->name }}</span><span class="text-uppercase" style="color: #cccccc;"> ({{ trans('user.native') }})</span>
                                                            </div>
                                                        </div>

                                                        <?php foreach($user->languages as $language) {?>
                                                            <div class="row margin-top-xs">
                                                                <div class="col-sm-12">
                                                                    <span class="text-uppercase user-view-bold-text">{{ $language->language->name }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="row margin-top-xs">
                                                                <div class="col-sm-6">
                                                                    <span class="user-view-text">{{ trans('user.understanding') }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <img src="{{ HTTP_IMAGE_PATH.'mark'.$language->understanding.'.png' }}">
                                                                </div>
                                                            </div>
                                                            <div class="row margin-top-xs">
                                                                <div class="col-sm-6">
                                                                    <span class="user-view-text">{{ trans('user.speaking') }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <img src="{{ HTTP_IMAGE_PATH.'mark'.$language->speaking.'.png' }}">
                                                                </div>
                                                            </div>
                                                            <div class="row margin-top-xs">
                                                                <div class="col-sm-6">
                                                                    <span class="user-view-text">{{ trans('user.writing') }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <img src="{{ HTTP_IMAGE_PATH.'mark'.$language->writing.'.png' }}">
                                                                </div>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($user->hobbies != '')
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_2">
                                                    {{ trans('user.hobbies') }} </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_2_2" class="panel-collapse collapse" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <div class="col-sm-12 text-center">
                                                            <?php
                                                                $hobbies = explode(',', str_replace(' ', '', $user->hobbies));
                                                                foreach($hobbies as $hobby) {
                                                            ?>
                                                                <span class="user-view-bold-text user-view-hobby">{{ $hobby }}</span>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if ($user->educations()->count() > 0 || $user->awards()->count() > 0)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_3">
                                                    @if ($user->educations->count() > 0){{ trans('user.education') }}, @endif @if ($user->awards()->count() > 0){{ trans('user.awards_honors') }}@endif</a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_2_3" class="panel-collapse collapse" style="height: 0px;">
                                                    <div class="panel-body">
                                                        @if ($user->educations()->count() > 0)
                                                        <div class="col-sm-12 margin-bottom-normal">
                                                            <div class="col-sm-12 text-center">
                                                                <span class="user-view-field-title"><i class="fa fa-bank"></i> {{ trans('user.education') }}</span>
                                                            </div>
                                                            <div class="col-sm-12 text-center margin-top-xs margin-bottom-normal">
                                                                <span class="user-view-field-description">{{ trans('user.text_04') }}</span>
                                                            </div>

                                                            <?php foreach($user->educations as $education) {?>
                                                            <div class="col-sm-12 text-center">
                                                                <div class="user-view-education-background">
                                                                    <div class="col-sm-12 text-center" style="margin-top: 23px;">
                                                                        <span style="color: white; font-weight: bold;">{{ $education->start }}</span>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center">
                                                                        <span style="color: white; font-weight: bold;">-</span>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center">
                                                                        <span style="color: white; font-weight: bold;">{{ $education->end }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6" style="border-right: 1px solid #16a085; height: 10px;"></div>
                                                            </div>

                                                            <div class="row margin-top-xs">
                                                                <div class="col-sm-12 text-center">
                                                                    <span class="user-view-bold-text" style="font-size: 17px;">{{ $education->name }}</span>
                                                                </div>
                                                                <div class="col-sm=12 text-center">
                                                                    <span class="user-view-text-small">{{ $education->faculty}}</span>
                                                                </div>
                                                                <div class="col-sm=12 text-center">
                                                                    <span class="user-view-text-small"><i class="fa fa-map-marker"></i> {{ $education->location}}</span>
                                                                </div>

                                                                <div class="col-sm-12 text-center margin-top-xs">
                                                                    <span class="user-view-text">{{ $education->notes }}</span>
                                                                </div>
                                                            </div>
                                                            <?php }?>
                                                        </div>
                                                        @endif

                                                        @if ($user->awards()->count() > 0)
                                                        <div class="col-sm-12" style="background-color: white;">
                                                            <div class="col-sm-12 text-center">
                                                                <span class="user-view-field-title"><i class="fa fa-trophy"></i> {{ trans('user.awards_honors') }}</span>
                                                            </div>
                                                            <div class="col-sm-12 margin-bottom-sm">
                                                                <hr/>
                                                            </div>

                                                            <?php foreach($user->awards as $award) {?>
                                                            <div class="col-sm-12 text-center">
                                                                <div class="user-view-award-background">
                                                                    <div class="col-sm-12 text-center" style="margin-top: 21px;">
                                                                        <span style="color: white; font-weight: bold; font-size: 25px;"><i class="fa fa-trophy"></i></span>
                                                                    </div>
                                                                    <div class="col-sm-12 text-center">
                                                                        <span style="color: white; font-weight: bold;">{{ $award->year }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6" style="border-right: 1px solid #e74c3c; height: 10px;"></div>
                                                            </div>

                                                            <div class="row margin-top-xs">
                                                                <div class="col-sm-12 text-center">
                                                                    <span class="user-view-bold-text" style="font-size: 17px;">{{ $award->name }}</span>
                                                                </div>
                                                                <div class="col-sm=12 text-center">
                                                                    <span class="user-view-text-small">{{ $award->prize}}</span>
                                                                </div>
                                                                <div class="col-sm=12 text-center">
                                                                    <span class="user-view-text-small"><i class="fa fa-map-marker"></i> {{ $award->location}}</span>
                                                                </div>
                                                            </div>
                                                            <?php }?>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if ($user->testimonials()->count() > 0)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_5">
                                                    {{ trans('user.testimonials') }} </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_2_5" class="panel-collapse collapse" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <?php foreach($user->testimonials as $testimonial) {?>
                                                        <div class="row">
                                                            <div class="col-sm-12 text-center">
                                                                <div class="user-view-testimonial-profile">
                                                                    <div class="col-sm-12" style="margin-top: 40px;">
                                                                        <span style="color: #cccccc; font-size: 35px;"><i class="fa fa-quote-right"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 margin-top-xs text-center">
                                                                <span class="user-view-text"> {{ $testimonial->notes }}</span>
                                                            </div>
                                                            <div class="col-sm-12 margin-top-xs text-center">
                                                                <span class="user-view-field-description"> {{ $testimonial->name }}</span>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2_6">
                                                    {{ trans('user.contact_detail') }} </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_2_6" class="panel-collapse collapse" style="height: 0px;">
                                                    <div class="panel-body">
                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-map-marker"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #666666">{{ $user->address }}</span>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-top-sm">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-mobile-phone"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #666666">{{ $user->phone }}</span>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-top-sm">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-envelope-o"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #666666">
                                                                        @if ($user->is_published)
                                                                            {{ $user->email }}
                                                                        @else
                                                                            <i class="fa fa-warning"></i> {{ trans('user.msg_30') }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-top-sm">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-gears"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #009cff">{{ $user->website }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <div class="row">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-facebook-square"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #009cff"><a>{{ trans('user.facebook') }}</a></span>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-top-sm">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-linkedin-square"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #009cff"><a>{{ trans('user.linkedin') }}</a></span>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-top-sm">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-twitter-square"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #009cff"><a>{{ trans('user.twitter') }}</a></span>
                                                                </div>
                                                            </div>

                                                            <div class="row margin-top-sm">
                                                                <div class="col-sm-2">
                                                                    <span style="color:#16a085;"><i class="fa fa-google-plus-square"></i></span>
                                                                </div>
                                                                <div class="col-sm-10">
                                                                    <span style="color: #009cff"><a>{{ trans('user.google') }}+</a></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_15_2">
                                        @foreach ($user->applies as $apply)
                                            @if ($apply->job->company_id == $company->id)
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p>
                                                            <a href="{{ URL::route('company.job.view', $apply->job->slug) }}">{{ $apply->job->name }}</a>
                                                        </p>

                                                        <p>
                                                            <span style="float: left; margin-top: 9px; margin-right: 10px;">Apply Rating:</span>
                                                            <input  name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" value="{{ $apply->score  }}" style="float: left;" readonly>
                                                        </p>
                                                        @if ($apply->notes()->count() > 0)
                                                        <p>
                                                            <span style="float: left; margin-right: 10px;">Apply Note:</span>
                                                            <div style="float: left;">
                                                                @foreach ($apply->notes as $note)
                                                                    @if ($note->company_id != $company->id)
                                                                        <p>{{ nl2br($note->notes) }} (by {{ $note->company->name }})</p>
                                                                    @else
                                                                        <p>{{ nl2br($note->notes) }} (by me)</p>
                                                                    @endif
                                                                    <hr/>
                                                                @endforeach
                                                            </div>
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="tab-pane" id="tab_15_3">

                                        <div id="note-box">
                                            <div class="alert alert-success alert-dismissable" id="js-div-userForm-note-alert" style="display: none;">
                                                <button type="button" class="close" onclick="hideAlert(this);" aria-hidden="true" ></button>
                                                Note has been saved successfully.
                                            </div>

                                            <p>
                                                <b>{{ trans('user.company_notes') }}:</b>
                                            </p>

                                            <div class="row margin-bottom-xs">
                                                <div class="col-sm-12">
                                                    <textarea style="width: 100%;" rows="5" id="js-textarea-user-note">{{ $myNote }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row text-center margin-bottom-xs">
                                                <button type="button" class="btn btn-primary" onclick="saveUserNote(this)" data-userid="{{ $user->id }}">Save Note</button>
                                            </div>

                                            @foreach ($user->notes as $note)
                                                <?php
                                                    if ($note->company->id == $company->id) $myNote = $note->notes;
                                                ?>
                                                @if ($note->company_id != $company->id)
                                                <div class="alert alert-success">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <strong>{{ $note->company->name }}</strong>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            {{ nl2br($note->notes) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <hr/>

                                        <p>
                                            <b>{{ trans('user.apply_notes') }}:</b>
                                        </p>
                                        @foreach ($user->applies as $apply)
                                            @if ($apply->job->company_id == $company->id || $apply->job->sharedToCompany($company->id))
                                            @foreach ($apply->notes as $note)
                                                <div class="alert alert-info">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            {{ trans('user.job_name') }}
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <strong>{{ $note->apply->job->name }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-xs">
                                                        <div class="col-sm-3">
                                                            {{ trans('user.notes') }}
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <p>{{ nl2br($note->notes) }} (by <strong>{{ $note->company->name }}</strong>)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @endif
                                        @endforeach

                                        <hr/>

                                        <p>
                                            <b>Interview Notes:</b>
                                        </p>
                                        @foreach ($user->viCreated($company->id)->get() as $viCreated)
                                            @if ($viCreated->responses()->count() > 0 && $viCreated->hasNotes())
                                                <div class="alert alert-warning">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            Interview title:
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <strong>{{ $viCreated->questionnaire->title }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-xs">
                                                        <div class="col-sm-3">
                                                            Questions & Notes:
                                                        </div>
                                                        <div class="col-sm-9">
                                                            @foreach ($viCreated->responses as $key => $value)
                                                                <p><strong>{{ $value->question->question }}</strong></p>
                                                                @foreach ($value->notes as $note)
                                                                    <p>{{ nl2br($note->notes) }} (by @if ($note->company->id != $company->id){{ $note->company->name }} @else me @endif)</p>
                                                                @endforeach
                                                                <hr/>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="tab-pane" id="tab_15_4">
                                        @foreach ($user->viCreated($company->id)->get() as $viCreated)
                                            @if ($viCreated->responses()->count() > 0)
                                                <div class="portlet box blue">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            {{ $viCreated->questionnaire->title }}
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:;" class="collapse">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <ul class="nav nav-tabs">
                                                            @foreach ($viCreated->responses as $key => $value)
                                                            <li class="@if ($key == 0) active @endif">
                                                                <a href="#tab_{{ $value->id }}{{ $value->id }}_{{ $key + 1 }}" data-toggle="tab">
                                                                {{ trans('user.question').' '.($key + 1) }} </a>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="tab-content">
                                                            @foreach ($viCreated->responses as $key => $value)
                                                            <div class="tab-pane @if ($key == 0) active @endif" id="tab_{{ $value->id }}{{ $value->id }}_{{ $key + 1 }}">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <video id="preview" controls style="width: 100%;">
                                                                            <source src="{{ HTTP_VIDEO_PATH.$value->file_name }}" type="video/webm">
                                                                        </video>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <p style="color: #A39D9D;">Interview Questions</p>
                                                                        <p class="p-interview-question">{{ $value->question->question }}</p>

                                                                        <hr/>
                                                                        <div class="row margin-top-xs">
                                                                            <div class="col-sm-12">
                                                                                <span class="span-job-description-title">My Note:</span>
                                                                            </div>

                                                                            <?php
                                                                                $myNotes = '';
                                                                                if ($value->notes()->where('company_id', $company->id)->get()->count() > 0) {
                                                                                    $myNotes = $value->notes()->where('company_id', $company->id)->firstOrFail()->notes;
                                                                                }
                                                                            ?>
                                                                            <div class="col-sm-12 margin-top-xs">
                                                                                {{ nl2br($myNotes) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="tab-pane" id="tab_15_5">
                                        @foreach ($company->availableJobs($user->id) as $item)
                                            <div class="row margin-bottom-xs">
                                                <div class="col-sm-9 padding-top-xs">
                                                    {{ $item->name }}
                                                </div>

                                                <div class="col-sm-3">
                                                    <button type="button" class="btn btn-circle btn-primary" onclick="sendInvite(this)" data-jobId="{{ $item->id }}" data-userId="{{ $user->id }}">Invite</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="tab-pane" id="tab_15_6">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <textarea class="form-control" rows="5" id="js_user_txt_message" placeholder="Please enter message here..."></textarea>
                                            </div>
                                        </div>
                                        <div class="row margin-top-xs margin-bottom-xs">
                                            <div class="col-sm-12 text-right">
                                                <button type="button" class="btn btn-primary" onclick="sendUserMessage(this)" data-userid="{{ $user->id }}">Send</button>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div id="user-message-container">
                                            @foreach($messages as $key => $value)
                                            <div class="row margin-top-sm">
                                                @if ($value->is_company_sent)
                                                <div class="col-sm-2 text-center">
                                                    <img src="{{ HTTP_LOGO_PATH.$value->company->logo }}" style="width: 50%;"/>
                                                    <div class="margin-top-xs">{{ $value->company->name }}</div>
                                                </div>
                                                <div class="col-sm-10">
                                                    <p>
                                                        {{ $value->description}}
                                                        <span class="color-gray-dark font-size-xs">
                                                            <i>( {{ $value->created_at }} )</i>
                                                        </span>
                                                    </p>
                                                </div>
                                                @else
                                                <div class="col-sm-10 text-right">
                                                    <p>{{ $value->description }}</p>
                                                    <span class="color-gray-dark font-size-xs">
                                                        <i>( {{ $value->created_at }} )</i>
                                                    </span>
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <img src="{{ HTTP_PHOTO_PATH.$value->user->profile_image }}" style="width: 50%;" class="img-rounded"/>
                                                    <div class="margin-top-xs">
                                                        <a href="{{ URL::route('user.view', $value->user->slug) }}">
                                                            {{ $value->user->name }}
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        // User Form Functions
        function saveUserNote(obj) {
            var userId = $(obj).attr('data-userid');
            var note = $(obj).parents('div#note-box').eq(0).find('textarea#js-textarea-user-note').eq(0).val();

            $.ajax({
                url: "{{ URL::route('company.user.async.saveNotes') }}",
                dataType : "json",
                type : "POST",
                data : {user_id : userId, notes : note},
                success : function(data) {
                    $(obj).parents('div#note-box').eq(0).find('div#js-div-userForm-note-alert').eq(0).fadeIn('normal');
                    $('textarea#js-textarea-user-note').val(note);
                }
            });
        }

        function hideAlert(obj) {
            $(obj).parents('div').eq(0).fadeOut('normal');
        }

        function sendUserMessage(obj) {
            var userId = $(obj).attr('data-userid');
            var message = $('textarea#js_user_txt_message').val();
            $(obj).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');
            $.ajax({
                url:"{{ URL::route('company.user.async.sendMessage') }}",
                dataType : "json",
                type : "POST",
                data : {user_id : userId, message : message},
                success : function(data){
                    $(obj).html('Send');
                    if (data.result == 'success') {
                        $('div#user-message-container').empty();
                        $('div#user-message-container').html(data.messageView);
                    }
                }
            });
        }
    </script>