@extends('user.layout')

@section('body')
<main class="bs-docs-masthead" role="main">
	<div class="background-dashboard"></div>      
    <div class="container">
    	<div class="margin-top-lg"></div>
        <div class="row text-center margin-top-normal margin-bottom-normal">
            <h2 class="home color-white"> {{ trans('job.applied_jobs') }}</h2>
        </div>  
        <div class="row" style="background: #F7F7F7; border: 1px solid #f7f7f7;">
        
			<div class="row margin-top-xs margin-bottom-xs">
				<div class="col-sm-12">
					<div class="col-sm-3">
						<span class="dark-font-color text-uppercase">{{ trans('job.job') }}</span>
					</div>
					<div class="col-sm-2">
						<span class="dark-font-color text-uppercase">{{ trans('job.by') }}</span>
					</div>
					<div class="col-sm-1 text-center">
						<span class="dark-font-color text-uppercase">{{ trans('job.applies') }}</span>
					</div>
					<div class="col-sm-2 text-center">
						<span class="dark-font-color text-uppercase">{{ trans('job.posted_date') }}</span>
					</div>
					<div class="col-sm-1 text-center">
						<span class="dark-font-color text-uppercase">{{ trans('job.budget') }}</span>
					</div>
					<div class="col-sm-2 text-center">
						<span class="dark-font-color text-uppercase">{{ trans('job.applied_date') }}</span>
					</div>
					<div class="col-sm-1">
						<span class="dark-font-color text-uppercase">{{ trans('job.state') }}</span>
					</div>					
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<hr class="devider-line">
				</div>
			</div>
			
			@foreach ($user->applies as $apply)
			<div class="row" id="div_job">
				<div class="row table-job-row padding-top-xs">
				
					<div class="row">
						<div class="col-sm-12">
							<div class="col-sm-3 padding-top-xxs">
								<span><a href="{{ URL::route('user.dashboard.viewJob', $apply->job->slug) }}">{{ $apply->job->name }}</a></span>
							</div>
							<div class="col-sm-2 white-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="bottom" data-image-url="{{ HTTP_LOGO_PATH.$apply->job->company->logo }}" data-tag="{{ $apply->job->company->tag }}" data-description="{{ nl2br($apply->job->company->description) }}">
								<div style="display: inline-block; margin-top: 5px;">
									<span><a href="{{ URL::route('user.company.view', $apply->job->company->slug) }}">{{ $apply->job->company->name }}</a></span>
								</div>
								<?php $rating = round($apply->job->company->reviews()->avg('score'));?>
								@if ($rating > 0)
								<div class="col-sm-12" style="padding-left: 0px; display: inline-block; position:absolute; margin-top: 3px; margin-left: 5px;">
									<?php for ($i = 1; $i <= $rating; $i ++) {?>
									<img src="/assets/img/star-full.png" style="width: 17px;">
									<?php }?>
									<?php for ($i = $rating+1; $i <= 5; $i ++) {?>
									<img src="/assets/img/star-blank.png" style="width: 17px;">
									<?php }?>
								</div>
								@endif
							</div>
							<div class="col-sm-1 text-center padding-top-xxs">
								<span>{{ count($apply->job->applies) }}</span>
							</div>
							<div class="col-sm-2 text-center padding-top-xxs" style="padding-left: 0px; padding-right: 0px;">
								<?php 
									$date = DateTime::createFromFormat('Y-m-d H:i:s', $apply->job->created_at);
								?>
								<span> {{ $date->format('d-m-Y') }}</span>	
							</div>
							<div class="col-sm-1 text-center padding-top-xxs">
								<span>{{ '$'.$apply->job->salary }}</span>
							</div>
							<div class="col-sm-2 text-center padding-top-xxs" style="padding-left: 0px; padding-right: 0px;">
								<?php 
									$date = DateTime::createFromFormat('Y-m-d H:i:s', $apply->created_at);
								?>
								<span> {{ $date->format('d-m-Y') }}</span>	
							</div>
							<div class="col-sm-1 text-center padding-top-xxs">
								<span>
		                        	@if ($apply->status == 1)
		                        		{{ trans('job.read') }}
		                        	@endif
								</span>
							</div>
						</div>
					</div>

					
					<div class="row margin-top-xs">
						<div class="col-sm-12">
							<div class="col-sm-3">
								<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_proposal" other-target-third="div_message" data-target="div_overview" onclick="showView(this)"> {{ trans('job.overview') }}</button>
								<!-- Commented for change -->
								<!-- 
								<button class="btn btn-link btn-sm text-uppercase btn-job-table"> Reviews</button>
								 -->
								<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_overview" other-target-second="div_proposal" other-target-third="div_message" data-target="div_more" onclick="showView(this)"> {{ trans('job.more') }}</button>
							</div>
							<div class="col-sm-2" style="padding-top: 2px;">
								<?php 
									$skillFlag = 0;
									$skillLength = 0;
								    foreach($apply->job->skills as $skill) {
										$skillLength += strlen($skill->name) + 3;
										if ($skillLength >= 14) {
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
							</div>
							<div class="col-sm-3 col-sm-offset-1 text-center">
								<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_overview" other-target-third="div_proposal" data-target="div_message" onclick="showView(this)"><i class="fa fa-check"></i> {{ trans('job.send_message') }}</button>
							</div>
							<div class="col-sm-3 text-center">
								<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_overview" other-target-third="div_message" data-target="div_proposal" onclick="showView(this)"> {{ trans('job.view_my_proposal') }}</button>
							</div>
						</div>
					</div>
					
					<!-- Div for Overview -->
					<div class="row" id="div_overview" style="display: none;">
						<div class="col-sm-12">
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in">
						            <button type="button" class="close" data-target="div_overview" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">Close</span>
						            </button>
									<p>
										<span class="span-job-description-title">{{ trans('job.job_description') }}:</span>
									</p>
									<p>	
										<span class="span-job-descripton-note">{{ nl2br($apply->job->description) }}</span>
									</p>
									<p>&nbsp</p>
									<p>
										<span class="span-job-description-title">{{ trans('job.additional_requirements') }}:</span>
									</p>
									<p>	
										<span class="span-job-descripton-note">{{ $apply->job->requirements }}</span>
									</p>
						        </div>
							</div>
						</div>
					</div>
					<!-- End for Overview -->
					
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
										<span class="span-job-description-title">{{ trans('job.similar_jobs') }}:</span>
									</p>
									@foreach($apply->job->category->jobs as $sjob)
									<?php if ($sjob->id == $apply->job->id) continue;?>
									<p>	
										<span class="span-job-descripton-note"><a href="{{ URL::route('user.dashboard.viewJob', $sjob->slug) }}">{{ $sjob->name }}</a></span>
									</p>
									@endforeach
						        </div>								
							</div>
						</div>
					</div>
					<!-- End for More -->
					
					
					<!-- Div for Proposal -->
					<div class="row" id="div_proposal" style="display: none;">
						<div class="col-sm-12">
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in">
						            <button type="button" class="close" data-target="div_proposal" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">{{ trans('job.close') }}</span>
						            </button>
									<p>
										<span class="span-job-description-title">{{ trans('job.title') }}:</span>
									</p>
									<p>	
										<span class="span-job-descripton-note">{{ $apply->name }}</span>
									</p>
									<p>&nbsp</p>
									<p>
										<span class="span-job-description-title">{{ trans('job.description') }}:</span>
									</p>
									<p>	
										<span class="span-job-descripton-note">{{ nl2br($apply->description) }}</span>
									</p>
						        </div>
							</div>
						</div>
					</div>
					<!-- End for Proposal -->
					
					
					<!-- Div for Message -->
					<div class="row" id="div_message" style="display: none;">
						<div class="col-sm-12">
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in">
						            <button type="button" class="close" data-target="div_message" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">{{ trans('job.close') }}</span>
						            </button>
									<div class="row padding-top-xs">
										<div class="col-sm-12">
											{{ Form::textarea('message_content', '', ['class' => 'form-control', 'rows' => '5', 'id' => 'message_content']) }}
										</div>
									</div>
									<div class="row margin-top-xs">
										<div class="col-sm-2 col-sm-offset-5 text-center">
											<button class="btn btn-sm btn-primary text-uppercase btn-block" id="js-btn-send-message" data-id="{{ $apply->job->id }}" data-company-id = {{ $apply->job->company->parent->id }}>{{ trans('job.send') }}</button>
										</div>
									</div>
						        </div>
							</div>
						</div>
					</div>
					<!-- End for Message -->
					
				</div>
			</div>		
			@endforeach
        </div>
    </div>
</main>
@stop

@section('custom-scripts')
	@include('js.user.dashboard.appliedJobs')
@stop