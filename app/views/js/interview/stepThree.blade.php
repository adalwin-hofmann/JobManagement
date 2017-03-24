<script type="text/javascript">

    var questions = [];

    @foreach($questions as $key => $value)
        questions['{{ $key }}'] = {
            time: '{{ $value->questions->time }}',
            question: '{{ $value->questions->question }}',
            id: '{{ $value->questions->id }}'
        };
    @endforeach



    var countQuestions = '{{ count($questions) }}';
    countQuestions = countQuestions * 1;
    var currentIndex = 0;

    var record = document.getElementById('record');
    var stop = document.getElementById('stop');
    var audio = document.querySelector('audio');
    var recordVideo = document.getElementById('record-video');
    var preview = document.getElementById('preview');
    var container = document.getElementById('container');
    var isFirefox = !!navigator.mozGetUserMedia;
    var recordAudio, recordVideo;
    var recordFiles = [];
    var recordedFile = '';

	$(document).ready(function() {
        var wHeight = window.innerHeight;
        var newBodyHeight = wHeight - 75 - 102 - 10;
        var newImageHeight = wHeight - 75 - 102 - 10;


        currentIndex = 0;

        var buttonTarget = 'button#nextQuestion-' + currentIndex;
        $(buttonTarget).addClass('disabled');

        $('div.interview-body').css('height', newBodyHeight + 'px');
        $('img.interview-video-content').css('height', newImageHeight + 'px');
        $('div#vi-main-container').css('margin-top', ((newImageHeight - $('div#vi-main-container').height()) / 2 - 30) + 'px');

        $('div#video-record-stop-container').css('display', 'none');
        $('div#viQuestionModal').modal({
                backdrop: 'static',
                keyboard: false
            });

    });

    function PostBlob(audioBlob, videoBlob, fileName) {
        var formData = new FormData();
        var buttonTarget1 = 'button#nextQuestion-' + currentIndex;
        var buttonTarget2 = 'button#restartRecord-' + currentIndex;

        formData.append('filename', fileName);
        formData.append('audio-blob', audioBlob);
        formData.append('video-blob', videoBlob);
        xhr('{{ URL::route('interview.video.save') }}', formData, function(ffmpeg_output) {

            $(buttonTarget1).removeClass('disabled');
            $(buttonTarget2).removeClass('disabled');

            $('label#label-video-processing').html('');

            preview.src = '{{ HTTP_VIDEO_PATH }}' + fileName + '-merged.webm';
            recordedFile = fileName + '-merged.webm';
            preview.play();
            preview.muted = false;
        });
    }

    record.onclick = function() {
        record.disabled = true;
        startRecord();
    };

    var fileName;
    stop.onclick = function() {
        saveVideo();
    };

    function startRecord() {
        var buttonTarget1 = 'button#restartRecord-' + currentIndex;
        var buttonTarget2 = 'button#nextQuestion-' + currentIndex;

        $(buttonTarget1).addClass('disabled');
        $(buttonTarget2).addClass('disabled');

        $('div#viQuestionModal').modal('hide');
        $('div#video-record-stop-container').css('display', 'block');

        !window.stream && navigator.getUserMedia({
            audio: true,
            video: true
        }, function(stream) {
            window.stream = stream;
            onstream();
        }, function(error) {
            alert(JSON.stringify(error, null, '\t'));
        });

        window.stream && onstream();

        function onstream() {


            //set time
            setTime();


            //
            preview.src = window.URL.createObjectURL(stream);
            preview.play();
            preview.muted = false;

            recordAudio = RecordRTC(stream, {
                // bufferSize: 16384,
                onAudioProcessStarted: function() {
                    if (!isFirefox) {
                        recordVideo.startRecording();
                    }
                }
            });

            recordVideo = RecordRTC(stream, {
                type: 'video'
            });

            recordAudio.startRecording();

            stop.disabled = false;
        }
    }


    function setTime() {
        $('label#label-record-time').empty();
        $('label#label-record-time').removeClass('is-countdown');
        var time = questions[currentIndex].time;
        time  = time * 1;

        var mt = Math.floor(time / 60);
        var st = time % 60;
        if (mt < 10) mt = '0' + mt;
        if (st < 10) st = '0' + st;

        $('label#label-record-time').html(mt+':'+st);

        var recordTime = new Date();
        recordTime.setSeconds(recordTime.getSeconds() + time + 0.5);
        $('label#label-record-time').countdown({until: recordTime, format: 'MS', compact: true, description: '', onExpiry:liftOff});
    }

    function saveVideo() {


        $('label#label-record-time').countdown('pause');
        $('div#video-record-stop-container').css('display', 'none');

        $('label#label-video-processing').html('Getting Blobs...');

        record.disabled = false;
        stop.disabled = true;

        preview.src = '';
        preview.poster = '/assets/img/video_processing.png';

        fileName = Math.round(Math.random() * 99999999) + 99999999;

        if (!isFirefox) {
            recordAudio.stopRecording(function() {
                $('label#label-video-processing').html('Got audio-blob. Getting video-blob...');
                recordVideo.stopRecording(function() {
                    $('label#label-video-processing').html('Final Processing...');
                    PostBlob(recordAudio.getBlob(), recordVideo.getBlob(), fileName);
                });
            });
        }
    }

    function xhr(url, data, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (request.readyState == 4 && request.status == 200) {
                callback(request.responseText);
            }
        };
        request.open('POST', url);
        request.send(data);
    }

    function liftOff() {
        alert ('Time is up!');
        saveVideo();
    }

    function restartRecord(number) {
        number = number * 1;
        currentIndex = number;
        $('label#label-record-time').countdown('resume');
        startRecord();
        setTime();
    }

    function recordNext(number) {

        recordFiles[currentIndex] = recordedFile;

        number = number * 1;

        var prevTab = 'li#li-tab-' + currentIndex;
        var curTab = 'li#li-tab-' + number;

        $(prevTab).removeClass('active');
        $(curTab).addClass('active');

        var prevTabPane = 'div#tab-' + (currentIndex + 1);
        var currTabPane = 'div#tab-' + (number + 1);

        $(prevTabPane).removeClass('in');
        $(prevTabPane).removeClass('active');
        $(currTabPane).addClass('active');
        $(currTabPane).addClass('in');

        currentIndex = number;

        $('p#js-vi-modal-question').html(questions[currentIndex].question);


        var buttonTarget = 'button#nextQuestion-' + currentIndex;
        $(buttonTarget).addClass('disabled');

        $('div#video-record-stop-container').css('display', 'none');
        $('div#viQuestionModal').modal({
                backdrop: 'static',
                keyboard: false
            });
    }


    function saveRecordedFiles() {

        recordFiles[currentIndex] = recordedFile;

        var cvcId = '{{ $viCreated->id }}';
        var rFiles = '';
        var rIds = '';

        recordFiles.forEach(function(item) {
            if (rFiles != '') {
                rFiles = rFiles + ',';
            }
            rFiles = rFiles + item;
        });

        questions.forEach(function(item) {
            if (rIds != '') {
                rIds = rIds + ',';
            }
            rIds = rIds + item.id;
        });


        $.ajax({
            url: "{{ URL::route('interview.video.async.saveResponses') }}",
            dataType : "json",
            type : "POST",
            data : {cvc_id: cvcId, questions_id: rIds, response_files: rFiles},
            success : function(data) {
                location.href = '{{ URL::route('interview.video.step4', array($user->slug, $company->slug)) }}' + '?_token=' + '{{ $viCreated->token }}';
            }
        });
    }

</script>