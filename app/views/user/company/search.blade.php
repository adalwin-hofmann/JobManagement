@extends('user.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/assets/global/plugins/select2/select2.css') }}
    {{ HTML::style('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}

    <style>
        div.dataTables_length label {
            color: white;
        }
        div.dataTables_filter label {
            color: white;
        }
        .table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th {
            vertical-align: middle;
        }
        table.table-bordered tbody th, table.table-bordered tbody td {
            vertical-align: middle;
        }
    </style>
@stop

@section('body')
<main class="bs-docs-masthead" role="main" style="min-height: 560px;">
	<div class="background-dashboard"></div>        
    <div class="container">
    	<div class="margin-top-lg"></div>
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home color-white"> {{ trans('user.search_company') }}</h2>
        </div>

        <div class="row">
            <table class="table table-striped table-bordered table-hover" id="sample_1">
                <thead>
                    <tr>
                        <th>
                             {{ trans('user.name') }}
                        </th>
                        <th>
                             {{ trans('user.email') }}
                        </th>
                        <th>
                             {{ trans('user.rating') }}
                        </th>
                        <th>
                            {{ trans('user.category') }}
                        </th>
                        <th>
                            {{ trans('user.location') }}
                        </th>
                        <th>
                            {{ trans('user.view') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr class="odd gradX">
                            <td>
                                <div class="white-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="right" data-image-url="{{ HTTP_LOGO_PATH.$company->logo }}" data-tag="{{ $company->tag }}" data-description="{{ nl2br($company->description) }}">
                                    <div style="display: inline-block; margin-top: 5px;">
                                        <span><a href="{{ URL::route('user.company.view', $company->slug) }}">{{ $company->name }}</a></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $company->email }}
                            </td>
                            <td>
                                <input id="input-rate" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ round($company->parent->reviews()->avg('score')) }}" readonly>
                            </td>
                            <td>
                                {{ $company->category->name }}
                            </td>
                            <td>
                                {{ $company->city->name }}
                            </td>
                            <td>
                                <a href="{{ URL::route('user.company.view', $company->slug) }}" class="btn btn-sm blue-hoki" target="_blank">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</main>
@stop

@section('custom-scripts')
	{{ HTML::script('/assets/metronic/assets/global/plugins/select2/select2.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
	{{ HTML::script('/assets/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
	@include('js.user.company.search')
    <script>
        jQuery(document).ready(function() {
            // initiate layout and plugins
            TableManaged.init();

            var obj = $('div#sample_1_filter').find('input').eq(0);

            $(obj).removeClass('input-small');
            $(obj).addClass('input-large')
            $(obj).attr('placeholder', "{{ trans('user.text_133') }}");
        });
    </script>
@stop