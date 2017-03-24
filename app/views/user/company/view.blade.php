<?php 
	$reviewFlag = 1;
	
	if (!isset($userId)) {
		$reviewFlag = 0;
	}else {
		foreach ($company->reviews as $review) {
			if ($review->user->id == $userId) {
				$reviewFlag = 0;
				break;
			}
		}
	}
	
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

@extends('user.layout')

@section('body')
<main class="bs-docs-masthead" role="main" style="background-color: #F2F5F7;">
	<div class="background-map-div">
		<div id="mapdiv" style="height:300px;"></div>
		<div class="col-sm-3 padding-top-normal company-view-title-div">
			<div class="col-sm-12">
				<div class="row">
					<i class="fa fa-star-o" style="float: left; margin-top: 14px;"></i>
					<div style="float:left;">
						<span style="font-size: 25px; font-weight: bold; color: #3c3c3c;">&nbsp{{ $company->name }}</span>
					</div>
				</div>
				<div class="row" style="font-size: 15px; color: #838383;">
					<span>&nbsp&nbsp"{{ $company->tag }}"</span>
				</div>

				<div class="row margin-top-sm">
					<img src="{{ HTTP_LOGO_PATH.$company->logo }}" width="90%" style="margin-left: 5%;">
					
					<!-- Commented for change -->
					<!-- 
					<div class="margin-top-sm text-center">
						<a class="a-job"><i class="fa fa-envelope"></i> &nbsp WRITE A MESSAGE</a>
					</div>
					 -->
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="col-sm-12 margin-top-sm margin-bottom-sm" style="background: white;">
			<div class="form-group">
				<div class="col-sm-8" style="border-right: 1px solid #EEE;">
					<div class="col-sm-12">
				
						<div class="row margin-top-sm">
							<div class="col-sm-3 user-view-experience-container">
								<div class="col-sm-4 text-center user-view-exp-icon">
									<i class="fa fa-clock-o"></i>
								</div>
								<div class="col-sm-8 text-center" style="background: #16a085; color: white;">
									<div class="row" style="font-weight:bold; padding-top: 1px;">
										<span class="text-uppercase">{{ $company->year }}+ {{ trans('company.years') }}</span>
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
									<span class="span-job-descripton-note">{{ $company->expertise }}</span>
								</div>			
							</div>
						</div>
						
						<div class="row margin-top-sm">
							<div class="col-sm-12">
								<div class="row">
									<span class="span-job-descripton-note">{{ $company->description }}</span>
								</div>
								
								<div class="row">
									<hr/>
								</div>
							</div>
						</div>
						
						<!-- Div for View Rating -->
						<div class="row">
							<div class="col-sm-6">
								<div class="row text-center padding-top-xs padding-bottom-xs">
									<?php for ($i = 1; $i <= $rating; $i ++) {?>
									<img src="/assets/img/star-full.png" style="width: 30px;">
									<?php }?>
									<?php for ($i = $rating+1; $i <= 5; $i ++) {?>
									<img src="/assets/img/star-blank.png" style="width: 30px;">
									<?php }?>
								</div>
								<div class="row text-center">
									<span class="job-company-info-title">{{ trans('company.rating') }}:</span>
								</div>
								<div class="row text-center margin-top-xs">
									<span class="job-company-info-text">{{ $rating }}</span>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="row text-center">
									<span style="font-size: 40px; color: #009cff;"><i class="fa fa-thumbs-up"></i></span>
								</div>
								<div class="row text-center">
									<span class="job-company-info-title">{{ trans('company.reviews') }}:</span>
								</div>
								<div class="row text-center margin-top-xs">
									<span class="job-company-info-text">{{ count($company->reviews) }}</span>
								</div>
							</div>
							
							<!-- 
							<div class="col-sm-4">
								<div class="row text-center">
									<span style="font-size: 40px; color: #009cff;"><i class="fa fa-comment"></i></span>
								</div>
								<div class="row text-center margin-top-xs">
									<span class="job-company-info-title">Comments:</span>
								</div>
								<div class="row text-center margin-top-xs">
									<span class="job-company-info-text">4</span>
								</div>
							</div>
							 -->
						</div>
						<!-- End for View Rating -->
						
						
						<!-- Div for Service -->
						<div class="row">
							<div class="col-sm-12">
								@if (count($company->services) > 0)
								<div class="row margin-top-lg">
									<span class="span-job-descripton-note text-uppercase"><b>{{ trans('company.our_services') }}</b></span>
								</div>
								
								<div class="row">
									<hr/>
								</div>
								
								@foreach ($company->services as $service)
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
								
								@endif
							</div>
						</div>
						<!-- End for Service -->
						
						
						<!-- Commented for change -->
						<!-- 
						<div class="row">
							<div class="col-sm-3" style="padding-left: 0px;">
								<button class="btn btn-success btn-sm btn-company-blue" id="js-btn-apply" data-id="2"><i class="fa fa-save"></i> FOLLOW COMPANY</button>
							</div>
							<div class="col-sm-3">
								<button class="btn btn-success btn-sm btn-company-blue" id="js-btn-apply" data-id="2"><i class="fa fa-check"></i> SEND APPLICATION</button>
							</div>
							<div class="col-sm-4">
								<button class="btn btn-success btn-sm btn-company-blue" id="js-btn-apply" data-id="2"><i class="fa fa-money"></i> RECOMMENDED A FRIEND</button>
							</div>
						</div>
						
						<div class="row">
							<hr/>
						</div>
						 -->
						
						
						<?php if (count($company->jobs()->where('by_company', 1)->get()) > 0) {?>
						<div class="row margin-top-lg">
							<span class="span-job-descripton-note text-uppercase"><b>{{ trans('company.our_opening_jobs') }}</b></span>
						</div>
						
						<div class="row margin-top-xs" style="background-color: #F2F5F7;">
							<div class="row margin-top-xs">
								<div class="col-sm-12">
									<div class="col-sm-5">
										<span class="table-header-span">{{ trans('company.job') }}</span>
									</div>
									<div class="col-sm-1 text-center">
										<span class="table-header-span">{{ trans('company.bids') }}</span>
									</div>
									<div class="col-sm-3 text-center">
										<span class="table-header-span">{{ trans('company.recruitment_bonus') }}</span>
									</div>
									<div class="col-sm-2 text-center">
										<span class="table-header-span">{{ trans('company.salary') }}</span>
									</div>
								</div>
							</div>
							
							
							@foreach ($company->jobs()->where('by_company', 1)->get() as $job)
							<div class="row margin-top-xs" id="div_job">
								<div class="row table-job-row padding-top-xs">
									<div class="row">
										<div class="col-sm-12">
											<div class="col-sm-5 padding-top-xxs" style="padding-left: 0px;">
												<span><a href="{{ URL::route('user.dashboard.viewJob', $job->slug) }}">{{ $job->name }}</a></span>
											</div>
											<div class="col-sm-1 text-center padding-top-xxs">
												<span>{{ count($job->applies) }}</span>
											</div>
											<div class="col-sm-3 text-center padding-top-xxs">
												<span>${{ $job->bonus }}</span>
											</div>
											<div class="col-sm-2 text-center padding-top-xxs">
												<span>${{ $job->salary }}</span>
											</div>
											<div class="col-sm-1 text-right" style="padding-left: 0px;">
												<?php if ($bid_flag[$job->id] == 1) {?>
												<div style="padding-top: 4px; height: 28px;">
													<span class="span-bid">{{ trans('company.applied') }}</span>
												</div>
												<?php }else {?>
												<button class="btn btn-success btn-sm btn-home" other-target="div_more" other-target-second="div_hint" other-target-third="div_overview" data-target="div_apply" onclick="showView(this)">{{ trans('company.apply') }}</button>
												<?php }?>
											</div>
										</div>
									</div>
									
									<div class="row margin-top-xxs">
										<div class="col-sm-12">
											<div class="col-sm-6" style="padding-left: 0px;">
												<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_hint" other-target-third="div_apply" data-target="div_overview" onclick="showView(this)"> {{ trans('company.overview') }}</button>
												<!-- Commented for change -->
												<!-- 
												<button class="btn btn-link btn-sm text-uppercase btn-job-table"> Reviews</button>
												 -->
												<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_overview" other-target-second="div_hint" other-target-third="div_apply" data-target="div_more" onclick="showView(this)"> {{ trans('company.more') }}</button>
											</div>
											<div class="col-sm-3 text-center">
												<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_overview" other-target-third="div_apply" data-target="div_hint" onclick="showView(this)"><i class="fa fa-check"></i> {{ trans('company.give_us_a_hint') }}</button>
											</div>
											<div class="col-sm-3 text-right" style="padding-left: 0px; padding-right: 0px;">
												@if ($bid_flag[$job->id] == 0)
													@if ($cart_flag[$job->id] == 0)
													<button class="btn btn-link btn-sm text-uppercase btn-job-table" data-id="{{ $job->id }}" id="js-btn-addToCart"><i class="fa fa-save"></i> {{ trans('company.add_to_application_cart') }}</button>
													@else 
													<div style="padding-top: 3px;">
														<span class="text-uppercase span-cart">{{ trans('company.added_to_application_cart') }}</span>
													</div>
													@endif
												@endif
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
										                <span class="sr-only">{{ trans('company.close') }}</span>
										            </button>
													<p>
														<span class="span-job-description-title">{{ trans('company.job_description') }}:</span>
													</p>
													<p>	
														<span class="span-job-descripton-note">{{ nl2br($job->description) }}</span>
													</p>
													<p>&nbsp</p>
													<p>
														<span class="span-job-description-title">{{ trans('company.additional_requirements') }}:</span>
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
										                <span class="sr-only">{{ trans('company.close') }}</span>
										            </button>
													<p>
														<span class="span-job-description-title">{{ trans('company.similar_jobs') }}:</span>
													</p>
													@foreach($job->category->jobs as $sjob)
													<?php if ($sjob->id == $job->id) continue;?>
													<p>	
														<span class="span-job-descripton-note"><a href="{{ URL::route('user.dashboard.viewJob', $sjob->slug) }}">{{ $sjob->name }}</a></span>
													</p>
													@endforeach
										        </div>								
											</div>
										</div>
									</div>
									<!-- End for More -->
									
									<!-- Div for Hint -->
									<div class="row" id="div_hint" style="display: none;">
										<div class="col-sm-12">
											<div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
												<div class="alert alert-success alert-dismissibl fade in">
										            <button type="button" class="close" data-target="div_hint" onclick="hideView(this)">
										                <span aria-hidden="true">&times;</span>
										                <span class="sr-only">{{ trans('company.close') }}</span>
										            </button>
										            
										            <div class="row">
										     			
										     			<input type="hidden" name="is_name" id="is_name" value="{{ $job->is_name }}">
										     			<input type="hidden" name="is_phonenumber" id="is_phonenumber" value="{{ $job->is_phonenumber }}">
										     			<input type="hidden" name="is_email" id="is_email" value="{{ $job->is_email }}">
										     			<input type="hidden" name="is_currentjob" id="is_currentjob" value="{{ $job->is_currentjob }}">
										     			<input type="hidden" name="is_previousjobs" id="is_previousjobs" value="{{ $job->is_previousjobs }}">
										     			<input type="hidden" name="is_description" id="is_description" value="{{ $job->is_description }}">
										     			
										            	<div class="col-sm-6">
															<?php if ($job->is_name) {?>
															<div class="row">
																<div class="col-sm-5 padding-top-xs text-right">
																	<span class="span-job-description-title">{{ trans('company.name') }} *:</span>
																</div>
																<div class="col-sm-7">
																	<input class="form-control" name="name" type="text" id="name">
																</div>
															</div>
															<?php }?>
															<?php if ($job->is_phonenumber) {?>
															<div class="row margin-top-xs">
																<div class="col-sm-5 padding-top-xs text-right">
																	<span class="span-job-description-title">{{ trans('company.phone_number') }} *:</span>
																</div>
																<div class="col-sm-7">
																	<input class="form-control" name="phone" type="text" id="phone">
																</div>
															</div>
															<?php }?>
															<?php if ($job->is_email) {?>
															<div class="row margin-top-xs">
																<div class="col-sm-5 padding-top-xs text-right">
																	<span class="span-job-description-title">{{ trans('company.email') }} *:</span>
																</div>
																<div class="col-sm-7">
																	<input class="form-control" name="email" type="text" id="email">
																</div>
															</div>
															<?php }?>
															<?php if ($job->is_currentjob) {?>
															<div class="row margin-top-xs">
																<div class="col-sm-5 padding-top-xs text-right">
																	<span class="span-job-description-title">{{ trans('company.current_job') }} *:</span>
																</div>
																<div class="col-sm-7">
																	<input class="form-control" name="currentJob" type="text" id="currentJob">
																</div>
															</div>
															<?php }?>
															<?php if ($job->is_previousjobs) {?>
															<div class="row margin-top-xs">
																<div class="col-sm-5 padding-top-xs text-right">
																	<span class="span-job-description-title">{{ trans('company.previous_jobs') }} *:</span>
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
																	<span class="span-job-description-title">{{ trans('company.description') }} *:</span>
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
																<a class="btn btn-success btn-sm btn-home" style="padding: 5px 30px;" id="js-a-hint" data-id="{{ $job->id }}">{{ trans('company.submit') }}</a>
															</div>	
										            	</div>
										            </div>													
										        </div>								
											</div>
										</div>					
									</div>
									<!-- End for Hint -->
									
									<!-- Div for Apply -->
									<div class="row" id="div_apply" style="display: none;">
										<div class="col-sm-12">
											<div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
												<div class="alert alert-success alert-dismissibl fade in">
										            <button type="button" class="close" data-target="div_apply" onclick="hideView(this)">
										                <span aria-hidden="true">&times;</span>
										                <span class="sr-only">{{ trans('company.close') }}</span>
										            </button>

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
                                                                    <option value="" data-descripton="">{{ trans('company.other') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-xs">
                                                            <div class="col-sm-2 col-sm-offset-1">
                                                                {{ Form::label('', trans('company.title'), ['class' => 'margin-top-xs job-form-label']) }}
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ Form::text('name', $patterns[0]->name, ['class' => 'form-control', 'id' => 'title']) }}
                                                            </div>
                                                        </div>

                                                        <div class="row margin-top-xs">
                                                            <div class="col-sm-2 col-sm-offset-1">
                                                                {{ Form::label('', trans('company.description'), ['class' => 'margin-top-xs job-form-label']) }}
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
                                                                    <button class="btn btn-sm btn-primary text-uppercase btn-block">SUBMIT</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
										        </div>								
											</div>
										</div>
									</div>
									<!-- End for Apply -->
								</div>
							</div>
							@endforeach
						</div>
						<?php }?>
						
						
						<!-- Div for View Feedback -->
						@if (count($company->reviews) > 0)
						
						<div class="row margin-top-lg">
							<span class="span-job-descripton-note text-uppercase"><b>{{ trans('company.employee_feedback') }}</b></span>
						</div>
						
						<div class="row">
							<hr/>
						</div>
						
						@foreach ($company->reviews as $review)
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
						
						<!-- Div for Review -->
						<?php if ($reviewFlag == 1) {?>
						<div class="row margin-top-lg">
							<span class="span-job-descripton-note text-uppercase"><b>{{ trans('company.leave_feedback') }}</b></span>
						</div>
						
						<div class="row">
							<hr/>
						</div>
						
						<div class="row">
							<input id="input-rate" name="rating" class="rating" data-size="sm" data-default-caption="{rating}" data-star-captions="{}">
						</div>
						<div class="row margin-top-sm">
							<textarea class="form-control" id="description" name="description" rows="7" placeholder="Message"></textarea>
						</div>
						<div class="row margin-top-sm margin-bottom-sm">
							<div class="col-sm-3" style="padding-left: 0px;">
								<button class="btn btn-success btn-sm btn-job-apply" id="js-btn-review" data-id="{{ $company->id }}">{{ trans('company.submit') }}</button>
							</div>
						</div>
						<?php }?>
						<!-- End for Review -->
					</div>
				</div>
				<div class="col-sm-4">
					<div class="row margin-top-sm">
						<div class="col-sm-12">
						
							<div class="col-sm-12">
								<i class="fa fa-star-o" style="float: left; margin-top: 14px;"></i>
								<div style="float:left;">
									<span style="font-size: 25px; font-weight: bold; color: #3c3c3c;">&nbsp{{ $company->name }}</span>
								</div>
							</div>
							<div class="col-sm-12" style="font-size: 15px; color: #838383;">
								<span>&nbsp&nbsp"{{ $company->tag }}"</span>
							</div>
							
							<div class="col-sm-12">
								<hr/>
							</div>
				
							<div class="col-sm-12">
								<div class="padding-bottom-xs">
									<span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.location') }}:</b>&nbsp{{ $company->city->name }}</span>
								</div>
								<div class="padding-bottom-xs">
									<span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.phone_number') }}:</b>&nbsp{{ $company->phone }}</span>
								</div>
								<div class="padding-bottom-xs">
									<span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.website') }}:</b>&nbsp{{ $company->website }}</span>
								</div>
								<div class="padding-bottom-xs">
									<span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.number_of_employees') }}:</b>&nbsp{{ $company->teamsize->min.'-'.$company->teamsize->max }}</span>
								</div>
								<div class="padding-bottom-xs">
									<span class="span-job-descripton-note">
										<b style="color: #3b3b3b;">{{ trans('company.email') }}:</b>&nbsp
										@if ($company->is_published)
										{{ $company->email }}
										@else
										<i class="fa fa-warning"></i> {{ trans('company.msg_21') }}
										@endif
									</span>
								</div>
								<div class="padding-bottom-xs">
									<span class="span-job-descripton-note"><b style="color: #3b3b3b;">{{ trans('company.address') }}:</b>&nbsp{{ $company->address }}</span>
								</div>
							</div>
							
							<div class="col-sm-12">
								<hr/>
							</div>

                            @if (isset($user))
                                <div class="col-sm-12">
                                    <?php
                                        $followFlag = 0;
                                        foreach($user->followingCompanies as $fcompany) {
                                            if ($fcompany->company_id == $company->id) {
                                                $followFlag = 1;
                                                break;
                                            }
                                        }
                                    ?>



                                    <button class="btn btn-success btn-sm btn-home col-sm-12" id="js-btn-following" data-id="{{ $company->id }}" @if ($followFlag == 0) style="display: none;" @endif><i class="fa fa-check"></i> {{ trans('company.following') }}</button>
                                    <button class="btn btn-success btn-sm btn-home col-sm-12" id="js-btn-follow" data-id="{{ $company->id }}" @if ($followFlag == 1) style="display: none;" @endif>{{ trans('company.follow') }}</button>
                                    <button class="btn btn-success btn-sm btn-home col-sm-12" id="js-btn-processing" style="display: none;">{{ trans('company.process') }}...</button>
                                </div>

                                @if ($company->jobs()->get()->count() == 0 && $company->companyApplies()->where('user_id', $user->id)->get()->count() == 0)
                                    <div class="col-sm-12">
                                        <button class="btn btn-success btn-sm btn-home col-sm-12 margin-top-xs" onclick="showApplyModal();">{{ trans('company.send_open_application') }}</button>
                                    </div>
                                @endif
                            @endif

						</div>
					</div>						
				</div>
			</div>
		</div>
	</div>
</main>


<!-- Modal Div for Send Application -->
<div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ URL::route('user.company.doApply') }}" role="form" class="form-login margin-top-normal" id="js_job_apply_form" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-body" id="div_apply">
                    <input type="hidden" name="company_id" value="{{ $company->id }}" />
                    <div class="row">
                        <div class="col-sm-2">
                            {{ Form::label('', 'Pattern', ['class' => 'margin-top-xs job-form-label']) }}
                        </div>
                        <div class="col-sm-10">
                            <select class="form-control" onchange="changePattern(this);">
                                @foreach($patterns as $pattern)
                                <option value="{{ $pattern->name }}" data-description="{{ $pattern->description }}">{{ $pattern->name }}</option>
                                @endforeach
                                <option value="" data-descripton="">{{ trans('job.other') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-2">
                            {{ Form::label('', trans('job.title'), ['class' => 'margin-top-xs job-form-label']) }}
                        </div>
                        <div class="col-sm-10">
                            {{ Form::text('name', $patterns[0]->name, ['class' => 'form-control', 'id' => 'title']) }}
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-2">
                            {{ Form::label('', trans('job.description'), ['class' => 'margin-top-xs job-form-label']) }}
                        </div>
                        <div class="col-sm-10">
                            {{ Form::textarea('description', $patterns[0]->description, ['class' => 'form-control job-description', 'rows' => '5', 'id' => 'description', 'style' => 'min-height: 200px;']) }}
                        </div>
                    </div>

                    <div class="row margin-top-xs">
                        <div class="col-sm-2">
                            {{ Form::label('', trans('job.add_attachments'), ['class' => 'margin-top-xs job-form-label']) }}
                        </div>
                        <div class="col-sm-10">
                            {{ Form::file('attachFile', ['class' => 'form-control', 'id' => 'file']) }}
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                            <button class="btn btn-primary">SUBMIT</button>
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>
<!-- End Div for Send Application -->

@stop

@section('custom-scripts')
    @include('js.user.company.view')
@stop
