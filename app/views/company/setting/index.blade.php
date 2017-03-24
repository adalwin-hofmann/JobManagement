@extends('company.layout')

@section('custom-styles')
{{ HTML::style('/assets/css/bootstrap-colorpicker.min.css') }}
@stop

@section('body')
<main class="bs-docs-masthead gray-container padding-top-xs padding-bottom-xs" role="main">
    <div class="container" style="background: white;">
        <div class="row margin-top-lg text-center">
            <div class="col-sm-12">
                <h2 class="">Settings</h2>
            </div>
        </div>
        
        <div class="row">
        
            @if (isset($alert))
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <p>
                            {{ $alert['msg'] }}
                        </p>
                    </div>                
                </div>
            @endif
                    
            <div class="col-sm-8 col-sm-offset-2">
                <form class="form-horizontal margin-top-lg margin-bottom-lg" role="form" method="post" action="{{ URL::route('company.setting.store') }}">
                    @foreach ([
                        'start_at' => 'Start At',
                        'end_at' => 'End At',
                        'slot_background' => 'Slot Background',
                    ] as $key => $value)                
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{ $value }}</label>
                        <div class="col-sm-9">
                            @if ($key == 'slot_background')
                            <div class="input-group" id="js-div-color-picker">
                                <input type="text" class="form-control" name="{{ $key }}" value="{{ ($company->setting) ? $company->setting->{$key} : DEFAULT_SLOT_BACKGROUND }}">
                                <span class="input-group-addon"><i></i></span>
                            </div>                            
                            @elseif ($key == 'start_at')
                            <div class="input-group" id="js-div-start-at">
                                <input type="text" class="form-control" name="{{ $key }}" value="{{ ($company->setting) ? $company->setting->{$key} : DEFAULT_START_AT }}">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                                </span>
                            </div>
                            @elseif ($key == 'end_at')
                            <div class="input-group" id="js-div-end-at">
                                <input type="text" class="form-control" name="{{ $key }}" value="{{ ($company->setting) ? $company->setting->{$key} : DEFAULT_END_AT }}">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 text-center">
                            <button class="btn btn-primary">
                                <span class="glyphicon glyphicon-ok-circle"></span> Save
                            </button>
                            <a href="{{ URL::route('admin.category') }}" class="btn btn-danger">
                                <span class="glyphicon glyphicon-share-alt"></span> Back
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@section('custom-scripts')
{{ HTML::script('/assets/js/bootstrap-colorpicker.js') }}
<script>
$(document).ready(function() {
    $('div#js-div-color-picker').colorpicker();
    $("input[name='start_at']").timepicker({autoclose: true, minuteStep: 5, showSeconds: false, showMeridian: false});
    $("input[name='end_at']").timepicker({autoclose: true, minuteStep: 5, showSeconds: false, showMeridian: false});
    $('span.input-group-btn').click(function() {
        $(this).parents("div").eq(0).find("input").timepicker('showWidget');
    });
});

</script>
@stop

@stop
