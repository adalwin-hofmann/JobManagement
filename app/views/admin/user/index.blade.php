@extends('admin.layout')

@section('content')
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

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">User Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>User</span>
				<i class="fa fa-angle-right"></i>
			</li>
			<li>
				<span>List</span>
			</li>
		</ul>
		
	</div>
</div>
                    
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> User List
		</div>
	</div>
    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Score</th>
                    <th>Applications Sent</th>
                    <th>Recommendations Sent</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th style="width: 80px;">Edit</th>
                    <th style="width: 80px;">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->email }}</td>
                        <td>{{ $value->score }}</td>
                        <td>{{ $value->applies()->count() }}</td>
                        <td>{{ $value->hints()->count() }}</td>
                        <td>{{ ($value->is_active == 0) ? 'Deactive':'Active' }}</td>
                        <td>{{ $value->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('admin.user.edit', $value->id)  }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('admin.user.delete', $value->id)  }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
        	<div class="col-sm-12">
        		<div class="pull-right">{{ $users->links() }}</div>
        	</div>
        </div>
    </div>
</div>    
@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    @include('js.admin.user.index')
@stop

@stop
