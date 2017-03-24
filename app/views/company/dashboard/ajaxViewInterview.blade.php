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
                            if ($interview->responses[$key]->notes()->where('company_id', $company->id)->get()->count() > 0) {
                                $myNotes = $interview->responses[$key]->notes()->where('company_id', $company->id)->firstOrFail()->notes;
                            }
                        ?>
                        <div class="col-sm-12">
                            <textarea class="form-control" id="js-textarea-interview-notes-{{ $interview->responses[$key]->id }}" rows="5">{{ $myNotes }}</textarea>
                        </div>

                        <div class="col-sm-12 margin-top-xs text-right">
                            <button class="btn btn-success btn-sm btn-home" data-id="{{ $interview->responses[$key]->id }}" onclick="saveInterviewNotes(this)"><i class="fa fa-save"></i> Save Note</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


<script type="text/javascript">
    function saveInterviewNotes(obj) {
        var cvrId = $(obj).attr('data-id');
        var target = 'textarea#js-textarea-interview-notes-'+cvrId;
        var notes = $(target).val();

        $.ajax({
            url: "{{ URL::route('company.job.async.saveInterviewNote') }}",
            dataType : "json",
            type : "POST",
            data : {cvr_id : cvrId, notes : notes},
            success : function(data) {
                bootbox.alert(data.msg);
                window.setTimeout(function(){
                    bootbox.hideAll();
                }, 1000);
            }
        });
    }
</script>