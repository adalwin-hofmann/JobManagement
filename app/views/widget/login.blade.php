@extends('widget.layout')

@section('body')
    <div class="container">
        <div class="row text-center">
            <h1 class="margin-top-xl">Welcome to {{ SITE_NAME }}</h1>
        </div>
        <div class="row text-center">
            <h4>( Job seeker )</h4>
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
                    <span class="sr-only">Close</span>
                </button>
                <p>
                    <?php echo $alert['msg'];?>
                </p>
            </div>
            <?php } ?>
        </div>

        <form method="POST" action="{{ URL::route('widget.doLogin', $company->slug) }}" role="form" class="form-login margin-top-normal">
            @foreach ([
                'email' => 'Email *',
                'password' => 'Password *',
            ] as $key => $value)
                <div class="row margin-top-sm">
                    <div class="center-input-field">
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

            <div class="row margin-top-sm">
                <div class="center-input-field">
                    <a class="btn green pull-left" href="{{ URL::route('widget.home', $company->slug) }}">
                        <i class="m-icon-swapleft m-icon-white"></i> Back to Job List
                    </a>

                    <button type="submit" class="btn blue pull-right">
                        Login <i class="m-icon-swapright m-icon-white"></i>
                    </button>
                </div>
            </div>

            <div class="row margin-top-sm">
                <div class="center-input-field">
                    <p>
                        Don't have an account yet? &nbsp <a href="{{ URL::route('widget.signup', $company->slug) }}">Create an account</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
@stop

@stop