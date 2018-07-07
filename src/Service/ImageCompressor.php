<?php

namespace App\Service;
use Gumlet\ImageResize;
// https://github.com/gumlet/php-image-resize

class ImageCompressor {

    private $thumbnailSize = 240;

    public function compressImage($source, $destination, $quality){
        $info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
    }

    public function resizeImage($filePath, $fileName){ 
        $image = new ImageResize($filePath . $fileName);
        $image->resize(1600, 900, $allow_enlarge = True);
        $image->save($filePath . $fileName);
        return $filePath;
    }

    public function createThumbnail($filePath, $fileName){
        $image = new ImageResize($filePath . $fileName);
        $image->resize(400, 225, $allow_enlarge = True);
        $generatedFileName = "min_" . $fileName;
        $image->save($filePath . $generatedFileName);
        return $generatedFileName;
        
    }

    public function resizeThumbnail($file){
        $image = new ImageResize($file);
        $image->resize(400, 225, $allow_enlarge = True);
        $image->save($file);
    }
}