@foreach($messages as $key => $value)
<div class="row margin-top-sm">
    @if ($value->is_company_sent)
    <div class="col-sm-2 text-center">
        <img src="{{ HTTP_LOGO_PATH.$value->company->logo }}" style="width: 50%;"/>
        <div class="margin-top-xs">{{ $value->company->name }}</div>
    </div>
    <div class="col-sm-10">
        <p>
            {{ $value->description}}
            <span class="color-gray-dark font-size-xs">
                <i>( {{ $value->created_at }} )</i>
            </span>
        </p>
    </div>
    @else
    <div class="col-sm-10 text-right">
        <p>{{ $value->description }}</p>
        <span class="color-gray-dark font-size-xs">
            <i>( {{ $value->created_at }} )</i>
        </span>
    </div>
    <div class="col-sm-2 text-center">
        <img src="{{ HTTP_PHOTO_PATH.$value->user->profile_image }}" style="width: 50%;" class="img-rounded"/>
        <div class="margin-top-xs">
            <a href="{{ URL::route('user.view', $value->user->slug) }}">
                {{ $value->user->name }}
            </a>
        </div>
    </div>
    @endif
</div>
@endforeach