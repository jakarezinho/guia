<?php

use \Login\Autoloader;
use \Login\App;
use Login\Guia\Image;

require 'inc/bootstrap.php';
require '../vendor/autoload.php';
$auth = App::getAuth();
$image = new Image();
////
$id = isset($_POST['id']) ? $_POST['id'] : die('error');
$my_save_dir = '../images_guia/';
$grande = $my_save_dir . 'grande' . $id . '.jpg';
$pequena = $my_save_dir . 'pequena' . $id . '.jpg';
$secure = md5(time() + rand());

if (!empty($id) && isset($secure) && $secure == $secure && isset($_POST['acao']) && $_POST['acao'] == 'removeAnexo') {
   $result = $image->delete($id);
   echo $result == true ?  1 : 0;
   //efface image
   if (file_exists($grande) && file_exists($pequena) && $result) {
      unlink($grande);
      unlink($pequena);
      clearstatcache();
   }
}
