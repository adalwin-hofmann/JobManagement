@extends('user.layout')

@section('body')
<main class="bs-docs-masthead" role="main" style="min-height: 560px;">
	<div class="background-dashboard"></div>        
    <div class="container">
    	<div class="margin-top-lg"></div>
        <div class="row text-center margin-top-lg margin-bottom-normal">
            <h2 class="home color-white"> Message Center</h2>
        </div>

        <div class="col-sm-10 col-sm-offset-1">
            <table class="table table-store-list" style="width: 100%;">
                <thead style="background-color: #F7F7F7">
                    <tr>
                        <th class="text-right">No</th>
                        <th class="text-center text-uppercase">Job Name</th>
                        <th class="text-center text-uppercase">Company</th>
                        <th class="text-center text-uppercase">User</th>
                        <th class="text-center text-uppercase"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $key => $value)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">
                            @if ($value->job_id != NULL)
                            <a href="{{ URL::route('user.dashboard.viewJob', $value->job->slug) }}">{{ $value->job->name }}</a>
                            @endif
                            @if (count($value->user->newMessages($value->job_id, $value->company_id)->get()) > 0)
                            &nbsp;&nbsp;&nbsp;
                            <span class="badge badge-danger">
                                {{ count($value->user->newMessages($value->job_id, $value->company_id)->get()) }}
                            </span>
                            @endif                            
                        </td>
                        <td class="text-center">{{ $value->company->name }}</td>
                        <td class="text-center"><a href="{{ URL::route('user.view', $value->user->slug) }}">{{ $value->user->name }}</a></td>
                        <td class="text-center">
                            @if ($value->job_id != NULL)
                            <a href="{{ URL::route('user.message.detail', array($user->slug, $value->company_id, $value->job_id)) }}" class="btn btn-primary btn-sm">Detail</a>
                            @else
                            <a href="{{ URL::route('user.message.detail', array($user->slug, $value->company_id)) }}" class="btn btn-primary btn-sm">Detail</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</main>
@stop
