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
if (isset($_POST['photo'])) {
	$foto = $_FILES['photoimg']['tmp_name'];
	if(!empty( $_FILES['photoimg']['tmp_name'])){

		$insert_photo= $imp->exif_foto($foto,'../images_guia/');
	     if ($insert_photo){
	     	$infos['foto']= "Insertion foto pequena e grande  OK";
	     	$local_id =$insert_photo[2];
		     }else{$errors['foto']= "A FOTO NÃO TEM  COORDENADAS GPS";}///LAT
	}else{ $errors['foto']= "FOTO COM ERROS...";}

	           }//FILE
	

///INFO//
	if (isset($_POST['infos']) && !empty( $_POST['infos'])) {
		$hastag= $hast->gethashtags($_POST['texte']);
     	$insert_infos =$imp->insert_infos($_POST['title'],$_POST['texte'],$hastag,$_POST['lat'],$_POST['lng'],$_POST['infos']);
     	if ($insert_infos){
     		$infos['foto']= "Insertion infos  OK";}else{ $errors['foto']= "ERRO AO INSERIR FOTO ";}


		}
		//////////
		include 'inc/header.php'
		?>
		<body>
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

				<?php if ( isset($insert_photo)):
				?>
					<h3> Enviar notas</h3>
					<hr>
					<form role="form" action="#" method="post" id="post_foto">
						<input type="text" name="lat" id="lat" value="<?=$insert_photo[0];?>">
						<input type="text" name="lng" id="lng" value="<?=$insert_photo[1];?>">

						<div class="form-group">
							<label for="title1">Titulo da foto </label>   <input type="text" class="form-control" autocomplete="off" id="title"  name ="title" placeholder="titulo">
						</div>

					
						<div class="form-group">
							<label for="texte">Texto e hastags</label>
							<textarea  name="texte" class="form-control" rows="3"></textarea>
						</div>
						<input name="infos" type="texte"class="form-control"  id="infoso" value="<?=$local_id;?>">
						<div class="form-group"><button type="submit" class="btn btn-info btn-lg btn-block">actualizar dados </button></div>
					</form>
					<div id="mapa" class="mapa"> Loading...</div>
					<hr>

				<?php else:?>
					<h3> Enviar foto GPS</h3>
					<hr>
					<div class="col-sm-4"> <div id="output" class="thumbnail"> </div></div>
					<form id="imageform" method="post" autocomplete="off" enctype="multipart/form-data" action='#'>
						Escolher inagem: 
						<div class="form-group">
						<input type="file" name="photoimg" id="photoimg" onchange="reader(event)" />
						</div>
						<div class="form-group"><button type="submit" class="btn btn-primary btn-lg btn-block">ENVIAR FOTO </button></div>

						<input name="photo" type="hidden"class="form-control"  id="photo" value="">
					</form>
					<hr>
				<?php endif;?>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
			<?php include 'inc/footer.php';?>