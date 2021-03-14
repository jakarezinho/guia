<?php
namespace guia;
class Rezise {
	
	
 public function save_image($ext,$uploadedfile, $path,$actual_image_name,$thumbnail_width,$thumbnail_height) { //$imgSrc is a FILE - Returns an image resource.
    //getting the image dimensions 
 
	////
	if($ext=="jpg" || $ext=="jpeg" )
{
$src = imagecreatefromjpeg($uploadedfile);
}
else if($ext=="png")
{
$src = imagecreatefrompng($uploadedfile);
}
else if($ext=="gif")
{
$src = imagecreatefromgif($uploadedfile);
}
else
{
$src = imagecreatefrombmp($uploadedfile);
}

list($width,$height)=getimagesize($uploadedfile);

   list($width_orig, $height_orig) = getimagesize($uploadedfile);  
    //$myImage = imagecreatefromjpeg($imgSrc);
	
	////
	
    $ratio_orig = $width_orig/$height_orig;
   
    if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
       $new_height = $thumbnail_width/$ratio_orig;
       $new_width = $thumbnail_width;
    } else {
       $new_width = $thumbnail_height*$ratio_orig;
       $new_height = $thumbnail_height;
    }
   
    $x_mid = $new_width/2;  //horizontal middle
    $y_mid = $new_height/2; //vertical middle
   
    $process = imagecreatetruecolor(round($new_width), round($new_height));
   
    imagecopyresampled($process, $src, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
    imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

	$filename = $path.$actual_image_name;
	imagejpeg($thumb ,$filename,100);
    imagedestroy($process);
    imagedestroy($src);

    return $actual_image_name;
}
}///

?>