<?php

use \Login\App;
use \Login\Session;

require '../vendor/autoload.php';
App::getAuth()->logout();
Session::getInstance()->setFlash('success', 'Vous êtes maintenant déconnecté');
App::redirect('login.php');