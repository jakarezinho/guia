<?php

use \Login\Autoloader;
use \Login\App;
use \Login\Guia\Divers;
use \Login\Guia\Image;
use \Login\Guia\Hastag;

require 'inc/bootstrap.php';
Autoloader::register();
$auth = App::getAuth();
$db = App::getDatabase();
App::getAuth()->restrict();
////

$my_save_dir = '../images_guia/';
$imp = new Image();
$hast = new Hastag();
$pages = new Divers();
///
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $items = $pages->select_id($id)->fetch(PDO::FETCH_OBJ);


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
    $errors['foto'] = "IFOTO COM ERROS!"; //peque/grande
  }
} //FILE1

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
    <h2> spot</h2>
    <form id="spot" method="post" autocomplete="off" enctype="multipart/form-data" action='#'>
      <div class="form-group">
        <label for="name_spot ">Nome do spot</label>
        <input type="text" class="form-control" name="name_spot" id="mame_spot" value="">
      </div>
      <input type="text" name="lat_spot" id="lat_spot" value="<?= $items->lat; ?>">
      <input type="text" name="lng_spot" id="lng_spot" value="<?= $items->lng; ?>">
      <input type="text" name="spot_post_ID" id="spot_post_ID" value="<?= $id; ?>">
    </form>
  </div>

  <?php include 'inc/footer.php'; ?>