<?php
use \Login\Autoloader;
use \Login\App;
use \Login\Session;
use \Login\Guia\Divers;
use \Login\Guia\Hastag;
require 'inc/bootstrap.php';
Autoloader::register();
$auth = App::getAuth();
$db = App::getDatabase();
////
 $id= isset($_POST['id'])? $_POST['id']: die('error');
$my_save_dir = '../images_guia/';
 $grande= $my_save_dir.'grande'.$id.'.jpg';
 $pequena=$my_save_dir.'pequena'.$id.'.jpg';
 $secure = md5(time()+rand());

				if ( !empty($id) && isset($secure) && $secure == $secure && isset($_POST['acao']) && $_POST['acao']=='removeAnexo'){ 
				$result= $db->query("DELETE from hastag WHERE id= '$id'");

  if ($result) {echo 1;} elseif (!$result){echo 0;}
  //efface image
  if (file_exists($grande)&& file_exists($pequena)&& $result) {
    unlink($grande); 
	unlink($pequena);
	clearstatcache(); 
   }
}
	?>






