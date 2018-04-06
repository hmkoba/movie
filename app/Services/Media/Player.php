<?php namespace App\Services\Media;
use App\Services\LocalFileAccessor;
use Input;
use File;

class Player {
    const BUFFER_SIZE = 102400;

    public function __construct()
    {
    }

    static public function play($path, $content_type = 'video/mp4')
    {
        $length = $size = filesize($path);
        $etag = md5($_SERVER["REQUEST_URI"]).$size;

        $fp = fopen($path,"rb");

        // 一回目のアクセス
        if(!@$_SERVER["HTTP_RANGE"]){

            // HTTP_RANGE(部分リクエスト)に対応していると伝える
            header("Accept-Ranges: bytes");
            header("Content-Type: " . $content_type);

        // ブラウザがHTTP_RANGEを要求してきた場合
        }else{

            header("HTTP/1.1 206 Partial Content"); header("Accept-Ranges: bytes");
            header("Content-Type: " . $content_type);

            // 要求された開始位置と終了位置を取得
            list($start,$end) = sscanf($_SERVER["HTTP_RANGE"],"bytes=%d-%d");

            // コンテンツ範囲: 開始位置-終了位置/ファイル総サイズ
            $range = sprintf("bytes %d-%d/%d",$start,$end,$size);
            header("Content-Range: {$range}");

            // コンテンツ長: 終了位置 - 開始位置 + 1
            $length = $end - $start + 1;

            // ファイルポインタを開始位置まで移動
            fseek($fp,$start);
        }

        header("Content-Length: {$length}");
        header("Etag: \"{$etag}\"");

        // ファイルポインタの開始位置からコンテンツ長だけ出力
        $rem = $length;
        $buff = self::BUFFER_SIZE;

        while(!feof($fp) and (connection_status() == 0)) {
            if($rem > $buff) {
                echo fread($fp, $buff);
                $rem = $rem - $buff;
            } else {
                echo fread($fp, $rem);
            }
        }
        fclose($fp);

        return;
    }
}
