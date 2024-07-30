function initializePlayer(mainMpdUrl, adMpdUrl, adPlaytimes,elementId) {
    var video = document.getElementById(elementId);
    var player = dashjs.MediaPlayer().create();
    var isAdPlaying = false;
    var adTimeIndex = 0;
    var mainContentTime = 0;

    player.initialize(video, mainMpdUrl, true);

    player.on(dashjs.MediaPlayer.events.PLAYBACK_TIME_UPDATED, function (e) {
        var currentTime = e.time;

        // Check if the current time is within any ad playtime
        if (!isAdPlaying && adTimeIndex < adPlaytimes.length &&
            currentTime >= adPlaytimes[adTimeIndex].start &&
            currentTime < adPlaytimes[adTimeIndex].end) {

            playAd();
        }
    });

    function playAd() {
        isAdPlaying = true;
        mainContentTime = player.time();
        player.pause();

        // Attach the ad source and play it
        player.attachSource(adMpdUrl);
        player.on(dashjs.MediaPlayer.events.STREAM_INITIALIZED, startAdPlayback);
    }

    function startAdPlayback() {
        player.play();
        player.off(dashjs.MediaPlayer.events.STREAM_INITIALIZED, startAdPlayback);

        // Listen for the ad playback end event
        player.on(dashjs.MediaPlayer.events.PLAYBACK_ENDED, handleAdEnded);
    }

    function handleAdEnded() {
        if (isAdPlaying) {
            isAdPlaying = false;
            player.off(dashjs.MediaPlayer.events.PLAYBACK_ENDED, handleAdEnded); // Unsubscribe from the ad end event

            // Switch back to the main content
            player.attachSource(mainMpdUrl);
            player.on(dashjs.MediaPlayer.events.STREAM_INITIALIZED, resumeMainContent);
        }
    }

    function resumeMainContent() {
        player.seek(mainContentTime); // Seek to the saved main content time
        player.play();
        player.off(dashjs.MediaPlayer.events.STREAM_INITIALIZED, resumeMainContent);
        adTimeIndex++;
    }

    player.on(dashjs.MediaPlayer.events.PLAYBACK_ENDED, function () {
        if (!isAdPlaying) {
            player.reset();
        }
    });

    player.on(dashjs.MediaPlayer.events.STREAM_INITIALIZED, function () {
        if (isAdPlaying) {
            player.play();
        }
    });
    
}
