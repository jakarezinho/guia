<?php 
require 'class/Autoloader.php'; 
use \guia\Autoloader;
use \guia\Mobile_Detect;
use \guia\Divers;
use \guia\Hastag;
use \guia\App;
Autoloader::register(); 
$db= App::getDatabase();
///
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$tail='pequena';

///
$pages= new Divers($db);
$my_save_dir = '../images_guia/';
$hast = new Hastag($db);
///
$hastag = !empty($_GET['hastag'])? $_GET['hastag']: header('Location: index.php');
$refer = isset($_GET['refer'])? $_GET['refer']: false;

$perPage=10;
$total = $pages->total ($hastag,$refer);
$nbPages= $pages->nb_Pages($total,$perPage);

$p =isset($_GET['p'])? $_GET['p']: 0;
$Cpage =$pages->page_page($p,$nbPages);

$notas= $pages->articles($perPage,$Cpage,$hastag,$refer);

/////

$dtc = $pages->detect("hastag",$hastag);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>PICTURE GUIDE - locais com hastags a visitar e fotografar</title>
  <meta name="description" content=" PEQUENO.EU picture guide | dicas de locais a descobrir e a fotografar referênciados com hastagas ">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#ffffff">
  <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="images/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="57x57" href="images/apple-touch-icon-57x57.png">
  <!--//-->

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="css/app.css">


</head>
<body>

 <div id="preloader">
  <div id="status">&nbsp;</div>
</div>
<!--//TOP NAV-->
<?php include 'includes/top-nav.php';?>
<div class="title">
  <h1>Picture Guide  </h1>
  <h2>Pequeno guia  de <?= $nbPages;?> <?php if($nbPages > 1) { echo "paginas"; }else{ echo "pagina";} ?> </h2>
</div>
<?php  if ($nbPages > 0) :?>
  <div class="container top_tag text-center"> 
    <p><span class="badge badge-secondary">#<a href="hastag.php?hastag=<?=$hastag;?>"><?=$hastag; ?></a></span>  <?php if ($refer):?><span class="badge badge-secondary">#<a href="hastag.php?hastag=<?=$refer;?>"><?=$refer; ?></a> </span></p><?php endif;?></p>
    <h3>Hastags relacionados <i class="material-icons">reply</i></h3> 
  </p><ul class="list-inline"><?php $pages->hastag_links('hastag.php',$hastag,$hastag)?></ul>

  <div class="boton_map">
    <p class="text-center"> <i class="material-icons">map</i>
      <?php if ($refer):?>
        <a href="mapa_hastag.php?hastag=<?=$hastag;?>&refer=<?=$refer;?>" > ver no mapa» </a> </p>
        <?php else:?>
         <a  href="mapa_hastag.php?hastag=<?=$hastag;?>">  ver no mapa » </a> 
       <?php endif; /// nav ?>
     </p>
     <?php else:?>
      <h2 class="text-center"> Ops.. sem resultados ;(</h2>
    <?php endif; /// SEM HASTAG ERROR?>
  </div>
  <!--///boton map-->
</div>
<!--//item/-->

<div  class="grid" id="loadHastag" data-hastag="<?=$hastag;?>" data-refer="<?=$refer;?>" data-nbpages="<?=$nbPages;?>">    
 <?php  foreach($notas as $req) :?>
  <?php  $image = $my_save_dir.$tail.$req->id.'.jpg';?>
  <div class="grid-sizer"></div>
  <!--//item/-->
  <div class="grid-item" data-itemId="<?=$req->id;?>">
   <span id="<?=$req->id;?>" class="favOK"> <i class="material-icons">bookmark</i></span>
   <a href="foto.php?id=<?=$req->id;?>" target="_blank"><img src="<?=$image;?>" alt="<?=$req->title;?>" width="100%"/></a>
   <h4><?=$req->title;?></h4>
   <div class="plus"> <?php if ($req->recomendo == "yes"):?><i class=" recomendo material-icons">favorite</i><?php endif;?></div>
   <p><?=$hast->convertHashtags($req->message,"hastag.php");?></p>

  <!-- <p class="samll_loc"><i class="material-icons">location_on</i> <?=$req->morada;?></p>-->
 </div>

<?php endforeach;?>
</div>
<!--//item/-->
<div class="page-load-status text-center">
 <div class="loader infinite-scroll-request">Loading...</div>
 <p class="infinite-scroll-last">End of content</p>
 <p class="infinite-scroll-error">No more pages to load</p>
</div>


<!--nav//-->
<div id="paginate" class="nav" >
  <?php 
  echo '<p class="centre"> Pages '.$Cpage.' / '.$nbPages.' </p> ';
  if ( $Cpage < $nbPages && $refer) {echo '<a class="pag-next" href="hastag.php?p='.($Cpage+1).'&hastag='.$hastag.'&refer='.$refer.'"></a>';}

  elseif ( $Cpage < $nbPages) {echo '<a class="pag-next" href="hastag.php?p='.($Cpage+1).'&hastag='.$hastag.'"></a>';}?>
</div>

<!-- localiza -->
<form action="mapa_env.php" method="post" id="local">
  <input type="hidden" id="lat" name="lat">
  <input type="hidden" id="lng" name="lng">
</form> 

<div id="envia" class="localiza pages_loc"> 
  <i class="material-icons">my_location</i></div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
  <script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.min.js"></script>
  <script src="js/localize.js"> </script>
  <script src="js/app.js"> </script>

  <script>
    
    function loadhastag(){
     let load = document.querySelector("#loadHastag");
     let hastag =load.dataset.hastag
     let refer = load.dataset.refer
     let count = load.dataset.nbpages

     const msnry = new Masonry( '.grid', {
  // Masonry options...
  itemSelector: '.grid-item',
  columnWidth: '.grid-sizer', 
});
     if (count> 1){
// init Infinite Scroll
const infScroll = new InfiniteScroll( '.grid', {
  path: 'hastag.php?p={{#}}&hastag='+hastag+'&refer='+refer,
// path set to string with {{#}}
checkLastPage: '.pag-next',
status: '.page-load-status',
  //hideNav: '#paginate',
  append: '.grid-item',
  outlayer: msnry,
  debug: true,
});

}else{
 document.querySelector('.infinite-scroll-request').classList.add('hidde_nave');
}

}///loadhastag
window.onload = function(){
  loadhastag(); 
  displayFavori()
  favIndex()
  console.log("All resources finished loading!")

}

window.onscroll=function(){
 favIndex()
 displayFavori()
 console.log("scroll")
}
</script>
<?php
include 'includes/footer.php';?>