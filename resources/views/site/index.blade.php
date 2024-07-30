@extends('layouts.site')
@section('content')
<center>
    <h3>Media Server</h3>
    <script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>
    <script src="https://cdn.dashjs.org/latest/dash.ads.min.js"></script>
    <script src="{{asset('js/videoplayer.js')}}"></script> <!-- Path to your JS file -->
    <video id="videoPlayer" controls></video>

    <script>
        // Parameters for the video player
        var mainMpdUrl = 'http://localhost:2222/storage/org1/mpd/381_input.mpd';
        var adMpdUrl = 'http://localhost:2222/storage/org1/mpd1/515_ad.mpd';
        var adPlaytimes = JSON.parse('{!! json_encode($params) !!}');
        initializePlayer(mainMpdUrl, adMpdUrl, adPlaytimes,"videoPlayer");
    </script>
</center>



@stop