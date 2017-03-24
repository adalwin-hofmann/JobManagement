@extends('main')

@section('styles')
    {{ HTML::style('/assets/css/style_bootstrap.css') }}
    {{ HTML::style('/assets/css/style_common.css') }}
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    
	{{ HTML::style('/assets/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
	<!-- END GLOBAL MANDATORY STYLES -->
    
	<!-- BEGIN PAGE LEVEL STYLES -->
	{{ HTML::style('/assets/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.css') }}
	{{ HTML::style('/assets/metronic/assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css') }}
	<!-- END PAGE LEVEL STYLES -->
	
	<!-- BEGIN THEME STYLES -->
	{{ HTML::style('/assets/metronic/assets/global/css/components.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/style.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/pages/css/style-revolution-slider.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/style-responsive.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/themes/blue.css') }}
	{{ HTML::style('/assets/metronic/assets/frontend/layout/css/custom.css') }}
	<!-- END THEME STYLES -->

    {{ HTML::style('/assets/css/style_interview.css') }}

@stop

@section('header')
    <div class="container">
        <div class="interview-header">
            <img src="{{ $company->video_interview_logo == '' ? HTTP_LOGO_PATH.'default_company_logo.gif' : HTTP_LOGO_PATH.$company->video_interview_logo }}" class="interview-company-logo">
        </div>
    </div>
@stop

@section ('body')

    <div class="container">
        <div class="interview-body">
            <div class="interview-date-div">
                {{ date('d/m/Y') }}
            </div>
            <div class="interview-video-content">
                <div class="row" id="vi-main-container">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-6 video-screen-container">
                            <video id="preview" controls class="video-interview-preview"></video>
                            <div class="video-record-time-countdown-box">
                                <p>Record Time</p>
                                <label id="label-record-time">00:59</label>
                                <p>Remaining</p>
                            </div>
                            <div class="video-record-stop-container" id="video-record-stop-container">
                                <button class="stop-recording-button" id="stop"><i class="fa fa-stop"></i> Stop Recording</button>
                            </div>
                            <div class="video-processing margin-top-xs">
                                <label id="label-video-processing"></label>
                            </div>
                        </div>
                        <!-- TABS -->
                        <div class="col-sm-6 tab-style-1">
                            <div class="row">
                                <ul class="nav nav-tabs">
                                    @foreach($questions as $key => $value)
                                        <li @if ($key == 0) class="active disabled" @else class="disabled" @endif id="li-tab-{{$key}}"><a href="#tab-{{ $key + 1 }}" data-toggle="tab">Q{{ $key+1 }}</a></li>
                                    @endforeach
                                </ul>

                                <div class="tab-content">
                                    @foreach($questions as $key => $value)
                                        <div class="tab-pane row fade interview-question-tab @if ($key == 0) in active @endif" id="tab-{{ $key+1 }}">
                                            <p style="color: #A39D9D;">Interview Questions</p>
                                            <p class="p-interview-question">{{ $value->questions->question }}</p>

                                            <div class="col-sm-12 margin-top-normal text-right">
                                                <button class="btn blue" id="restartRecord-{{ $key }}" onclick="restartRecord('{{ $key }}')">Restart Recording</button> @if ($key != count($questions) - 1)<button class="btn green" onclick="recordNext('{{ $key + 1 }}')" id="nextQuestion-{{ $key }}">Next</button> @else <button class="btn green" onclick="saveRecordedFiles()" id="nextQuestion-{{ $key }}">Done</button> @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- END TABS -->
                    </div>
                </div>
            </div>

            <div class="interview-step-box">
                <div class="step-div">
                    <label class="step-div-text">Welcome</label>
                </div>
                <div class="step-div">
                    <label class="step-div-text">Testing webcam</label>
                </div>
                <div class="step-div active">
                    <label class="step-div-text">Interview</label>
                </div>
                <div class="step-div">
                    <label class="step-div-text">Close</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Div for Show Question and Start Recording -->
    <div class="modal fade" id="viQuestionModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body padding-0">
                    <div class="modal-background" style="background-image: url('{{ $company->video_interview_image == '' ? HTTP_COMPANY_PHOTO_PATH.'default.jpg' : HTTP_COMPANY_PHOTO_PATH.$company->video_interview_image }}');">
                        <img src="{{ $company->video_interview_image == '' ? HTTP_COMPANY_PHOTO_PATH.'default.jpg' : HTTP_COMPANY_PHOTO_PATH.$company->video_interview_image }}" style="visibility: hidden; width: 100%">


                        <div class="vi-modal-content">
                            <p class="vi-modal-description">Interview Question:</p>
                            <p class="vi-modal-question" id="js-vi-modal-question">{{ $questions[0]->questions->question }}</p>

                            <div class="row text-center margin-top-normal">
                                <button class="interview-continue-button" id="record">Start Recording</button>
                            </div>
                            <div class="row margin-top-xs text-center">
                                <p style="color: white;">* Don't click anything else than "Start Recording" *</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- EOF for Show Question and Start Recording -->
@stop

@section('footer')
    <div class="container">
        <div class="interview-footer text-center">
            <button class="btn blue disabled">Continue</button>
        </div>
    </div>
    @if($company->video_interview_background != '')
        <img src="{{ HTTP_COMPANY_PHOTO_PATH.$company->video_interview_background }}" class="img-interview-background">
    @endif
@stop

@section('scripts')
    {{ HTML::script('/assets/js/alert.js') }}
    {{ HTML::script('/assets/js/bootbox.js') }}
    
    
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-1.11.0.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}
	{{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/back-to-top.js') }}
	<!-- END CORE PLUGINS -->
	
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	{{ HTML::script('/assets/metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.min.js') }}
	<!-- END PAGE LEVEL PLUGINS -->
	
	
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	{{ HTML::script('/assets/metronic/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.plugins.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js') }}
	{{ HTML::script('/assets/metronic/assets/frontend/pages/scripts/revo-slider-init.js') }}
	{{ HTML::script('/assets/metronic/assets/frontend/layout/scripts/layout.js') }}
	<!-- END PAGE LEVEL SCRIPTS -->

	{{ HTML::script('/assets/js/RecordRTC.js') }}
	{{ HTML::script('/assets/js/jquery.plugin.js') }}
	{{ HTML::script('/assets/js/jquery.countdown.js') }}

    
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            RevosliderInit.initRevoSlider();
            Layout.initTwitter();
            Layout.initFixHeaderWithPreHeader(); /* Switch On Header Fixing (only if you have pre-header) */
            Layout.initNavScrolling(); 
        });
    </script>
    @include('js.interview.stepThree')
@stop

@stop