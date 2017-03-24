<?php 
	$bid_flag = array();
	$cart_flag = array();
	
	foreach ($jobs as $job) {
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

@section('custom-styles')
    <link rel="stylesheet" href="/assets/css/bootstrap-slider.css">
@stop

@section('body')
<main class="bs-docs-masthead" role="main" style="min-height: 0px;">
    <div class="search-container-image">
        <div class="search-container-color">
            <div class="container">
                <div class="row" style="background-color: rgba(255, 255, 255, 1.0);">
                    <form class="form-horizontal" method="post" action="{{ URL::route('user.job.search') }}" id="search_form">
                        <div class="col-sm-2 custom-border-right custom-col-4 padding-normal">
                            <div class="col-sm-2 custom-col-5 margin-top-xs">
                                <div class="form-group search-container-field custom-margin">
                                    <label class="color-blue">{{ trans('job.category') }}</label>
                                    <select class="form-control" name="category_id" id="category_select" onchange="getResult()">
                                        <option value="">{{ trans('job.all') }}</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ ($category->id == $category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 custom-col-5 margin-top-xs">
                                <div class="form-group search-container-field custom-margin">
                                    <label class="color-blue">{{ trans('job.sub-category') }}</label>
                                    <div id="sub-category-box"></div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group search-container-field custom-margin">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="{{ trans('job.plc_01') }}" name="keyword" value="{{ $keyword }}" onkeyup="reloadResult(this)">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 custom-border-right custom-col-2 padding-normal">

                            <div class="margin-top-xs">
                                <div class="form-group search-container-field custom-margin">
                                    <label class="color-blue">{{ trans('job.job_type') }}</label>
                                    <select class="form-control" name="type_id" id="type_select" onchange="getResult()">
                                        <option value="">{{ trans('job.all_work_types') }}</option>
                                        @foreach ($types as $type)
                                        <option value="{{ $type->id }}" {{ ($type->id == $type_id) ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="margin-top-xs">
                                <label class="color-blue">Time Period</label>
                            
								
                                <select class="form-control" id="period" name="period" onchange="getResult()">
                                    <option value="0" <?php if ($period == 0) {?> selected <?php }?>>{{ trans('job.any') }}</option>
                                    <option value="1" <?php if ($period == 1) {?> selected <?php }?>>{{ trans('job.today') }}</option>
                                    <option value="3" <?php if ($period == 3) {?> selected <?php }?>>{{ trans('job.last_3_days') }}</option>
                                    <option value="7" <?php if ($period == 7) {?> selected <?php }?>>{{ trans('job.last_7_days') }}</option>
                                    <option value="30" <?php if ($period == 14) {?> selected <?php }?>>{{ trans('job.last_14_days') }}</option>
                                    <option value="60" <?php if ($period == 30) {?> selected <?php }?>>{{ trans('job.last_30_days') }}</option>
                                </select>
								
                            </div>
                        </div>
                        <div class="col-sm-3 custom-border-right custom-col-2 padding-normal search-container-box">
                            <div class="form-group search-container-field custom-margin">
                                <label class="color-blue">{{ trans('job.salary') }}</label>
                                <div>
                                    <input id="js-slider-waiting-time" data-slider-id='js-slider-waiting-time-slider' type="text" data-slider-min="0" data-slider-max="{{ BUDGET_MAX }}" data-slider-step="50" data-slider-value="[{{ $budget_min }},{{ $budget_max }}]"/>
                                </div>
                                <div id="js-div-range-waiting-min" style="color:#1198eb">
                                    ${{ $budget_min }} - ${{ $budget_max }}
                                </div>
                                
                                <input type="hidden" id="js-waiting-time-min" name="min" value="{{ $budget_min }}"/>
                                <input type="hidden" id="js-waiting-time-max" name="max" value="{{ $budget_max }}"/>                               
                            </div>
                        </div> 
                        <div class="col-sm-3 custom-col-2 padding-normal search-container-box">
                            <div class="form-group search-container-field custom-margin">
                                <label class="color-blue">{{ trans('job.location') }}</label>
						        <select class="form-control" name="city_id" onchange="getResult()">
                                    <option value = "0">{{ trans('job.any') }}</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" @if($city->id == $city_id) selected @endif>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>                      	
                        </div>                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="gray-container padding-bottom-xs" style="min-height: 250px;">
    <div class="container">
        <div class="row">
			<div class="row margin-top-xs">
				<div class="col-sm-12">
					<div class="col-sm-3">
						<span class="table-header-span">{{ trans('job.job') }}</span>
					</div>
					<div class="col-sm-1 text-center">
						<span class="table-header-span">{{ trans('job.bids') }}</span>
					</div>
					<div class="col-sm-3">
						<span class="table-header-span">{{ trans('job.by') }}</span>
					</div>
					<div class="col-sm-1 text-center" style="padding-left: 0px; padding-right: 0px;">
						<span class="table-header-span">{{ trans('job.started') }}</span>
					</div>
					<div class="col-sm-2 text-center">
						<span class="table-header-span">{{ trans('job.recruitment_bonus') }}</span>
					</div>
					<div class="col-sm-1">
						<span class="table-header-span">{{ trans('job.salary') }}</span>
					</div>
				</div>
			</div>
			
			@foreach ($jobs as $job)
			<div class="row margin-top-xs" id="div_job">
				<div class="row table-job-row padding-top-xs">
					<div class="row">
						<div class="col-sm-12">
							<div class="col-sm-3 padding-top-xxs">
								<span><a href="{{ URL::route('user.dashboard.viewJob', $job->slug) }}" id="a-job-title">{{ $job->name }}</a></span>
							</div>
							<div class="col-sm-1 text-center padding-top-xxs">
								<span>{{ count($job->applies) }}</span>
							</div>
							@if ($job->link_address == '')
                                <div class="col-sm-3 white-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="bottom" data-image-url="{{ HTTP_LOGO_PATH.$job->company->parent->logo }}" data-tag="{{ $job->company->parent->tag }}" data-description="{{ nl2br($job->company->parent->description) }}">
                                    <div style="display: inline-block; margin-top: 5px;">
                                        <span><a href="{{ URL::route('user.company.view', $job->company->parent->slug) }}">{{ $job->company->parent->name }}</a></span>
                                    </div>
                                    <?php
                                        $rating = round($job->company->parent->reviews()->avg('score'));
                                    ?>
                                    @if ($rating > 0)
                                    <div style="display: inline-block; position:absolute; margin-top: 3px; margin-left: 5px;">
                                        <?php for ($i = 1; $i <= $rating; $i ++) {?>
                                        <img src="/assets/img/star-full.png" style="width: 17px;">
                                        <?php }?>
                                        <?php for ($i = $rating+1; $i <= 5; $i ++) {?>
                                        <img src="/assets/img/star-blank.png" style="width: 17px;">
                                        <?php }?>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="col-sm-3"></div>
                            @endif
							<div class="col-sm-1 text-center padding-top-xxs" style="padding-left: 0px; padding-right: 0px;">
								<?php 
									$date = DateTime::createFromFormat('Y-m-d H:i:s', $job->created_at);
								?>
								<span> {{ $date->format('d-m-Y') }}</span>	
							</div>
							<div class="col-sm-2 text-center padding-top-xxs">
							    @if ($job->bonus != 0)
								    <span>${{ $job->bonus }}</span>
								@else
								    <span>---</span>
								@endif
							</div>
							<div class="col-sm-1 text-center padding-top-xxs">
							    @if ($job->salary != 0)
								    <span>${{ $job->salary }}</span>
                                @else
                                    <span>---</span>
                                @endif
							</div>
							<div class="col-sm-1 text-right">
								<?php if ($bid_flag[$job->id] == 1) {?>
								<div style="padding-top: 4px; height: 28px;">
									<span class="span-bid">{{ trans('job.applied') }}</span>
								</div>
								<?php }else {?>
								    @if ($job->link_address == '')
								        <button class="btn btn-success btn-sm btn-home" other-target="div_more" other-target-second="div_hint" other-target-third="div_overview" data-target="div_apply" onclick="showView(this)">{{ trans('job.apply') }}</button>
                                    @else
                                        <a class="btn btn-success btn-sm btn-home" href="{{ $job->link_address }}">{{ trans('job.apply') }}</a>
                                    @endif
								<?php }?>
							</div>
						</div>
					</div>
					
					<div class="row margin-top-xxs">
						<div class="col-sm-12">
							<div class="col-sm-3">
								<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_hint" other-target-third="div_apply" data-target="div_overview" onclick="showView(this)"> {{ trans('job.overview') }}</button>
								<!-- Commented for change -->
								<!-- 
								<button class="btn btn-link btn-sm text-uppercase btn-job-table"> Reviews</button>
								 -->
								<button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_overview" other-target-second="div_hint" other-target-third="div_apply" data-target="div_more" onclick="showView(this)"> {{ trans('job.more') }}</button>
							</div>
							<div class="col-sm-4 col-sm-offset-1" style="padding-top: 2px;">
								<?php 
									$skillFlag = 0;
									$skillLength = 0;
								    foreach($job->skills as $skill) {
										$skillLength += strlen($skill->name);
										if ($skillLength >= 25) {
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

                                <?php
                                    $total_skill='';
                                    foreach ($job->skills as $skill) {
                                        if ($total_skill == '') {
                                            $total_skill = $skill->name;
                                        }else {
                                            $total_skill .= ','.$skill->name;
                                        }
                                    }
                                ?>

                                <input type="hidden" name="skill_name" id="skill_name" value="{{ $total_skill }}">

							</div>
							<div class="col-sm-2 text-center">
							    @if ($job->link_address == '')
								    <button class="btn btn-link btn-sm text-uppercase btn-job-table" other-target="div_more" other-target-second="div_overview" other-target-third="div_apply" data-target="div_hint" onclick="showView(this)"><i class="fa fa-check"></i> {{ trans('job.give_us_hint') }}</button>
								@endif
							</div>
							<div class="col-sm-2">
							    @if ($job->link_address == '')
                                    @if ($bid_flag[$job->id] == 0)
                                        @if ($cart_flag[$job->id] == 0)
                                        <button class="btn btn-link btn-sm text-uppercase btn-job-table" data-id="{{ $job->id }}" id="js-btn-addToCart"><i class="fa fa-save"></i> {{ trans('job.add_to_application_cart') }}</button>
                                        @else
                                        <div style="padding-top: 3px;">
                                            <span class="text-uppercase span-cart">{{ trans('job.added_to_application_cart') }}</span>
                                        </div>
                                        @endif
                                    @endif
								@endif
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
										<span class="span-job-descripton-note">{{ nl2br($job->description) }}</span>
									</p>
									<p>&nbsp</p>
									<p>
										<span class="span-job-description-title">{{ trans('job.additional_requirements') }}:</span>
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
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in">
						            <button type="button" class="close" data-target="div_more" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">{{ trans('job.close') }}</span>
						            </button>
									<p>
										<span class="span-job-description-title">{{ trans('job.similar_jobs') }}:</span>
									</p>
									<?php $count  = 0;?>
									@foreach($job->category->jobs as $sjob)
									<?php if ($sjob->id == $job->id) continue;?>
									<?php if ($sjob->is_finished == 0) continue;?>
									<?php
									    $count ++;
									    if ($count > 10) break;
                                    ?>
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
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in">
						            <button type="button" class="close" data-target="div_hint" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">{{ trans('job.close') }}</span>
						            </button>
						            
						            <div class="row">
						     			
						     			<input type="hidden" name="is_name" id="is_name" value="{{ $job->is_name }}">
						     			<input type="hidden" name="is_phonenumber" id="is_phonenumber" value="{{ $job->is_phonenumber }}">
						     			<input type="hidden" name="is_email" id="is_email" value="{{ $job->is_email }}">
						     			<input type="hidden" name="is_currentjob" id="is_currentjob" value="{{ $job->is_currentjob }}">
						     			<input type="hidden" name="is_previousjobs" id="is_previousjobs" value="{{ $job->is_previousjobs }}">
						     			<input type="hidden" name="is_description" id="is_description" value="{{ $job->is_description }}">
						     			
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
													<span class="span-job-description-title">{{ trans('job.name') }}:</span>
												</div>
												<div class="col-sm-7">
													<input class="form-control" name="name" type="text" id="name">
												</div>
											</div>
											<?php }?>
											<?php if ($job->is_phonenumber) {?>
											<div class="row margin-top-xs">
												<div class="col-sm-5 padding-top-xs text-right">
													<span class="span-job-description-title">{{ trans('job.phone_number') }}:</span>
												</div>
												<div class="col-sm-7">
													<input class="form-control" name="phone" type="text" id="phone">
												</div>
											</div>
											<?php }?>
											<?php if ($job->is_email) {?>
											<div class="row margin-top-xs">
												<div class="col-sm-5 padding-top-xs text-right">
													<span class="span-job-description-title">{{ trans('job.email') }}:</span>
												</div>
												<div class="col-sm-7">
													<input class="form-control" name="email" type="text" id="email">
												</div>
											</div>
											<?php }?>
											<?php if ($job->is_currentjob) {?>
											<div class="row margin-top-xs">
												<div class="col-sm-5 padding-top-xs text-right">
													<span class="span-job-description-title">{{ trans('job.current_job') }}:</span>
												</div>
												<div class="col-sm-7">
													<input class="form-control" name="currentJob" type="text" id="currentJob">
												</div>
											</div>
											<?php }?>
											<?php if ($job->is_previousjobs) {?>
											<div class="row margin-top-xs">
												<div class="col-sm-5 padding-top-xs text-right">
													<span class="span-job-description-title">{{ trans('job.previous_jobs') }}:</span>
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
													<span class="span-job-description-title">{{ trans('job.description') }}:</span>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<textarea class="form-control" name="description" id="description" rows="3"></textarea>
												</div>
											</div>
											<?php }?>
						            	</div>
						            </div>
						            
						            <div class="row margin-top-xs">
						            	<div class="col-sm-12 text-center">
											<div class="row margin-top-xs">
												<a class="btn btn-success btn-sm btn-home" style="padding: 5px 30px;" id="js-a-hint" data-id="{{ $job->id }}">Submit</a>
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
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in">
						            <button type="button" class="close" data-target="div_apply" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">Close</span>
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


			<div class="row" <?php if (count($jobs) > 0) {?>  style="display: none;" <?php } ?> id="div-no-job">
				<div class="col-sm-12">
					<div class="col-sm-12 padding-top-sm padding-bottom-sm text-center" style="background-color: white;">
						{{ trans('job.there_is_no_jobs') }}
					</div>
				</div>
			</div>

            <div class="pull-right margin-top-xs">{{ $jobs->appends(Request::input())->links() }}</div>
        </div>
    </div>

    <!-- Modal Div for Loading -->
    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="js-div-loading" style="width: 110px;">
            <div class="modal-content" style="border-radius: 5px !important;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="/assets/img/ajax-loader.gif">
                        </div>
                        <div class="col-sm-12 margin-top-xs">
                            <span>{{ trans('job.processing') }}...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Div for Loading -->
</div>


{{--Select Boxs for Sub-Categories --}}
@foreach ($categories as $category)
    <select class="form-control hidden" id="select-sub-{{$category->id}}" name="sub_category_id">
        <option value="">All</option>
        @foreach($category->child as $sCategory)
            <option value="{{ $sCategory->id }}"  @if ($sub_category_id == $sCategory->id) selected @endif>{{ $sCategory->name }}</option>
        @endforeach
    </select>
@endforeach
{{----}}

@stop

@section('custom-scripts')
    <script type="text/javascript" src="/assets/js/bootstrap-slider.js"></script>
    @include('js.user.job.search')
@stop