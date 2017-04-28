<?php
//////initialize the session
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ocitv - Cambio contraseña</title>
        <?php include "head.php"; ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core-min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/md5.js"></script>
        <script>
  
            updatePass= function(){
                var pass = CryptoJS.MD5($('input[name="pass"]')[0].value).toString();
                var cpass = CryptoJS.MD5($('input[name="pass-confirm"]')[0].value).toString();
                if(pass == cpass){
                    var object = {"$set" : { "pass" : pass } };
                    var query = {"user":"<?php echo $_SESSION["user"]; ?>"}
      
                    $.ajax({ url: "https://api.mlab.com/api/1/databases/ocitv/collections/Users?q="+JSON.stringify(query)+"&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T",
                        data: JSON.stringify(object),
                        type: "PUT",
                        contentType: "application/json",
                        success: function(){
                            window.location = "Tareas.php";
                        },
                        error: function (xhr, status, error) { alert("No ha sido posible actualizar la contraseña","alert-danger");}
                    });
                }
                else{
                    alertm("Las contraseñas no coinciden","alert-danger");
                }
      
      
            }
  
        </script>
    </head>
    <body>
        <?php include "header.php" ?>   
        <div class="container-fluid text-center">
            <div class="row content"> 
                <div class="col-sm-4">

                </div>
                <div class="col-sm-4 text-left">
                    <div id="message"></div>
                    <h1>Cambiar Contraseña</h1>
                    <form class="form-horizontal" action="javascript:updatePass();" >
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Nueva contraseña: </label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="pass">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Confirmar contraseña: </label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="pass-confirm" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-info">Cambiar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include "footer.php" ?>

    </body>
</html>

