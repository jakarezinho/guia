<?php 

use \Login\App;

use \Login\Guia\Image;
use \Login\Guia\Hastag;


require '../vendor/autoload.php';
$auth = App::getAuth();
//////////////////
$imp = new Image();

$hast = new Hastag();
///////////IMAGE
$infos= array();
$errors= array();
if (isset($_POST['infos']) && !empty( $_POST['infos'])) {
   $local_id = $_POST['infos'];
  if(!empty( $_FILES['photoimg']['tmp_name'])){
     $foto = $_FILES['photoimg']['tmp_name'];
     $insert_photo=$imp->insert_sans($foto,'../images_guia/',$local_id);
           if ($insert_photo){
          $infos['foto']= "Insertion foto pequena e grande  OK";}else{$errors['foto']="Insertion foto falhou"; $imp->delete($db,$local_id);}
  }else{$errors['foto']="FOTO COM ERROS!"; $imp->delete($local_id);}
	        
	}//FILE
	
///INFO//
if ( !empty( $_POST['title']) ) {
	if ( !empty( $_POST['lat']) && !empty( $_POST['lng'])) {
	$hastag= $hast->gethashtags($_POST['texte']);
	var_dump($hastag);

    $insert_local_ID= $imp->insert_infos_sans($_POST['title'],$_POST['texte'],$hastag,$_POST['lat'],$_POST['lng'],$recomendo='non',$public='0');

    if ($insert_local_ID){ $infos['foto']= "Insertion infos  OK";}

   }else {$errors['infos']= "Algo correu mal tente de novo";}
		
	
}
///include header //
include 'inc/header.php'

?>
<div class="container">

<h3> Enviar foto sem GPS</h3>
 <?php
 if (!empty($errors)){
	echo "<div class=' alert alert-danger' <ul>";
	 foreach ($errors as $error){
		echo "<li> $error </li>";
		
		} 
		echo "</ul> </div>";
	}
 
  if (!empty($infos)){
	echo "<div class=' alert alert-success' <ul>";
	 foreach ($infos as $info){
		echo "<li> $info </li>";
		
		} 
		echo "</ul> </div>";
	}?>
  <?php if (isset($insert_local_ID)):?>  
  <!--//foto//-->
<div class="col-sm-6 col-md-4"> <div id="output" class="thumbnail"> </div></div>
  <form id="imageform" method="post" autocomplete="off" enctype="multipart/form-data" action='#'>
Escolher inagem: 
 <div class="form-group">
 <input type="file" name="photoimg" id="photoimg" onchange="reader(event)" />

</div>
 <div class="form-group"><button type="submit" class="btn btn-primary btn-lg btn-block">ENVIAR FOTO </button></div>

  <input name="infos" type="texte"class="form-control"  id="infos" value="<?=$insert_local_ID;?>">
</form>
<?php else:?>
<hr>
<!--//infos//-->
<form role="form" action="#" method="post" autocomplete="off" id="post_foto">
<input type="text" name="lat" id="lat" value="">
<input type="text" name="lng" id="lng" value="">

<div class="form-group">
    <label for="exampleInputEmail1">Titulo da foto</label>
    <input type="text" class="form-control" id="title"  name ="title" placeholder="titulo">
  </div>

<div class="form-group">
    <label for="Morada">local da foto</label>
    <input type="text" class="form-control" id="localiza"  name ="localiza" placeholder="local" value="">
    <hr>
    <p class="text-center"><input name="verifier" class="btn btn-default  btn-lg btn-block" type="button" id="verifier" value="Verificar no mapa" /></p>
  </div>
  <div class="form-group">
    <label for="texte">Texto e hastags</label>
   <textarea  name="texte" class="form-control" rows="3"></textarea>
  </div>

   <div class="form-group"><button type="submit" class="btn btn-info btn-lg btn-block">Enviar local </button></div>
</form>
<div id="mapa" class="mapa"> Loading...</div>
<hr>

 <?php endif;?>
 </div>

<?php include 'inc/footer.php';?>