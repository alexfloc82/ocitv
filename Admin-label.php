<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ocitv - Admin labels</title>
        <?php include "head.php" ?>
        <script>
            var collection = "ValuePairs";
            $( document ).ready(function(){init();});
      
            init=function(){
                $('#valores').empty();
                $('#nav-admin').addClass('active');
                getDocuments("ValuePairs",null,0,1,null,function(data){
                    combos=data[0];
                    $.each(combos, function(key, value){
                        if(key!=="_id"){
                            $('#valores').append('<div class="desplegable" style="width:90%;" name="'+key+'"><button type="button" class="btn btn-info add-btn pull-right" name="'+key+'"><span aria-hidden="true"> Añadir</span></button><h2>'+key+'</h2></div>');
                            $.each(value,function(k,v){
                                $('div.desplegable[name="'+key+'"]').append('<div class="form-group pair row" name="'+key+'"><div class="col-sm-1"><label>Clave: </label></div>\n\
                                                      <div class="col-sm-3"><input  class="form-control" name="k" value="'+k+'" ></div>\n\
                                                      <div class="col-sm-1"><label> Valor: </label></div>\n\
                                                      <div class="col-sm-6"><input  class="form-control" name="v" value="'+v+'" ></div>\n\
                                                      <button type="button" class="btn btn-danger del-btn"><span class="glyphicon glyphicon-trash"></span></button></div>');
                            });
                        }
                  
                    });
                    $('.add-btn').click(function(event){
                        var key = this.name;
                        $('div.desplegable[name="'+key+'"]').append('<div class="form-group pair row" name="'+key+'"><div class="col-sm-1"><label>Clave: </label></div>\n\
                                                          <div class="col-sm-3"><input  class="form-control" name="k" value="" ></div>\n\
                                                          <div class="col-sm-1"><label> Valor: </label></div>\n\
                                                          <div class="col-sm-6"><input  class="form-control" name="v" value="" ></div>\n\
                                                          <button type="button" class="btn btn-danger del-btn"><span class="glyphicon glyphicon-trash"></span></button></div>');
                        $('.del-btn').click(function(event){
                            $(this).parent().remove();
                        });
                    });
                    $('.del-btn').click(function(event){
                        $(this).parent().remove();
                    });
                });
               
            };
      
            saveData = function(){
                var o={};
                o["_id"]=0;
                $('.desplegable').each(function(){
                    var key = $(this).find('h2')[0].innerText;
                    var obj={};
                    $('div.pair[name="'+key+'"]').each(function(){
                        obj[$(this).find('input[name="k"]')[0].value] = $(this).find('input[name="v"]')[0].value;
                    });
                    o[key]=obj;
                });
                postObject(collection,o, init);
            };

        </script>
    </head>
    <body>
        <?php include "header.php" ?>

        <div class="container-fluid text-center">
            <div class="row content"> 
                <div class="col-sm-2 sidenav">

                </div>
                <div class="col-sm-8 text-left" style="height:inherit;overflow:auto">
                    <div id="message"></div>
                    <form id="formValue" action="javascript:saveData()">
                        <h1>Desplegables
                            <div class="btn-group pull-right" role="group">
                                <button type="submit" class="btn btn-success">
                                    <span aria-hidden="true"> Guardar</span>
                                </button>
                            </div>
                        </h1>
                        <div id="valores">

                        </div>  
                    </form>
                </div>


                <script src="script/labels.js"></script>
                <div class="col-sm-2 sidenav">
                    <?php include "leftcol.php" ?>
                </div>
            </div>
        </div>

        <?php include "footer.php" ?>


    </body>
</html>