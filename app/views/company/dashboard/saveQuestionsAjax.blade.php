<table class="table table-striped table-bordered table-hover" id="select_questions_table">
    <thead>
        <tr>
            <th class="table-checkbox">
                <input type="checkbox" class="group-checkable" data-set="#select_questions_table input.checkboxes"/>
            </th>
            <th>
                {{ trans('company.question')}}
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($questions as $key => $value)
        <tr class="odd gradeX">
            <td>
                <input type="checkbox" class="checkboxes" value="{{ $value->id }}">
            </td>
            <td>
                {{ $value->question }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>