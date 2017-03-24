@extends('user.layout')

@section('body')

<main class="background-auth">
	<div class="auth-container-color">
		<div class="container">
		    <div class="row text-center">
		        <h1 class="margin-top-xl">{{ trans('auth.msg_20') }}</h1>
		    </div>
		    <div class="row text-center">
		    	<h4>( {{ trans('auth.agency') }} )</h4>
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
		    
		    <form method="POST" action="{{ URL::route('company.auth.doResetPassword') }}" role="form" class="form-login margin-top-normal">
		        <input type="hidden" name="agencyId" value="{{ $agency->id }}">
		        @foreach ([
		            'password' => trans('auth.password').' *',
		            'confirm_password' => trans('auth.confirm_password')
		        ] as $key => $value)
		            <div class="row margin-top-normal">
		                <div class="col-sm-4 col-sm-offset-4">
		                    <div class="form-group">
		                        <label>{{ Form::label($key, $value) }}</label>
                                {{ Form::password($key, ['class' => 'form-control']) }}
		                    </div>
		                </div>
		            </div>        
		        @endforeach

		        <div class="row margin-top-normal padding-bottom-xl text-center">
		            <div class="col-sm-2 col-sm-offset-5 padding-top-xs">
		                <button class="btn btn-lg btn-primary text-uppercase btn-block" style="background-color: #125B9B;">{{ trans('auth.submit') }} <span class="glyphicon glyphicon-ok-circle"></span></button>
		            </div>
		        </div>
		    </form>
		</div>
	</div>           
</main>
@stop

@stop