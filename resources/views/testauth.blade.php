<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>

    <script>

        var gapiPromise = (function () {
            var deffered = $.Deferred();
            window.start = function () {
                deffered.resolve(gapi);
            };
            return deffered.promise()

        }());

        var authInited = gapiPromise.then(function () {
            gapi.load('auth2', function () {
                auth2 = gapi.auth2.init({
                    client_id: '{{env('GOOGLE_CLIENT')}}',
                    scope: 'https://www.googleapis.com/auth/youtube'
                });
            });
            console.log('gapi loaded');
        });

    </script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('css/custom.css')}}" type="text/css" rel="stylesheet">
    <script src="{{asset('js/userdropdown.js')}}"></script>
    <script src="{{asset('js/loadselectedchat.js')}}"></script>
    <script src="{{asset('js/streamselectdropdown.js')}}"></script>
    <script src="{{asset('js/postchatmessage.js')}}"></script>

    <script>
        $(document).ready(function () {

            userdropdown();
            loadselectedchat();
            postchatmessage();
        })

    </script>

</head>
<body>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="liveChatID" id="liveChatID" value="">
<input type="hidden" name="broadcastID" id="broadcastID" value="">
<input type="hidden" name="videoID" id="videoID" value="">
<input type="hidden" name="currentVideoID" id="currentVideoID" value="">

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">demo live chat</a>
        </div>
        <div>
            <ul class="nav navbar-nav">
                <li class="active" style="padding-top: 9px; padding-bottom: 9px">

                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="alert"></div>
<div class="container-fluid">
    <div class="row top-buffer">
        <div class="col-md-12 col-sm-12 text-center">
            <div id="player_frame" style="display:none">
                <iframe id="player"
                        width="640" height="360"
                        frameborder="0"
                ></iframe>
            </div>

        </div>
    </div>

    <div class="row top-buffer">
        <div class="col-md-12 col-sm-12">
            <div class="standard-width">
                <div class="input-group">
                    <input type="text" class="form-control" id="stream-search" placeholder="search for a stream..."
                           style="width: 250px">
                    <button class="btn btn-secondary" type="button" onclick="loadDropdown()">Search</button>
                    <span style="padding-left: 119px;"></span>
                    <select class="selectpicker select-stream" id="stream-select" disabled>
                        <option selected disabled style="font-style: italic">search to load streams</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row top-buffer">
        <div class="col-md-12 col-sm-12">
            <div class="status-place">Chat Status: <span id="chatStatus">disconnected</span></div>
        </div>
        <div class="col-md-12 col-sm-12">
            <div class="chat chat-offline" id="chatbox">waiting for chat to start...</div>
        </div>
    </div>

    <div class="row top-buffer">
        <div class="col-md-12 col-sm-12">
            <div class="standard-width">
                <div class="input-group">
                    <input type="text" class="form-control" id="postChatInput" placeholder="login to leave a comment..."
                           style="width: 440px" disabled>
                    <span style="padding-left: 35px;"></span>
                    <button id="signinButton" class="btn btn-secondary">Sign in with Google <i id="signinSpinner"
                                                                                               class="fa fa-refresh"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row top-buffer">
        <div class="col-md-12 col-sm-12">
            <div class="user-placement">
                <div class="dropdown" id="dropdown-menu-div" style="z-index: 999999;">
                    <button class="dropdown-toggle" type="button" data-toggle=""
                            id="userSelect"><span
                                id="select-text">Select User</span>
                        <span class="caret"></span></button>
                    <span id="select-status"></span>
                    <ul class="dropdown-menu dropdown-menu-xtra" id="dropdown-menu" role="menu">
                    </ul>
                </div>
                <div class="chat" id="userMessages"></div>
            </div>
        </div>
    </div>

</div>
<script>
    $('#signinButton').click(function () {

        try {

            gapiPromise.then(function () {

            });

            authInited.then(function () {
                auth2.grantOfflineAccess().then(signInCallback);
            });

        } catch (err) {

        }

    });
</script>
<script src="{{asset('js/callback.js')}}"></script>
</body>
<footer>
    <div class="row top-buffer">
    </div>
</footer>
</html>