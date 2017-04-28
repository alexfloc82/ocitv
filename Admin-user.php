<!DOCTYPE html>
<html lang="en" ng-app="userApp">
    <head>
        <title>Ocitv - Admin usuario</title>
        <?php include "head.php" ?>
        <script src="script/users.js"></script>
        <script>
            updatePass= function(id){
                var object = {"$set" : { "pass" : "a9c5c54a0bed5ecd0340dbc718225efc" } };
      
                $.ajax({ url: "https://api.mlab.com/api/1/databases/ocitv/collections/Users/"+id+"?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T",
                    data: JSON.stringify(object),
                    type: "PUT",
                    contentType: "application/json",
                    success: function(){
                        alertm("Contraseña cambiada con exito","alert-success"); 
                    },
                    error: function (xhr, status, error) { alertm("No ha sido posible actualizar la contraseña","alert-warning");}
                });    
            }
            
            
           

        </script>
    </head>
    <body>
        <?php include "header.php" ?>
        <input type="hidden" id="user" value="<?php echo $_SESSION["user"]; ?>" />   
        <div class="container-fluid text-center" ng-controller="UserController as control">
            <div class="row content"> 
                <div class="col-sm-2 sidenav">
                    <form id="searchForm" action="javascript:search();" >
                        <div class="form-group" style="text-align:left;">
                            <label>Perfil:</label>
                            <select name="perfil" class="form-control" ng-model="control.query.perfil">
                                <option></option>
                                <option ng-repeat="(key,value) in control.combos.perfil" value="{{key}}">{{value}}</option>
                            </select>
                        </div>
                        <button type="submit"  class="btn btn-info" ng-click="control.search()">Buscar</Button>
                                </form>
                                <!--<p><a href="Admin-label.php">Valores</a></p>-->
                            </div>
                            <div class="col-sm-8 text-left" ng-controller="UserListController as Users">
                                <div id="message"></div>
                                <h1>Usuarios
                                    <div class="btn-group pull-right" role="group">
                                        <button type="button" class="btn btn-success" ng-click="Users.crearUser()">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"> Nuevo</span>
                                        </button>
                                    </div>
                                </h1>
                                <!-- Order -->
                                <div class="col-sm-4">
                                    <select id="order" class="form-control" ng-model="control.orderby" ng-change="control.order()">
                                        <option class="order" value="user">Usuario Ascendente</option>
                                        <option class="order" value="userd">Usuario Descendente</option>
                                    </select>
                                </div>

                                <!-- Pagination -->
                                <div class="col-sm-8">
                                    <nav aria-label="Page navigation" class="pull-right">
                                        <ul class="pagination" id="pagination" style="margin:0;">
                                            <li ng-repeat="n in [] | range:control.numPage" ng-class='{"active":n==control.currPage}'><a href="#" ng-click="control.setPage(n)">{{n+1}}</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <table class="table table-striped" id="usertable">
                                    <thead> <tr> <th>Usuario</th> <th>Nombre</th> <th>Email</th> <th>Perfil</th> <th></th></tr> </thead> 
                                    <tbody id="userTableBody">
                                        <tr ng-repeat="(key, user) in users">
                                            <td><a href="#" ng-click="Users.editUser(key,'Guardar')">{{user.user}}</a></td> 
                                            <td>{{user.name}}</td>  
                                            <td>{{user.email}}</td>
                                            <td>{{control.combos.perfil[user.perfil]}}</td>
                                            <td>
                                                <div class="btn-group pull-right">
                                                    <button type="button" class="btn btn-info" ng-click="Users.updatePass(user)"><span class="glyphicon glyphicon-wrench"></span></button>
                                                    <button type="button" class="btn btn-danger" ng-click="Users.editUser(key,'Eliminar')"><span class="glyphicon glyphicon-trash"></span></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>

                                <!-- Modal -->
                                <div id="Modal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <form class="form" id="Form" data-collection="Users" data-action="modify">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" onclick="javascript:closeModal()">
                                                        &times;</button>
                                                    <h4 class="modal-title">
                                                        User</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- poner el formulario Aqui--> 
                                                    <div class="form-group">    
                                                        <label>
                                                            <b>Usuario</b></label>
                                                        <input type="text" placeholder="Identificador" class="form-control"  ng-model="selectedUser.user" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>
                                                            <b>Nombre</b></label>
                                                        <input type="text" placeholder="Nombre y appelidos" class="form-control" ng-model="selectedUser.name" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>
                                                            <b>Email</b></label>
                                                        <input type="email" placeholder="Enter Email" class="form-control"  ng-model="selectedUser.email" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>
                                                            <b>Perfil</b></label>
                                                        <select ng-model="selectedUser.perfil" class="form-control" >
                                                            <option></option>
                                                            <option ng-repeat="(key,value) in control.combos.perfil" value="{{key}}">{{value}}</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </form>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal" ng-click="Users.executeUser()" >{{Users.action}}</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:closeModal()">
                                                    Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script src="script/users.js"></script>
                            <div class="col-sm-2 sidenav">
                                <?php include "leftcol.php" ?>
                            </div>
                            </div>
                            </div>

                            <?php include "footer.php" ?>


                            </body>
                            </html>