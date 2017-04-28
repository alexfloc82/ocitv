(function () {
    var combos; 
    var tableTarea=[];
    var tableFichas=[];
    var tableGeo=[];
    var tableQuien=[];
    var tableDquien=[];
    var tableDque=[];
    var myConnector = tableau.makeConnector();
    getComboValue = function(combo, key){
        return combos[combo][key];
    };

    myConnector.init= function(initok){
        combos=  JSON.parse($.ajax({
            url: 'https://api.mlab.com/api/1/databases/ocitv/collections/ValuePairs?&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T',
            type: "GET",
            contentType: "application/json",
            async:false,
            error: function (xhr, status, error) {
                alertm(error);
            }
        }).responseText);
    
        combos=combos[0];
        
        $.getJSON("https://api.mlab.com/api/1/databases/ocitv/collections/piezas?apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T", function(resp) {
            var tareas = resp;

            // Iterate over the JSON object
            for (var i = 0, len = tareas.length; i < len; i++) {
                var fecha = new Date(tareas[i].date.$date);
                tableTarea.push({
                    "id": tareas[i]._id.$oid,
                    "id_tarea":tareas[i].id_tarea,
                    "semestre": tareas[i].semestre,
                    "analista": tareas[i].analista,
                    "analizado": tareas[i].analizado,
                    "revisor": tareas[i].revisor,
                    "revisado": tareas[i].revisado,
                    "cadena": getComboValue('cadena',tareas[i].cadena),
                    "fecha": fecha.getFullYear()+"-"+(fecha.getMonth()+1) +"-"+fecha.getDate(),
                    //"hora_fin": tareas[i].eh+':'+tareas[i].em,
                    //"hora_ini": tareas[i].bh+':'+tareas[i].bm,
                    "duracion": (tareas[i].eh-tareas[i].bh)*60 + (tareas[i].em - tareas[i].bm),
                    "edicion": getComboValue('edicion',tareas[i].edicion)
                });
                var fichas =tareas[i].fichas;
                if(fichas != undefined){
                    for (var j = 0, length = fichas.length; j < length; j++) {
                        tableFichas.push({
                            "id":tareas[i]._id.$oid+'-'+j,
                            "tarea_id":tareas[i]._id.$oid,
                            "terminado":fichas[j].terminado,
                            "title":fichas[j].title,
                            "duracion":0,
                            "format":getComboValue('format',fichas[j].format),
                            "genero":getComboValue('genero',fichas[j].genero),
                            "ambito":getComboValue('ambito',fichas[j].ambito),
                            "valorambito":fichas[j].valorambito,
                            "espConjunto":fichas[j].espConjunto,
                            "zona":getComboValue('zona',fichas[j].zona),
                            "fuentes":fichas[j].fuentes,
                            "fDirecta":fichas[j].fDirecta,
                            "fDir":fichas[j].fDir,
                            "fGenerica":fichas[j].fGenerica,
                            "fConfidencial":fichas[j].fConfidencial,
                            "decltype":getComboValue('decltype',fichas[j].decltype),
                            "declhombre":fichas[j].declhombre,
                            "declmujeres":fichas[j].declmujeres,
                            "catTem":getComboValue('catTem',fichas[j].catTem),
                            "valedit":getComboValue('valedit',fichas[j].valedit),
                            "informador":fichas[j].informador,
                            "infoAparece":fichas[j].infoAparece,
                            "equilibrio":getComboValue('equilibrio',fichas[j].equilibrio),
                            "context":getComboValue('context',fichas[j].context),
                            "relcad":getComboValue('relcad',fichas[j].relcad)
                        });
                        var locs =fichas[j].loc;
                        if(locs != undefined){
                            for (var k = 0, len1 = locs.length; k < len1; k++) {
                                tableGeo.push({
                                    "id": tareas[i]._id.$oid+'-'+j,
                                    "pais": locs[k]["pais"],
                                    "comunidad": locs[k]["comunidad"],
                                    "localidad": locs[k]["localidad"],
                                    "valor": locs[k]["valor"]
                                });
                            }
                        }
                        var quienes =fichas[j].quienes;
                        if(quienes != undefined){
                            for (var k = 0, len1 = quienes.length; k < len1; k++) {
                                tableQuien.push({
                                    "id": tareas[i]._id.$oid+'-'+j,
                                    "agente": getComboValue('categoria',quienes[k]["categoria"]),
                                    "valor": quienes[k]["valor"]
                                });
                            }
                        }
                        var dquienes =fichas[j].dquienes;
                        if(dquienes != undefined){
                            for (var k = 0, len1 = dquienes.length; k < len1; k++) {
                                tableDquien.push({
                                    "id": tareas[i]._id.$oid+'-'+j,
                                    "agente": getComboValue('categoria',dquienes[k]["categoria"]),
                                    "mencionado": dquienes[k]["mencionado"],
                                    "valor": dquienes[k]["valor"]
                                });
                            }
                        }
                        var dques =fichas[j].dques;
                        if(dques != undefined){
                            for (var k = 0, len1 = dques.length; k < len1; k++) {
                                tableDque.push({
                                    "id": tareas[i]._id.$oid+'-'+j,
                                    "deque": getComboValue('dqcategoria',dques[k]["categoria"]),
                                    "etiqueta": dques[k]["etiqueta"],
                                    "valor": dques[k]["valor"]
                                });
                            }
                        }
                    }
                }
            }   
        });
        
        
        initok();
    };
    
    myConnector.getSchema = function (schemaCallback) {
        var col_tarea=[
        {
            id : "id",     
            alias : "id",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "id_tarea",     
            alias : "Id_tarea",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "semestre",     
            alias : "Semestre",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "analista",     
            alias : "Analista",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "analizado",     
            alias : "Analizado",  
            dataType : tableau.dataTypeEnum.bool
        },
        {
            id : "revisor",     
            alias : "Revisor",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "revisado",     
            alias : "Revisado",  
            dataType : tableau.dataTypeEnum.bool
        },
        {
            id : "cadena",     
            alias : "Cadena",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "fecha",     
            alias : "Fecha",  
            dataType : tableau.dataTypeEnum.date
        },
        /*{
            id : "hora_ini",     
            alias : "Hora inicio",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "hora_fin",     
            alias : "Hora fin",  
            dataType : tableau.dataTypeEnum.string
        },*/
        {
            id : "duracion",     
            alias : "Duracion",  
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "edicion",     
            alias : "Edicion",  
            dataType : tableau.dataTypeEnum.string
        }
        ];
        
        var tableTarea = {
            id : "tareas",
            alias : "Tareas",
            columns : col_tarea
        };
    
        
        var cols_ficha = [
        {
            id : "id",     
            alias : "Id",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "tarea_id",     
            alias : "Tarea_id",  
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "terminado",  
            alias : "Terminado",      
            dataType : tableau.dataTypeEnum.bool
        },
        {
            id : "title",  
            alias : "Titulo",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "duracion",  
            alias : "Duracion",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "format", 
            alias : "Formato",     
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "genero", 
            alias : "Genero",     
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "ambito", 
            alias : "Ambito",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "valorambito", 
            alias : "ValorAmbito",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "espConjunto", 
            alias : "ConjuntoEspaña",      
            dataType : tableau.dataTypeEnum.bool
        },
        {
            id : "zona", 
            alias : "Zona",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "fuentes", 
            alias : "Fuentes",      
            dataType : tableau.dataTypeEnum.bool
        },
        {
            id : "fDirecta", 
            alias : "Fuente Directa",      
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "fDir", 
            alias : "Directa",      
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "fGenerica", 
            alias : "Fuente Generica",      
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "fConfidencial", 
            alias : "Fuente Confidencial",      
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "decltype", 
            alias : "Declaracion Type",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "declhombre", 
            alias : "Declaraciones Hombres",      
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "declmujeres", 
            alias : "Declaraciones Mujeres",      
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "catTem", 
            alias : "Categoria Tematica",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "valedit", 
            alias : "Valoracion Editorial",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "informador", 
            alias : "Informador",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "infoAparece", 
            alias : "Informador Aparece",      
            dataType : tableau.dataTypeEnum.bool
        },
        {
            id : "equilibrio", 
            alias : "Equilibrio",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "context", 
            alias : "Contexto",      
            dataType : tableau.dataTypeEnum.string
        },
        {
            id : "relcad", 
            alias : "Relacion con cadena",      
            dataType : tableau.dataTypeEnum.string
        }
        ];

        var tableFicha = {
            id : "fichas",
            alias : "fichas",
            columns : cols_ficha
        };
    
        var cols_loc = [
        {
            id : "id",         
            alias : "id",    
            dataType : tableau.dataTypeEnum.string
        },

        {
            id : "pais",       
            alias : "pais",          
            dataType : tableau.dataTypeEnum.string, 
            geoRole : tableau.geographicRoleEnum.country_region
        },

        {
            id : "comunidad",  
            alias : "comunidad",     
            dataType : tableau.dataTypeEnum.string, 
            geoRole : tableau.geographicRoleEnum.state_province
        },

        {
            id : "localidad",  
            alias : "localidad",     
            dataType : tableau.dataTypeEnum.string, 
            geoRole : tableau.geographicRoleEnum.city
        },

        {
            id : "valor",      
            alias : "valorizacion",  
            dataType : tableau.dataTypeEnum.int
        }
        ];
    
        var tableGeo = {
            id : "geo",
            alias : "geo",
            columns : cols_loc
        };
        
        var cols_quien = [
        {
            id : "id",         
            alias : "id",    
            dataType : tableau.dataTypeEnum.string
        },

        {
            id : "agente",       
            alias : "Agente Informativo",          
            dataType : tableau.dataTypeEnum.string, 
            geoRole : tableau.geographicRoleEnum.country_region
        },
        {
            id : "valor",      
            alias : "Valorizacion",  
            dataType : tableau.dataTypeEnum.int
        }
        ];
    
        var tableQuien = {
            id : "quien",
            alias : "quien",
            columns : cols_quien
        };
        
        var cols_dquien = [
        {
            id : "id",         
            alias : "id",    
            dataType : tableau.dataTypeEnum.string
        },

        {
            id : "agente",       
            alias : "Agente Informativo",          
            dataType : tableau.dataTypeEnum.string, 
            geoRole : tableau.geographicRoleEnum.country_region
        },
        {
            id : "valor",      
            alias : "Valorizacion",  
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "mencionado",      
            alias : "Mencionado",  
            dataType : tableau.dataTypeEnum.string
        }
        ];
    
        var tableDquien = {
            id : "dquien",
            alias : "dquien",
            columns : cols_dquien
        };
        
        var cols_dque = [
        {
            id : "id",         
            alias : "id",    
            dataType : tableau.dataTypeEnum.string
        },

        {
            id : "deque",       
            alias : "De que se habla",          
            dataType : tableau.dataTypeEnum.string, 
            geoRole : tableau.geographicRoleEnum.country_region
        },
        {
            id : "valor",      
            alias : "Valorizacion",  
            dataType : tableau.dataTypeEnum.int
        },
        {
            id : "etiqueta",      
            alias : "Etiqueta",  
            dataType : tableau.dataTypeEnum.string
        }
        ];
    
        var tableDque = {
            id : "dque",
            alias : "dque",
            columns : cols_dque
        };
    
        var StandardConnection = {
            "alias": "Joins",
            "tables": [
            {
                "id" : "tareas",
                "alias":"tareas"
            },
            {
                "id": "fichas",
                "alias": "fichas"
            },
            {
                "id": "geo",
                "alias": "geo"
            },
            {
                "id": "dquien",
                "alias": "dquien"
            },
            {
                "id": "quien",
                "alias": "quien"
            },
            {
                "id": "dque",
                "alias": "dque"
            }
            ],
            "joins": [
            {
                "left": {
                    "tableAlias": "tareas",
                    "columnId": "id"
                },
                "right": {
                    "tableAlias": "fichas",
                    "columnId": "tarea_id"
                },
                "joinType": "left"
            },
            {
                "left": {
                    "tableAlias": "fichas",
                    "columnId": "id"
                },
                "right": {
                    "tableAlias": "geo",
                    "columnId": "id"
                },
                "joinType": "left"
            },
            {
                "left": {
                    "tableAlias": "fichas",
                    "columnId": "id"
                },
                "right": {
                    "tableAlias": "dquien",
                    "columnId": "id"
                },
                "joinType": "left"
            },
            {
                "left": {
                    "tableAlias": "fichas",
                    "columnId": "id"
                },
                "right": {
                    "tableAlias": "quien",
                    "columnId": "id"
                },
                "joinType": "left"
            },
            {
                "left": {
                    "tableAlias": "fichas",
                    "columnId": "id"
                },
                "right": {
                    "tableAlias": "dque",
                    "columnId": "id"
                },
                "joinType": "left"
            }
            ]
        };

        schemaCallback([tableTarea,tableFicha,tableGeo,tableQuien,tableDquien,tableDque],[StandardConnection]);
    };

    myConnector.getData = function(table, doneCallback) {
        
        switch (table.tableInfo.id){
            case "tareas":
                table.appendRows(tableTarea);
                break;
            case "fichas":
                table.appendRows(tableFichas);
                break;
            case "geo":
                table.appendRows(tableGeo);
                break;
            case "dquien":
                table.appendRows(tableDquien);
                break;
            case "que":
                table.appendRows(tableDque);
                break;
            case "quien":
                table.appendRows(tableQuien);
                break;
        }
        
        doneCallback();
    };

    tableau.registerConnector(myConnector);
    
    $(document).ready(function () {
        $("#submitButton").click(function () {
            tableau.connectionName = "Xolomon Feed";
            tableau.submit();
        });
    });
})();


    


