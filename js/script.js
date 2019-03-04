$(document).ready(function(){
	
	ctx = $("#canvas").get()[0].getContext("2d");
	// the AudioContext is the primary 'container' for all your audio node objects
	try {
		audioContext = new AudioContext();
	} catch(e) {
		alert('Web Audio API is not supported in this browser');
	}

  $('.record-voice').mouseover(function() {
    $('.back img').attr('src','image/round-web20-button-with-metal-ring-active.png');
  }).mouseout(function(){
    $('.back img').attr('src','image/round-web20-button-with-metal-ring.png');
  });

  $('.mic').mouseover(function() {
    $('.back img').attr('src','image/round-web20-button-with-metal-ring-active.png');
  });

  $('.record-voice').click(function() {
	//if(!is_live_audio_input) {
		//alert('No live audio input.');
		//return;
	//}
    $( "#dialog" ).dialog();
    $('div[aria-describedby="dialog"]').css('display', 'block');
    $('div[aria-describedby="dialog"] textarea').val('');
    $('div[aria-describedby="dialog"] textarea').css('border-color', '#e3e3e3');
  });

  $('.mic').click(function() {
	//if(!is_live_audio_input) {
		//alert('No live audio input.');
		//return;
	//}
    $( "#dialog" ).dialog();
    $('div[aria-describedby="dialog"]').css('display', 'block');
    $('div[aria-describedby="dialog"] textarea').val('');
    $('div[aria-describedby="dialog"] textarea').css('border-color', '#e3e3e3');
  });

  $('.dialog-record-voice').mouseover(function() {
    $('#dialog .back img').attr('src','image/round-web20-button-with-metal-ring-active.png');
  }).mouseout(function(){
    $('#dialog .back img').attr('src','image/round-web20-button-with-metal-ring.png');
  });

  $('.dialog-mic img').mouseover(function() {
    $('#dialog .back img').attr('src','image/round-web20-button-with-metal-ring-active.png');
  });

  $('.dialog-record-voice').click(function() {
    if( $( 'div[aria-describedby="dialog"] textarea' ).val() == '' ) {
      $('div[aria-describedby="dialog"] textarea').css('border-color', 'red');
      return;
    }
    $( "#dialog2" ).dialog();
    $('div[aria-describedby="dialog2"]').css('display', 'block');
	  startRecording();
  });

  $('.dialog-mic').click(function( event ) {
    event.stopPropagation();
    if($('div[aria-describedby="dialog"] textarea').val() == '') {
      $('div[aria-describedby="dialog"] textarea').css('border-color', 'red');
      return;
    }
    $( "#dialog2" ).dialog();
    $('div[aria-describedby="dialog2"]').css('display', 'block');
	startRecording();
  });

  $('#dialog2 .dialog-letter').click(function() {
    $( "#dialog3" ).dialog();
    $('div[aria-describedby="dialog3"]').css('display', 'block');
	stopRecording();
  });

  $('#dialog3 .dialog-letter').click(function(e) {
    $( "#dialog4" ).dialog();
    $('div[aria-describedby="dialog4"]').css('display', 'block');
    $('div[aria-describedby="dialog4"] .email-box').val('');
	e.preventDefault();
	if(sourceNode != null) {
		sourceNode.stop(0);
	}
	audioPlaying = false;
	uploadAudio(blob1);
  });

  $('#dialog3 #return').click(function(e) {
    $('div[aria-describedby="dialog3"]').css('display', 'none');
    $('div[aria-describedby="dialog2"]').css('display', 'block');
	e.preventDefault();
	if(sourceNode != null) {
		sourceNode.stop(0);
	}
	audioPlaying = false;
	url_mp3 = "";
	startRecording();
  });

  $('#dialog3 #start_button').click(function(e) {
    e.preventDefault();
	// Set up the audio Analyser, the Source Buffer and javascriptNode
	setupAudioNodes();
	// setup the event handler that is triggered every time enough samples have been collected
	// trigger the audio analysis and draw the results
	javascriptNode.onaudioprocess = function () {
		// get the Time Domain data for this sample
		analyserNode.getByteTimeDomainData(amplitudeArray);
		// draw the display if the audio is playing
		if (audioPlaying == true) {
			requestAnimFrame(drawTimeDomain);
		}
	}
	// Load the Audio the first time through, otherwise play it from the buffer
	audioUrl = url_mp3;
	//if(audioData == null) {
		loadSound(audioUrl);
	//} else {
		//playSound(audioData);
	//}
  });

  $('#dialog3 #stop_button').click(function(e) {
    e.preventDefault();
	sourceNode.stop(0);
	audioPlaying = false;
  });

  $('#dialog4 .btn-send-email').click(function() {
    if($('#dialog4 .email-box').val() == '') {
      $('#dialog4 .email-box').css('border-color', 'red');
      return;
    }
    $('div[aria-describedby="dialog"]').css('display', 'none');
    $('div[aria-describedby="dialog2"]').css('display', 'none');
    $('div[aria-describedby="dialog3"]').css('display', 'none');
  });

  //$('form .btn-send-email').click(function(e){
    //e.preventDefault();
    //email = $('.email-box').val();
    //param = "email_address=" + email;
    //$.ajax({
            //type: "POST",
            //url: "email.php",
            //data: param,
            //success: function(response){
              //alert(response);
            //}
        //});
  //});
});
function uploadAudio(mp3Data){
		var reader = new FileReader();
		reader.onload = function(event){
			var fd = new FormData();
			var mp3Name = encodeURIComponent('audio_recording_' + new Date().getTime() + '.mp3');
			console.log("mp3name = " + mp3Name);
			fd.append('fname', mp3Name);
			fd.append('data', event.target.result);
			$.ajax({
				type: 'POST',
				url: 'upload.php',
				data: fd,
				processData: false,
				contentType: false
			}).done(function(data) {
				console.log('Upload.php');
			});
		};
		reader.readAsDataURL(mp3Data);
	}
