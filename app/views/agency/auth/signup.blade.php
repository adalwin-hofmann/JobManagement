<?php 
	if (!isset($tabIndex)) {
		$tabIndex = '1';
	}
	
	if (!isset($email)) {
		$email = '';
	}
	
	if (!isset($companyName)) {
		$companyName = '';
	}
	
	if (!isset($cityId)) {
		$cityId = 1;
	}

?>

@extends('company.layout')

@section('body')
<main class="background-auth">
	<div class="auth-container-color">
		<div class="container">
		    <div class="row text-center">
		        <h1 class="margin-top-xl">Sign Up for {{ SITE_NAME }}</h1>
		    </div>
		    <div class="row text-center">
		    	<h4>( {{ trans('auth.agency') }} )</h4>
		    </div>
		    
		    @if ($errors->has() || isset($alert)) 
		    <div class="col-sm-4 col-sm-offset-4 margin-top-lg">
		        @if ($errors->has())
		        <div class="alert alert-danger alert-dismissibl fade in">
		            <button type="button" class="close" data-dismiss="alert">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">Close</span>
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
		    @endif
		    
		    <div class="col-sm-6 col-sm-offset-3 margin-top-sm">
		    	<div class="row">
                    <form method="POST" action="{{ URL::route('agency.auth.doSignup') }}" role="form" class="form-login margin-top-normal">
                        <input type="hidden" name="tabIndex" value="1">
                        @foreach ([
                            'email' => trans('auth.email').' *',
                            'password' =>  trans('auth.password').' *',
                            'password_confirmation' => trans('auth.confirm_password'),
                            'name' => trans('auth.company_name').' *',
                            'city_id' => trans('auth.location').' *',
                        ] as $key => $value)
                            <div class="row margin-top-normal">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group">
                                        <label>{{ Form::label($key, $value) }}</label>
                                        @if ($key == 'password')
                                            {{ Form::password($key, ['class' => 'form-control']) }}
                                        @elseif ($key == 'password_confirmation')
                                            {{ Form::password($key, ['class' => 'form-control']) }}
                                        @elseif ($key == 'city_id')
                                            {{ Form::select($key
                                               , $cities->lists('name', 'id')
                                               , null
                                               , array('class' => 'form-control')) }}
                                        @else
                                            {{ Form::text($key, null, ['class' => 'form-control']) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="row margin-top-normal padding-bottom-xl">
                            <div class="col-sm-4 col-sm-offset-4">
                                <button class="btn btn-lg btn-primary text-uppercase btn-block" style="background-color: #125B9B;">Submit <span class="glyphicon glyphicon-ok-circle"></span></button>
                            </div>
                        </div>
                    </form>
		    	</div>
		    </div> 
		</div>
	</div>           
</main>
@stop

@stop