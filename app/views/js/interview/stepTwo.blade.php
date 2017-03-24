<script type="text/javascript">


    var audio = document.querySelector('audio');
    var cameraFlag = false;
    var cameraErr = '';

    var recordVideo = document.getElementById('record-video');
    var preview = document.getElementById('preview');

    var container = document.getElementById('container');


	$(document).ready(function() {
        var wHeight = window.innerHeight;
        var newBodyHeight = wHeight - 75 - 102 - 10;
        var newImageHeight = wHeight - 75 - 102 - 28;
        var newContentHeight = newBodyHeight -  10;
        $('div.interview-body').css('height', newBodyHeight + 'px');
        $('img.interview-camera-test').css('height', newImageHeight + 'px');


        !window.stream && navigator.getUserMedia({
            audio: true,
            video: true
        }, function(stream) {
            window.stream = stream;
            onstream();
        }, function(error) {
            cameraErr = JSON.stringify(error, null, '\t');
            alert(JSON.stringify(error, null, '\t'));
        });

        function onstream() {
            cameraFlag = true;
            preview.src = window.URL.createObjectURL(stream);
            preview.play();
            preview.muted = false;

            recordAudio = RecordRTC(stream, {
                // bufferSize: 16384,
                onAudioProcessStarted: function() {
                    if (!isFirefox) {

                    }
                }
            });

            recordVideo = RecordRTC(stream, {
                type: 'video'
            });


            stop.disabled = false;
        }
    });


    function goThirdPage() {
        if (!cameraFlag) {
            alert (cameraErr);
            return;
        }

        location.href = '{{ URL::route('interview.video.step3', array($user->slug, $company->slug)) }}' + '?_token=' + '{{ $viCreated->token }}';
    }

</script>