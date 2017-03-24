@extends('agency.layout')

@section('body')
 
<main class="bs-docs-masthead gray-container padding-top-xs padding-bottom-xs" role="main">
	<div class="background-dashboard" style="display: none;"></div>
    <div class="container" style="background: white;">
    	<div class="margin-top-50"></div>
        <div class="row text-center margin-top-normal margin-bottom-normal">
            <h2 class="">Message Center</h2>
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
                            <a href="{{ URL::route('agency.job.view', $value->job->slug) }}">{{ $value->job->name }}</a>
                            @endif

                            @if (count($value->company->newMessages($value->job_id, $value->user_id)->get()) > 0)
                            &nbsp;&nbsp;&nbsp;
                            <span class="badge badge-danger">
                                {{ count($value->company->newMessages($value->job_id, $value->user_id)->get()) }}
                            </span>
                            @endif
                        </td>
                        <td class="text-center">{{ $value->company->name }}</td>
                        <td class="text-center"><a onclick="showUserView(this)" data-userId="{{ $value->user->id }}" class="username">{{ $value->user->name }}</a></td>
                        <td class="text-center">
                            @if ($value->job_id != NULL)
                            <a href="{{ URL::route('agency.message.detail', array($agency->slug, $value->user_id, $value->job_id)) }}" class="btn btn-primary btn-sm">Detail</a>
                            @else
                            <a href="{{ URL::route('agency.message.detail', array($agency->slug, $value->user_id)) }}" class="btn btn-primary btn-sm">Detail</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach


                    @if (count($messages) == 0)
                        <tr>
                            <td colspan="5">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-12 padding-top-sm padding-bottom-sm text-center" style="background-color: white;">
                                            There are no messages.
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>   


<div id="js-div-userview" style="display: none;">
</div>

@stop


@section('custom-scripts')

    <script>
        function showUserView(obj) {
            var userId = $(obj).attr('data-userId');
            $.ajax({
                url:"{{ URL::route('agency.user.async.view') }}",
                dataType : "json",
                type : "POST",
                data : {user_id : userId},
                success : function(data){
                    if (data.result == 'success') {
                        $('div#js-div-userview').empty();
                        $('div#js-div-userview').html(data.userView);
                        $('div#js-div-userview').fadeIn('normal');
                    }
                }
            });
        }

        function hideUserView() {
            $('div#js-div-userview').fadeOut('normal');
        }

        function sendInvite(obj) {
            var userId = $(obj).attr('data-userid');
            var jobId = $(obj).attr('data-jobid');

            $(obj).html('<img src="{{ HTTP_IMAGE_PATH.'loading.gif' }}" style="height: 16px;">');

            $.ajax({
                url:"{{ URL::route('agency.user.async.sendInvite') }}",
                dataType : "json",
                type : "POST",
                data : {user_id : userId, job_id: jobId},
                success : function(data){
                    if (data.result == 'success') {
                        $(obj).html('Invited');
                        $(obj).addClass('disabled');
                    }
                }
            });
        }


        $(document).mouseup(function (e)
        {
            var container = $("div#js-div-user-detail-view");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                hideUserView();
            }
        });

    </script>
@stop