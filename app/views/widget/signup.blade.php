@extends('widget.layout')

@section('body')
    <div class="container">
        <div class="row text-center">
            <h1 class="margin-top-xl">Sign Up for {{ SITE_NAME }}</h1>
        </div>
        <div class="row text-center">
            <h4>( Employeer )</h4>
        </div>

        <div class="row margin-top-lg">
            <div class="center-input-field">
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
                        <span class="sr-only">Close</span>
                    </button>
                    <p>
                        <?php echo $alert['msg'];?>
                    </p>
                </div>
                <?php } ?>
            </div>
        </div>

        <form method="POST" action="{{ URL::route('widget.doSignup', $company->slug) }}" role="form" class="form-login margin-top-normal">
            <input name="token" type="hidden" value="{{ $token }}" id="token">
            <input name="company_id" type="hidden" value="{{ $company->id }}">
            @foreach ([
                'name' => 'Your Name *',
                'email' => 'Email *',
                'password' => 'Password *',
                'password_confirmation' => 'Confirm Password *',
                'city_id' => 'Location *',
            ] as $key => $value)
                <div class="row margin-top-xs">
                    <div class="center-input-field">
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
                                   , array('class' => 'form-control', 'onchange' => 'saveUserInfo(this)', 'id' => 'city_id')) }}
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
                                {{ Form::text($key, null, ['class' => 'form-control', 'onchange' => 'saveUserInfo(this)', 'id' => $key]) }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="row margin-top-normal padding-bottom-xl">
                <div class="center-input-field">
                    <a class="btn green pull-left" href="{{ URL::route('widget.login', $company->slug) }}">
                        <i class="m-icon-swapleft m-icon-white"></i> Back
                    </a>

                    <button type="submit" class="btn blue pull-right">
                        Sign Up <i class="m-icon-swapright m-icon-white"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('custom-scripts')
	{{ HTML::script('/assets/js/star-rating.min.js') }}
    @include('js.widget.signup')
@stop