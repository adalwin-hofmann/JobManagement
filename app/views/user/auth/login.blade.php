@extends('user.layout')

@section('body')
<main class="background-auth">
	<div class="auth-container-color">
		<div class="container">
		    <div class="row text-center">
		        <h1 class="margin-top-xl">{{ trans('auth.msg_01') }}</h1>
		    </div>
		    <div class="row text-center">
		    	<h4>( {{ trans('auth.job_seeker') }} )</h4>
		    </div>
		    
		    <div class="col-sm-4 col-sm-offset-4 margin-top-lg">
		        @if ($errors->has())
		        <div class="alert alert-danger alert-dismissibl fade in">
		            <button type="button" class="close" data-dismiss="alert">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">{{ trans('auth.close') }}</span>
		            </button>
		            @foreach ($errors->all() as $error)
		        		<p>{{ $error }}</p>		
		        	@endforeach
		        </div>
		        @endif    
		        
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
		    </div>    
		    
		    <form method="POST" action="{{ URL::route('user.auth.doLogin') }}" role="form" class="form-login margin-top-normal">
		        @foreach ([
		            'email' => trans('auth.email').' *',
		            'password' => trans('auth.password').' *',
		        ] as $key => $value)
		            <div class="row margin-top-normal">
		                <div class="col-sm-6 col-sm-offset-3">
		                    <div class="form-group">
		                        <label>{{ Form::label($key, $value) }}</label>
		                        @if ($key == 'password')
		                            {{ Form::password($key, ['class' => 'form-control']) }}                        
		                        @else
		                            {{ Form::text($key, null, ['class' => 'form-control']) }}
		                        @endif
		                    </div>
		                </div>
		            </div>        
		        @endforeach

		        <div class="row margin-top-xs">
		            <div class="col-sm-6 col-sm-offset-3">
	                    <a onclick="resetPassword()" style="color:white; cursor: pointer;">{{ trans('auth.msg_14') }}</a>
                    </div>
		        </div>
		        <div class="form-group">
    		        <div class="row margin-top-normal padding-bottom-xl">
    		            <div class="col-sm-4 col-sm-offset-3 padding-top-xs">
                            <label class="checkbox-inline">
        					    <input type="checkbox" id="js-chk-is-remember" name="is_remember" value="1" style="height: inherit;">&nbsp;Remember Me
        					</label>
        					
                            <button type="submit" class="btn green btn-lg text-uppercase" style="margin-left: 30px; background-color: #125B9B;">
                                {{ trans('auth.sign_in') }} <span class="glyphicon glyphicon-ok-circle"></span>
                            </button>
    		            </div>
    		            
    		            <div class="col-sm-2 text-center">
                            <div class="login-options">
                                <label class="pull-left">Or Sign In with</label>
                                <ul class="social-icons pull-left">
                                    <li class="text-center">
                                        <a class="facebook" data-original-title="facebook" href="{{ URL::route('user.auth.fbauth') }}"></a>
                                    </li>
                                    <li>
                                        <a class="googleplus" data-original-title="Goole Plus" href="{{ URL::route('user.auth.gauth') }}"></a>
                                    </li>
                                    
                                    <li style="margin-right: 0px;">
                                        <a class="linkedin" data-original-title="Linkedin" href="{{ URL::route('user.auth.lauth') }}"></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>		            
    		        </div>
		        </div>
		    </form>
		</div>
	</div>           
</main>

<!-- Modal Div for Send Message -->
<div class="modal fade" id="forgotModal" tabindex="-1" role="dialog" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 320px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <label style="font-size: 20px;">{{ trans('auth.msg_14') }}</label><br/>
                    <label class="margin-top-xs">{{ trans('auth.msg_16') }}</label>
                </div>

                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input type="text" id="txt_email" class="form-control" placeholder="Email"/>
                    </div>

                    <div class="col-sm-12 margin-top-xs" id="reset-alert-box" style="display: none;">
                        <div class="alert alert-danger alert-dismissibl fade in">
                            <button type="button" class="close" onclick="hideResetAlert(this);">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">{{ trans('company.close') }}</span>
                            </button>
                            <p id="js-p-reset-alert">{{ trans('auth.msg_17') }}</p>
                        </div>
                    </div>

                    <div class="col-sm-12 margin-top-xs" id="reset-success-alert-box" style="display: none;">
                        <div class="alert alert-success alert-dismissibl fade in">
                            <button type="button" class="close" onclick="hideResetSuccessAlert();">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">{{ trans('company.close') }}</span>
                            </button>
                            <p>{{ trans('auth.msg_19') }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group text-right" style="margin-bottom: 0px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="js-btn-submit" onclick="sendEmailToReset()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Div for Send Message -->

@stop


@section('custom-scripts')
	@include('js.user.auth.login')
    <script>
    $(document).ready(function() {
        $("input#js-chk-is-remember").click(function() {
            if ($(this).prop('checked')) {
                $(this).val() = 1;
            } else {
                $(this).val() = 0;
            }
        });
    });
    </script>
@stop

@stop