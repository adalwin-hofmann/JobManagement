<?php 
	$rating = round($company->reviews()->avg('score'));

	$bid_flag = array();
	$cart_flag = array();
	
	foreach ($company->jobs()->get() as $job) {
		$bid_flag[$job->id] = 0;
		$cart_flag[$job->id] = 0;
	}
	
	if (isset($user)) {
		foreach ($user->applies as $apply) {
			$bid_flag[$apply->job_id] = 1;
		}
	
		foreach ($user->carts as $cart) {
			$cart_flag[$cart->job_id] = 1;
		}
	}
?>

@extends('widget.layout')

@section('body')
<div class="container">
    @if ($company->jobs()->get()->count() > 0)
    <div class="row margin-top-lg">
        <span class="span-job-descripton-note"><b>OUR OPENNING JOBS</b></span>
    </div>

    <div class="row margin-top-xs" style="background-color: #F2F5F7;">

        <div class="row margin-top-xs">
            <div class="col-sm-12">
                <div class="col-sm-5" id="job_main_info">
                    <span class="table-header-span">Job</span>
                </div>
                <div class="col-sm-1 text-center" id="job_other_info">
                    @if (!$company->hide_bids_iframe)
                    <span class="table-header-span">Bids</span>
                    @endif
                </div>
                <div class="col-sm-3 text-center" id="job_other_info">
                    @if (!$company->hide_bonus_iframe)
                    <span class="table-header-span">Recruitment Bonus</span>
                    @endif
                </div>
                <div class="col-sm-2 text-center" id="job_other_info">
                    @if (!$company->hide_salary_iframe)
                    <span class="table-header-span">Salary</span>
                    @endif
                </div>
            </div>
        </div>


        @foreach ($company->jobs()->get() as $job)
        <div class="row margin-top-xs" id="div_job">
            <div class="row table-job-row padding-top-xs">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-5 padding-top-xxs" style="padding-left: 0px;" id="job_main_info">
                            <span><a href="{{ URL::route('widget.jobView', array($company->slug, $job->slug)) }}">{{ $job->name }}</a></span>
                        </div>
                        <div class="col-sm-1 text-center padding-top-xxs" id="job_other_info">
                            @if (!$company->hide_bids_iframe)
                            <span>{{ count($job->applies) }}</span>
                            @endif
                        </div>
                        <div class="col-sm-3 text-center padding-top-xxs" id="job_other_info">
                            @if (!$company->hide_bonus_iframe)
                            <span>${{ $job->bonus }}</span>
                            @endif
                        </div>
                        <div class="col-sm-2 text-center padding-top-xxs" id="job_other_info">
                            @if (!$company->hide_salary_iframe)
                            <span>${{ $job->salary }}</span>
                            @endif
                        </div>
                        <div class="col-sm-1 text-right" style="padding-left: 0px;" id="job_apply_button">
                            <?php if ($bid_flag[$job->id] == 1) {?>
                            <div style="padding-top: 4px; height: 28px;">
                                <span class="span-bid">Applied</span>
                            </div>
                            <?php }else {?>
                            <a class="btn btn-success btn-sm btn-home" href="{{ URL::route('widget.job.apply', array($company->slug, $job->slug)) }}">Apply</a>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-xxs">
                    <div class="col-sm-12">
                        <div class="col-sm-12" style="padding-left: 0px;">
                            <button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_hint" other-target-third="div_apply" data-target="div_overview" onclick="showView(this)"> Overview</button>
                            <button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_overview" other-target-third="div_apply" data-target="div_hint" onclick="showView(this)"><i class="fa fa-check"></i> Give us a hint</button>
                        </div>
                    </div>
                </div>


                <!-- Div for Overview -->
                <div class="row" id="div_overview" style="display: none;">
                    <div class="col-sm-12">
                        <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                            <div class="alert alert-success alert-dismissibl fade in">
                                <button type="button" class="close" data-target="div_overview" onclick="hideView(this)">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <p>
                                    <span class="span-job-description-title">Job description:</span>
                                </p>
                                <p>
                                    <span class="span-job-descripton-note">{{ nl2br($job->description) }}</span>
                                </p>
                                <p>&nbsp</p>
                                <p>
                                    <span class="span-job-description-title">Additional requirements:</span>
                                </p>
                                <p>
                                    <span class="span-job-descripton-note">{{ $job->requirements }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End for Overview -->

                <!-- Div for More -->
                <div class="row" id="div_more" style="display: none;">
                    <div class="col-sm-12">
                        <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                            <div class="alert alert-success alert-dismissibl fade in">
                                <button type="button" class="close" data-target="div_more" onclick="hideView(this)">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <p>
                                    <span class="span-job-description-title">Similar Jobs:</span>
                                </p>
                                @foreach($job->category->jobs as $sjob)
                                <?php if ($sjob->id == $job->id) continue;?>
                                <p>
                                    <span class="span-job-descripton-note"><a href="{{ URL::route('widget.jobView', array($company->slug, $sjob->slug)) }}">{{ $sjob->name }}</a></span>
                                </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End for More -->
            </div>
        </div>
        @endforeach
    </div>
    @else
        <div class="row margin-top-lg text-center">
            <span class="span-job-descripton-note"><b>There are no opening jobs.</b></span>
        </div>

        <div class="row margin-top-normal text-center">
            <a class="btn btn-success btn-sm btn-home" href="{{ URL::route('widget.job.apply', $company->slug) }}">Send Open Application</a>
        </div>
    @endif

</div>
@stop

@section('custom-scripts')
	{{ HTML::script('/assets/js/star-rating.min.js') }}
    @include('js.widget.job')
@stop
