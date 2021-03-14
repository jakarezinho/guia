<?php
use \Login\Autoloader;
use \Login\App;
use \Login\Session;
require 'inc/bootstrap.php';
Autoloader::register();
App::getAuth()->logout();
Session::getInstance()->setFlash('success', 'Vous êtes maintenant déconnecté');
App::redirect('login.php');