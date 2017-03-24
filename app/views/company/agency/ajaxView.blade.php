    {{ HTML::style('/assets/css/star-rating.min.css') }}
    {{ HTML::script('/assets/js/star-rating.min.js') }}

    <style>
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
            color: black;
        }
    </style>


    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-6" id="js-div-user-detail-view">
                <div class="alert alert-userview alert-dismissable" style="background-color: white; box-shadow: 0 0 8px #989898;">
                    <button type="button" class="close" onclick="hideAgencyView()" aria-hidden="true"></button>

                    <div class="row">
                        <div class="col-sm-3">
                            <img style="width:100px; border: 2px solid #FFF;" src="{{ HTTP_LOGO_PATH.$agency->logo }}">
                        </div>

                        <div class="col-sm-9">
                            <p class="ajax-username">{{ $agency->name }}</p>
                            <p style="margin-bottom: 5px;">{{ $agency->tag }}</p>

                            <input id="input-rate-{{ $agency->id }}" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $agency->reviews()->avg('score')  }}" readonly>
                        </div>
                    </div>

                    <div class="row margin-top-sm">
                        <div class="col-sm-12">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_15_1" data-toggle="tab">
                                        Profile </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_15_2" data-toggle="tab">
                                        Jobs </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_15_3" data-toggle="tab">
                                        Notes </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_15_4" data-toggle="tab">
                                            Interviews
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_15_1">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-3 user-view-experience-container">
                                                    <div class="col-sm-4 text-center user-view-exp-icon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <div class="col-sm-8 text-center" style="background: #16a085; color: white;">
                                                        <div class="row" style="font-weight:bold; padding-top: 1px;">
                                                            <span class="text-uppercase">{{ $agency->year }}+ {{ trans('company.years') }}</span>
                                                        </div>
                                                        <div class="row" style="font-size: 10px; padding-bottom: 3px;">
                                                            <span class="text-uppercase">{{ trans('company.of_foundation') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 col-sm-offset-1">
                                                    <div class="row">
                                                        <span class="span-job-descripton-note"><b>{{ trans('company.expertise') }}:</b>&nbsp</span>
                                                    </div>
                                                    <div class="row">
                                                        <span class="span-job-descripton-note">{{ $agency->expertise }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($agency->description != '')
                                            <div class="row margin-top-sm">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <span class="span-job-descripton-note">{{ $agency->description }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Div for Service -->
                                            @if (count($agency->services) > 0)
                                            <div class="row">
                                                <div class="row margin-top-lg">
                                                    <span class="span-job-descripton-note text-uppercase"><b>{{ trans('company.our_services') }}</b></span>
                                                </div>

                                                <div class="row">
                                                    <hr/>
                                                </div>

                                                @foreach ($agency->services as $service)
                                                <div class="row">
                                                    <div style="width: 100%; display: inline-block;">
                                                        <i class="fa fa-star-o" style="float: left; margin-top: 7px; color: #b4b7bb;"></i>
                                                        <div style="float:left;">
                                                            <span style="font-size: 15px; font-weight: bold;">&nbsp;{{$service->service->name}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 user-view-text">
                                                        {{ $service->description }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <hr/>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            <!-- End for Service -->


                                            <!-- Div for View Feedback -->
                                            @if (count($agency->reviews) > 0)

                                                <div class="row margin-top-sm">
                                                    <span class="span-job-descripton-note text-uppercase"><b>{{ trans('company.employee_feedback') }}</b></span>
                                                </div>

                                                <div class="row">
                                                    <hr/>
                                                </div>

                                                @foreach ($agency->reviews as $review)
                                                <div class="row margin-bottom-normal">
                                                    <div class="col-sm-2">
                                                        <img style="width: 80px; height: 80px;" src="{{ HTTP_PHOTO_PATH.$review->user->profile_image }}" class="img-circle">
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="row">
                                                            <a href="{{ URL::route('user.view', $review->user->slug) }}">{{ $review->user->name }}</a><span style="font-size: 12px; color: #B0B0B0;"> - posted {{ explode(" ", $review->created_at)[0] }}</span>
                                                        </div>
                                                        <div class="row margin-top-xs">
                                                            {{ nl2br($review->description) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach

                                            @endif
                                            <!-- End for View Feedback -->

                                            <div class="row margin-top-normal">
                                                <div class="padding-bottom-xs">
                                                    <span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.location') }}:</b>&nbsp{{ $agency->city->name }}</span>
                                                </div>
                                                <div class="padding-bottom-xs">
                                                    <span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.phone_number') }}:</b>&nbsp{{ $agency->phone }}</span>
                                                </div>
                                                <div class="padding-bottom-xs">
                                                    <span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.website') }}:</b>&nbsp{{ $agency->website }}</span>
                                                </div>
                                                <div class="padding-bottom-xs">
                                                    <span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.number_of_employees') }}:</b>&nbsp{{ $agency->teamsize->min.'-'.$agency->teamsize->max }}</span>
                                                </div>
                                                <div class="padding-bottom-xs">
                                                    <span class="span-job-descripton-note">
                                                        <b style="color: #3b3b3b;">{{ trans('company.email') }}:</b>&nbsp
                                                        {{ $agency->email }}
                                                    </span>
                                                </div>
                                                <div class="padding-bottom-xs">
                                                    <span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.address') }}:</b>&nbsp{{ $agency->address }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_15_2">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                @foreach ($agency->jobs()->get() as $job)
                                                    <p>
                                                        <a href="{{ URL::route('user.dashboard.viewJob', $job->slug) }}">{{ $job->name }}</a>
                                                    </p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_15_3">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <p>
                                                    <b>Share Notes</b>
                                                </p>
                                                @foreach ($company->shareNotesByAgency($agency->id) as $note)
                                                    <div class="alert alert-info">
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                @if ($note->agencyShare->job_id != NULL)
                                                                    Job Name:
                                                                @elseif ($note->agencyShare->user_id != NULL)
                                                                    User Name:
                                                                @else
                                                                    Interview Name:
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-9">
                                                                @if ($note->agencyShare->job_id != NULL)
                                                                    <strong>{{ $note->agencyShare->job->name }}</strong>
                                                                @elseif ($note->agencyShare->user_id != NULL)
                                                                    <strong>{{ $note->agencyShare->user->name }}</strong>
                                                                @else
                                                                    <strong>{{ $note->agencyShare->interview->questionnaire->title }}</strong>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row margin-top-xs">
                                                            <div class="col-sm-3">
                                                                Notes:
                                                            </div>
                                                            <div class="col-sm-9">
                                                                <p>{{ nl2br($note->note) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_15_4">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                @foreach($agency->videoInterviews() as $viCreated)
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
    </div>


    <script type="text/javascript">
        // User Form Functions
        function saveUserNote(obj) {
            var userId = $(obj).attr('data-userid');
            var note = $('textarea#js-textarea-user-note').val();

            $.ajax({
                url: "{{ URL::route('company.user.async.saveNotes') }}",
                dataType : "json",
                type : "POST",
                data : {user_id : userId, notes : note},
                success : function(data) {
                    $('div#js-div-userForm-note-alert').fadeIn('normal');
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

        function hideAgencyView() {
            $('div#js-div-agencyview').fadeOut('normal');
        }

        $(document).mouseup(function (e)
        {
            var container = $("div#js-div-user-detail-view");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                hideAgencyView();
            }
        });
    </script>