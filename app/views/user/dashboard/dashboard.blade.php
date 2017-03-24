<?php 

	/* Calculate percent of profile complete */
	$total_count = count($user->getAllColumnsNames()) + 6;
	$reg_count = 0;
	
	foreach ($user->getAllColumnsNames() as $key) {
		$value = $user->{$key};
		$type = gettype($value);
		
		if ($key == 'profile_image') {
			if ($value != 'default.png') $reg_count ++;
			continue;
		}
		
		if ($type == 'integer') {
			if ($value > 0) $reg_count ++;
		}else if ($type == 'string') {
			if (strlen($value) > 0) $reg_count ++;
		}else if ($type == 'double') {
			if ($value > 0) $reg_count ++;
		}else if ($type == 'boolean') {
			$reg_count ++;
		}
	}
	
	if (count($user->awards) > 0) $reg_count ++;
	if (count($user->educations) > 0) $reg_count ++;
	if (count($user->experiences) > 0) $reg_count ++;
	if (count($user->testimonials) > 0) $reg_count ++;
	if (count($user->skills) > 0) $reg_count ++;
	if (count($user->languages) > 0) $reg_count ++;
	
	$rate = round($reg_count / $total_count * 100);
	

?>

@extends('user.layout')

@section('body')
<main class="bs-docs-masthead gray-container" role="main">
	<div class="background-dashboard" style="z-index: 0;"></div>
    <div class="container">
    	<div class="margin-top-lg"></div>
		<div class="col-sm-9">
	        <div class="row text-center margin-top-sm">
	            <h1 class="color-home" style="color: white;">{{ trans('job.dashboard') }}</h1>
	        </div>
	        
	        <div class="row" style="min-height: 600px;">
				<div class="row margin-top-normal">
					<div class="col-sm-2 alert alert-info1 text-center color-blue" style="height: 130px;">
						<div class="col-sm-12">
							<p><b>{{ trans('job.applications_sent') }}</b></p>
						</div>
						<p style="color: #009cff; font-size: 30px;">{{ $user->applies()->count() }}</p>
					</div>
					<div class="col-sm-2 alert alert-info1 text-center col-sm-offset-2 color-blue" style="height: 130px;">
						<div class="col-sm-12">
							<p><b>{{ trans('job.applications_opened') }}</b></p>
						</div>
						<p style="color: #009cff; font-size: 30px;">{{ $user->applies()->where('status', '>', '0')->count(); }}</p>
					</div>
					<div class="col-sm-2 alert alert-info1 text-center col-sm-offset-2 color-blue" style="height: 130px;">
						<div class="col-sm-12">
							<p><b>{{ trans('job.applications_in_cart') }}</b></p>
						</div>
						<p style="color: #009cff; font-size: 30px;">{{ $user->carts()->count() }}</p>
					</div>                       
				</div>
				
				<!-- Div for Applications sent -->
				<div class="row margin-top-sm">
					<div class="col-sm-11">
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-diamond"></i>{{ trans('job.my_apply') }}
								</div>
							</div>
							<div class="portlet-body">
                                <div class="portlet-tabs">
                                    <ul class="nav nav-tabs dashboard-nav-tabs">
                                        <li class="@if ($applies_state == 'all') active @endif"><a href="{{ URL::route('user.dashboard', 'all') }}">{{ trans('job.all') }} &nbsp <span class="badge badge-primary">{{ $applies_all_count }}</span></a></li>
                                        <li class="@if ($applies_state == 'read') active @endif"><a href="{{ URL::route('user.dashboard', 'read') }}">{{ trans('job.read') }} &nbsp <span class="badge badge-warning">{{ $applies_read_count }}</span></a></li>
                                        <li class="@if ($applies_state == 'sent') active @endif"><a href="{{ URL::route('user.dashboard', 'sent') }}">{{ trans('job.sent') }} &nbsp <span class="badge badge-info">{{ $applies_sent_count }}</span></a></li>
                                        <li class="@if ($applies_state == 'rejected') active @endif"><a href="{{ URL::route('user.dashboard', 'rejected') }}">{{ trans('job.rejected') }} &nbsp <span class="badge badge-danger">{{ $applies_rejected_count }}</span></a></li>
                                    </ul>

                                    <div class="tab-content custom-tab-content">
                                        <div class="tab-pane fade active in">
                                            <div class="row margin-top-xs margin-bottom-xs">
                                                <div class="col-sm-12">
                                                    <div class="col-sm-3">
                                                        <span class="dark-font-color text-uppercase">{{ trans('job.job') }}</span>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <span class="dark-font-color text-uppercase">{{ trans('job.by') }}</span>
                                                    </div>
                                                    <div class="col-sm-1 text-center">
                                                        <span class="dark-font-color text-uppercase">{{ trans('job.applies') }}</span>
                                                    </div>
                                                    <div class="col-sm-2 text-center">
                                                        <span class="dark-font-color text-uppercase">{{ trans('job.budget') }}</span>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <span class="dark-font-color text-uppercase">{{ trans('job.applied_date') }}</span>
                                                    </div>
                                                    <div class="col-sm-1 text-center" style="padding-left: 0px; padding-right: 0px;">
                                                        <span class="dark-font-color text-uppercase">{{ trans('job.state') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <hr class="devider-line">
                                                </div>
                                            </div>

                                            @if (count($applies) > 0)
                                                @foreach ($applies as $apply)
                                                    <div class="row" id="div_job">
                                                        <div class="row table-job-row padding-top-xs">

                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="col-sm-3 padding-top-xxs">
                                                                        <span><a href="{{ URL::route('user.dashboard.viewJob', $apply->job->slug) }}">{{ $apply->job->name }}</a></span>
                                                                    </div>
                                                                    <div class="col-sm-3 white-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="bottom" data-image-url="{{ HTTP_LOGO_PATH.$apply->job->company->parent->logo }}" data-tag="{{ $apply->job->company->parent->tag }}" data-description="{{ nl2br($apply->job->company->parent->description) }}">
                                                                        <div style="display: inline-block; margin-top: 5px;">
                                                                            <span><a href="{{ URL::route('user.company.view', $apply->job->company->parent->slug) }}">{{ $apply->job->company->parent->name }}</a></span>
                                                                        </div>
                                                                        <?php $rating = round($apply->job->company->parent->reviews()->avg('score'));?>
                                                                        @if ($rating > 0)
                                                                        <div class="row company-star-rate-box">
                                                                            <?php for ($i = 1; $i <= $rating; $i ++) {?>
                                                                            <img src="/assets/img/star-full.png" style="width: 17px;">
                                                                            <?php }?>
                                                                            <?php for ($i = $rating+1; $i <= 5; $i ++) {?>
                                                                            <img src="/assets/img/star-blank.png" style="width: 17px;">
                                                                            <?php }?>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-sm-1 text-right padding-top-xxs">
                                                                        <span>{{ count($apply->job->applies) }}</span>
                                                                    </div>
                                                                    <div class="col-sm-2 text-center padding-top-xxs">
                                                                        <span>{{ '$'.$apply->job->salary }}</span>
                                                                    </div>
                                                                    <div class="col-sm-2 padding-top-xxs" style="padding-right: 0px;">
                                                                        <?php
                                                                            $date = DateTime::createFromFormat('Y-m-d H:i:s', $apply->created_at);
                                                                        ?>
                                                                        <span> {{ $date->format('d-m-Y') }}</span>
                                                                    </div>
                                                                    <div class="col-sm-1 text-center padding-top-xxs" style="padding-left: 0px; padding-right: 0px;">
                                                                        <span>
                                                                            @if ($apply->status == 1)
                                                                                <span class="label label-success">{{ trans('job.read') }}</span>
                                                                            @elseif ($apply->status == 0)
                                                                                <span class="label label-warning">{{ trans('job.sent') }}</span>
                                                                            @elseif ($apply->status == 2)
                                                                                <span class="label label-danger">{{ trans('job.rejected') }}</span>
                                                                            @elseif ($apply->status == 3)
                                                                                <span class="label label-info">{{ trans('job.processing') }}</span>
                                                                            @elseif ($apply->status == 4)
                                                                                <span class="label label-primary">{{ trans('job.interview') }}</span>
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
                                                                    <div class="col-sm-3" style="padding-top: 2px;">
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
                                                                    <div class="col-sm-3 text-center">
                                                                        <button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_overview" other-target-third="div_proposal" data-target="div_message" onclick="showView(this)"><i class="fa fa-check"></i> {{ trans('job.send_message') }}</button>
                                                                    </div>
                                                                    <div class="col-sm-3">
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
                                                                                <span class="sr-only">{{ trans('job.close') }}</span>
                                                                            </button>
                                                                            <p>
                                                                                <span class="span-job-description-title">{{ trans('job.job_description') }}:</span>
                                                                            </p>
                                                                            <p>
                                                                                <span class="span-job-descripton-note">{{ nl2br($apply->job->description) }}</span>
                                                                            </p>
                                                                            <p>&nbsp;</p>
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
                                                                                    <button class="btn btn-sm btn-primary text-uppercase btn-block" id="js-btn-send-message" data-id="{{ $apply->job->id }}" data-company-id="{{ $apply->job->company->parent->id }}">{{ trans('job.send') }}</button>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="col-sm-12">
                                                                                    <span> {{ trans('job.my_message_history') }} </span>
                                                                                </div>
                                                                                <div class="col-sm-12">
                                                                                    <hr/>
                                                                                </div>
                                                                            </div>                                                                            
                                                                            
                                                                            @foreach ($apply->job->messages(Session::get('user_id'))->get() as $value)
                                                                    		<div class="row margin-top-sm">
                                                                    			<div class="col-sm-2 text-right">
                                                                    			    @if ($value->is_company_sent)
                                                                    		        {{ $value->company->name." : " }}
                                                                    		        @else
                                                                    		        {{ " You : " }}
                                                                    		        @endif
                                                                    			</div>
                                                                    			<div class="col-sm-10">
                                                                    				<p>
                                                                    				    {{ $value->description}}
                                                                                        <span class="color-gray-dark font-size-xs">
                                                                    					    <i>( {{ $value->created_at }} )</i>
                                                                    				    </span>				    
                                                                    			    </p>
                                                                    			</div>
                                                                    		</div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End for Message -->

                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                            <div class="row">
                                                <div class="col-sm-12 text-center padding-top-xs padding-bottom-xs">
                                                    {{ trans('job.there_are_no_applied_jobs') }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>					
					</div>
				</div>
				<!-- EOF for Applications sent -->

                <!-- Div for Job Recommend sent -->
				<div class="row margin-top-sm">
					<div class="col-sm-11">
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-diamond"></i>{{ trans('job.recommendations_sent') }}
								</div>
								<div class="tools">
									<a href="javascript:;" class="collapse">
									</a>
									<a href="javascript:;" class="remove">
									</a>
								</div>
							</div>
							<div class="portlet-body">

								@if (count($hints) > 0)
								<table class="table table-striped table-bordered table-hover dataTable no-footer">
						            <thead>
						                <tr>
						                    <th>#</th>
						                    <th>{{ trans('job.job_name') }}</th>
						                    <th>{{ trans('job.name') }}</th>
						                    <th>{{ trans('job.state') }}</th>
						                    <th>{{ trans('job.recommended_at') }}</th>
						                    <th style="width: 80px;">{{ trans('job.view') }}</th>
						                </tr>
						            </thead>

						            <tbody>
						            	@foreach ($hints as $key => $value)
										<tr>
					                        <td>{{ $key + 1 }}</td>
					                        <td><a href="{{ URL::route('user.dashboard.viewJob', $value->job->slug) }}">{{ $value->job->name }}</a></td>
					                        <td>{{ $value->name }}</td>
					                        <td>
					                            @if ($value->is_verified == 0)
					                            {{ trans('job.sent') }}
					                        	@elseif ($value->status == 0)
					                        	{{ trans('job.verified') }}
					                        	@elseif ($value->status == 1)
					                        	{{ trans('job.viewed') }}
					                        	@elseif ($value->status == 2)
					                        	{{ trans('job.rejected') }}
					                        	@elseif ($value->status == 3)
					                        	{{ trans('job.processing') }}
					                        	@else
					                        	{{ trans('job.interview') }}
					                        	@endif
					                        </td>
					                        <td>{{ $value->created_at }}</td>
					                        <td>
					                            <a data-id="{{ $value->id }}" onclick="viewHint(this);" class="btn btn-sm btn-info">
					                                <span class="glyphicon glyphicon-edit"></span> {{ trans('job.view') }}
					                            </a>
					                        </td>
										</tr>
						            	@endforeach
						            </tbody>
						    	</table>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">{{ $hints->links() }}</div>
                                    </div>
                                </div>

                                @foreach ($hints as $hint)
                                    <!-- Modal Div for View Recommendation -->
                                    <div class="modal fade" id="viewHintModal{{ $hint->id }}" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                                                    <h4 class="modal-title" id="msgModalLabel">{{ trans('job.view_recommendation') }}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row margin-bottom-xs">
                                                        <div class="col-sm-3">
                                                            <span class="span-job-description-title">{{ trans('job.job_name') }}:</span>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <span class="span-job-descripton-note">{{ $hint->job->name }}</span>
                                                        </div>
                                                    </div>

                                                    <?php if ($hint->name != '') {?>
                                                    <div class="row margin-bottom-xs">
                                                        <div class="col-sm-3">
                                                            <span class="span-job-description-title">{{ trans('job.name') }}:</span>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <span class="span-job-descripton-note">{{ $hint->name }}</span>
                                                        </div>
                                                    </div>
                                                    <?php }?>

                                                    <?php if ($hint->email != '') {?>
                                                    <div class="row margin-bottom-xs">
                                                        <div class="col-sm-3">
                                                            <span class="span-job-description-title">{{ trans('job.email') }}:</span>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <span class="span-job-descripton-note">{{ $hint->email }}</span>
                                                        </div>
                                                    </div>
                                                    <?php }?>

                                                    <?php if ($hint->phone != '') {?>
                                                    <div class="row margin-bottom-xs">
                                                        <div class="col-sm-3">
                                                            <span class="span-job-description-title">{{ trans('job.phone_number') }}:</span>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <span class="span-job-descripton-note">{{ $hint->phone }}</span>
                                                        </div>
                                                    </div>
                                                    <?php }?>

                                                    <?php if ($hint->currentJob != '') {?>
                                                    <div class="row margin-bottom-xs">
                                                        <div class="col-sm-3">
                                                            <span class="span-job-description-title">{{ trans('job.current_job') }}:</span>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <span class="span-job-descripton-note">{{ $hint->currentJob }}</span>
                                                        </div>
                                                    </div>
                                                    <?php }?>

                                                    <?php if ($hint->previousJobs != '') {?>
                                                    <div class="row margin-bottom-xs">
                                                        <div class="col-sm-3">
                                                            <span class="span-job-description-title">{{ trans('job.previous_jobs') }}:</span>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <span class="span-job-descripton-note">{{ $hint->previousJobs }}</span>
                                                        </div>
                                                    </div>
                                                    <?php }?>

                                                    <?php if ($hint->description != '') {?>
                                                    <div class="row margin-bottom-xs">
                                                        <div class="col-sm-3">
                                                            <span class="span-job-description-title">{{ trans('job.description') }}:</span>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <span class="span-job-descripton-note">{{ nl2br($hint->description) }}</span>
                                                        </div>
                                                    </div>
                                                    <?php }?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.close') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Div for View Recommendation -->
                                @endforeach
						    	@else
						    	<div class="row">
						    		<div class="col-sm-12 text-center">
										There are no job recommendations.
						    		</div>
						    	</div>
						    	@endif
							</div>
						</div>
					</div>
				</div>
				<!-- EOF for Job recommend sent -->
				
				<!-- Div for New Jobs -->
				<div class="row margin-top-sm">
					<div class="col-sm-11">
						<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-diamond"></i>{{ trans('job.new_jobs') }}
								</div>
								<div class="tools">
									<a href="javascript:;" class="collapse">
									</a>
									<a href="javascript:;" class="remove">
									</a>
								</div>
							</div>
							<div class="portlet-body">
								
								@if (count($jobs) > 0)
								<table class="table table-striped table-bordered table-hover dataTable no-footer">
						            <thead>
						                <tr>
						                    <th>#</th>
						                    <th>{{ trans('job.job_name') }}</th>
						                    <th>{{ trans('job.count_of_applies') }}</th>
						                    <th>{{ trans('job.state') }}</th>
						                    <th>{{ trans('job.applied_at') }}</th>
						                    <th style="width: 80px;">{{ trans('job.view') }}</th>
						                </tr>
						            </thead>
						            	
						            <tbody>
						            	@foreach ($jobs as $key => $value)
										<tr>
					                        <td>{{ $key + 1 }}</td>
					                        <td><a href="{{ URL::route('user.dashboard.viewJob', $value->slug) }}">{{ $value->name }}</a></td>
					                        <td>{{ $value->applies()->count() }}</td>
					                        <td>
					                        	@if ($value->status == 0)
					                        	{{ trans('job.open') }}
					                        	@elseif ($value->status == 1)
					                        	{{ trans('job.pending') }}
					                        	@elseif ($value->status == 2)
					                        	{{ trans('job.closed') }}
					                        	@endif
					                        </td>
					                        <td>{{ $value->created_at }}</td>
					                        <td>
					                            <a href="{{ URL::route('user.dashboard.viewJob', $value->slug)  }}" class="btn btn-sm btn-info">
					                                <span class="glyphicon glyphicon-edit"></span> {{ trans('job.view') }}
					                            </a>
					                        </td>
										</tr>				            		
						            	@endforeach
						            </tbody>
						    	</table>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">{{ $jobs->links() }}</div>
                                    </div>
                                </div>
						    	@else
						    	<div class="row">
						    		<div class="col-sm-12 text-center">
										{{ trans('job.there_are_no_applied_jobs') }}
						    		</div>
						    	</div>
						    	@endif
							</div>
						</div>					
					</div>
				</div>
				<!-- EOF for New Jobs -->
	        </div> 
		</div>  
		<div class="col-sm-3">
			<!-- Div for Profile -->
			<div class="row margin-top-lg div-gray-box">
				<div class="col-sm-12 div-gray-title-box">
					<i class="fa fa-user"></i>&nbsp <span>{{ trans('job.my_profile') }}</span>
				</div>
				<div class="col-sm-12 margin-top-normal">
					<div class="col-sm-6">
						<img style="width: 80px; height: 80px; border-radius: 5px;" src="{{ HTTP_PHOTO_PATH.$user->profile_image }}">
					</div>
					<div class="col-sm-6">
						<div class="row">
							<span style="color: #717785; font-size: 13px;">{{ trans('job.welcome_back') }},</span>
						</div>
						<div class="row">
							<a href="{{ URL::route('user.view', $user->slug) }}" style="font-size: 20px;">{{ $user->name }}</a>
						</div>
						<div class="row margin-top-xs">
                            <span><b>{{ 'Lv. '.(int)($user->score / $levelCriteria) }}</b></span>
						</div>
						<div class="row">
						    <div class="progress" style="height: 16px;">
                                <div class="progress-bar progress-bar-warning" style="width: {{ (($user->score % $levelCriteria) / $levelCriteria) * 100 }}%">
                                </div>
                            </div>
						</div>
					</div>
				</div>

                <div class="col-sm-12 margin-top-xs">
                    <div class="col-sm-12">
                        <span style="font-size: 13px; word-break: break-word;">{{ trans('job.profile_link') }}: <a href="{{ URL::route('user.view', $user->slug) }}"> {{ URL::route('user.view', $user->slug) }}</a></span>
                    </div>
                </div>

				<div class="col-sm-12 margin-top-sm">
					<div class="col-sm-12">
						<span style="font-size: 13px;">{{ trans('job.setup_your_account') }}</span>
						<span style="font-size: 17px; float:right;">{{ $rate }}%</span>
					</div>
					<div class="col-sm-12">
						<div class="progress">
						    <div class="progress-bar progress-bar-success" style="width: {{ $rate }}%">
						        <span class="sr-only">{{ $rate }}% {{ trans('job.complete') }}</span>
						    </div>
						</div>
					</div>
				</div>
			</div>
			<!-- End for Profile -->
			
			<!-- Div for My Jobs -->


			<div class="row div-gray-box margin-top-sm">
				<div class="col-sm-12 div-gray-title-box">
					<i class="fa fa-tasks"></i>&nbsp <span>{{ trans('job.new_jobs') }}</span>
				</div>
				@if (count($newJobs) > 0)
				<div class="col-sm-12 div-myjobs-box padding-bottom-sm" style="height: 280px;">
					@foreach ($newJobs as $item)
					<div class="col-sm-12 margin-top-sm">
						<span><a href="{{ URL::route('user.dashboard.viewJob', $item->slug) }}" style="color: rgb(62, 77, 92);">{{$item->name}}</a></span>
					</div>
					<div class="col-sm-12 margin-top-xxs">
						@if ($item->status != 2)
						<label class="lavel-inprogress">{{ trans('job.opened') }}</label>
						@else
						<label class="lavel-closed">{{ trans('job.closed') }}</label>
						@endif
					</div>
					<div class="col-sm-12">
						<hr style="margin-top: 10px; margin-bottom: 0px;"/>
					</div>
					@endforeach
				</div>
				@else
				<div class="col-sm-12 text-center margin-top-sm margin-bottom-sm">
					<span>{{ trans('job.text_17') }}</span>
				</div>
				@endif
			</div>
			<!-- End for My Jobs -->
		</div>   
    </div> 
</main>



<!-- Modal Div for Share User Profile to FB -->
@if ($user->fb_share == 1)
<div class="modal fade" id="shareModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('job.close') }}</span></button>
                <h4 class="modal-title" id="msgModalLabel">{{ trans('user.congratulation') }}!</h4>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <p style="font-size: 16px;">{{ trans('user.msg_34') }}</p>
                </div>

                <div class="row margin-top-xs">
                    <div class="col-sm-12 text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.close') }}</button>
                        <a class="btn btn-primary" id="js-btn-add-candidate" href="{{ Share::load(URL::route('user.view', $user->slug) , $user->name)->facebook() }}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); updateScore(this); return false;" data-id="{{ $user->id }}">{{ trans('user.share') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- EOF for Add Candidate -->
@stop

@section('custom-scripts')
    @include('js.user.dashboard.dashboard')
@stop