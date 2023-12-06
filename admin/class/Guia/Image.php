<?php

namespace Login\Guia;

use Login\App;


//// class 
class Image
{

  private $db;
  public function __construct()
  {
    $this->db = App::getDatabase();
  }

  //////// FUNCTION GET GPS LATITUDE LONGITUDE 
  public function getGps($exifCoord, $hemi)
  {

    $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;

    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
  }

  /*function gps2num*/

  private function gps2Num($coordPart)
  {

    $parts = explode('/', $coordPart);

    if (count($parts) <= 0)
      return 0;

    if (count($parts) == 1)
      return $parts[0];

    return floatval($parts[0]) / floatval($parts[1]);
  }




  ///////// FUNCTION EXIF FOTO ////////
  public function exif_foto($foto, $my_save_dir)
  {

    $exif = @exif_read_data($foto);

    if (isset($exif["GPSLongitude"]) && isset($exif['GPSLongitudeRef']) && isset($exif["GPSLatitude"]) && isset($exif['GPSLatitudeRef'])) {
      $lng = $this->getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
      $lat = $this->getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
    }
    if (isset($lat) && isset($lng)) {

      $insert = $this->db->query("INSERT INTO hastag SET lat='$lat', lng='$lng', recomendo='non', public='0',time=NOW()");
      $local_id = $this->db->lastInsertId();

      if ($insert) {
        $resize = new Rezise_w();
        $image_pequena = "pequena" . $local_id . ".jpg";
        $image_grande = "grande" . $local_id . ".jpg";
        $pequena = $resize->ResizImage("jpg", $foto, $my_save_dir, $image_pequena, "400");
        $grande = $resize->ResizImage("jpg", $foto, $my_save_dir, $image_grande, "900");

        $insert_photo = $this->db->query("UPDATE hastag SET foto='$grande', foto_mini='$pequena' WHERE id='$local_id'");
        if ($insert_photo) {

          return [$lat, $lng, $local_id];
        }
        //insert 
      } //LATgit 

    } //exif
  }


  ////////// FUNCTION INSER INFOS 
  public function insert_infos($title, $message, $hastag, $lat, $lng, $id_local)
  {

    $insert_infos = $this->db->query("UPDATE hastag SET title=?, message=?, hastag=?, lat=?, lng=?  WHERE id=?", [$title, $message, $hastag, $lat, $lng, $id_local]);
    if ($insert_infos) {
      return true;
    } else {
      return false;
    }
  }




  //////////////  INSERT FOTO SANS GPS 
  public function insert_sans($foto, $my_save_dir, $local_id)
  {

    $resize = new Rezise_w();
    $image_pequena = "pequena" . $local_id . ".jpg";
    $image_grande = "grande" . $local_id . ".jpg";
    $pequena = $resize->ResizImage("jpg", $foto, $my_save_dir, $image_pequena, "400");
    $grande = $resize->ResizImage("jpg", $foto, $my_save_dir, $image_grande, "900");

    //reduit image
    if ($pequena && $grande) {
      $insert_photo = $this->db->query("UPDATE hastag SET foto='$grande', foto_mini='$pequena' WHERE id='$local_id'");
      if ($insert_photo) {
        return true;
      } else {
        return false;
      }
    } //peque/grande

  }

  ///////////// INSER INFOS SANS EFIX/////////
  public function insert_infos_sans(
    $title,
    $message,
    $hastag,
    $lat,
    $lng,
    $recomendo,
    $public
  ) {

    $insert_sans = $this->db->query("INSERT INTO hastag SET title=?, message=?, hastag=?,  lat=?, lng=?, recomendo=?, public=?,time=NOW()", [$title, $message, $hastag, $lat, $lng,  $recomendo, $public]);

    if ($insert_sans) {
      return $this->db->lastInsertId();
    } else {
      return false;
    }
  }



  //////////VALID IMAGE 
  public function valid($filde)
  {

    if (isset($filde) || !empty($filde)) {
      return true;
    } else {
      return false;
    }
  }


  //////////EDITE UPDATE INFOS /////////
  public function update_infos($title, $message, $hastag,  $lat, $lng, $recomendo, $public, $id)
  {

    $update = $this->db->query("UPDATE   hastag SET title=?, message=?, hastag=?, lat=?, lng=?, recomendo=?, public=? WHERE id=?", [$title, $message, $hastag,  $lat, $lng, $recomendo, $public, $id]);

    if ($update) {
      return true;
    } else {
      return false;
    }
  }

  /////////// DELETE /////////
  public function delete($id)
  {

    $result = $this->db->query("DELETE from hastag WHERE id=?", [$id]);
    return  $result = true  ? true : false;
  }


  //////// DELETE FROM EDITE PAGE
  public function delete_from_edit($id, $my_save_dir, $token)
  {
    if ($_SESSION['csrf_token'] != $token) {
      return false;
    }
    $grande = $my_save_dir . 'grande' . $id . '.jpg';
    $pequena = $my_save_dir . 'pequena' . $id . '.jpg';
    $result = $this->delete($id);
    if (file_exists($grande) && file_exists($pequena) && $result) {
      unlink($grande);
      unlink($pequena);
      clearstatcache();
      return true;
    }
  }
}///
