<?php
define('ROOT', __DIR__);


use Login\App;
use \Login\Guia\Divers;
use Login\Guia\Galeria;
use \Login\Guia\Hastag;

require '../vendor/autoload.php';
$auth = App::getAuth();
App::getAuth()->restrict();

//////////////////
$pages = new Divers();
$my_save_dir = '../images_guia/';
$hast = new Hastag();
$galeria = new Galeria();

$perPage = 25;
$public = 0;
$total = $pages->total($public);
$nbPages = $pages->nb_Pages($total, $perPage);

if (isset($_GET['p'])  && $_GET['p'] > 0 && $_GET['p'] <= $nbPages) {
  $Cpage = $_GET['p'];
} else {
  $Cpage = 1;
}

$r = $pages->articles($perPage, $Cpage, $public);

/// paginate_num($item_per_page, $current_page, $total_records, $total_pages, $page_url)
///include header //
include 'inc/header.php'
?>

<body>
  <div class="container">
    <?php
    echo " <h3>Nº de fotos  não publicadas-$total ; </h3>"; ?>
    <p><?= $pages->paginate($nbPages, $Cpage, "rascunhos.php"); ?></p>

    <hr>
    <ol id="dados" class="list-unstyled">
      <?php  foreach($r as $req)  : ?>
        <?php $history_galeria = $galeria->history_index($req->id); ?>
        <?php $m =  $my_save_dir . 'pequena' . $req->id . '.jpg';
        $mini = file_exists($m) ? '<img class="media-object" src=' . $m . '>' : '<img class="media-object"  src="default.jpg">'; ?>


        <li class="media" id="<?= $req->id; ?>">
          <div class="mr-3">
            <?= $mini; ?>
          </div>
          <div class="media-body">
            <p class="media-heading"><strong><?= $req->title; ?></strong> //<a href="edite.php?id=<?= $req->id; ?>">Editar</a><br><small><?= date_format(date_create($req->time),"d-m-Y H:i:s");?></small> </p>
          
            <p> <?= $hast->convertHashtags($req->message, "h.php"); ?> </p>

          </div>
          <?php echo  count($history_galeria) > 0 ? '<p> Eliminar primeiro  as fotos do historico !</p>' : ' <p class="delete">REMOVER </p>';?>

        </li>

      <?php endforeach; ?>
    </ol>
    <nav>
     
      <?= $pages->paginate_num($Cpage, $total,$nbPages, "rascunhos.php"); ?>
    </nav>
    
  </div>
  <?php include 'inc/footer.php'; ?>