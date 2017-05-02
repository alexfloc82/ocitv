<!DOCTYPE html>
<html lang="en" ng-app="tareaApp">
    <head>
        <title>Ocitv - Tareas</title>
        <?php include "head.php" ?>
        <script src="script/tareas.js"></script>
        <style>.ui-autocomplete { z-index:2147483647; }
        .modal-lg {width: 80%;}
        </style>
    </head>

    <body>
        <?php include "header.php" ?>
        <input type="hidden" id="user" value="<?php echo $_SESSION["user"]; ?>" />
        <div class="container-fluid text-center" ng-controller="TareaController as control">
            <div class="row content"> 
                <!-- Search control-->
                <div class="col-sm-2 sidenav" >
                    <form id="searchForm"  >
                        <div class="form-group" style="text-align:left;">
                            <label>ID Tarea:</label>
                            <input class="form-control" ng-model="control.query.id_tarea">
                        </div>
                        <div class="form-group" style="text-align:left;">
                            <label>Cadena:</label>
                            <select class="form-control" ng-model="control.query.cadena" >
                                <option></option>
                                <option ng-repeat="(key,value) in control.combos.cadena" value="{{key}}">{{value}}</option>
                            </select>
                        </div>
                        <button type="submit"  class="btn btn-info" ng-click="control.search()">Buscar</Button>
                    </form>
                </div>
                <!-- Main-->
                <div class="col-sm-8 text-left" ng-controller="TareaListController as Tareas">
                    <!-- Page -->
                    <div>
                        <!-- Message container -->
                        <div id="message"></div>
                        <!-- Title -->
                        <h1>Tareas
                            <?php if($_SESSION['perfil']>40){?><div class="btn-group pull-right" role="group">
                                <button type="button" class="btn btn-success" ng-click="Tareas.crearTarea()">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"> Nuevo</span>
                                </button>
                            </div><?php } ?>
                        </h1>
                        <!-- Order -->
                        <div class="col-sm-4">
                            <select id="order" class="form-control" ng-model="control.orderby" ng-change="control.order()">
                                <option class="order" value="dated">Más recien</option>
                                <option class="order" value="date">Más antiguo</option>
                                <option class="order" value="cadena">Cadena Ascendente</option>
                                <option class="order" value="cadenad">Cadena Descendente</option>
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
                        <!-- Table -->
                        <table class="table table-striped">
                            <thead> <tr> <th>ID Tarea</th><th>Semestre</th><th>Cadena</th> <th>Fecha</th><th style="text-align:center;">Analizado</th><th style="text-align:center;">Revisado</th><?php if($_SESSION['perfil']>40){?><th></th><?php } ?></tr> </thead> 
                            <tbody id="infoTableBody">
                                <tr ng-repeat="(key, tarea) in tareas">
                                    <td><a href="#" ng-click="Tareas.editTarea(key,'Guardar')">{{tarea.id_tarea}}</a></td>
                                    <td>{{tarea.semestre}}</td>
                                    <td>{{control.combos.cadena[tarea.cadena]}}</td>
                                    <td>{{tarea.date.$date | date : "dd/MM/yyyy" }}</td>
                                    <td style="text-align:center;"><input type="checkbox" ng-checked="tarea.analizado"  disabled/></td>
                                    <td style="text-align:center;"><input type="checkbox" ng-checked="tarea.revisado"  disabled/></td>
                                    <?php if($_SESSION['perfil']>40){?><td>
                                        <div class="btn-group pull-right">
                                            <button type="button" class="btn btn-danger" ng-click="Tareas.editTarea(key,'Eliminar')" ng-if="!tarea.revisado" >
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        </div><?php } ?>
                                    </td>
                                </tr>
                            </tbody>


                        </table>

                    <!-- Modal -->
                        <div id="Modal" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <form class="form" id="Form" data-collection="piezas" data-action="modify">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" ng-click="Tareas.Cerrar()">
                                                &times;</button>
                                            <h4 class="modal-title">
                                                Tarea {{selectedTarea.id_tarea}}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="messageModal"></div>
                                            <ul class="nav nav-tabs">
                                                <li><a href="#general" data-toggle="tab">General</a></li>
                                                <li><a href="#fichas" data-toggle="tab">Piezas</a></li>
                                            </ul>
                                            <div class="tab-content clearfix" >
                                                <!-- Tab datos Tarea-->
                                                <div id="general" class="tab-pane active">
                                                    <!-- poner el formulario Aqui-->
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-3">Verificado profesor: </label>
                                                        <input type="checkbox" ng-model="selectedTarea.comprobado" ng-disabled="<?php if($_SESSION['perfil']<40){echo "true";}?>"></input>
                                                        
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">ID Tarea:</label>
                                                        <input class="form-control" name="id_tarea" ng-model="selectedTarea.id_tarea" ng-disabled="selectedTarea.comprobado <?php if($_SESSION['perfil']<40){echo "|| true";}?>">
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Semestre:</label>
                                                        <select name="cadena" class="form-control" ng-model="selectedTarea.semestre" ng-disabled="selectedTarea.comprobado <?php if($_SESSION['perfil']<40){echo "|| true";}?>">  
                                                            <option></option>
                                                            <option ng-repeat="(key,value) in control.combos.semestre" value="{{key}}">{{value}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Analista:</label>
                                                        <input class="form-control" name="analista" ng-model="selectedTarea.analista" my-users ng-disabled="selectedTarea.comprobado<?php if($_SESSION['perfil']<40){echo "|| true";}?>">
                                                        <?php if($_SESSION['perfil']>20){?><label><input type="checkbox" ng-model="selectedTarea.analizado" ng-click="Tareas.checkTerminado(selectedTarea.analizado)" ng-disabled="selectedTarea.revisado"> Analizado</label><?php } ?>
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Revisor:</label>
                                                        <input class="form-control" name="revisor" ng-model="selectedTarea.revisor" my-users ng-disabled="selectedTarea.comprobado<?php if($_SESSION['perfil']<40){echo "|| true";}?>">
                                                        <label><input type="checkbox" ng-model="selectedTarea.revisado" ng-disabled="selectedTarea.comprobado"> Revisado</label>
                                                    </div>
                                                    
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Cadena:</label>
                                                        <select name="cadena" class="form-control" ng-model="selectedTarea.cadena" ng-disabled="selectedTarea.comprobado<?php if($_SESSION['perfil']<40){echo "|| true";}?>">
                                                            <option></option>
                                                            <option ng-repeat="(key,value) in control.combos.cadena" value="{{key}}">{{value}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group form-inline" ng-controller="Datepicker">
                                                        <label class="col-sm-2">Fecha:</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" ng-disabled="selectedTarea.revisado" uib-datepicker-popup="{{'dd/MM/yyyy'}}" placeholder="{{selectedTarea.date.$date | date:'dd/MM/yyyy'}}" ng-model="selectedTarea.date.$date" is-open="popup1.opened" datepicker-options="dateOptions" close-text="Cerrar" current-text="Hoy" clear-text="Borrar"/>
                                                            <span class="input-group-btn">
                                                                <button type="button" class="btn btn-default" ng-click="open1()" ng-disabled="selectedTarea.comprobado<?php if($_SESSION['perfil']<40){echo "|| true";}?>"><i class="glyphicon glyphicon-calendar"></i></button>

                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Hora de comienzo:</label>
                                                        <select class="form-control hour" ng-model="selectedTarea.bh" ng-disabled="selectedTarea.revisado"></select>
                                                        <label>:</label>
                                                        <select class="form-control minute" ng-model="selectedTarea.bm" ng-disabled="selectedTarea.revisado"></select>
                                                        <label>:</label>
                                                        <select class="form-control minute" ng-model="selectedTarea.bs" ng-disabled="selectedTarea.revisado"></select>
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Hora de fin:</label>
                                                        <select class="form-control hour" ng-model="selectedTarea.eh" ng-disabled="selectedTarea.revisado"></select>
                                                        <label>:</label>
                                                        <select class="form-control minute" ng-model="selectedTarea.em" ng-disabled="selectedTarea.revisado"></select>
                                                        <label>:</label>
                                                        <select class="form-control minute" ng-model="selectedTarea.es" ng-disabled="selectedTarea.revisado"></select>
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Duración:</label>
                                                        <label>{{(selectedTarea.eh-selectedTarea.bh)*3600+(selectedTarea.em-selectedTarea.bm)*60+(selectedTarea.es-selectedTarea.bs)}} sec </label>
                                                    </div>
                                                    <div class="form-group form-inline">
                                                        <label class="col-sm-2">Edición:</label>
                                                        <select name="edicion" class="form-control" ng-model="selectedTarea.edicion" ng-disabled="selectedTarea.comprobado<?php if($_SESSION['perfil']<40){echo " || true";}?>">
                                                            <option></option>
                                                            <option ng-repeat="(key,value) in control.combos.edicion" value="{{key}}">{{value}}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Tab ficha-->
                                                <div id="fichas" class="tab-pane active">
                                                    <div class="col-md-3">
                                                        <div style="margin-bottom:5px"><input type="text" ng-model="search" placeholder="Buscar (Titulo, localidad, etiqueta)"> <?php if($_SESSION['perfil']>20){?><a href="#" ng-click="Tareas.addFicha()" ng-if="!selectedTarea.analizado"><span class="glyphicon glyphicon-plus-sign" ></span></a><?php } ?></div>
                                                        <ul class="nav nav-pills nav-stacked">
                                                            <li ng-repeat="(key, ficha) in selectedTarea.fichas | filter:search" ng-class='{"active":ficha==selectedFicha}' >
                                                                <a ng-click="Tareas.editFicha(ficha,'Modificar')" data-toggle="tab" style="padding: 5px 5px;">{{ficha.title}}
                                                                    <span class="glyphicon glyphicon-ok pull-right" ng-if="ficha.terminado"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-md-9" ng-if="selectedFicha != undefined">

                                                        <!-- poner el formulario Aqui-->
                                                        <input type="hidden" name ="create_date" value="" ng-model="selectedFicha.create_date.$date"/>
                                                        <input type="hidden" name ="create_user" value="" ng-model="selectedFicha.create_user"/>
                                                        
                                                        <!-- General-->
                                                        <div class="panel-group">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse1">General</a>
                                                                        <?php if($_SESSION['perfil']>20){?><a class="pull-right" href="#" ng-click="Tareas.deleteFicha(ficha.$$hashKey)" ng-if="!selectedFicha.terminado"><span class="glyphicon glyphicon-trash" ></span></a><?php } ?>
                                                                        
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse1" class="panel-collapse collapse in">
                                                                    <div class="panel-body">
                                                                        <?php if($_SESSION['perfil']>20){?><div class="form-group form-inline">
                                                                            <label class="col-sm-2">Terminado:</label>
                                                                            <input type="checkbox" ng-model="selectedFicha.terminado" ng-disabled="selectedTarea.analizado">
                                                                        </div><?php } ?>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-2">Titular:</label>
                                                                            <input type="text" class="form-control" placeholder="Breve titular indicativo" required ng-model="selectedFicha.title" ng-disabled="selectedFicha.terminado">
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-2">Inicio:</label>
                                                                            <select class="form-control hour" ng-model="selectedFicha.bhour" ng-disabled="selectedFicha.terminado"></select>
                                                                            <label>:</label>
                                                                            <select class="form-control minute" ng-model="selectedFicha.bmin" ng-disabled="selectedFicha.terminado"></select>
                                                                            <label>:</label>
                                                                            <select class="form-control minute" ng-model="selectedFicha.bsec" ng-disabled="selectedFicha.terminado"></select>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-2">Fin:</label>
                                                                            <select class="form-control hour" ng-model="selectedFicha.ehour" ng-disabled="selectedFicha.terminado"v></select>
                                                                            <label>:</label>
                                                                            <select class="form-control minute" ng-model="selectedFicha.emin" ng-disabled="selectedFicha.terminado"></select>
                                                                            <label>:</label>
                                                                            <select class="form-control minute" ng-model="selectedFicha.esec" ng-disabled="selectedFicha.terminado"></select>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-2">Duración:</label>
                                                                            <label>{{(selectedFicha.ehour-selectedFicha.bhour)*3600+(selectedFicha.emin-selectedFicha.bmin)*60+(selectedFicha.esec-selectedFicha.bsec)}} segundos</label>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-2">Formato:</label>
                                                                            <select class="form-control" ng-model="selectedFicha.format" ng-disabled="selectedFicha.terminado">
                                                                                <option></option>
                                                                                <option ng-repeat="(key,value) in control.combos.format" value="{{key}}">{{value}}</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-2">Genero:</label>
                                                                            <select class="form-control" ng-model="selectedFicha.genero" ng-disabled="selectedFicha.terminado">
                                                                                <option></option>
                                                                                <option ng-repeat="(key,value) in control.combos.genero" value="{{key}}">{{value}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Localización-->
                                                        <div class="panel-group">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse2">Localización</a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse2" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-6">Ambito:</label>
                                                                            <select id="ambito" class="form-control" ng-model="selectedFicha.ambito" ng-disabled="selectedFicha.terminado">
                                                                                <option></option>
                                                                                <option ng-repeat="(key,value) in control.combos.ambito" value="{{key}}">{{value}}</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-6"> Valoración:</label>
                                                                            <select name="valorAmbito" class="rating form-control" ng-model="selectedFicha.valorAmbito" ng-disabled="selectedFicha.terminado"></select>
                                                                        </div>
                                                                        <div ng-if='selectedFicha.ambito=="N"' class="form-group form-inline"">
                                                                             <label><input type="checkbox" ng-model="selectedFicha.espConjunto" ng-disabled="selectedFicha.terminado">  ¿España en su conjunto?</label>
                                                                        </div>
                                                                        <div ng-if='selectedFicha.ambito=="I"||selectedFicha.ambito=="G"' class="form-group form-inline"">
                                                                             <label class="col-sm-6">Zona Ambito Internacional:</label>
                                                                            <div>
                                                                                <select class="form-control" ng-model="selectedFicha.zona" ng-disabled="selectedFicha.terminado">
                                                                                    <option></option>
                                                                                    <option ng-repeat="(key,value) in control.combos.zona" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div ng-if='selectedFicha.ambito=="I"||selectedFicha.ambito=="N"||selectedFicha.ambito=="G"'>
                                                                        <table class="table" >
                                                                            <thead>
                                                                                <tr><th ng-if='selectedFicha.ambito=="I"'>Pais</th><th ng-if='selectedFicha.ambito=="N"'>Comunidad</th><th>Valoración</th><th></th></tr>
                                                                            </thead> 
                                                                            <tbody>
                                                                                <tr ng-repeat="local in selectedFicha.loc">
                                                                                    <td ng-if='selectedFicha.ambito=="I"||selectedFicha.ambito=="G"'>
                                                                                        <select class="form-control " ng-model="local.pais" ng-disabled="selectedFicha.terminado">
                                                                                            <option></option>
                                                                                            <option ng-repeat="(key,value) in control.combos.pais" value="{{key}}">{{value}}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td ng-if='selectedFicha.ambito=="N"'>
                                                                                        <select class="form-control" ng-model="local.comunidad" ng-disabled="selectedFicha.terminado">
                                                                                            <option></option>
                                                                                            <option ng-repeat="(key,value) in control.combos.comunidad" value="{{key}}">{{value}}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="rating form-control" ng-model="local.valor" ng-disabled="selectedFicha.terminado">
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-danger" ng-click="Tareas.deleteElem(selectedFicha.loc,local.$$hashKey)" ng-if="!selectedFicha.terminado">
                                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                               
                                                                            </tbody>
                                                                        </table>
                                                                        <div ng-if="(selectedFicha.loc.length < 3||selectedFicha.loc==undefined)&& !selectedFicha.terminado"><button type="button" class="btn btn-info" ng-click="Tareas.addElem(selectedFicha.loc)">Añadir</button></div>
                                                                        </div>
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr><th>Localidad</th><th></th></tr>
                                                                            </thead> 
                                                                            <tbody>
                                                                                <tr ng-repeat="local in selectedFicha.loc">
                                                                                    <td>
                                                                                        <input type="text" class="form-control text" ng-model="local.localidad" my-localisation ng-disabled="selectedFicha.terminado">
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="rating form-control" ng-model="local.valor" ng-disabled="selectedFicha.terminado">
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-danger" ng-click="Tareas.deleteElem(selectedFicha.loc,local.$$hashKey)" ng-if="!selectedFicha.terminado">
                                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                               
                                                                            </tbody>
                                                                        </table>
                                                                        <div ng-if="(selectedFicha.loc.length < 3||selectedFicha.loc==undefined)&& !selectedFicha.terminado"><button type="button" class="btn btn-info" ng-click="Tareas.addElem(selectedFicha.loc)">Añadir lugar</button></div>
                                                                
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Fuentes-->
                                                        <div class="panel-group">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse3">Fuentes</a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse3" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="form-group">
                                                                            <label><input type="checkbox" ng-model="selectedFicha.fuente" ng-disabled="selectedFicha.terminado"> Tiene Fuentes</label>
                                                                        </div>

                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr><th>Fuente Directa</th><th>Directa</th><th>Generica</th><th>Confidencial</th>
                                                                                    <th>Total</th>
                                                                                </tr>
                                                                            </thead> 
                                                                            <tbody>
                                                                                <tr><td><input type="number" class="form-control" ng-model="selectedFicha.fDirecta" placeholder="Num fuentes" ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td><input type="number" class="form-control" ng-model="selectedFicha.fDir" placeholder="Num fuentes" ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td><input type="number" class="form-control" ng-model="selectedFicha.fGenerica" placeholder="Num fuentes" ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td><input type="number" class="form-control" ng-model="selectedFicha.fConfidencial" placeholder="Num fuentes" ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td>{{selectedFicha.fDirecta+selectedFicha.fDir+selectedFicha.fGenerica+selectedFicha.fConfidencial}}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="form-group form-inline">
                                                                             <label><input type="checkbox" ng-model="selectedFicha.declaraciones" ng-disabled="selectedFicha.terminado"> Declaraciones</label>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-3">Declaraciones de Hombres:</label>
                                                                            <input type="number" class="form-control" ng-model="selectedFicha.declhombre" placeholder="Numero" ng-disabled="selectedFicha.terminado">
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-3">Declaraciones de Mujeres:</label>
                                                                            <input type="number" class="form-control" ng-model="selectedFicha.declmujeres" placeholder="Numero" ng-disabled="selectedFicha.terminado">
                                                                        </div>
                                                                        <div><label class="col-sm-3">Total declaraciones : </label>{{selectedFicha.declhombre+selectedFicha.declmujeres}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Quien Habla-->
                                                        <div class="panel-group">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse4">¿Quien Habla?</a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse4" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr><th>Agente Informativo</th><th>Numero de veces</th><th>Personaje</th><th></th></tr>
                                                                            </thead> 
                                                                            <tbody>
                                                                                <tr ng-repeat="quien in selectedFicha.quienes">
                                                                                    <td><select class="form-control" ng-model="quien.categoria" ng-disabled="selectedFicha.terminado">
                                                                                            <option></option>
                                                                                            <option ng-repeat="(key,value) in control.combos.categoria" value="{{key}}">{{value}}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td><select class="rating form-control" ng-model="quien.valor" ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td><input type="text" class="form-control etiquetas" ng-model="quien.persona" my-etiquetas ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td><button type="button" class="btn btn-danger" ng-click="Tareas.deleteElem(selectedFicha.quienes, quien.$$hashKey)" ng-if="!selectedFicha.terminado">
                                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div ng-if="!selectedFicha.terminado"><button type="button" class="btn btn-info" ng-click="Tareas.addElem(selectedFicha.quienes)">Añadir</button></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- De quien se habla-->
                                                        <div class="panel-group" >
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse5">¿De quién se habla?</a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse5" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr><th>Agente Informativo</th><th>Valoración</th><th>Mencionado</th><th></th></tr>
                                                                            </thead> 
                                                                            
                                                                            <tbody>
                                                                                <tr ng-repeat="dquien in selectedFicha.dquienes">
                                                                                    <td><select class="form-control" ng-model="dquien.categoria" ng-disabled="selectedFicha.terminado">
                                                                                            <option></option>
                                                                                            <option ng-repeat="(key,value) in control.combos.categoria" value="{{key}}">{{value}}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td><select class="rating form-control" ng-model="dquien.valor" ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td><input type="text" class="form-control" ng-model="dquien.persona" my-persona ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td><button type="button" class="btn btn-danger" ng-click="Tareas.deleteElem(selectedFicha.dquienes, dquien.$$hashKey)" ng-if="!selectedFicha.terminado">
                                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div ng-if="!selectedFicha.terminado"><button type="button" class="btn btn-info" ng-click="Tareas.addElem(selectedFicha.dquienes)">Añadir</button></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- De que se habla-->
                                                        <div class="panel-group">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse6">¿De qué se habla?</a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse6" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Categoria Tematica:</label>
                                                                            <div>
                                                                                <select ng-model="selectedFicha.catTem" class="form-control" ng-disabled="selectedFicha.terminado">
                                                                                    <option></option>
                                                                                    <option ng-repeat="(key,value) in control.combos.catTem" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr><th>¿De que se habla?</th><th>Valoración</th><th>Etiqueta</th><th></th></tr>
                                                                            </thead> 
                                                                            <tbody>
                                                                                <tr ng-repeat="dque in selectedFicha.dques" >
                                                                                    <td>
                                                                                        <select class="form-control" ng-model="dque.categoria" ng-disabled="selectedFicha.terminado">
                                                                                            <option></option>
                                                                                            <option ng-repeat="(key,value) in control.combos.dqcategoria" value="{{key}}">{{value}}</option>
                                                                                        </select></td>
                                                                                    <td>
                                                                                        <select class="rating form-control" ng-model="dque.valor" ng-disabled="selectedFicha.terminado">
                                                                                    </td>
                                                                                    <td><input type="text" class="form-control etiquetas" ng-model="dque.etiqueta" my-etiquetas ng-disabled="selectedFicha.terminado"></td>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-danger" ng-click="Tareas.deleteElem(selectedFicha.dques, dque.$$hashKey)" ng-if="!selectedFicha.terminado">
                                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div ng-if="!selectedFicha.terminado"><button type="button" class="btn btn-info" ng-click="Tareas.addElem(selectedFicha.dques)">Añadir</button></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Tratamiento-->
                                                        <div class="panel-group">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse7">Tratamiento</a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse7" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Valoración editorial:</label>
                                                                            <div>
                                                                                <select ng-model="selectedFicha.valedit"class="form-control" ng-disabled="selectedFicha.terminado">
                                                                                    <option></option>
                                                                                    <option ng-repeat="(key,value) in control.combos.valedit" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Origen de la imagen:</label>
                                                                            <div>
                                                                                <select multiple ng-model="selectedFicha.origen" class="form-control" ng-disabled="selectedFicha.terminado">
                                                                                    <option ng-repeat="(key,value) in control.combos.origen" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Rótulos:</label>
                                                                            <div>
                                                                                <select multiple ng-model="selectedFicha.rotulos" class="form-control" ng-disabled="selectedFicha.terminado">
                                                                                    <option ng-repeat="(key,value) in control.combos.rotulos" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Informador:</label>
                                                                            <div>
                                                                                <select class="form-control" ng-model="selectedFicha.informador" ng-disabled="selectedFicha.terminado">
                                                                                    <option></option>
                                                                                    <option ng-repeat="(key,value) in control.combos.informador" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                                <label><input type="checkbox" ng-model="selectedFicha.infoAparece" ng-disabled="selectedFicha.terminado"> ¿Aparece en la imagen?</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Retorica:</label>
                                                                            <div>
                                                                                <select multiple ng-model="selectedFicha.retorica" class="form-control" ng-disabled="selectedFicha.terminado">
                                                                                    <option ng-repeat="(key,value) in control.combos.retorica" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Equilibrio:</label>
                                                                            <div>
                                                                                <select ng-model="selectedFicha.equilibrio"class="form-control rating" ng-disabled="selectedFicha.terminado">
                                                                                    <option></option>
                                                                                    <option ng-repeat="(key,value) in control.combos.equilibrio" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Contextualización:</label>
                                                                            <div>
                                                                                <select ng-model="selectedFicha.context"class="form-control rating" ng-disabled="selectedFicha.terminado">
                                                                                    <option></option>
                                                                                    <option ng-repeat="(key,value) in control.combos.context" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group form-inline">
                                                                            <label class="col-sm-4">Relación con la cadena:</label>
                                                                            <div>
                                                                                <select ng-model="selectedFicha.relcad" class="form-control" ng-disabled="selectedFicha.terminado">
                                                                                    <option></option>
                                                                                    <option ng-repeat="(key,value) in control.combos.relcad" value="{{key}}">{{value}}</option>
                                                                                </select>
                                                                            </div>  
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Observaciones-->
                                                        <div class="panel-group">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <a data-toggle="collapse" href="#collapse8">Observaciones</a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse8" class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="form-group form-inline">
                                                                            <div>
                                                                                <textarea rows="10" style="width:100%" ng-model="selectedFicha.observaciones" ng-disabled="selectedFicha.terminado"></textarea>                                                              
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    <div class="modal-footer">

                                        <?php if($_SESSION['perfil']>20 ){?><button class="btn btn-info" data-dismiss="modal" ng-disabled="!(selectedTarea.analista == <?php echo "'".$_SESSION['user']."'";?> || selectedTarea.revisor == <?php echo "'".  $_SESSION['user']."'"; if($_SESSION['perfil']>40 ){echo " || true";} ?>)" ng-click="Tareas.executeTarea()">{{Tareas.action}}</button><?php } ?>
                                        <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="Tareas.Cerrar()">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>    
                        
                        
                    </div>
                </div>
                <!-- Rightcolumn-->
                <div class="col-sm-2 sidenav">
                    <?php include "leftcol.php" ?>
                </div>
            </div>
        </div>

        <?php include "footer.php" ?>

    </body>
</html>