<?php
use \Login\Autoloader;
use \Login\App;
use \Login\Session;
use \Login\Validator;
require 'inc/bootstrap.php';
Autoloader::register();
if(isset($_GET['id']) && isset($_GET['token'])){
    $auth = App::getAuth();
    $db = App::getDatabase();
    $user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);
    if($user){
        if(!empty($_POST)){

            $validator = new Validator($_POST);
            $validator->isConfirmed('password');
            if($validator->isValid()){
                $password = $auth->hashPassword($_POST['password']);
                $db->query('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL WHERE id = ?', [$password, $_GET['id']]);
                $auth->connect($user);
                Session::getInstance()->setFlash('success','Votre mot de passe a bien été modifié');
                App::redirect('account.php');
            }
        }
    }else{
        Session::getInstance()->setFlash('danger',"Ce token n'est pas valide");
        App::redirect('login.php');
    }
}else{
    App::redirect('login.php');
}
?>
<?php require 'inc/header.php'; ?>

    <h1>Réinitialiser mon mot de passe</h1>

    <form action="" method="POST">

        <div class="form-group">
            <label for="">Mot de passe</label>
            <input type="password" name="password" class="form-control"/>
        </div>

        <div class="form-group">
            <label for="">Confirmation du mot de passe</label>
            <input type="password" name="password_confirm" class="form-control"/>
        </div>

        <button type="submit" class="btn btn-primary">Réinitialiser votre mot de passe</button>

    </form>

<?php require 'inc/footer.php'; ?>