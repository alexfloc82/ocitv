/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var page = 0;
var limit = 20;
var availableTags=[];
var availableLocs=[];
var availablePers=[];
var availableUsers=[];
var squery={};
var collection = "piezas";
var sorder={
    "date":-1
};
$( document ).ready(function(){
    $('#nav-piezas').addClass('active');
    $('.nav-tabs a[href="#general"]')[0].click();
    reload();
    
});

          
angular.module('tareaApp', ['ngAnimate', 'ngSanitize', 'ui.bootstrap'])
    .controller('TareaListController', ['$http','$scope',function($http,$scope) {
        var self = this;
        $scope.tareas = [];
        $scope.selectedTarea={};
        self.search="";
        self.currkey=0;
        self.action="Crear";
    
    
    
        reload = function(){
            $http.get('https://api.mlab.com/api/1/databases/ocitv/collections/' + collection + '?sk=' + page*limit + '&l=' + limit + '&q=' + JSON.stringify(squery) + '&s=' + JSON.stringify(sorder) + '&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T').then(function(response) {
                $scope.tareas = response.data;
            });   
      
            $http.get('https://api.mlab.com/api/1/databases/ocitv/collections/piezas?f={"fichas.dques.etiqueta":1,"fichas.loc.localidad":1,"fichas.dquienes.persona":1}&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T').then(function(response) {
                $.each(response.data, function(key, val){
                    var ficha = response.data[key].fichas;
                    $.each(ficha,function(k, v){
                        var dque = v.dques;
                        var local = v.local;
                        var quien = v.dquienes;
                        $.each(dque, function(k1,v1){
                            if($.inArray(v1.etiqueta, availableTags) < 0 && v1.etiqueta != undefined){
                                availableTags.push(v1.etiqueta);
                            }
                        });
                        $.each(local, function(k1,v1){
                            if($.inArray(v1.localidad, availableLocs) < 0 && v1.localidad != undefined){
                                availableLocs.push(v1.localidad);
                            }
                        });
                         $.each(quien, function(k1,v1){
                            if($.inArray(v1.persona, availablePers) < 0 && v1.persona != undefined){
                                availablePers.push(v1.persona);
                            }
                        });
                    });
                });
            });  
            $http.get('https://api.mlab.com/api/1/databases/ocitv/collections/Users?f={"user":1}&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T').then(function(response) {
                $.each(response.data, function(key, val){
                    availableUsers.push(response.data[key].user);
                });
            });
        };
      
        self.editTarea =function(k, action){
            $('#messageModal').empty();
            $scope.selectedFicha=undefined;
            $('.nav-tabs a[href="#general"]')[0].click();
            self.action=action;
            self.currkey=k;
            $scope.selectedTarea = $scope.tareas[self.currkey];
            $("#Modal").modal();
        };
    
        self.crearTarea= function(){
            $('#messageModal').empty();
            self.action="Crear";
            $scope.selectedTarea={};
            $scope.selectedTarea.create_user=$('#user')[0].value;
            $scope.selectedTarea.create_date={
                "$date":new Date()
            }
            $scope.currkey=$scope.tareas.push($scope.selectedTarea);
            $("#Modal").modal();
        };
    
        self.executeTarea = function(){
            var postobj = $scope.selectedTarea;
            delete postobj.$$hashKey;
            $.each(postobj.fichas,function(key,value){
                delete postobj.fichas[key].$$hashKey;
                $.each(postobj.fichas[key].loc,function(k,val){
                    delete postobj.fichas[key].loc[k].$$hashKey;
                });
                $.each(postobj.fichas[key].local,function(k,val){
                    delete postobj.fichas[key].local[k].$$hashKey;
                });
                $.each(postobj.fichas[key].quienes,function(k,val){
                    delete postobj.fichas[key].quienes[k].$$hashKey;
                });
                $.each(postobj.fichas[key].dquienes,function(k,val){
                    delete postobj.fichas[key].dquienes[k].$$hashKey;
                });
                $.each(postobj.fichas[key].dques,function(k,val){
                    delete postobj.fichas[key].dques[k].$$hashKey;
                });
            });
        
        
            switch(self.action){
                case "Crear":
                case "Guardar":
                    postobj.update_user=$('#user')[0].value;
                    postobj.update_date={
                        "$date":new Date()
                    }
                    postObject(collection, postobj, function(){
                        alertm("Los datos han sido actualizados","alert-success");
                        reload();
                    }); 
                    $scope.tareas[self.currkey] = $scope.selectedTarea;
                    break;
                case "Eliminar":
                    deleteDocument(collection, postobj._id.$oid, function(){
                        alertm("Los datos han sido borrados","alert-success");
                        reload();
                    });
                    $scope.tareas.splice(self.currkey,1);   
                    break;
            }
            reload();
        
        };
        
        self.checkTerminado= function(value){
            if(value){
                var fichasNoTerminadas = jQuery.grep($scope.selectedTarea.fichas,function(n,i){
                    return !n.terminado;
                });
                if(fichasNoTerminadas.length > 0){
                    $scope.selectedTarea.analizado=false;
                    alertmm('Existen ' +fichasNoTerminadas.length+' fichas no terminadas','alert-danger');
                }
                
            }
        };
    
        self.addFicha= function(){
            if($scope.selectedTarea.fichas==undefined){
                $scope.selectedTarea.fichas=[]; 
            }
            var newFicha={
                title:"Nuevo titular",
                loc:[],
                local:[],
                dques:[],
                dquienes:[],
                quienes:[]
            };
            $scope.selectedTarea.fichas.push(newFicha);
            $scope.selectedFicha =newFicha;
        
        };
    
        self.editFicha = function (ficha){
            $scope.selectedFicha = ficha;
        };
    
        self.deleteFicha = function(hashkey){
            $scope.selectedTarea.fichas.splice($scope.selectedTarea.fichas.findIndex(function(value){
                return value.$$hashKey==hashkey;
            }),1)
            $scope.selectedFicha=undefined;
 
        };
   
        self.deleteElem = function(elem, hashkey){
            elem.splice(elem.findIndex(function(value){
                return value.$$hashKey==hashkey;
            }),1)
        };
    
        self.addElem = function(elem){
            var newElem = {};
            elem.push(newElem);
        };
        
        self.Cerrar = function(){
            reload();
        };
        
    }])
    .controller('TareaController', ['$http',function($http){  
        var self = this;
        self.combos={};
        self.query={};
        self.orderby="dated";
        self.numPage=0;
        self.currPage=0;
    
        $http.get('https://api.mlab.com/api/1/databases/ocitv/collections/ValuePairs?&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T').then(function(response) {
            self.combos = response.data[0];
        });
    
        $http.get('https://api.mlab.com/api/1/databases/ocitv/collections/' + collection + '?c=true&q=' + JSON.stringify(squery) + '&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T').then(function(response) {
            self.numPage = Math.ceil(response.data/limit);
        });
    
        self.search= function(){
            clean(self.query);
            squery = self.query;
            self.setPage(0);
        };
    
        self.order = function(){
            switch(self.orderby){
                case "cadena":
                    sorder={
                    "cadena":1
                };               
                break;
                case "cadenad":
                    sorder={
                    "cadena":-1
                };                
                break;
                case "date":
                    sorder={
                    "date":1
                };             
                break;
                case "dated":
                    sorder={
                    "date":-1
                };              
                break;
            }
            reload();
            self.setPage(0);
        };
    
        self.setPage = function(p){
            $http.get('https://api.mlab.com/api/1/databases/ocitv/collections/' + collection + '?c=true&q=' + JSON.stringify(squery) + '&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T').then(function(response) {
                self.numPage = Math.ceil(response.data/limit);
            });
            self.currPage=p;
            page=p;
            reload();
        };
        
    }])
    .filter('range', function() {
        return function(input, total) {
            total = parseInt(total);

            for (var i=0; i<total; i++) {
                input.push(i);
            }

            return input;
        };
    })
    .controller('Datepicker', function ($scope) {
        $scope.today = function() {
            $scope.dt = new Date();
        };
        $scope.today();

        $scope.clear = function() {
            $scope.dt = null;
        };

        $scope.inlineOptions = {
            customClass: getDayClass,
            minDate: new Date(),
            showWeeks: true
        };

        $scope.dateOptions = {
            dateDisabled: disabled,
            formatYear: 'yy',
            maxDate: new Date(2020, 5, 22),
            minDate: new Date(),
            startingDay: 1
        };

        // Disable weekend selection
        function disabled(data) {
            var date = data.date,
            mode = data.mode;
            return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
        }

        $scope.toggleMin = function() {
            $scope.inlineOptions.minDate = $scope.inlineOptions.minDate ? null : new Date();
            $scope.dateOptions.minDate = $scope.inlineOptions.minDate;
        };

        $scope.toggleMin();

        $scope.open1 = function() {
            $scope.popup1.opened = true;
        };

        $scope.setDate = function(year, month, day) {
            $scope.dt = new Date(year, month, day);
        };

        $scope.popup1 = {
            opened: false
        };

        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        var afterTomorrow = new Date();
        afterTomorrow.setDate(tomorrow.getDate() + 1);
        $scope.events = [
        {
            date: tomorrow,
            status: 'full'
        },
        {
            date: afterTomorrow,
            status: 'partially'
        }
        ];

        function getDayClass(data) {
            var date = data.date,
            mode = data.mode;
            if (mode === 'day') {
                var dayToCheck = new Date(date).setHours(0,0,0,0);

                for (var i = 0; i < $scope.events.length; i++) {
                    var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

                    if (dayToCheck === currentDay) {
                        return $scope.events[i].status;
                    }
                }
            }
    
            return '';
        }
    }
    )
    .directive('myEtiquetas', function() {
        return function(scope, element, attrs) {
            element.autocomplete({
                source: availableTags
            });
        };
    })
    .directive('myLocalisation', function() {
        return function(scope, element, attrs) {
            element.autocomplete({
                source: availableLocs
            });
        };
    })
    .directive('myPersona', function() {
        return function(scope, element, attrs) {
            element.autocomplete({
                source: availablePers
            });
        };
    })
    .directive('myUsers', function() {
        return function(scope, element, attrs) {
            element.autocomplete({
                source: availableUsers
            });
        };
    });