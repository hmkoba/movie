<?php namespace App\Http\Controllers\Movie;
use App\Http\Controllers\Controller;
use App\Services\File\LocalFileAccessor;
use App\Services\Media\Player;;
use Input;
use Redirect;

class MovieController extends Controller {

    const MEDIA_ROOT_PATH = '/mnt/hdd1/共有/アニメ';
    const MEDIA_ROOT_PATH_A = '/mnt/hdd1/共有/A';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * ファイルリストの表示 
	 *
	 * @return Response
	 */
	public function index()
	{
        $path = Input::get('fp');
        $mode = Input::get('m');

        $accessor = self::createLocalFileAccessor($mode);

		return view('movie/index')->with(array(
                    'parent' => $accessor->getParentDirectory($path),
                    'dir_list' => $accessor->getDirectories($path),
                    'file_list' => $accessor->getFiles($path, array('mp4',)),
                    'params' => '&m='.$mode,
                    ));
	}
   
	/**
	 * メディア再生の表示
	 *
	 * @return Response
	 */
    public function play()
    {
        $path = Input::get('fp');
        $mode = Input::get('m');
        if(!isset($path)) {
           return redirect()->action('Movie\MovieController@index');
        }
        $info = pathinfo($path);
        
        return view('movie/play')->with(array(
                    'title' => $info['filename'],
                    'path' => $path,
                    'params' => '&m='.$mode));
    }

	/**
	 * public以外に格納されているメディアファイルの再生
	 *
	 * @return Response
	 */
    public function proxy()
    {
        $path = Input::get('fp');
        $mode = Input::get('m');
        if(!isset($path)) {
            return;
        }
        $accessor = self::createLocalFileAccessor($mode);
        Player::play($accessor->getFileSystemPathname($path));

        return;
    }

    private function createLocalFileAccessor($mode = null)
    {
        return new LocalFileAccessor(
            isset($mode) && $mode == 'A' ? self::MEDIA_ROOT_PATH_A : self::MEDIA_ROOT_PATH);
    }
}
