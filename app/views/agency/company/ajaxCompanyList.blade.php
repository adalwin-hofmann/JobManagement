<table class="table table-striped table-bordered table-hover" id="sample_1">
    <thead>
        <tr>
            <th>
                 Name
            </th>
            <th>
                 Email
            </th>
            <th>
                Client
            </th>
            <th class="text-center">
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($companies as $key => $value)
            <tr class="odd gradX">
                <td>
                    {{ $value->name }}
                </td>
                <td>
                    {{ $value->email }}
                </td>
                <td>
                    @if ($value->isClient($agency->id))
                        YES
                    @else
                        NO
                    @endif
                </td>
                <td>
                    @if ($value->isClient($agency->id))
                        <button class="btn btn-sm btn-danger margin-top-xs" data-id="{{ $value->id }}" onclick="removeClient(this)">Remove from Client</button>
                    @else
                        <button class="btn btn-sm btn-primary" data-id="{{ $value->id }}" onclick="setClient(this)">Set as Client</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>