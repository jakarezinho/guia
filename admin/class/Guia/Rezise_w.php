<?php

namespace Login\Guia;

class Rezise_w
{


    public function ResizImage($ext, $uploadedfile, $path, $actual_image_name, $newwidth)
    {

        if ($ext == "jpg" || $ext == "jpeg") {
            $src = imagecreatefromjpeg($uploadedfile);
        } else if ($ext == "png") {
            $src = imagecreatefrompng($uploadedfile);
        } else if ($ext == "gif") {
            $src = imagecreatefromgif($uploadedfile);
        } else {
            $src = imagecreatefrombmp($uploadedfile);
        }

        if (function_exists('exif_read_data')) {
          $exif = @exif_read_data($uploadedfile);
            if($exif && isset($exif['Orientation'])) {
        
        $orientation = $exif['Orientation'];
            }
        }

        list($width, $height) = getimagesize($uploadedfile);
        $newheight = (int) round(($height / $width) * $newwidth);
        //$int_value = (int) round($float_value);

        $tmp = imagecreatetruecolor($newwidth, $newheight);

        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        if (isset($orientation) && $orientation != 1) {
            switch ($orientation) {
                case 3:
                    $tmp = imagerotate($tmp, 180, 0);
                    break;
                case 6:
                    $tmp = imagerotate($tmp, 270, 0);
                    break;
                case 8:
                    $tmp = imagerotate($tmp, 90, 0);
                    break;
            }
        }
        $filename = $path . $actual_image_name;

        //PixelSize_TimeStamp.jpg
        //$filename = $path.$newwidth.'_'.$actual_image_name; 
        imagejpeg($tmp, $filename, 100);

        imagedestroy($tmp);
        return $actual_image_name;
    }
}///
