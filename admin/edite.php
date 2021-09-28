<?php


use \Login\App;
use \Login\Guia\Image;
use \Login\Guia\Divers;
use \Login\Guia\Hastag;
use Login\Guia\Galeria;

require 'inc/bootstrap.php';
require '../vendor/autoload.php';
$auth = App::getAuth();
App::getAuth()->restrict();
////

$my_save_dir = '../images_guia/';
$my_save_dir_historic = '../history/';
$galeria = new Galeria();
$actual_link = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];


$imp = new Image();
$hast = new Hastag();
$pages = new Divers();
///
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $items = $pages->select_id($id)->fetch(PDO::FETCH_OBJ);
  $history_galeria = $galeria->history_index($id);



  if ($items == false) {
    die('<h2>Informação indesponivel</h2>');
  }
  $image = $my_save_dir . $items->foto_mini;
}; //fin GET

$infos = [];
$errors = [];

//////////////POST  UPDATE INFO /////
if (!empty($_POST['lat']) && !empty($_POST['lng'])) {

  $hastag = $hast->gethashtags($_POST['texte']);
  ///// UPDATE PHOTO INFOS
  $insert = $imp->update_infos(
    $_POST["title"],
    $_POST['texte'],
    $hastag,
    $_POST['lat'],
    $_POST['lng'],
    $_POST['recomendado'],
    $_POST['public'],
    $id
  );


  if ($insert) {
    $infos['base'] = "actgualizado  hastag ok";
  }
}

//// IMAGE SEM GPS  ////
if (isset($_POST['infos']) && !empty($_POST['infos'])) {
  if (!empty($_FILES['photoimg']['tmp_name'])) {
    $local_id = $_POST['infos'];
    $foto = $_FILES['photoimg']['tmp_name'];
    $insert_photo = $imp->insert_sans($foto, '../images_guia/', $local_id);
    if ($insert_photo) {
      $infos['foto'] = "Insertion foto pequena e grande  OK";
    } else {
      $errors['foto'] = "Insertion foto falhou";
    }
  } else {
    $errors['foto'] = "FOTO COM ERROS!"; //peque/grande
  }
} //FILE1

////// FILE HISTORY 
if (isset($_POST['history']) && !empty($_POST['history'])) {
  if (!empty($_FILES['photo_history']['tmp_name'])) {
    $photo_history = $galeria->insert_history($_FILES['photo_history']['tmp_name'], $my_save_dir_historic, $_POST['id_photo']);
    $photo_history = true ?  header('location:'.$actual_link) : $errors['foto'] = " SEM FOTO HISTORY !";
  } else {
    $errors['foto'] = " SEM FOTO HISTORY !";
  }
}


//////FILE DELETE
if(isset($_POST['photo_delete'])&& !empty($_POST['photo_delete'])&& !empty($_POST['pequena'])&& !empty($_POST['grande']))
{
 $delete= $galeria->delete_history($_POST['photo_delete'], $my_save_dir_historic, $_POST['grande'], $_POST['pequena']);
 $delete = true ? header('location:'.$actual_link) : $errors['foto'] = " SEM FOTO HISTORY !";
 
}
/////header///
include 'inc/header.php'
?>

