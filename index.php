<?php
//////initialize the session
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_POST['user']) && isset($_POST['password'])) {
    $json_data = file_get_contents('https://api.mlab.com/api/1/databases/ocitv/collections/Users?q={"user":"' . $_POST['user'] . '","pass":"' . md5($_POST['password']) . '"}&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T');
    $obj = json_decode($json_data);
    if (count($obj) > 0) {
        $_SESSION['user'] = $obj[0]->user;
        $_SESSION['username'] = $obj[0]->name;
        $_SESSION['perfil'] = $obj[0]->perfil;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ocitv</title>
        <?php include "head.php"; ?>
    </head>
    <body>
        <nav class="navbar navbar-inverse"></nav>
        <?php if (!isset($_SESSION['user'])) { ?>
            <div class="container-fluid text-center">
                <div class="row content"> 
                    <div class="col-sm-4">

                    </div>
                    <div class="col-sm-4 text-left">
                        <div id="message"></div>
                        <h1>Login</h1>
                        <form class="form-horizontal" method="POST" >
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Usuario</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="user" placeholder="Usuario">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Contraseña</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="password" placeholder="Contraseña">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-info">Sign in</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php include "footer.php" ?>

        </body>
    </html>
    <?php
} else {
    header("Location: Tareas.php");
    die();
}
?>
