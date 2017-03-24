@extends('admin.layout')

@section('content')
<?php if (isset($alert)) { ?>
<div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <p>
        <?php echo $alert['msg'];?>
    </p>
</div>
<?php } ?>

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">Other Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Other</span>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<span>List</span>
			</li>
		</ul>
		
	</div>
</div>


<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Score Management
		</div>
	</div>
    <div class="portlet-body">
        <div class="row">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">Level Score</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="level_score" id="level_score" value="{{ $levelScore }}">
                        </div>
                    </div>
                </div>
                <div class="row margin-top-xs">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">Share Score</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="share_score" id="share_score" value="{{ $shareScore }}">
                        </div>
                    </div>
                </div>
                <div class="row margin-top-xs">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">Apply Score</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="apply_score" id="apply_score" value="{{ $applyScore }}">
                        </div>
                    </div>
                </div>
                <div class="row margin-top-xs">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">Recruit Score</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="recruit_score" id="recruit_score" value="{{ $recruitScore }}">
                        </div>
                    </div>
                </div>
                <div class="row margin-top-xs">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">Recruit Score (Verify)</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="recruit_verify_score" id="recruit_verify_score" value="{{ $recruitVerifyScore }}">
                        </div>
                    </div>
                </div>
                <div class="row margin-top-xs">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">Recruit Score (Success)</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="recruit_success_score" id="recruit_success_score" value="{{ $recruitSuccessScore }}">
                        </div>
                    </div>
                </div>
                <div class="row margin-top-xs">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-2">
                            <label class="margin-top-xs">Invite Score</label>
                        </div>
                        <div class="col-sm-10">
                            <input class="form-control" name="invite_score" id="invite_score" value="{{ $inviteScore }}">
                        </div>
                    </div>
                </div>
                <div class="row text-center margin-top-xs">
                    <a onclick="updateScore()" class="btn green">Save <i class="fa fa-save"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
                    

@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    @include('js.admin.other.index')
@stop

@stop
