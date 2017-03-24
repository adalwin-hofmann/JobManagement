<ul class="tabbable faq-tabbable margin-bottom-normal custom-left-menu">
    <li class="{{ ($statusType == 0) ? 'active' : '' }}">
        <a href="{{ URL::route('company.job.myjobs', 0) }}">
            {{ trans('company.all') }} &nbsp; <span style="float: right;">( {{ $company->jobs()->where('is_finished', 1)->where('by_company', 1)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 1) ? 'active' : '' }}">
        <a href="{{ URL::route('company.job.myjobs', 1) }}">
            {{ trans('company.pending') }} &nbsp; <span style="float: right;">( {{ $company->jobs()->where('is_active', 1)->where('is_finished', 1)->where('by_company', 1)->where('status', 1)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 2) ? 'active' : '' }}">
        <a href="{{ URL::route('company.job.myjobs', 2) }}">
            {{ trans('company.closed') }} &nbsp; <span style="float: right;">( {{ $company->jobs()->where('is_active', 1)->where('is_finished', 1)->where('by_company', 1)->where('status', 2)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 3) ? 'active' : '' }}">
        <a href="{{ URL::route('company.job.myjobs', 3) }}">
            {{ trans('company.active') }} &nbsp; <span style="float: right;">( {{ $company->jobs()->where('is_active', 1)->where('is_finished', 1)->where('by_company', 1)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 4) ? 'active' : '' }}">
        <a href="{{ URL::route('company.job.myjobs', 4) }}">
            {{ trans('company.shared') }} &nbsp; <span style="float: right;">( {{ $company->companyShares()->whereNotNull('job_id')->get()->count() }} )</span>
        </a>
    </li>
</ul>