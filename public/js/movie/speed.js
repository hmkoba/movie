function speed(n){
    var video = document.getElementById("movie_play");
    video.playbackRate = n;

    var speed = document.getElementById("speed");
    speed.innerHTML = "x" + n;
}
