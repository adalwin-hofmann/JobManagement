@extends('admin.layout')

@section('content')
@if (isset($alert))
<div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <p>
        {{ $alert['msg'] }}
    </p>
</div>
@endif

<div class="row">
	<div class="col-md-12">
		<h3 class="page-title">Category Management</h3>
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<i class="fa fa-home"></i>
				<span>Category</span>
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
			<i class="fa fa-navicon"></i> Category List
		</div>
		<div class="actions">
			<a href="{{ URL::route('admin.category.create') }}" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-plus"></span>&nbsp;Create
			</a>								    
		</div>
	</div>
    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th style="width: 80px;">Edit</th>
                    <th style="width: 80px;">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('admin.category.edit', $value->id)  }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('admin.category.delete', $value->id)  }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
        	<div class="col-sm-12">
        		<div class="pull-right">{{ $categories->links() }}</div>
        	</div>
        </div>
    </div>
</div>    
@stop

@section('custom_scripts')
    {{ HTML::script('/assets/js/bootbox.js') }}
    @include('js.admin.category.index')
@stop

@stop
