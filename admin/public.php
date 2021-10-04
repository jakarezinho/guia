<?php 

use Login\App;

use \Login\Guia\Divers;
use \Login\Guia\Hastag;
use Login\Guia\Galeria;
require '../vendor/autoload.php';


$auth = App::getAuth();
App::getAuth()->restrict();
///////////////
$pages= new Divers();
$my_save_dir = '../images_guia/';
$galeria = new Galeria();
$hast = new Hastag();
$perPage=25;
$public= 1;
$total = $pages->total ($public);
$nbPages= $pages->nb_Pages($total,$perPage);

if (isset($_GET['p'])  && $_GET['p']>0 && $_GET['p']<=$nbPages ) {
	 $Cpage= $_GET['p'];
	}else {$Cpage = 1;}
	  
$r= $pages->articles ($perPage,$Cpage,$public);

//////include header // INDEX PUBLIC 
include 'inc/header.php'

?>

<body>
<form class="form-inline" action="edite.php" method="get" >
    <input  name="id" class="form-control mr-sm-2" type="search" placeholder="Pesquisa id" aria-label="Search">
    <button class="btn btn-outline my-2 my-sm-0" type="submit">OK</button>
  </form>
<?php 
echo " <h3>Nº de fotos publicadas-$total ; </h3>";?>

<hr>
 <ol id="dados" class="list-unstyled">
<?php  foreach($r as $req) : ?>
  <?php $history_galeria = $galeria->history_index($req->id); 
  $date=date_create($req->time);

  ?>
<?php  $m=  $my_save_dir.'pequena'.$req->id.'.jpg';
 $mini= file_exists($m) ? '<img class="media-object" src='.$m.'>': '<img class="media-object"  src="default.jpg">';?>
 <li class="media" id="<?=$req->id;?>" >
 <div class="mr-3">
<?=$mini;?>
 </div>
 <div class="media-body">
    <p class="media-heading"><strong><?=$req->title;?></strong> //<a href="edite.php?id=<?=$req->id;?>">Editar</a><br><small><?=date_format(date_create($req->time),"d-m-Y H:i:s");  ?></small> </p>
 
   <p> <?=$hast->convertHashtags($req->message,"h.php");?> </p>
 
  </div>
  <?php echo  count($history_galeria) > 0 ? '<p> Eliminar primeiro  as fotos do historico !</p>' : ' <p class="delete">REMOVER </p>';?>
   </li>
   
 <?php  endforeach;?>
 </ol>
 <nav>
 <?=$pages-> paginate_num($Cpage, $total,$nbPages, "public.php");?>

</nav>



<?php include 'inc/footer.php';?>