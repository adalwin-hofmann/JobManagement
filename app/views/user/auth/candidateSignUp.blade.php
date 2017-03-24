@extends('user.layout')

@section('body')
<main class="background-auth">
	<div class="auth-container-color">
		<div class="container">
		    <div class="row text-center">
		        <h1 class="margin-top-xl">{{ trans('auth.msg_02') }}</h1>
		    </div>
		    <div class="row text-center">
		    	<h4>( {{ trans('auth.employer') }} )</h4>
		    </div>
		    
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
		                <span class="sr-only">{{ trans('auth.submit') }}</span>
		            </button>
		            <p>
		                <?php echo $alert['msg'];?>
		            </p>
		        </div>
		        <?php } ?>
		    </div>    
		    
		    <form method="POST" action="{{ URL::route('user.auth.doCandidateSignUp') }}" role="form" class="form-login margin-top-normal">
		        <input type="hidden" name="userId" value="{{ $user->id }}"/>
		        @foreach ([
		            'name' => trans('auth.your_name').' *',
		            'email' => trans('auth.email').' *',
		            'password' => trans('auth.password').' *',
		            'password_confirmation' => trans('auth.confirm_password').' *',
		            'city_id' => trans('auth.location').' *',
		        ] as $key => $value)
		            <div class="row margin-top-normal">
		                <div class="col-sm-4 col-sm-offset-4">
		                    <div class="form-group">
		                        <label>{{ Form::label($key, $value) }}</label>
		                        @if ($key == 'password')
		                            {{ Form::password($key, ['class' => 'form-control']) }}
		                        @elseif ($key == 'password_confirmation')                        
		                        	{{ Form::password($key, ['class' => 'form-control']) }}
		                        @elseif ($key == 'gender')
		                            {{ Form::select($key
		                               , array('0' => 'Male', '1' => 'Female')
		                               , null
		                               , array('class' => 'form-control')) }}
			                    @elseif ($key == 'city_id')
		                            {{ Form::select($key
		                               , $cities->lists('name', 'id')
		                               , null
		                               , array('class' => 'form-control')) }}
		                        @elseif ($key == 'category_id')
		                            {{ Form::select($key
		                               , $categories->lists('name', 'id')
		                               , null
		                               , array('class' => 'form-control')) }}
		                        @elseif ($key == 'level_id')
		                            {{ Form::select('level_id'
		                               , $levels->lists('name', 'id')
		                               , null
		                               , array('class' => 'form-control')) }}		                       		                        			                    
		                        @else
		                            {{ Form::text($key, $user->{$key}, ['class' => 'form-control', 'readonly']) }}
		                        @endif
		                    </div>
		                </div>
		            </div>        
		        @endforeach   
		        
		        <div class="row margin-top-normal padding-bottom-xl">
		            <div class="col-sm-2 col-sm-offset-5">
		                <button class="btn btn-lg btn-primary text-uppercase btn-block" style="background-color: #125B9B;">{{ trans('auth.submit') }} <span class="glyphicon glyphicon-ok-circle"></span></button>
		            </div>
		        </div>
		    </form> 
		</div>
	</div>           
</main>
@stop

@stop