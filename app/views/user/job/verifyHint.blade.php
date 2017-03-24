@extends('user.layout')

@section('body')
<div class="page-container" style="min-height: 560px;">
	<div class="page-contect-wrapper">
		<div class="page-content" style="min-height: 554px;">
		    <div class="col-sm-6 col-sm-offset-3 thumbnail margin-top-xl margin-bottom-xl">
		    
		        <?php if (isset($alert)) { ?>
		        <div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
		            <button type="button" class="close" data-dismiss="alert">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">{{ trans('auth.close') }}</span>
		            </button>
		            <p>
		                <?php echo $alert['msg'];?>
		            </p>
		        </div>
		        <?php } ?>
		            
		        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('user.job.doVerifyHint') }}">
		            <input type="hidden" name="job_id" value="{{ $jobId }}">
		            <input type="hidden" name="hint_id" value="{{ $hintId }}">

		            <div class="form-group">
		                <div class="row text-center">
		                    <p class="form-control-static">
		                        <h2>{{ trans('auth.recommendation_verify') }}</h2>
		                    </p>
		                </div>
		            </div>
		            <div class="form-group">
		                <div class="col-sm-10 col-sm-offset-1">
		                    <hr/>
		                </div>
		            </div>        
		            <div class="form-group">
		                <label class="col-sm-3 col-sm-offset-1 control-label">{{ trans('auth.verification_code') }}</label>
		                <div class="col-sm-7">
		                    <input type="text" class="form-control" placeholder="Verification Code" name="verify_code">
		                </div>
		            </div>
		            <div class="form-group">
		                <div class="col-sm-10 col-sm-offset-1">
		                    <hr/>
		                </div>
		            </div>
		            
		            <div class="form-group">
		                <div class="col-sm-offset-2 col-sm-9 text-right">
		                    <button type="submit" class="btn btn-success btn-lg">
		                        {{ trans('auth.verify') }} <span class="glyphicon glyphicon-ok-circle"></span>
		                    </button>
		                </div>
		            </div>
		        </form>    
		    
		    </div>
		</div>
	
	</div>
</div>
@stop

@stop