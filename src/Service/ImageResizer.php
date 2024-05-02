<?php
// src/Service/ImageResizer.php
namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageResizer
{
    public function resize($filePath, $width, $height)
    {
        $imagine = new Imagine();
        $size = new Box($width, $height);

        $imagine->open($filePath)
            ->thumbnail($size, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND)
            ->save($filePath);
    }
}
