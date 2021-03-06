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
App::getAuth()->restrict();
///////////////
$pages= new Divers();
$my_save_dir = '../images_guia/';
$hast = new Hastag();
$perPage=25;
$public= 1;
$total = $pages->total ($db,$public);
$nbPages= $pages->nb_Pages($total,$perPage);

if (isset($_GET['p'])  && $_GET['p']>0 && $_GET['p']<=$nbPages ) {
	 $Cpage= $_GET['p'];
	}else {$Cpage = 1;}
	  
$r= $pages->articles ($db,$perPage,$Cpage,$public);

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
<?php  while( $req = $r->fetch(PDO::FETCH_OBJ) ) : ?>
<?php  $m=  $my_save_dir.'pequena'.$req->id.'.jpg';
 $mini= file_exists($m) ? '<img class="media-object" src='.$m.'>': '<img class="media-object"  src="default.jpg">';?>
 <li class="media" id="<?=$req->id;?>" >
 <div class="mr-3">
<?=$mini;?>
 </div>
 <div class="media-body">
    <p class="media-heading"><strong><?=$req->title;?></strong> /<a href="edite.php?id=<?=$req->id;?>">/Editar</a></p>
 
   <p> <?=$hast->convertHashtags($req->message,"h.php");?> </p>
 
  </div>
   <p class="delete"> REMOVER</p>
   </li>
   
 <?php  endwhile;?>
 </ol>
 <nav>
 <?=$pages-> paginate_num($perPage, $Cpage, $total, $nbPages, "public.php");?>
</nav>
<hr>

<?php include 'inc/footer.php';?>