<?php


use \Login\App;
use \Login\Guia\Divers;
use \Login\Guia\Hastag;
use Login\Guia\Galeria;

require '../vendor/autoload.php';

App::getAuth()->restrict();
///////////////
$pages = new Divers();
$my_save_dir = '../images_guia/';
$hast = new Hastag();
$galeria = new Galeria();
$search = (isset($_GET['search']) && !empty($_GET['search'])) ? $_GET['search'] : header("location:public.php");
$perPage = 25;
$public = $_GET['public'];
$total = $pages->total($public, $search);
$nbPages = $pages->nb_Pages($total, $perPage);
$pub = $pages->publicado($public);

if (isset($_GET['p'])  && $_GET['p'] > 0 && $_GET['p'] <= $nbPages) {
  $Cpage = $_GET['p'];
} else {
  $Cpage = 1;
}

$result = $pages->cherche($search, $perPage, $Cpage, $public);

//////include header //
include 'inc/header.php'

?>

<body>
  <form class="form-inline" action="edite.php" method="get">
    <input name="id" class="form-control mr-sm-2" type="search" placeholder="Pesquisa id" aria-label="Search">
    <button class="btn btn-outline my-2 my-sm-0" type="submit">OK</button>
  </form>
  <?php echo " <h3>($search)  Nº de fotos $pub -$total</h3>"; ?>

  <hr>
  <ol id="dados" class="list-unstyled">
    <?php foreach ($result as $req) : ?>
      <?php $history_galeria = $galeria->history_index($req->id); ?>
      <?php $m =  $my_save_dir . 'pequena' . $req->id . '.jpg';
      $mini = file_exists($m) ? '<img class="media-object" src=' . $m . '>' : '<img class="media-object"  src="default.jpg">'; ?>
      <li class="media" id="<?= $req->id; ?>">
        <div class="mr-3">
          <?= $mini; ?>
        </div>
        <div class="media-body">
          <p class="media-heading"><strong><?= $req->title; ?></strong> //<a href="edite.php?id=<?= $req->id; ?>">Editar</a><br><small><?= date_format(date_create($req->time),"d-m-Y H:i:s"); ?></small></p>

          <p> <?= $hast->convertHashtags($req->message, "h.php"); ?> </p>

        </div>
        <?php echo  count($history_galeria) > 0 ? '<p> Eliminar primeiro  as fotos do historico !</p>' : ' <p class="delete">REMOVER </p>';?>
      </li>

    <?php endforeach; ?>
  </ol>
  <nav>
    <?php
    echo '<p class="text-center"> Pages ' . $Cpage . ' / ' . $nbPages . ' </p> ';
    if ($Cpage < $nbPages) {
      echo ' <h3 class="text-center"><a class="pag-next" href="cherche.php?p=' . ($Cpage + 1) . '&search=' . $search . '&public=' . $public . '"> Seguinte »</a></h3>';
    }
    if ($Cpage >= 2) {
      echo ' <h3 class="text-center"><a class="pag-next" href="cherche.php?p=' . ($Cpage - 1) . '&search=' . $search . '&public=' . $public . '"> « Precedente</a></h3>';
    }
    ?>
  </nav>
  <hr>

  <?php include 'inc/footer.php'; ?>