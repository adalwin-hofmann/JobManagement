{{ HTML::style('/assets/css/star-rating.min.css') }}

<div class="col-sm-2 text-center">
    <img style="width: 50px; height: 50px;" src="{{ HTTP_PHOTO_PATH. $user->profile_image }}" class="img-circle">
    <div class="col-sm-12 margin-top-xs">
        <a onclick="showUserView(this)" data-userId="{{ $user->id }}" class="username">{{ $user->name }}</a>@if ($user->age($user->id) != 0), <b>{{ $user->age($user->id) }}</b> @endif
    </div>

    <div class="col-sm-12 find-people-rating">
        <input id="input-rate-{{ $user->id }}" name="rating" class="rating" data-size="xs" data-default-caption="{rating}" data-star-captions="{}" style="float: left;" value="{{ count($user->scores()->where('company_id', $agency->id)->get()) > 0 ? $user->scores()->where('company_id', $agency->id)->firstOrFail()->score : 0  }}" onchange="showSaveButton(this)">
    </div>

    <div class="col-sm-12">
        <div class="row">
            <a class="btn btn-sm blue" id="js-a-save-rate" data-id="{{ $user->id }}" style="display: none;" onclick="saveUserScore(this)">Save</a>
        </div>
    </div>

    @if ($user->labelIdsOfAgency($agency->id) != '')
        <div class="col-sm-12 margin-top-xs">
            <div class="row">
                @foreach($user->labels()->where('company_id', $agency->id)->get() as $label)
                    <span class="label" style="background-color: {{ $label->label->color }}; font-size: 11px;">{{ $label->label->name }}</span>
                @endforeach
            </div>
        </div>
    @endif
</div>

<div class="col-sm-2 text-center">
    <div class="col-sm-12 margin-top-xs">
        <?php
            $skillFlag = 0;
            $skillLength = 0;
            foreach($user->skills()->orderBy('value', 'desc')->get() as $skill) {
                $skillLength += strlen($skill->name);
                if ($skillFlag >= 3) {
                    break;
                }
                $skillFlag ++;
        ?>
            <p>{{ $skill->name }} ({{ $skill->value }})</p>
        <?php }
            if ($skillFlag == 3) {
        ?>
            <p>...</p>
        <?php }?>
    </div>
</div>

<div class="col-sm-3">
    <div class="row">
        <div class="col-sm-12 margin-top-xs">
            @foreach($user->experiences()->orderBy('start', 'desc')->get() as $item)
                @if ($item->end == '0' || $item->end == '')
                    <p>{{ trans('user.current_job') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ trans('company.still_working') }}</p>
                @else
                    <p>{{ trans('user.previous_jobs') }}: {{ $item->position }}, {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                @endif
            @endforeach
        </div>
        <div class="col-sm-12 margin-top-xs">
            @foreach ($user->educations()->orderBy('start', 'desc')->get() as $item)
                <p>{{ trans('user.education_studied') }}: {{ $item->name }}, {{ $item->start }} - {{ $item->end }}</p>
                <?php break;?>
            @endforeach
        </div>
    </div>
</div>

<div class="col-sm-3">
    <div class="row">
        <div class="col-sm-12 margin-top-xs company-tooltip" style="position:relative;" data-toggle="tooltip" data-html="true" data-placement="left" data-image-url="{{ HTTP_PHOTO_PATH. $user->profile_image }}" data-tag="{{ $user->name }}" data-description="{{ nl2br($user->about) }}">
            <?php
                $aboutString = $user->about;
                if (preg_match('/^.{1,300}\b/s', $aboutString, $match))
                {
                    if (strlen($aboutString) > 300) {
                        $aboutString = $match[0].'...';
                    }
                }
            ?>
            <p>{{ trans('user.about_me') }}: {{ $aboutString }}</p>
        </div>
    </div>
</div>

<div class="col-sm-2 margin-top-xs">
    <div class="row">
        <button class="btn btn-sm btn-primary" onclick="showMsgModal(this)" data-id="{{ $user->id }}"><i class="fa fa-envelope-o"></i> Send Message</button>
    </div>
    <div class="row">
        <button class="btn btn-sm green margin-top-xxs" id="js-btn-video-interview" data-name="{{ $user->name }}"  data-userId="{{ $user->id }}">
            <i class="fa fa-video-camera"></i> Video Interview
        </button>
    </div>
    <div class="row">
        <button class="btn btn-sm green margin-top-xxs" id="js-btn-face-interview" data-name="{{ $user->name }}"  data-userId="{{ $user->id }}">
            <i class="fa fa-male"></i> Face Interview
        </button>
    </div>
    <div class="row">
        @if (!$user->isCandidate($agency->id))
        <button class="btn btn-sm btn-danger margin-top-xxs" data-id="{{ $user->id }}" id="js-btn-addToCandidates"><i class="fa fa-save"></i> {{ trans('user.add_to_candidates') }}</button>
        @endif
    </div>
    <div class="row">
        <button class="btn btn-sm margin-top-xxs" onclick="showLabelBox(this)" data-id="{{ $user->id }}" data-labelids="{{ $user->labelIdsOfAgency($agency->id) }}"><i class="fa fa-gear"></i> Labels</button>
        <div id="label-box-container"></div>
    </div>
</div>

{{ HTML::script('/assets/js/star-rating.min.js') }}