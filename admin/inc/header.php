<?php use \Login\Session;?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="">

  <title>Pequeno eut</title>
  <meta name="robots" content="noindex,nofollow">
  <!-- Bootstrap core CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="css/style.css">

  <!-- Load Leaflet from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>


  <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@2.4.1/dist/esri-leaflet.js"
    integrity="sha512-xY2smLIHKirD03vHKDJ2u4pqeHA7OQZZ27EjtqmuhDguxiUvdsOuXMwkg16PQrm9cgTmXtoxA6kwr8KBy3cdcw=="
    crossorigin=""></script>


  <!-- Load Esri Leaflet Geocoder from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
    integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
    crossorigin=""></script>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="#">PEQUENO.EU</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse my-2"  id="navbarNav">
    <ul class="navbar-nav mr-auto ">
        <?php if (isset($_SESSION['auth'])): ?>
         <li>  <form class="form-inline" method="get" action="cherche.php"  >
    <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search" aria-label="Search">
     <select class="form-control mr-sm-2" name="public">
  <option selected="1" value="1"> publicado</option>
  <option value="0">Rascunho</option>}

</select>
    <button class="btn btn-outline my-2 my-sm-0" type="submit">Search</button>
  </form></li>
          <li><a class="nav-link" href="logout.php">logout</a></li>
           <li><a class="nav-link" href="account.php">conta</a></li>
        <?php else: ?>  
          <li ><a class="nav-link" href="register.php">S'inscrire</a></li>
          <li><a class="nav-link" href="login.php">login</a></li>
        <?php endif; ?>
      </ul>
      <ul class=" navbar-nav list-inline my-2 my-lg-0">
       <?php if (isset($_SESSION['auth'])): ?>
        <li><a class="nav-link" href="public.php">Public</a></li>
        <li><a class="nav-link" href="rascunhos.php">Rascunhos</a></li>
      <?php endif; ?>  
      <li class="list-inline-item"><a class="nav-link" href="index.php">photo Gps</a></li>
      <li class="list-inline-item"><a class="nav-link" href="publich_file_sans.php">photo sans</a></li>
      
    </ul>
  </div>
</nav>

<div class="container top_espace">

  <?php if(Session::getInstance()->hasFlashes()): ?>
    <?php foreach(Session::getInstance()->getFlashes() as $type => $message): ?>
      <div class="alert alert-<?= $type; ?>">
        <?= $message; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

