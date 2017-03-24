@extends('company.layout')

@section('body')
<main class="bs-docs-masthead gray-container padding-top-xs padding-bottom-normal" role="main">
	<div class="container" style="background-color: #FFF;">
	
        @if (isset($alert))
        <div class="row margin-top-normal">
            <div class="col-sm-12">
                <div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">{{ trans('common.close') }}</span>
                    </button>
                    <p>{{ $alert['msg'] }}</p>
                </div>
            </div>
        </div>
        @endif  
            	
		<div class="row margin-top-normal">
			<div class="col-sm-6 company-job-title">
				{{ $job->name }}
				@if ($sharedJob)
				    &nbsp;<span class="label" style="background-color: #009cff">Shared By <a href="{{ URL::route('user.company.view', $agency->slug) }}" target="_blank" class="pointer color-white">{{ $agency->name }}</a></span>
				@endif
			</div>
			<div class="col-sm-2 text-center">
				<div class="row">
					<span class="job-span-title-normal">CREATED AT</span>
				</div>
				<div class="row company-info-value">
					{{ $job->created_at }}
				</div>
			</div>
			<div class="col-sm-2 text-center">
				<div class="row">
					<span class="job-span-title-normal">SALARY</span>
				</div>
				<div class="row company-info-value company-info-number">
					${{ $job->salary }}
				</div>
			</div>
			<div class="col-sm-2 text-center">
				<div class="row">
					<span class="job-span-title-normal">TYPE</span>
				</div>
				<div class="row company-info-value">
					{{ $job->type->name }}
				</div>
			</div>

			<div class="col-sm-12">
				<hr/>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6" style="border-right: 1px solid #EEE;">
				<div>
					<span class="job-span-title-normal">Description:</span>
				</div>
				<div class="margin-top-xs">
					{{ nl2br($job->description) }}
				</div>
				<div class="margin-top-normal">
					<span class="job-span-title-normal">Additional requirements:</span>
				</div>
				<div class="margin-top-xs">
					{{ $job->requirements }}
				</div>

				<div class="margin-top-normal">
					<span class="job-span-title-normal">Required Skills:</span>
				</div>
				<div class="margin-top-xs">
					<?php foreach($job->skills as $skill) {?>
						<label class="job-skill-label" style="color: #333;">{{ $skill->name }}</label>
					<?php }?>
				</div>
			</div>
			<div class="col-sm-6">
			    <div class="row">
    				<div class="col-sm-4 text-center">
    					<span class="company-job-info-span">{{ $job->views }}</span> Views
    				</div>
    				<div class="col-sm-4 text-center">
    					<span class="company-job-info-span">{{ count($job->applies) }}</span> Bids
    				</div>
    				<div class="col-sm-4 text-center">
    					<span class="company-job-info-span">{{ count($job->hints()->where('is_verified', 1)->get()) }}</span> Hints
    				</div>
				</div>

				<div class="row">
    				<div class="col-sm-12 margin-top-lg">
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2">
                        <label class="margin-top-xs">Status:</label>
                    </div>
                    <div class="col-sm-4">
                        <input type="hidden" id="jobId" value="{{ $job->id }}">
                        {{ Form::select('status'
                           , array('0' => 'Open', '1' => 'Pending', '2' => 'Closed', '4' => 'Deactive')
                           , $job->status
                           , array('class' => 'form-control', 'id' => 'status')) }}
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-sm blue form-control" onclick="updateStatus()"><i class="fa fa-upload"></i> Update Status</button>
                    </div>
                </div>
                
                <div class="row margin-top-sm">
                    <div class="col-sm-2">
                        <label class="margin-top-xs">Bonus:</label>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="bonus" id="bonus" value="{{ $job->bonus }}">
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-sm blue form-control" onclick="updateBonus()"><i class="fa fa-upload"></i> Update Bonus</button>
                    </div>
                </div>

                @if ($sharedJob)
                <div class="row margin-top-sm">
                    <div class="col-sm-12">
                        <label>Note for {{ $agency->name }}:</label>
                    </div>
                </div>
                <div class="row margin-top-xxs">
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="8" id="textarea-share-note">{{ $agencyShare->noteByCompany($company->id) }}</textarea>
                    </div>
                </div>
                <div class="row margin-top-xs">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button class="btn btn-sm blue form-control" onclick="saveShareNote(this)" data-id="{{ $agencyShare->id }}" ><i class="fa fa-save"></i> Save Note</button>
                    </div>
                </div>
                @endif
			</div>

			<div class="col-sm-12">
				<hr/>
			</div>
		</div>

		<div class="row margin-top-sm">
			<div class="col-sm-8 col-sm-offset-2">
				<?php if (!isset($category)) { $category = 0; } ?>
				<ul class="nav nav-tabs nav-justified ul-project-category" role="tablist">
	                <li class="{{ ($category == 5) ? 'active' : '' }}">
	                    <a href="{{ URL::route('company.job.view', $job->slug) }}">All ({{ $job->applies()->count() }})</a>
	                </li>
	                <li class="{{ ($category == 0) ? 'active' : '' }}">
	                    <a href="{{ URL::route('company.job.view', array($job->slug, 0)) }}">News ({{ $job->applies()->where('status', 0)->count() }})</a>
	                </li>
	                <li class="{{ ($category == 1) ? 'active' : '' }}">
	                    <a href="{{ URL::route('company.job.view', array($job->slug, 1)) }}">Viewed ({{ $job->applies()->where('status', 1)->count() }})</a>
	                </li>
	                <li class="{{ ($category == 2) ? 'active' : '' }}">
	                    <a href="{{ URL::route('company.job.view', array($job->slug, 2)) }}">Archived ({{ $job->applies()->where('status', 2)->count() }})</a>
	                </li>
	                <li class="{{ ($category == 3) ? 'active' : '' }}">
	                    <a href="{{ URL::route('company.job.view', array($job->slug, 3)) }}">Processing ({{ $job->applies()->where('status', 3)->count() }})</a>
	                </li>
	                <li class="{{ ($category == 4) ? 'active' : '' }}">
	                    <a href="{{ URL::route('company.job.view', array($job->slug, 4)) }}">Interview ({{ $job->applies()->where('status', 4)->count() }})</a>
	                </li>
	            </ul>
			</div>
		</div>


		<div class="row margin-top-sm">
			<div class="col-sm-12">
				<span class="job-view-small-title">Bidder List</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<hr/>
			</div>
		</div>

		<?php if (count($applies) == 0) {?>
		<div class="row margin-bottom-sm">
			<div class="col-sm-12 text-center">
				There are no bidders.
			</div>
		</div>
		<?php }?>

		<div class="row">
			<?php foreach($applies as $apply){?>
				<div id = "div_apply">
					<div class="row">
						<div class="col-sm-2 text-center" style="position: relative;">

                            @if ($apply->status == 0)
                                <span class="label label-primary apply-status-label">New</span>
                            @elseif ($apply->status == 1)
                                <span class="label label-warning apply-status-label">Viewed</span>
                            @elseif ($apply->status == 2)
                                <span class="label label-danger apply-status-label">Archived</span>
                            @elseif ($apply->statue == 3)
                                <span class="label label-success apply-status-label">Processing</span>
                            @elseif ($apply->status == 4)
                                <span class="label label-info apply-status-label">Interview</span>
                            @endif

							<img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$apply->user->profile_image }}" class="img-circle">
							<div class="col-sm-12 margin-top-xs">
                                <a onclick="showUserView(this)" class="username" data-userId="{{ $apply->user->id }}">{{ $apply->user->name }}</a>@if ($apply->user->age($apply->user->id) != 0), <b>{{ $apply->user->age($apply->user->id) }}</b> @endif
                            </div>

                            <div class="col-sm-12 find-people-rating">
                                <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $apply->score }}" onchange="showSaveButton(this)">
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $apply->id }}" style="display: none;" onclick="saveApplyScore(this)">Save</a>
                                </div>
                            </div>
						</div>
						<div class="col-sm-1 text-center">
                            <div class="col-sm-12 margin-top-xs">
                                <p><b>{{ trans('user.skills') }}</b></p>
                                <?php
                                    $skillFlag = 0;
                                    $skillLength = 0;
                                    foreach($apply->user->skills()->orderBy('value', 'desc')->get() as $skill) {
                                        $skillLength += strlen($skill->name);
                                        if ($skillFlag >= 3) {
                                            break;
                                        }
                                        $skillFlag ++;
                                ?>
                                    <p>{{ $skill->name }} ({{ $skill->value }})</p>
                                <?php }?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="row">
                                <div class="col-sm-12 margin-top-xs">
                                    <p><b>{{ trans('user.jobs') }}</b></p>
                                    @foreach($apply->user->experiences()->orderBy('start', 'desc')->get() as $item)
                                        @if ($item->end == '' || $item->end == '0')
                                            <p>{{ trans('user.current_job') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ trans('company.still_working') }}</p>
                                        @else
                                            <p>{{ trans('user.previous_jobs') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="col-sm-12 margin-top-xs">
                                    @foreach ($apply->user->educations()->orderBy('start', 'desc')->get() as $item)
                                        <p>{{ trans('user.education_studied') }}: {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                                        <?php break;?>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-12 margin-top-xs about-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="left" data-image-url="{{ HTTP_PHOTO_PATH. $apply->user->profile_image }}" data-tag="{{ $apply->user->name }}" data-description="{{ nl2br($apply->user->about) }}">
                                    <?php
                                        $aboutString = $apply->user->about;
                                        if (preg_match('/^.{1,300}\b/s', $aboutString, $match))
                                        {
                                            if (strlen($aboutString) > 300) {
                                                $aboutString = $match[0].'...';
                                            }
                                        }
                                    ?>
                                    <p><b>{{ trans('user.about_me') }}</b></p>
                                    <p>{{ $aboutString }}</p>
                                </div>
                            </div>
                        </div>

						<div class="col-sm-4 text-center" style="margin-top: 4px;">

						    <div class="row margin-bottom-xs">
                                <div class="col-sm-10 margin-top-xs col-sm-offset-1">
                                    <?php
                                        $myNotes = '';
                                        foreach ($apply->notes as $note) {
                                            if ($note->company->id == $company->id) {
                                                $myNotes = $note->notes;
                                            }
                                        }
                                    ?>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="notes" rows="3" id="js-textarea-apply-notes" data-id="{{ $apply->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
                                        </div>
                                        <div class="col-sm-12 text-center margin-top-xs">
                                            <button class="btn btn-success btn-sm btn-home" onclick="saveApplyNotes(this)" style="display: none;" id="js-button-saveNote"><i class="fa fa-save"></i> Save Note</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($apply->status != 3)
                                <a class="btn btn-sm blue" id="js-a-apply-process" data-id="{{ $apply->id }}">
                                    <i class="fa fa-toggle-right"></i> Process
                                </a>
                            @endif
                            @if ($apply->status != 4)
                                <a class="btn btn-sm green" id="js-a-apply-interview" data-id="{{ $apply->id }}" data-name="{{ $apply->user->name }}" data-userId="{{ $apply->user->id }}">
                                    <i class="fa fa-comments-o"></i> Interview
                                </a>
                            @endif
							<a class="btn btn-sm btn-danger disabled" id="js-a-apply-reject" data-id="{{ $apply->id }}">
			                	<i class="fa fa-times"></i> Reject
			                </a>

			                <div class="row margin-top-xs">
                                <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_apply" data-target="div_notes" other-data-target="div_proposal" onclick="showView(this)" data-id="{{ $apply->id }}">View Proposal</button>|
                                <!--
                                <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_apply" data-target="div_proposal" other-data-target="div_notes" onclick="showView(this)" data-id="{{ $apply->id }}">View Proposal</button>
                                 -->
                                <button class="btn btn-link btn-sm btn-common" id="js-btn-open-message" super-data-target="div_apply">Send Message</button>|
                                <button class="btn btn-link btn-sm btn-common" id="js-btn-open-request" super-data-target="div_apply">Request Feedback</button>
                            </div>

						</div>
					</div>

					<!-- Div for Notes -->
					<div class="row  margin-top-sm" id="div_notes" style="display:none;">
						<div class="col-sm-12">
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in" style="margin-bottom: 0px;">
						            <button type="button" class="close" data-target="div_notes" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">Close</span>
						            </button>

									<p>
										<span class="span-job-description-title">{{ $apply->name }}</span>
									</p>
									<p>
										<span class="span-job-descripton-note">{{ nl2br($apply->description) }}</span>
									</p>

									@if ($apply->attached_file != '')
									    <?php
									        $imageTypes = array('gif', 'png', 'jpg', 'pdf');
									        $fileType = explode('.', $apply->attached_file)[1];
									    ?>
									    <br/>
									    <p>
									        <i class="fa fa-paste"></i> <span class="span-job-descripton-note"><a href="{{ HTTP_UPLOAD_PATH.$apply->attached_file }}" target="_blank" @if (in_array($fileType, $imageTypes)) download="{{ 'attachedImage.'.$fileType }}" @endif>Attached File</a></span>
									    </p>
									@endif

									<div class="row">
										<hr/>
									</div>

									<?php $myNotes = '';?>
									@foreach ($apply->notes as $note)
										@if ($note->company->id != $company->id)
											<div class="row margin-bottom-xs">
												<div class="col-sm-12">
													@if ($note->company->is_admin == 1)
														<span class="span-job-description-title">{{ 'Admin: ' }}</span>
													@else
														<span class="span-job-description-title">{{ $note->company->name }}</span>
													@endif
													<span class="span-job-descripton-note">{{ nl2br($note->notes).': ' }}</span>
												</div>
											</div>
										@else
											<?php
												$myNotes = $note->notes;
											?>
										@endif
									@endforeach

									<div class="row">
										<div class="col-sm-12">
											<span class="span-job-description-title">My Note</span>
										</div>
										<div class="col-sm-12">
											<textarea class="form-control" name="notes" rows="3" id="js-textarea-apply-notes" data-id="{{ $apply->user->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
										</div>
										<div class="col-sm-12 text-center margin-top-xs">
										    <button class="btn btn-success btn-sm btn-home" onclick="saveApplyNotes(this)"><i class="fa fa-save"></i> Save Note</button>
										    <button class="btn btn-success btn-sm" style="margin-left: 10px;" data-target="div_notes" onclick="hideView(this)"><i class="fa fa-close"></i> Close</button>
										</div>
									</div>
						        </div>
							</div>
						</div>
					</div>
					<!-- End for Notes -->

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
					                <button type="button" class="btn btn-primary" id="js-btn-send-message" data-id="{{ $apply->id }}">Send</button>
					            </div>
					        </div>
					    </div>
					</div>
					<!-- End Div for Send Message -->


					<!-- Modal Div for Send Message -->
					<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
					 	<div class="modal-dialog">
					    	<div class="modal-content">
					      		<div class="modal-header">
					        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					        		<h4 class="modal-title" id="msgModalLabel">Request Feedback</h4>
					      		</div>
					      		<div class="modal-body">
									<div class="row">
						      	  		<div class="form-group">
						      	  	  		<div class="col-sm-1" style="margin-top: 9px;">
						      	  	  			To:
						      	  	  		</div>
						      	  	  		<div class="col-sm-11">
						      	  	  			<select class="form-control" id="js-select-member">
						      	  	  				@foreach ($members as $member)
						      	  	  					@if ($member->id != $company->id)
						      	  	  						<option value="{{ $member->id }}">{{ $member->name }}</option>
						      	  	  					@endif
						      	  	  				@endforeach
						      	  	  			</select>
						      	  	  		</div>

						      	 			<div class="col-sm-12 margin-top-xs">
						              			<textarea class="form-control" rows="8" id="js-textarea-request-content"></textarea>
						              		</div>
						      	  		</div>
									</div>
					      		</div>
					      		<div class="modal-footer">
					        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					        		<button type="button" class="btn btn-primary" id="js-btn-send-request" data-id="{{ $apply->user->id }}">Send</button>
					      		</div>
					    	</div>
					  	</div>
					</div>
					<!-- End Div for Send Message -->

					<div class="col-sm-12">
						<hr/>
					</div>
				</div>
			<?php }?>
		</div>


		<div class="row margin-top-sm">
			<div class="col-sm-12">
				<span class="job-view-small-title">Hint List</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<hr/>
			</div>
		</div>
		<?php if (count($hints) == 0) {?>
		<div class="row margin-bottom-sm">
			<div class="col-sm-12 text-center">
				There are no hints.
			</div>
		</div>
		<?php }?>

		<!-- Div for Hint List -->
		<div class="row">
			<?php foreach($hints as $hint){?>
				<div id = "div_hint">
					<div class="row">

                        <div class="col-sm-2 text-center">
							<img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH.$hint->user->profile_image }}" class="img-circle">
						</div>
						<div class="col-sm-2 text-center">
						    <div class="row">
						        <a onclick="showUserView(this)" data-userId="{{ $hint->user->id }}" class="username">{{ $hint->user->name }}</a>
						    </div>
							<div class="row margin-top-xs">
                                <?php
                                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $hint->created_at);
                                ?>
                                <i class="fa fa-clock-o"></i>&nbsp {{ $date->format('d-m-Y') }}
							</div>
						</div>
						<div class="col-sm-4 text-center">
							<div class="row">
							    <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_hint" data-target="div_notes" other-data-target="div_proposal" onclick="showView(this)" data-id="{{ $hint->id }}">View Hint</button>|
                                <!--
                                <button class="btn btn-link btn-sm btn-common" id="js-btn-view-proposal" super-data-target="div_hint" data-target="div_proposal" other-data-target="div_notes" onclick="showView(this)">View Hint</button>
                                 -->
                                <button class="btn btn-link btn-sm btn-common" id="js-btn-open-message" super-data-target="div_hint">Send Message</button>
							</div>

							<div class="row" style="position: relative;">
                                <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ $hint->score }}" onchange="showSaveButton(this)">
                                <a class="btn btn-sm blue btn-save-rate" id="js-a-save-rate" data-id="{{ $hint->id }}" style="display: none;" onclick="saveHintScore(this)">Save</a>
                            </div>
						</div>
						<div class="col-sm-4 text-center" style="margin-top: 4px;">
							<a class="btn btn-sm btn-danger <?php if ($hint->job->company->id != $company->id) {?> disabled <?php }?>" id="js-a-hint-reject" data-id="{{ $hint->id }}">
			                	<i class="fa fa-times"></i> Reject
			                </a>
						</div>
					</div>

					<!-- Div for Notes -->
					<div class="row  margin-top-sm" id="div_notes" style="display:none;">
						<div class="col-sm-12">
							<div class="col-sm-12">
								<div class="alert alert-success alert-dismissibl fade in" style="margin-bottom: 0px;">
						            <button type="button" class="close" data-target="div_notes" onclick="hideView(this)">
						                <span aria-hidden="true">&times;</span>
						                <span class="sr-only">Close</span>
						            </button>

									<?php if ($hint->name != '') {?>
									<div class="row margin-bottom-xs">
										<div class="col-sm-2">
											<span class="span-job-description-title">Name:</span>
										</div>
										<div class="col-sm-9">
											<span class="span-job-descripton-note">{{ $hint->name }}</span>
										</div>
									</div>
									<?php }?>

						            <?php if ($hint->email != '') {?>
									<div class="row margin-bottom-xs">
										<div class="col-sm-2">
											<span class="span-job-description-title">Email:</span>
										</div>
										<div class="col-sm-9">
											<span class="span-job-descripton-note">{{ $hint->email }}</span>
										</div>
									</div>
									<?php }?>

						            <?php if ($hint->phone != '') {?>
									<div class="row margin-bottom-xs">
										<div class="col-sm-2">
											<span class="span-job-description-title">Phone number:</span>
										</div>
										<div class="col-sm-9">
											<span class="span-job-descripton-note">{{ $hint->phone }}</span>
										</div>
									</div>
									<?php }?>

						            <?php if ($hint->currentJob != '') {?>
									<div class="row margin-bottom-xs">
										<div class="col-sm-2">
											<span class="span-job-description-title">Current job:</span>
										</div>
										<div class="col-sm-9">
											<span class="span-job-descripton-note">{{ $hint->currentJob }}</span>
										</div>
									</div>
									<?php }?>

						            <?php if ($hint->previousJobs != '') {?>
									<div class="row margin-bottom-xs">
										<div class="col-sm-2">
											<span class="span-job-description-title">Previous jobs:</span>
										</div>
										<div class="col-sm-9">
											<span class="span-job-descripton-note">{{ $hint->previousJobs }}</span>
										</div>
									</div>
									<?php }?>

						            <?php if ($hint->description != '') {?>
									<div class="row margin-bottom-xs">
										<div class="col-sm-2">
											<span class="span-job-description-title">Description:</span>
										</div>
										<div class="col-sm-9">
											<span class="span-job-descripton-note">{{ nl2br($hint->description) }}</span>
										</div>
									</div>
									<?php }?>

									<div class="row">
										<hr/>
									</div>

									<?php $myNotes = '';?>
									@foreach ($hint->notes as $note)
										@if ($note->company->id != $company->id)
											<div class="row margin-bottom-xs">
												<div class="col-sm-12">
													@if ($note->company->is_admin == 1)
														<span class="span-job-description-title">{{ 'Admin: ' }}</span>
													@else
														<span class="span-job-description-title">{{ $note->company->name.': ' }}</span>
													@endif
													<span class="span-job-descripton-note">{{ nl2br($note->notes) }}</span>
												</div>
											</div>
										@else
											<?php
												$myNotes = $note->notes;
											?>
										@endif
									@endforeach

									<div class="row">
										<div class="col-sm-12">
											<span class="span-job-description-title">My Note</span>
										</div>
										<div class="col-sm-12">
											<textarea class="form-control" name="notes" rows="3" id="js-textarea-hint-notes" data-id="{{ $hint->user->id }}" placeholder="Note...">{{ $myNotes }}</textarea>
										</div>
										<div class="col-sm-12 text-center margin-top-xs">
                                            <button class="btn btn-success btn-sm btn-home" onclick="saveHintNotes(this)"><i class="fa fa-save"></i> Save Note</button>
                                            <button class="btn btn-success btn-sm" style="margin-left: 10px;" data-target="div_notes" onclick="hideView(this)"><i class="fa fa-close"></i> Close</button>
                                        </div>
									</div>

						        </div>
							</div>
						</div>
					</div>
					<!-- End for Notes -->


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
                                    <button type="button" class="btn btn-primary" id="js-btn-send-message-hint" data-id="{{ $hint->id }}">Send</button>
                                </div>
					        </div>
					    </div>
					</div>
					<!-- End Div for Send Message -->

					<div class="col-sm-12">
						<hr/>
					</div>
				</div>
			<?php }?>
		</div>
		<!-- End for Hint List -->


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
		      			        <span>Processing...</span>
		      		        </div>
				        </div>
		            </div>
		        </div>
		    </div>
		</div>
		<!-- End Div for Send Message -->

		<!-- Modal Div for Video Interview -->
		<div class="modal fade" id="viModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
            <div class="modal-dialog">
		        <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="msgModalLabel">{{ trans('job.send_video_interview') }}</h4>
                    </div>
		            <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" id="js-input-vi-userid" value="">
                            <input type="hidden" id="js-input-vi-apply-id" value="">
                            @if (count($viTemplates) == 0 || count($questionnaires) == 0)
                            <div class="row margin-bottom-xs">
                                <div class="col-sm-12">
                                    <div class="alert alert-danger alert-dismissibl fade in" style="margin-bottom: 0px;">
                                        <button type="button" class="close" onclick="hideQuestionnaireQuestionsAlert(this);">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">{{ trans('company.close') }}</span>
                                        </button>
                                        <p>{{ trans('company.msg_41') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="margin-top-xs">{{ trans('job.to') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <label id="js-label-vi-username" class="margin-top-xs"></label>
                                </div>
                            </div>
                            <div class="row margin-top-xs">
                                <div class="col-sm-3">
                                    <label class="margin-top-xs"> {{ trans('job.questionnaire') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    {{ Form::select('questionnaire_id'
                                       , $questionnaires->lists('title', 'id')
                                       , null
                                       , array('class' => 'form-control', 'id' => 'js-vi-questionnaire-id')) }}
                                </div>
                            </div>

                            <div class="row margin-top-xs">
                                <div class="col-sm-3">
                                    <label class="margin-top-xs">{{ trans('job.expiration_date') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd" data-date-viewmode="years" data-date-minviewmode="months">
                                        <input type="text" class="form-control" readonly="" name="vi-expiration" value="{{ date("Y-m-d",strtotime("+1 week")) }}" id="vi-expiration">
                                        <span class="input-group-btn">
                                        <button class="btn default" type="button" style="padding-top: 7px; padding-bottom: 7px;"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top-xs">
                                <div class="col-sm-3">
                                    <label class="margin-top-xs">{{ trans('job.template') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    {{ Form::select('vi_template_id'
                                       , $viTemplates->lists('title', 'id')
                                       , null
                                       , array('class' => 'form-control', 'id' => "js-vi-template-id")) }}
                                </div>
                            </div>

                            <div class="row margin-top-xs">
                                <div class="col-sm-3">
                                    <label class="margin-top-xs">{{ trans('job.subject') }}</label>
                                </div>
                                <div class="col-sm-9">
                                    <input class="form-control" id="js-vi-template-title" value="@if (count($viTemplates) > 0) {{ $viTemplates[0]->title }} @endif"/>
                                </div>
                            </div>

                            <div class="row margin-top-xs">
                                <div class="col-sm-12">
                                    <textarea class="form-control" id="js-vi-template-description">@if (count($viTemplates) > 0){{ $viTemplates[0]->description }}@endif</textarea>
                                </div>
                            </div>

                        </div>
		            </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('job.cancel') }}</button>
                        <button type="button" class="btn btn-primary @if (count($viTemplates) == 0 || count($questionnaires) == 0) disabled @endif" onclick="sendVIInterview(this);">{{ trans('job.send') }}</button>
                    </div>
		        </div>
		    </div>
		</div>
		<!-- End Div for Video Interview -->

	</div>

    <div id="js-div-userview" style="display: none;">

    </div>
</main>
@stop


@section('custom-scripts')
    @include('js.company.job.view')
@stop