<body>

  <div class="container">
    <h3> Edite foto </h3>
    <hr>
    <?php
    if (!empty($errors)) {
      echo "<div class=' alert alert-danger' <ul>";
      foreach ($errors as $error) {
        echo "<li> $error </li>";
      }
      echo "</ul> </div>";
    }

    if (!empty($infos)) {
      echo "<div class=' alert alert-success' <ul>";
      foreach ($infos as $info) {
        echo "<li> $info </li>";
      }
      echo "</ul> </div>";
    } ?>
    <p>Lat <?= $items->lat; ?> / Lng <?= $items->lng; ?> </p>

    <hr>
    <div class="row">

      <div class="col-xs-12 col-sm-4 col-md-4 fond thumbnail "><img class="img-responsive" src="<?= $image; ?>"></div>
      <div class="col-xs-12 col-sm-8 col-md-8">

        <hr>
        <form role="form" action="#" method="post" id="post_foto">
          <input type="text" name="lat" id="lat" value="<?= $items->lat; ?>">
          <input type="text" name="lng" id="lng" value="<?= $items->lng; ?>">



          <div class="form-group">
            <label for="exampleInputEmail1">Titulo da foto</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="titulo" value="<?= $items->title; ?>">
          </div>
          <div class="form-group">
            <label for="tedxte ">Texto e hastags</label>
            <textarea name="texte" class="form-control" rows="3"><?= $items->message; ?></textarea>
          </div>

          <div class="form-group">
            <select class="form-control" name="public" id="public">
              <label for="public ">publicado</label>
              <option value="<?= $items->public; ?>" selected><?= $pages->publicado($items->public); ?></option>
              <option value="0">Não publicado</option>
              <option value="1">publicado</option>
            </select>
          </div>

          <div class="form-group">
            <label for="recomendado ">Recomendado</label>
            <select class="form-control" name="recomendado" id="recomendado">
              <option value="<?= $items->recomendo; ?>" selected><?= $pages->publicado($items->recomendo); ?></option>
              <option value="non">não recomendado</option>
              <option value="yes">recomendado</option>
            </select>
          </div>
          <hr>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Actualizar nota »</button>
          </div>
        </form>
      </div>
    </div>


    <hr>

    <div class="col-xs-12 col-sm-12 col-md-12 fond">
      <div id="mapa" class="mapa"> loading... </div>
    </div>
    <hr>
    <!--//foto//-->
    <h3> Modificar foto</h3>
    <div class="col-sm-6 col-md-4">
      <div id="output" class="thumbnail"> </div>
    </div>
    <form id="imageform" method="post" autocomplete="off" enctype="multipart/form-data" action='#'>
      Escolher inagem:
      <div class="form-group">
        <input type="file" name="photoimg" id="photoimg" />
      </div>
      <div class="form-group"><button type="submit" class="btn btn-info btn-lg btn-block">Actualizar foto </button></div>

      <input name="infos" type="hidden" class="form-control" id="infos" value="<?= $id; ?>">
    </form>
    <hr>
    <h2> Histórico</h2>
    <div>
      <p>historique</p>
    </div>


    <form id="historic" method="post" autocomplete="off" enctype="multipart/form-data" >
      <div class="form-group">
        <label for="photo_history ">Enviar foto para o historico</label>

        <input type="file" name="photo_history" id="photo_history" />
        <input type="text" name="id_photo" id="id_photo" value="<?= $id ?>">
        <input type="hidden" name="history" id="history" value="history">
      </div>
      <div class="form-group"><button type="submit" class="btn btn-lg btn-block btn-secondary">Foto para o historico</button></div>
    </form>
    <h2> Histórico em photos</h2>
    <div class="row">
    <?php if (count($history_galeria) > 0) : ?>
      <?php foreach ($history_galeria as $item_galerie) : ?>
        
        <div class="col-md-6">
          <hr>
        <p> <img id="asas" src="<?= $my_save_dir_historic . $item_galerie->foto_pequena ?>" width="300px" height="auto"></p>
        <p>Data: <?=$item_galerie->date?></p>
        <form method="post" name="delete" id="delete">
          <input type="hidden" name="photo_delete" id="photo_delete" value="<?= $item_galerie->id ?>">
          <input type="hidden" name="pequena" id="pequena" value="<?= $item_galerie->foto_pequena ?>">
          <input type="hidden" name="grande" id="grande" value="<?= $item_galerie->foto_grande?>">
         
          <input type="submit" class="btn  btn-secondary  " onclick="if (!confirm('Tem a certeza ?')) { return false }" value="Delete">
        </form>
        </div>
        

      <?php endforeach ?>
    <?php endif ?>
    </div>
  </div>

  <?php include 'inc/footer.php'; ?>