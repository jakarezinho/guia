<?php
use \Login\Autoloader;
use \Login\App;
use \Login\Session;
require 'inc/bootstrap.php';
Autoloader::register();
$db = App::getDatabase();

if(App::getAuth()->confirm($db, $_GET['id'], $_GET['token'], Session::getInstance())){
    Session::getInstance()->setFlash('danger', "Votre compte a bien été validé");
    App::redirect('account.php');
}else{
    Session::getInstance()->setFlash('danger', "Ce token n'est plus valide");
    App::redirect('login.php');
}