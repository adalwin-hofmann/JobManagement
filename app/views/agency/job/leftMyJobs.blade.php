<ul class="tabbable faq-tabbable margin-bottom-normal custom-left-menu">
    <li class="{{ ($statusType == 0) ? 'active' : '' }}">
        <a href="{{ URL::route('agency.job.myjobs', 0) }}">
            {{ trans('company.all') }} &nbsp; <span style="float: right;">( {{ $agency->jobs()->where('is_finished', 1)->where('by_company', 1)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 1) ? 'active' : '' }}">
        <a href="{{ URL::route('agency.job.myjobs', 1) }}">
            {{ trans('company.pending') }} &nbsp; <span style="float: right;">( {{ $agency->jobs()->where('is_active', 1)->where('is_finished', 1)->where('by_company', 1)->where('status', 1)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 2) ? 'active' : '' }}">
        <a href="{{ URL::route('agency.job.myjobs', 2) }}">
            {{ trans('company.closed') }} &nbsp; <span style="float: right;">( {{ $agency->jobs()->where('is_active', 1)->where('is_finished', 1)->where('by_company', 1)->where('status', 2)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 3) ? 'active' : '' }}">
        <a href="{{ URL::route('agency.job.myjobs', 3) }}">
            {{ trans('company.active') }} &nbsp; <span style="float: right;">( {{ $agency->jobs()->where('is_active', 1)->where('is_finished', 1)->where('by_company', 1)->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 5) ? 'active' : '' }}" style="border-top: 2px solid #83BEE0;">
        <a href="{{ URL::route('agency.job.myjobs', 5) }}">
            {{ trans('company.all_candidates') }} &nbsp;<span style="float: right;">( {{ $agency->applies()->groupBy('user_id')->get()->count() }} )</span>
        </a>
    </li>
    <li class="{{ ($statusType == 6) ? 'active' : '' }}">
        <a href="{{ URL::route('agency.job.myjobs', 6) }}">
            {{ trans('company.interviews') }} &nbsp;<span style="float: right;">{{ isset($interviews) ? '( '.count($interviews).' )' : '' }}</span>
        </a>
    </li>            
</ul>