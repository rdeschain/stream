<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-signin-client_id" content="1056739458323-cce1gl0jd0l3ntjrg6226phgv8q2o1a1.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="/js/custom.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css" >

    <title>livesteam</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

</head>
<body>
<header>

    <style>
        .top-buffer { margin-top:20px; }
        .chat
        {
            font-size: 15px;
            width:100%;
            height:150px;
            border: 1px solid black;
            overflow:auto;
            float:left;

        }
    </style>

    <script>
        $(document).ready(function () {

            gapi.auth2.init();
            var bootstrap_enabled = (typeof $().modal == 'function');
            console.log('page ready ' + bootstrap_enabled);

            //some dummy chats
            /*
            setInterval(function(){

                $('#chatbox').append( '<div class="chatterName col-sm-3">james:</div><div class="chatterMessage col-sm-9">hi</div>');
                $('#chatbox').append( '<div class="chatterName col-sm-3">billy_bumbler:</div><div class="chatterMessage col-sm-9">not first</div>');
                $("#chatbox").animate({ scrollTop: $(document).height() }, "slow");
                return false;
            }, 5500); */

            $('#userinput').on('keyup', function(e) {
                if (e.keyCode === 13) {
                    $('#chatbox').append( '<div class="chatterName col-sm-3"><mark>YOU:</mark></div><div class="chatterMessage col-sm-9"><mark>' + $('#userinput').val() + '</mark></div>');
                    $("#chatbox").animate({ scrollTop: $(document).height() }, "slow");
                    $('#userinput').val('');
                }
            });

        });

        function onSignIn() {
            var googleUser = gapi.auth2.getAuthInstance().currentUser.get();
            var profile = googleUser.getBasicProfile();
            console.log("ID: " + profile.getId());
            console.log('Full Name: ' + profile.getName());
            console.log('Given Name: ' + profile.getGivenName());
            console.log('Family Name: ' + profile.getFamilyName());
            console.log("Image URL: " + profile.getImageUrl());
            console.log("Email: " + profile.getEmail());

            // The ID token you need to pass to your backend:
            var id_token = googleUser.getAuthResponse().id_token;
            console.log("ID Token: " + id_token);

            //next grant
            var options = new gapi.auth2.SigninOptionsBuilder(
                    {'scope': 'https://www.googleapis.com/auth/youtube'});

            googleUser.grant(options).then(
                    function(success){
                        console.log(JSON.stringify({message: "success", value: success}));
                    },
                    function(fail){
                        alert(JSON.stringify({message: "fail", value: fail}));
                    });


            //enable input
            $('#userinput').prop('disabled', false);
            $('#userinput').val('');
        };

        function signOut() {
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(function () {
                console.log('User signed out.');
            });
        }

        //https://developers.google.com/youtube/iframe_api_reference
        //This code loads the IFrame Player API code asynchronously.
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        //This function creates an <iframe> (and YouTube player) after the API code downloads.
        var player;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: '390',
                width: '640',
                videoId: 'ArwVHSydxIw',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        //The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            event.target.playVideo();
        }

        //The API calls this function when the player's state changes.
        //The function indicates that when playing a video (state=1), the player should play for six seconds and then stop.
        var done = false;
        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING && !done) {
                setTimeout(stopVideo, 6000);
                done = true;
            }
        }
        function stopVideo() {
            player.stopVideo();
        }

    </script>
    <div>
       <!-- <a href="#" onclick="signOut();">Sign out</a> -->
    </div>
</header>
<div class="container-fluid">
    <div class="row top-buffer">
        <div class="col-md-4"></div>
        <div class="col-md-4" id="player"></div>
        <div class="col-md-4"></div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="chat" id="chatbox">
                <div class="chatterName col-sm-3">chris:</div>
                <div class="chatterMessage col-sm-9">anyone here?</div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div class="row top-buffer">
        <div class="col-md-4"></div>
        <div class="col-md-4">
                <input class="col-sm-8" type="text" id="userinput" value="type message and press enter" disabled><div class="g-signin2 col-sm-4" data-onsuccess="onSignIn" data-theme="dark"></div>
        </div>
        <div class="col-md-4"></div>
    </div>

</div>
</body>
</html>
