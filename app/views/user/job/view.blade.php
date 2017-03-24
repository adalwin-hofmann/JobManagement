<?php 
	$flag = 0;
	if (isset($userId)) {
		foreach($job->applies as $apply) {
			if ($apply->user->id == $userId) {
				$flag = 1;
				break;
			}
		}
	}
	
	
	$reviewFlag = 1;
	
	if (!isset($userId)) {
		$reviewFlag = 0;
	}else {
        foreach ($job->company->parent->reviews as $review) {
            if ($review->user->id == $userId) {
                $reviewFlag = 0;
                break;
            }
        }
	}

    $rating = round($job->company->parent->reviews()->avg('score'));

?>

@extends('user.layout')

@section('body')
<div class="container job-container">
	<div class="form-group">
		<div class="row margin-top-normal">
			<div class="col-sm-9" id="div_job">
				<div class="row">
					<h2 class="color-gray-dark"><b>{{ $job->name }}</b></h2>
				</div>

				@if ($job->is_crawled == 0)
                    <div class="row job-info-bar">
                        <div class="form-group">
                            <div class="col-sm-3 text-center">
                                <label class="job-info-bar-company-name">{{ $job->company->parent->name }}</label>
                            </div>
                            <div class="col-sm-9">
                                <label class="job-info-bar-type">{{ $job->presence->name }}</label>

                                <label class="job-info-bar-address" style="margin-left: 30px;"><i class="fa fa-map-marker"></i> {{ $job->city->name }}</label>


                                <!-- Commented for change-->
                                <!--
                                <img src="/assets/img/star-full.png" style="height: 21px; margin-left: 30px;">
                                <img src="/assets/img/star-full.png" style="height: 21px;">
                                <img src="/assets/img/star-full.png" style="height: 21px;">
                                <img src="/assets/img/star-full.png" style="height: 21px;">
                                <img src="/assets/img/star-blank.png" style="height: 21px;">
                                 -->

                                <label class="job-info-bar-created-time">{{ $job->created_at }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row margin-top-sm">
                        <div class="col-sm-4 text-center">
                            <div class="row">
                                <span class="job-span-title-normal text-uppercase">{{ trans('job.job_type') }}</span>
                            </div>
                            <div class="row">
                                <span class="job-span-value-normal">{{ $job->type->name }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="row">
                                <span class="job-span-title-normal text-uppercase">{{ trans('job.salary') }}</span>
                            </div>
                            <div class="row">
                                <span class="job-span-value-normal">${{ $job->salary }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center" style="padding-right: 0px;">
                            <div class="row">
                                <span class="job-span-title-normal text-uppercase">{{ trans('job.recruitment_bonus') }}</span>
                            </div>
                            <div class="row">
                                <span class="job-span-value-normal">${{ $job->bonus }}</span>
                            </div>
                        </div>
                    </div>

				@endif

				
				<div class="row margin-top-sm">
					@if ($flag == 0)
					<div class="col-sm-4" style="padding-left: 0px;">
					    @if ($job->link_address == '')
						    <button class="btn btn-success btn-sm btn-job-apply text-uppercase @if (Session::has('company_id')) disabled @endif" id="js-btn-addToCart" data-id="{{ $job->id }}" ><i class="fa fa-save"></i> {{ trans('job.add_to_application_cart') }}</button>
                        @endif
					</div>
					@endif
					<div class="col-sm-4 text-center" @if ($flag == 1) style="padding-left: 0px;" @endif>
					    @if ($job->link_address == '')
						    <button class="btn btn-success btn-sm btn-job-apply text-uppercase @if (Session::has('company_id')) disabled @endif" data-id="2" data-target="div_hint" onclick="showView(this)"><i class="fa fa-check"></i> {{ trans('job.give_us_hint') }}</button>
                        @endif
					</div>
					<?php if ($flag == 1) {?>
					<div class="col-sm-4 text-center" style="padding-right: 0px;">
					    @if ($job->link_address == '')
                            <button class="btn btn-success btn-sm btn-job-apply text-uppercase @if (Session::has('company_id')) disabled @endif" data-id="2" id="js-btn-open-message" super-data-target="div_job"><i class="fa fa-envelope"></i> {{ trans('job.send_message') }}</button>
                        @endif
					</div>
					<?php }else {?>
					<div class="col-sm-4 text-center" style="padding-right: 0px;">
					    @if ($job->link_address == '')
					    	<button class="btn btn-success btn-sm btn-job-apply @if (Session::has('company_id')) disabled @endif" id="js-btn-check-apply" data-id="{{ $job->id }}">{{ trans('job.apply') }}</button>
                        @else
                            <a class="btn btn-success btn-sm btn-job-apply @if (Session::has('company_id')) disabled @endif" href="{{ $job->link_address }}" target="_blank">{{ trans('job.apply') }}</a>
                        @endif
					</div>					
					<?php }?>
				</div>
				
				<!-- Div for apply job -->
				<div id="job-apply-div" class="hidden">
					<div class="row">
						<hr/>
					</div>

					<form method="POST" action="{{ URL::route('user.job.doApply') }}" role="form" class="form-login margin-top-normal" id="js_job_apply_form" enctype="multipart/form-data">

					    <input type="hidden" name="job_id" value="{{ $job->id }}" />

                        <div class="row">
                            <div class="col-sm-2 col-sm-offset-1">
                                {{ Form::label('', 'Pattern', ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-8">
                                <select class="form-control" onchange="changePattern(this);">
                                    @foreach($patterns as $pattern)
                                    <option value="{{ $pattern->name }}" data-description="{{ $pattern->description }}">{{ $pattern->name }}</option>
                                    @endforeach
                                    <option value="" data-descripton="">{{ trans('job.other') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row margin-top-xs">
                            <div class="col-sm-2 col-sm-offset-1">
                                {{ Form::label('', trans('job.title'), ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-8">
                                {{ Form::text('name', $patterns[0]->name, ['class' => 'form-control', 'id' => 'title']) }}
                            </div>
                        </div>

                        <div class="row margin-top-xs">
                            <div class="col-sm-2 col-sm-offset-1">
                                {{ Form::label('', trans('job.description'), ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-8">
                                {{ Form::textarea('description', $patterns[0]->description, ['class' => 'form-control job-description', 'rows' => '5', 'id' => 'description']) }}
                            </div>
                        </div>



                        <div class="row margin-top-xs">
                            <div class="col-sm-2 col-sm-offset-1">
                                {{ Form::label('', trans('job.add_attachments'), ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-8">
                                {{ Form::file('attachFile', ['class' => 'form-control', 'id' => 'file']) }}
                            </div>
                        </div>

                        <div class="row margin-top-sm">
                            <div class="col-sm-8 col-sm-offset-3 text-right">
                                <div class="col-sm-4 col-sm-offset-8 text-right">
                                    <button class="btn btn-sm btn-primary text-uppercase btn-block text-uppercase">{{ trans('job.submit') }}</button>
                                </div>
                            </div>
                        </div>

					</form>

					<div class="row">
						<hr/>
					</div>	
				</div>
				<!-- End for apply job -->
				
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
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.close') }}</button>
                                <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $job->id }}" data-company-id="{{ $job->company->parent->id }}">{{ trans('job.send') }}</button>
                            </div>
                        </div>
                    </div>
				</div> 
				<!-- End Div for Send Message -->
				
				<!-- Div for Hint -->
				<div class="row margin-top-xs" id="div_hint" style="display: none;">
					<div class="alert alert-success alert-dismissibl fade in" style="background-color: #F5F7FC; border-color: #D5EAF7;">
			            <button type="button" class="close" data-target="div_hint" onclick="hideView(this)">
			                <span aria-hidden="true">&times;</span>
			                <span class="sr-only">{{ trans('job.close') }}</span>
			            </button>
			            
			            <div class="row">
			            	<div class="col-sm-6">
			            		@if (isset($contacts))
								<div class="row">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="span-job-description-title">{{ trans('job.contact') }} :</span>
									</div>
									<div class="col-sm-7">
				            			<select class="form-control" id="js_select_contact" onchange="fillInput(this);">
				            				<option value=""> </option>
				            				@foreach ($contacts as $contact)
				            					<option value="{{ $contact->id }}">{{ $contact->name }}</option>
				            				@endforeach
				            			</select>													
									</div>
								</div>
			            		@endif
								<?php if ($job->is_name) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">{{ trans('job.name') }} *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="name" type="text" id="name">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_phonenumber) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">{{ trans('job.phone_number') }} *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="phone" type="text" id="phone">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_email) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">{{ trans('job.email') }} *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="email" type="text" id="email">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_currentjob) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">{{ trans('job.current_job') }} *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="currentJob" type="text" id="currentJob">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_previousjobs) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">{{ trans('job.previous_jobs') }} *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="previousJobs" type="text" id="previousJobs">
									</div>
								</div>
								<?php }?>						            		
			            	</div>
			            	
			            	<div class="col-sm-5">
			            			
								<?php if ($job->is_description) {?>
								<div class="row">
									<div class="col-sm-12 text-left">
										<span class="job-form-label">{{ trans('job.description') }} *:</span>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<textarea class="form-control" name="description" rows="3" id="description"></textarea>
									</div>
								</div>
								<?php }?>
			            	</div>
			            </div>
			            
			            <div class="row margin-top-xs">
			            	<div class="col-sm-12 text-center">
								<div class="row margin-top-xs">
									<a class="btn btn-success btn-sm btn-home" style="padding: 5px 30px;" id="js-btn-hint" data-id="{{ $job->id }}">{{ trans('job.submit') }}</a>
								</div>	
			            	</div>
			            </div>													
			        </div>				
				</div>
				<!-- End for Hint -->


				@if ($job->is_crawled == 0)
                    <div class="row margin-top-sm">
                        <span class="span-job-description-title">{{ trans('job.required_skills') }}:</span>
                    </div>
                    <div class="row">
                        <?php foreach ($jobSkills as $jobSkill) {?>
                            <label class="job-skill-label">{{ $jobSkill->name }}</label>
                        <?php }?>
                    </div>
				@endif
				

				<div class="row margin-top-normal">
					<span class="span-job-description-title">{{ trans('job.job_description') }}:</span>
				</div>
				<div class="row">
					<span class="span-job-descripton-note">{{ nl2br($job->description) }}</span>
				</div>

				@if ($job->is_crawled == 0)
                    <div class="row margin-top-normal">
                        <span class="span-job-description-title">{{ trans('job.additional_requirements') }}:</span>
                    </div>
                    <div class="row">
                        <span class="span-job-descripton-note">{{ $job->requirements }}</span>
                    </div>

                    <div class="row margin-top-normal">
                        <span class="span-job-description-title">{{ trans('job.languages') }}:</span>
                    </div>
                    <div class="row">
                        <span class="span-job-descripton-note">{{ $job->language->name }} </span><span style="color: #B8B5B5;">({{ trans('job.native') }})</span>
                        @foreach ($job->foreignLanguages as $language)
                        <span class="span-job-descripton-note">, {{ $language->language->name }}</span>
                        @endforeach
                    </div>

                    @if (count($job->benefits) > 0)
                    <div class="row margin-top-normal">
                        <span class="span-job-description-title">{{ trans('job.benefits') }}:</span>
                    </div>
                    @foreach ($job->benefits as $benefit)
                    <div class="row">
                        <span class="span-job-descripton-note">{{ $benefit->name }}</span>
                    </div>
                    @endforeach
                    @endif

                    <div class="row margin-top-normal">
                        <div class="col-sm-6" style="padding-left: 0px;">
                            <span class="span-job-description-title">{{ trans('job.phone_number') }}: </span>
                            <span class="span-job-descripton-note"> {{ $job->phone }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="span-job-description-title">{{ trans('job.email') }}: </span>
                            @if ($job->is_published)
                            <span class="span-job-descripton-note"> {{ $job->email }}</span>
                            @else
                            <span class="span-job-descripton-note"> <i class="fa fa-warning"></i>{{ trans('job.not_published_by_company') }}</span>
                            @endif
                        </div>
                    </div>
				@endif
				
				<div class="row">
					<hr/>
				</div>
				
				
				@if ($job->link_address == '')
                    <div class="row margin-top-lg">
                        <i class="fa fa-star-o" style="float: left; margin-top: 14px;"></i>
                        <div style="float:left;">
                            <span style="font-size: 30px; font-weight: bold; color: #3c3c3c;">&nbsp{{ $job->company->parent->name }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <hr/>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <img src="{{ HTTP_LOGO_PATH.$job->company->parent->logo }}" width="90%" style="margin-left: 5%;">
                        </div>
                        <div class="col-sm-8">
                            <div class="row">
                                <span class="span-job-descripton-note">{{ nl2br($job->company->parent->description) }}</span>
                            </div>
                            <div class="row">
                                <hr/>
                            </div>
                            <div class="row">
                                <div class="col-sm-6" style="padding-left: 0px;">
                                    <div>
                                        <span class="span-job-descripton-note"><b>{{ trans('job.location') }}:</b>&nbsp{{ $job->company->parent->city->name }}</span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note"><b>{{ trans('job.phone_number') }}:</b>&nbsp{{ $job->company->parent->phone }}</span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note"><b>{{ trans('job.website') }}:</b>&nbsp{{ $job->company->parent->website }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6" style="padding-right: 0px;">
                                    <div>
                                        <span class="span-job-descripton-note"><b>{{ trans('job.number_of_employees') }}:</b>&nbsp{{ $job->company->parent->teamsize->min.'-'.$job->company->teamsize->max }}</span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note">
                                            <b>Email:</b>&nbsp
                                            @if ($job->company->parent->is_published == 1)
                                                {{ $job->company->parent->email }}
                                            @else
                                            <i class="fa fa-warning"></i>{{ trans('job.not_published_by_company') }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note"><b>{{ trans('job.address') }}:</b>&nbsp{{ $job->company->parent->address }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <hr/>
                    </div>

                    <!-- Div for View Rating -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row text-center padding-top-xs padding-bottom-xs">
                                <?php for ($i = 1; $i <= $rating; $i ++) {?>
                                <img src="/assets/img/star-full.png" style="width: 40px;">
                                <?php }?>
                                <?php for ($i = $rating+1; $i <= 5; $i ++) {?>
                                <img src="/assets/img/star-blank.png" style="width: 40px;">
                                <?php }?>
                            </div>
                            <div class="row text-center">
                                <span class="job-company-info-title">{{ trans('job.rating') }}:</span>
                            </div>
                            <div class="row text-center margin-top-xs">
                                <span class="job-company-info-text">{{ $rating }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row text-center">
                                <span style="font-size: 40px; color: #009cff;"><i class="fa fa-thumbs-up"></i></span>
                            </div>
                            <div class="row text-center margin-top-xs">
                                <span class="job-company-info-title">{{ trans('job.reviews') }}:</span>
                            </div>
                            <div class="row text-center margin-top-xs">
                                <span class="job-company-info-text">{{ count($job->company->parent->reviews) }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- End for View Rating -->


                    <!-- Div for Review -->
                    <?php if ($reviewFlag == 1) {?>
                    <div class="row margin-top-lg">
                        <span class="span-job-descripton-note text-uppercase"><b>{{ trans('job.leave_feedback') }}</b></span>
                    </div>

                    <div class="row">
                        <hr/>
                    </div>

                    <div class="row">
                        <input id="input-rate" name="rating" class="rating" data-size="sm" data-default-caption="{rating}" data-star-captions="{}">
                    </div>
                    <div class="row margin-top-sm">
                        <textarea class="form-control" id="rating-description" name="description" rows="7" placeholder="Message"></textarea>
                    </div>
                    <div class="row margin-top-sm margin-bottom-sm">
                        <div class="col-sm-3" style="padding-left: 0px;">
                            <button class="btn btn-success btn-sm btn-job-apply" id="js-btn-review" data-id="{{ $job->company->parent->id }}">{{ trans('job.submit') }}</button>
                        </div>
                    </div>
                    <?php }?>
                    <!-- End for Review -->

				@endif
			</div>
			<div class="col-sm-3">
			    <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i> {{ trans('job.similar_jobs') }}
                        </div>
                        <div class="tools">
                            <a class="collapse" href="javascript:;">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div style="max-height: 600px; overflow-y: scroll; overflow-x: hidden;">
                            @foreach($job->category->jobs as $sjob)
                            <?php if ($sjob->id == $job->id) continue;?>
                            <?php if ($sjob->is_finished == 0) continue;?>
                            <?php if ($sjob->is_active == 0) continue;?>
                            <p>
                                <span class="span-job-descripton-note"><a href="{{ URL::route('user.dashboard.viewJob', $sjob->slug) }}">{{ $sjob->name }}</a></span>
                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

@if (isset($company) && !Session::has('company_id'))
<div class="modal fade" id="js-div-modal">
    <div class="modal-dialog large">
        <div class="modal-content">
            <form method="post" action="{{ URL::route('company.auth.referSignup') }}" id="js-form-sign-up">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('user.sign_up') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-7" style="border-right: 2px solid #CCC;">
                            <div class="form-horizontal">
                                <input type="hidden" value="{{ $company->id }}" name="company_id"/>
                                <input type="hidden" value="{{ $job->id }}" name="job_id"/>
                                
                                <div class="form-group has-success">
                                    <label class="col-sm-3 control-label">{{ trans('company.email') }}</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control readonly" placeholder="{{ trans('company.email') }}" value="{{ $company->email }}">
                                    </div>
                                </div>
                                
                                <div class="form-group has-success">
                                    <label class="col-sm-3 control-label">{{ trans('company.name') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control" placeholder="{{ trans('company.name') }}" value="{{ $company->name }}">
                                    </div>
                                </div>
                                
                                <div class="form-group has-error">
                                    <label class="col-sm-3 control-label">{{ trans('company.password') }}</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" class="form-control" placeholder="{{ trans('company.password') }}" autofocus>
                                    </div>
                                </div>
                                
                                <div class="form-group has-error">
                                    <label class="col-sm-3 control-label">{{ trans('company.confirm_password') }}</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('company.confirm_password') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group has-success">
                                    <label class="col-sm-3 control-label">{{ trans('user.phone') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone" class="form-control" placeholder="{{ trans('user.phone') }}" value="{{ $company->phone }}">
                                    </div>
                                </div>

                                <div class="form-group has-success">
                                    <label class="col-sm-3 control-label">{{ trans('company.description') }}</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="description" placeholder="{{ trans('company.description') }}" rows="5">{{ $company->description }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary btn-block" id="js-btn-submit" type="button">{{ trans('user.sign_up') }}</button>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-sm-5">
                            <h3 class="margin-top-normal text-center">{{ trans('company.become_our_member') }}</h3>
                            
                            <div class="row margin-top-normal">
                                <div class="col-sm-2 text-center">
                                    <div class="sign-up-step-no">
                                        <b>1</b>
                                    </div>
                                </div>
                                <div class="col-sm-10 sign-up-step-text">
                                    {{ trans('company.signup_step1') }}
                                </div>
                            </div>
                            
                            <div class="row margin-top-normal">
                                <div class="col-sm-2 text-center">
                                    <div class="sign-up-step-no">
                                        <b>2</b>
                                    </div>
                                </div>
                                <div class="col-sm-10 sign-up-step-text">
                                    {{ trans('company.signup_step2') }}
                                </div>
                            </div>
                            
                            <div class="row margin-top-normal">
                                <div class="col-sm-2 text-center">
                                    <div class="sign-up-step-no">
                                        <b>3</b>
                                    </div>
                                </div>
                                <div class="col-sm-10 sign-up-step-text">
                                    {{ trans('company.signup_step3') }}
                                </div>
                            </div>                 
                                                            
                            <div class="text-center margin-top-normal">
                                <p>{{ trans('company.msg_signup_enjoy') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>  
@endif

@stop

@section('custom-scripts')
	@include('js.user.job.view')
	<script>
	$(document).ready(function() {
	    $("#js-div-modal").modal();

        $("button#js-btn-submit").click(function() {
            var password = $("input[name='password']").val();
            var password_confirmation = $("input[name='password_confirmation']").val();
            if (password == '') {
                bootbox.alert("{{ trans('company.msg_enter_password') }}");
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 2000);                
            } else if (password != password_confirmation) {
                bootbox.alert("{{ trans('company.msg_not_same_password_confirmation') }}");
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 2000);                
            } else {
                $("#js-form-sign-up").submit();
            }
        });	    
	});
	</script>
@stop