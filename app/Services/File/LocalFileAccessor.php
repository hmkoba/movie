<?php namespace App\Services\File;
use Input;
use File;

class LocalFileAccessor {

    private $base_dir = null;

    public function __construct($base_dir = null)
    {
        $this->base_dir = $base_dir;
    }

    public function getParentDirectory($dir)
    {
        if (!isset($dir) || $dir === null ||$dir === '' || $dir === '/' || $dir === $this->base_dir) {
            return null;
        }
        return array(
                    'path' => $this->getParamPath(dirname($this->getRealPath($dir))),
                    'basename' => $dir,
                );

    }

    public function getDirectories($dir)
    {
        $dir_list = array();
        foreach (File::directories($this->getRealPath($dir)) as $dir) {
            $dir_list[] = array(
                'path' => $this->getParamPath($dir),
                'basename' => basename($dir),
            );  
        }        
        return $dir_list;
    }

    public function getFiles($dir, $extension = null)
    {
        // ファイルリスト
        $file_list = array();
        foreach (File::files($this->getRealPath($dir)) as $file) {
            $fileinfo = pathinfo($file);
            if(!is_array($extension) || in_array($fileinfo['extension'], $extension, true)) {
                $file_list[] = array(
                    'path' => self::getParamPath($file),
                    'extension' => $fileinfo['extension'],
                    'basename' => $fileinfo['basename'],
                    'filename' => $fileinfo['filename'],
                );  
            }
        }
        return $file_list;
    }

    public function getFileSystemPathname($dir)
    {
        return $this->getRealPath($dir);
    }


    private function getRealPath($path)
    {
        return $this->base_dir . $path;
    }
    private function getParamPath($path)
    {
        return str_replace($this->base_dir, '', $path);
    }
}
