@extends('movie/movie')

@section('content')
<h1>{{$title}}</h1>
<table class="movie"><tr>
<td class="video">
<div>
    <video id="movie_play" width="100%" preload="auto" controls>
        <source src="./proxy?fp={{$path}}{{$params or ''}}" onclick="this.play();">
    </video>
</div>
</td>
<td class="func">
<div>
    <h2 width="20px" id="speed">x1</h2>
    <p><button class="speed" onclick="speed(1.0)">等倍</button></p>
    <p><button class="speed" onclick="speed(1.3)">1.3倍</button></p>
    <p><button class="speed" onclick="speed(1.5)">1.5倍</button></p>
    <p><button class="speed" onclick="speed(2)">2倍</button></p>
</div>
</td>
</tr></table>

<script type="text/javascript" src="/js/movie/speed.js"></script>
@endsection
