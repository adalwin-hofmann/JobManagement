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
		foreach ($job->company->reviews as $review) {
			if ($review->user->id == $userId) {
				$reviewFlag = 0;
				break;
			}
		}
	}
	
	$rating = round($job->company->reviews()->avg('score'));
?>

@extends('widget.layout')

@section('custom-styles')
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	{{ HTML::style('/assets/css/star-rating.min.css') }}
@stop

@section('body')
<div class="container job-container">
	<div class="form-group">
		<div class="row margin-top-normal">
			<div class="col-sm-12" id="div_job">
			    <div class="row">
                    <div class="pull-left detail-navi">
                        <a href="{{ URL::route('widget.home', $job->company->slug) }}" class="color-blue">«&nbsp;Back to Job List</a>
                    </div>
                    <div class="clearfix"></div>
                </div>

				<div class="row">
					<h2 class="color-gray-dark"><b>{{ $job->name }}</b></h2>
				</div>
				<div class="row job-info-bar">	
					<div class="form-group">
						<div class="col-sm-3 text-center">
							<label class="job-info-bar-company-name">{{ $job->company->name }}</label>
						</div>
						<div class="col-sm-9">
							<label class="job-info-bar-type">{{ $job->presence->name }}</label>
							<label class="job-info-bar-address" style="margin-left: 30px;"><i class="fa fa-map-marker"></i> {{ $job->city->name }}</label>
							<label class="job-info-bar-created-time">{{ $job->created_at }}</label>
						</div>
					</div>
				</div>
				
				<div class="row margin-top-sm">
					<div class="col-sm-4 text-center">
						<div class="row">
							<span class="job-span-title-normal">JOB TYPE</span>
						</div>
						<div class="row">
							<span class="job-span-value-normal">{{ $job->type->name }}</span>
						</div>
					</div>
					<div class="col-sm-4 text-center">
						<div class="row">
							<span class="job-span-title-normal">SALARY</span>
						</div>
						<div class="row">
							<span class="job-span-value-normal">${{ $job->salary }}</span>
						</div>
					</div>
					<div class="col-sm-4 text-center" style="padding-right: 0px;">
						<div class="row">
							<span class="job-span-title-normal">RECRUIT BONUS</span>
						</div>
						<div class="row">
							<span class="job-span-value-normal">${{ $job->bonus }}</span>
						</div>
					</div>
				</div>

				
				<div class="row margin-top-sm">
					<div class="col-sm-4 text-center" style="padding-left: 0px;">
						<button class="btn btn-success btn-sm btn-job-apply" data-id="2" data-target="div_hint" onclick="showView(this)"><i class="fa fa-check"></i> GIVE US A HINT</button>
					</div>
					<?php if ($flag == 1) {?>
					<div class="col-sm-4 text-center" style="padding-left: 0px;">
						<button class="btn btn-success btn-sm btn-job-apply" data-id="2" id="js-btn-open-message" super-data-target="div_job"><i class="fa fa-envelope"></i> SEND A MESSAGE</button>
					</div>
					<?php }else {?>
					<div class="col-sm-4 text-center" style="padding-left: 0px;">
						<button class="btn btn-success btn-sm btn-job-apply" id="js-btn-check-apply" data-id="{{ $job->id }}">Apply</button>
					</div>					
					<?php }?>
				</div>
				
				<!-- Div for apply job -->
				<div id="job-apply-div" class="hidden">
					<div class="row">
						<hr/>
					</div>
					
					<div class="row">
						<div class="col-sm-2 col-sm-offset-1">
							{{ Form::label('', 'Pattern', ['class' => 'margin-top-xs job-form-label']) }}
						</div>
						<div class="col-sm-8">
							<select class="form-control" onchange="changePattern(this);">
								@foreach($patterns as $pattern)
								<option value="{{ $pattern->name }}" data-description="{{ $pattern->description }}">{{ $pattern->name }}</option>
								@endforeach
								<option value="" data-descripton="">Other</option>
							</select>
						</div>
					</div>

                    <form method="POST" action="{{ URL::route('user.job.doApply') }}" role="form" class="form-login margin-top-normal" id="js_job_apply_form" enctype="multipart/form-data">

                        <input type="hidden" name="job_id" value="{{ $job->id }}" />

                        <div class="row margin-top-xs">
                            <div class="col-sm-2 col-sm-offset-1">
                                {{ Form::label('', 'Title', ['class' => 'margin-top-xs job-form-label']) }}
                            </div>
                            <div class="col-sm-8">
                                {{ Form::text('name', $patterns[0]->name, ['class' => 'form-control', 'id' => 'title']) }}
                            </div>
                        </div>

                        <div class="row margin-top-xs">
                            <div class="col-sm-2 col-sm-offset-1">
                                {{ Form::label('', 'Description', ['class' => 'margin-top-xs job-form-label']) }}
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
				        <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $job->id }}" data-company-id="{{ $job->company->parent->id }}">Send</button>
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
			                <span class="sr-only">Close</span>
			            </button>
			            
			            <div class="row">
			            	<div class="col-sm-6">
			            		@if (isset($contacts))
								<div class="row">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="span-job-description-title">Contact :</span>
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
										<span class="job-form-label">Name *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="name" type="text" id="name">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_phonenumber) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">Phonenumber *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="phone" type="text" id="phone">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_email) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">Email *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="email" type="text" id="email">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_currentjob) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">Current Job *:</span>
									</div>
									<div class="col-sm-7">
										<input class="form-control" name="currentJob" type="text" id="currentJob">
									</div>
								</div>
								<?php }?>
								<?php if ($job->is_previousjobs) {?>
								<div class="row margin-top-xs">
									<div class="col-sm-5 padding-top-xs text-right">
										<span class="job-form-label">Previous jobs *:</span>
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
										<span class="job-form-label">Description *:</span>
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
									<a class="btn btn-success btn-sm btn-home" style="padding: 5px 30px;" id="js-btn-hint" data-id="{{ $job->id }}">Submit</a>
								</div>	
			            	</div>
			            </div>													
			        </div>				
				</div>
				<!-- End for Hint -->
				
				<div class="row margin-top-sm">
					<span class="span-job-description-title">Required Skills:</span>
				</div>
				<div class="row">
					<?php foreach ($jobSkills as $jobSkill) {?>
						<label class="job-skill-label">{{ $jobSkill->name }}</label>
					<?php }?>
				</div>
				
				<div class="row margin-top-normal">
					<span class="span-job-description-title">Job description:</span>
				</div>
				<div class="row">
					<span class="span-job-descripton-note">{{ nl2br($job->description) }}</span>
				</div>
				
				<div class="row margin-top-normal">
					<span class="span-job-description-title">Additional requirements:</span>
				</div>
				<div class="row">
					<span class="span-job-descripton-note">{{ $job->requirements }}</span>
				</div>
				
				<div class="row margin-top-normal">
					<span class="span-job-description-title">Languages:</span>
				</div>
				<div class="row">
					<span class="span-job-descripton-note">{{ $job->language->name }} </span><span style="color: #B8B5B5;">(Native)</span>
					@foreach ($job->foreignLanguages as $language)
					<span class="span-job-descripton-note">, {{ $language->language->name }}</span>
					@endforeach
				</div>
				
				@if (count($job->benefits) > 0)
				<div class="row margin-top-normal">
					<span class="span-job-description-title">Benefits:</span>
				</div>
				@foreach ($job->benefits as $benefit)
				<div class="row">
					<span class="span-job-descripton-note">{{ $benefit->name }}</span>
				</div>
				@endforeach				
				@endif
				
				<div class="row margin-top-normal">
					<div class="col-sm-6" style="padding-left: 0px;">
						<span class="span-job-description-title">Phone number: </span>
						<span class="span-job-descripton-note"> {{ $job->phone }}</span>
					</div>
					<div class="col-sm-6">
						<span class="span-job-description-title">Email: </span>
						@if ($job->is_published)
						<span class="span-job-descripton-note"> {{ $job->email }}</span>
						@else
						<span class="span-job-descripton-note"> <i class="fa fa-warning"></i>{{ ' Not published by company' }}</span>
						@endif
					</div>
				</div>
				
				<div class="row">
					<hr/>
				</div>
				
				

                @if ($job->company->is_show == 1)
                    <div class="row margin-top-lg">
                        <i class="fa fa-star-o" style="float: left; margin-top: 14px;"></i>
                        <div style="float:left;">
                            <span style="font-size: 30px; font-weight: bold; color: #3c3c3c;">&nbsp{{ $job->company->name }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <hr/>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <img src="{{ HTTP_LOGO_PATH.$job->company->logo }}" width="90%" style="margin-left: 5%;">
                        </div>
                        <div class="col-sm-8">
                            <div class="row">
                                <span class="span-job-descripton-note">{{ nl2br($job->company->description) }}</span>
                            </div>
                            <div class="row">
                                <hr/>
                            </div>
                            <div class="row">
                                <div class="col-sm-6" style="padding-left: 0px;">
                                    <div>
                                        <span class="span-job-descripton-note"><b>Location:</b>&nbsp{{ $job->company->city->name }}</span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note"><b>Phone Number:</b>&nbsp{{ $job->company->phone }}</span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note"><b>Website:</b>&nbsp{{ $job->company->website }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6" style="padding-right: 0px;">
                                    <div>
                                        <span class="span-job-descripton-note"><b>Number of Employees:</b>&nbsp{{ $job->company->teamsize->min.'-'.$job->company->teamsize->max }}</span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note">
                                            <b>Email:</b>&nbsp
                                            @if ($job->company->is_published == 1)
                                            {{ $job->company->email }}
                                            @else
                                            <i class="fa fa-warning"></i>{{ ' Not published by company' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="span-job-descripton-note"><b>Address:</b>&nbsp{{ $job->company->address }}</span>
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
                                <span class="job-company-info-title">Rating:</span>
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
                                <span class="job-company-info-title">Reviews:</span>
                            </div>
                            <div class="row text-center margin-top-xs">
                                <span class="job-company-info-text">{{ count($job->company->reviews) }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- End for View Rating -->


                    <!-- Div for Review -->
                    <?php if ($reviewFlag == 1) {?>
                    <div class="row margin-top-lg">
                        <span class="span-job-descripton-note"><b>LEAVE FEEDBACK</b></span>
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
                            <button class="btn btn-success btn-sm btn-job-apply" id="js-btn-review" data-id="{{ $job->company->id }}">Submit</button>
                        </div>
                    </div>
                    <?php }?>
                    <!-- End for Review -->
				@endif
			</div>
		</div>
	</div>
</div>
@stop

@section('custom-scripts')
	{{ HTML::script('/assets/js/star-rating.min.js') }}
	@include('js.widget.jobView')
@stop