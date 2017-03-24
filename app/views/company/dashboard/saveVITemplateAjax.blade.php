<table class="table table-striped table-hover table-bordered" id="vi_templates_table">
    <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                {{ trans('company.title') }}
            </th>
            <th>
                {{ trans('company.edit') }}
            </th>
            <th>
                {{ trans('company.delete') }}
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($templates as $key => $value)
        <tr>
            <td>
                {{ $key + 1 }}
            </td>
            <td>
                {{ $value->title }}
            </td>
            <td>
                <a class="edit" href="javascript:;">
                    Edit
                </a>
            </td>
            <td>
                <a class="delete" href="javascript:;">
                    Delete
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>