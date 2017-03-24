@foreach ($company->availableJobs($user->id) as $item)
    <div class="row margin-bottom-xs">
        <div class="col-sm-9 padding-top-xs">
            {{ $item->name }}
        </div>

        <div class="col-sm-3 text-right">
            <button type="button" class="btn btn-circle btn-primary" onclick="moveToJob(this)" data-jobId="{{ $item->id }}" data-userId="{{ $user->id }}">Move</button>
        </div>
    </div>
@endforeach