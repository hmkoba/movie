@extends('movie/movie')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">ディレクトリ</div>
				<div class="panel-body">
                    @if (isset($parent))
                    <p><a href="./movie?fp={{$parent['path']}}{{$params or ''}}">[親ディレクトリに戻る]</a><p>
                    @endif
                @foreach ($dir_list as $dir)
                    <p><a href="./movie?fp={{$dir['path']}}{{$params or ''}}">{{$dir['basename']}}</a><p>
                @endforeach
				</div>
		    </div>
			<div class="panel panel-default">
				<div class="panel-heading">ファイル</div>
				<div class="panel-body">
                @foreach ($file_list as $file)
                    @if ($file['extension'] === 'mp4')
                    <p><a href="./movie/play?fp={{$file['path']}}{{$params or ''}}">{{$file['basename']}}</a><p>
                    @else
                    <p>{{$file['basename']}}<p>
                    @endif
                @endforeach
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
