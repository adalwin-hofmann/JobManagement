@extends('admin.layout')

@section('content')
	<b>This page will come soon.</b>   
@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    @include('js.admin.city.index')
@stop

@stop
