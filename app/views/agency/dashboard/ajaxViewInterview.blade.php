<ul class="nav nav-tabs interview-nav-tabs">
    @foreach($interview->questionnaire->questions as $key => $value)
        <li @if ($key == 0) class="active" @endif id="li-tab-{{$key}}"><a href="#tab-{{ $interview->id }}-{{ $key + 1 }}" data-toggle="tab">{{ trans('company.question').' '.($key+1) }}</a></li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach($interview->questionnaire->questions as $key => $value)
        <div class="tab-pane row fade interview-question-tab @if ($key == 0) in active @endif" id="tab-{{ $interview->id }}-{{ $key+1 }}">
            <div class="row">
                <div class="col-sm-7">
                    <video id="preview" controls class="video-interview-preview">
                        <source src="{{ HTTP_VIDEO_PATH.$interview->responses[$key]->file_name }}" type="video/webm">
                    </video>
                </div>
                <div class="col-sm-5">
                    <p style="color: #A39D9D;">Interview Questions</p>
                    <p class="p-interview-question">{{ $value->questions->question }}</p>

                    <hr/>
                    <div class="row margin-top-xs">
                        <div class="col-sm-12">
                            <span class="span-job-description-title">My Note</span>
                        </div>

                        <?php
                            $myNotes = '';
                            if ($interview->responses[$key]->notes()->where('company_id', $agency->id)->get()->count() > 0) {
                                $myNotes = $interview->responses[$key]->notes()->where('company_id', $agency->id)->firstOrFail()->notes;
                            }
                        ?>
                        <div class="col-sm-12">
                            {{ nl2br($myNotes) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>