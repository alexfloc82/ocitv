<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Ocitv - Analisis</title>
        <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
        <?php include "head.php" ?>
        <script>
            var collection = "";
            var chart;
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
            
            see = function(data){
            
                switch(data){
                    case "1":
                        $("#ejemplo1").show();
                        $("#ejemplo2").hide();
                        break;
                    case "2":
                        $("#ejemplo2").show();
                        chart.render();
                        $("#ejemplo1").hide();
                        break;
                }
            }

            window.onload = function () {
	chart = new CanvasJS.Chart("chartContainer", {
		theme: "theme1",//theme1
		title:{
			text: "My chart"              
		},
		animationEnabled: true,   // change to true
		data: [              
		{
			// Change type to "bar", "area", "spline", "pie",etc.
			type: "bar",
			dataPoints: [
				{ label: "apple",  y: 10  },
				{ label: "orange", y: 15  },
				{ label: "banana", y: 25  },
				{ label: "mango",  y: 30  },
				{ label: "grape",  y: 28  }
			]
		}
		]
	});
	chart.render();
        //$("#ejemplo2").hide();
}
        </script>
    </head>
    <body>
        <?php include "header.php" ?>

        <div class="container-fluid text-center">
            <div class="row content"> 
                <div class="col-sm-2 sidenav">
                    <ul>
                        <li><a href="javascript:see('1');">Tableau</a></li>
                        <li><a href="javascript:see('2');">CanvasJS</a></li>
                    </ul>
                </div>
                <div class="col-sm-8 text-left" style="height:inherit;overflow:auto">
                    <div id="message"></div>
                    <div id="ejemplo2">
                        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                    </div>
                    <div id="ejemplo1">
                        <div class='tableauPlaceholder' id='viz1484992443384' style='position: relative'><noscript><a href='#'><img alt='Dashboard 1 ' src='https:&#47;&#47;public.tableau.com&#47;static&#47;images&#47;Li&#47;Libro2_281&#47;Dashboard1&#47;1_rss.png' style='border: none' /></a></noscript><object class='tableauViz'  style='display:none;'><param name='host_url' value='https%3A%2F%2Fpublic.tableau.com%2F' /> <param name='site_root' value='' /><param name='name' value='Libro2_281&#47;Dashboard1' /><param name='tabs' value='no' /><param name='toolbar' value='yes' /><param name='static_image' value='https:&#47;&#47;public.tableau.com&#47;static&#47;images&#47;Li&#47;Libro2_281&#47;Dashboard1&#47;1.png' /> <param name='animate_transition' value='yes' /><param name='display_static_image' value='yes' /><param name='display_spinner' value='yes' /><param name='display_overlay' value='yes' /><param name='display_count' value='yes' /></object></div>                <script type='text/javascript'>                    var divElement = document.getElementById('viz1484992443384');                    var vizElement = divElement.getElementsByTagName('object')[0];                    vizElement.style.minWidth='424px';vizElement.style.maxWidth='654px';vizElement.style.width='100%';vizElement.style.minHeight='500px';vizElement.style.maxHeight='929px';vizElement.style.height=(divElement.offsetWidth*0.75)+'px';                    var scriptElement = document.createElement('script');                    scriptElement.src = 'https://public.tableau.com/javascripts/api/viz_v1.js';                    vizElement.parentNode.insertBefore(scriptElement, vizElement);                </script>
                    </div>
                    
                </div>
                <div class="col-sm-2 sidenav">
                    <?php include "leftcol.php" ?>
                </div>
            </div>
        </div>

        <?php include "footer.php" ?>


    </body>
</html>