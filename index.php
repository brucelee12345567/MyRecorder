<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="shortcut icon" href="img/favicon.ico" type="image/vnd.microsoft.icon"/>
<title>Vocal Site</title>
<link type="text/css" rel="stylesheet" href="css/style.css" media="all"/>
<link type="text/css" rel="stylesheet" href="css/jquery-ui.css" media="all"/>
<link type="text/css" rel="stylesheet" href="css/bootstrap.css" media="all"/>
<link type="text/css" rel="stylesheet" href="css/bootstrap-theme.css" media="all"/>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/recorder.js"></script>

</head>
<body>

  <div class="container">
    <div class="row">
      <div class="top-image">
        <img src="image/mario.png">
      </div>
      <div class="under-image">
        <img src="image/mario_base_shadow.png">
      </div>
      <div class="top-letter">
        <div class="top-letter-large">RECORD ANOTHER QUESTION</div>
        <div class="top-letter-small">AND GET YOUR ANSWER</div>
      </div>
      <div class="record-mark">
    		<div class="back">
    			<img src="image/round-web20-button-with-metal-ring.png" usemap="#record-voice" alt="voice">
          <map name="record-voice">
            <area shape="circle" class="record-voice" alt="voice1" coords="60,60,55">
          </map>
    		</div>
    		<div class="mic">
    			<img src="image/voice-recorder.png">
    		</div>
      </div>
  	  <div class="bottom-letter">
    		<div class="bottom-letter-large"><a href="">browse all questions &amp; answers</a></div>
    		<div class="bottom-letter-middle"><a href="">ABOUT ME</a></div>
    		<div class="bottom-letter-small">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
    		<div class="bottom-letter-small">In commodo facilisis sapien, id yehicula eros consectetur non. Proin in dapibus enim.</div>
    		<div class="bottom-letter-small">Vitae blandit arcu. Vivamus id lobortis augue.</div>
    		<div class="bottom-letter-small">Nulla vulputate tristique laoreet. Sed ullamcorper sem id mauris pharetra consequat.</div>
    		<div class="bottom-letter-small">Donec nec cursus, non semper odio. Cars nec tempor dolor, ut elementum risus.</div>
  	  </div>
      <div id="dialog" title="RECORDING...">
        <textarea class="question_title" style="width:100%; height:80px;" placeholder="Title of your question."></textarea>
        <div class="dialog-letter">PUSH TO START RECORDING...</div>
        <div class="record-mark">
      		<div class="back">
      			<img src="image/round-web20-button-with-metal-ring.png" usemap="#dialog-record-voice" alt="voice">
            <map name="dialog-record-voice">
              <area shape="circle" class="dialog-record-voice" alt="voice1" coords="40,40,40">
            </map>
      		</div>
      		<div class="dialog-mic">
      			<img src="image/voice-recorder.png">
      		</div>
        </div>
      </div>
      <div id="dialog2">
        <div>
          <img src="image/Vector_Smart_Object.png">
        </div>
        <div class="dialog-letter" style="margin-top: 10px;">CLICK TO STOP</div>
      </div>
      <div id="dialog3">
        <div id="return" style="text-align: left;">
          <img style="height: 20px" src="image/return.png">
        </div>
		<canvas id="canvas" width="256" height="100" ></canvas>
		<p id="controls">
		  <input type="button" id="start_button" value="Start">
		  &nbsp; &nbsp;
		  <input type="button" id="stop_button" value="Stop">
		</p>
        <div id="speaker">
          <img src="image/speaker.png">
        </div>
        <div class="dialog-letter">APPROVE &amp; SEND!</div>
      </div>
      <div id="dialog4" title="THANK YOU FOR YOUR QUESTION.">
        <div class="final-dialog-letter">PLEASE, NOTIFY ME WHEN MY ANSWER IS READY.</div>
        <div>
          <form>
            <input class="email-box" type="email" name="email_address" placeholder="Enter your email here." required="">
            <button type="submit" class="btn-send-email">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            </button>
          </form>
          <p class="final-dialog-letter" style="margin-top: 20px;">We are creating a community<br>where questions and answers are the focus<br>of the learning process.</p>
        </div>
      </div>
	  <div id="recordingslist"></div>
    </div>
  </div>
  <script>


  function logHTML(e, data) {

  }


  var audioContext;
  var audioRecorder;
  var is_live_audio_input = true;
  var is_play = false;

  var _realAudioInput;

  // Global Variables for Audio
	var audioContext;
	var audioBuffer;
	var sourceNode;
	var analyserNode;
	var javascriptNode;
	var audioData = null;
	var audioPlaying = false;
	var sampleSize = 1024;  // number of samples to collect before analyzing data
	var amplitudeArray;     // array to hold time domain data
	// This must be hosted on the same server as this page - otherwise you get a Cross Site Scripting error
	var audioUrl;
	// Global Variables for the Graphics
	var canvasWidth  = 256;
	var canvasHeight = 100;
	var ctx;


  function handlerStartUserMedia(stream) {

    console.log('handlerStartUserMedia');
    console.log('sampleRate:'+ audioContext.sampleRate);

    // MEDIA STREAM SOURCE -> ZERO GAIN >
    _realAudioInput = audioContext.createMediaStreamSource(stream);

    audioRecorder = new Recorder(_realAudioInput);


  }

  function handlerErrorUserMedia(e) {
      //alert('No live audio input: ' + e);
	  //is_live_audio_input = false;
  }


  function startRecording() {

    if(!audioRecorder)
      return;


    audioRecorder && audioRecorder.record();

    logHTML('Recording...');


  }

  function stopRecording() {

    if(!audioRecorder)
      return;


    audioRecorder && audioRecorder.stop();

    logHTML('Stopped recording.');


  }

  window.onload = function init() {

    //try {

      // webkit shim.
      window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext;

      navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

      window.URL = window.URL || window.webkitURL;

      audioContext = new AudioContext;

      logHTML('Audio context set up.');
      logHTML('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));

    //} catch (e) {

    //  alert('No web audio support in this browser!');

    //}


    navigator.getUserMedia({vide:false, audio: true}, handlerStartUserMedia, handlerErrorUserMedia);

  };

  window.requestAnimFrame = (function(){
      return  window.requestAnimationFrame       ||
              window.webkitRequestAnimationFrame ||
              window.mozRequestAnimationFrame    ||
              function(callback, element){
                window.setTimeout(callback, 1000 / 60);
              };
    })();

  function setupAudioNodes() {
        sourceNode     = audioContext.createBufferSource();
        analyserNode   = audioContext.createAnalyser();
        javascriptNode = audioContext.createScriptProcessor(sampleSize, 1, 1);
        // Create the array for the data values
        amplitudeArray = new Uint8Array(analyserNode.frequencyBinCount);
        // Now connect the nodes together
        sourceNode.connect(audioContext.destination);
        sourceNode.connect(analyserNode);
        analyserNode.connect(javascriptNode);
        javascriptNode.connect(audioContext.destination);
    }
    // Load the audio from the URL via Ajax and store it in global variable audioData
    // Note that the audio load is asynchronous
    function loadSound(url) {
        var request = new XMLHttpRequest();
        request.open('GET', url, true);
        request.responseType = 'arraybuffer';
        // When loaded, decode the data and play the sound
        request.onload = function () {
            audioContext.decodeAudioData(request.response, function (buffer) {
                audioData = buffer;
                playSound(audioData);
            }, onError);
        }
        request.send();
    }
    // Play the audio and loop until stopped
    function playSound(buffer) {
        sourceNode.buffer = buffer;
        sourceNode.start(0);    // Play the sound now
        sourceNode.loop = true;
        audioPlaying = true;
    }
    function onError(e) {
        console.log(e);
    }
    function drawTimeDomain() {
        clearCanvas();
        for (var i = 0; i < amplitudeArray.length; i++) {
            var value = amplitudeArray[i] / 256;
            var y = canvasHeight - (canvasHeight * value) - 1;
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(i, y, 1, 1);
        }
    }
    function clearCanvas() {
        ctx.clearRect(0, 0, canvasWidth, canvasHeight);
    }

  </script>
</body>
</html>
