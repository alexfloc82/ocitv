/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var page = 0;
var limit = 20;
var squery={};
var collection = "Users";
var sorder={"user":1};
$( document).ready(function(){
    $('#nav-admin').addClass('active');
    reload();
    
});

          
angular.module('userApp', ['ngAnimate', 'ngSanitize', 'ui.bootstrap'])
    .controller('UserListController', ['$http','$scope','$timeout',function($http,$scope,$timeout) {
        var self = this;
        $scope.users = [];
        $scope.selectedUser={};
        self.search="";
        self.currkey=0;
        self.action="Crear";

        reload = function(){
            $http.get('https://api.mlab.com/api/1/databases/ocitv/collections/' + collection + '?sk=' + page*limit + '&l=' + limit + '&q=' + JSON.stringify(squery) + '&s=' + JSON.stringify(sorder) + '&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T').then(function(response) {
                $scope.users = response.data;
            });   
        };
      
        self.editUser =function(k, action){
            self.action=action;
            self.currkey=k;
            $scope.selectedUser = $scope.users[self.currkey];
            $("#Modal").modal();
        };
    
        self.crearUser= function(){
            self.action="Crear";
            $scope.selectedUser={"pass" : "a9c5c54a0bed5ecd0340dbc718225efc", "create_user":$('#user')[0].value};
            $scope.selectedUser.create_date={
                "$date":new Date()
            }
            $scope.currkey=$scope.users.push($scope.selectedUser);
            $("#Modal").modal();
        }
    
        self.executeUser = function(){
            var postobj = $scope.selectedUser;
            delete postobj.$$hashKey;
        
            switch(self.action){
                case "Crear":
                var exist =$.ajax({
                    type: "GET",
                    url: "https://api.mlab.com/api/1/databases/ocitv/collections/" + collection + "?c=true&q=" + JSON.stringify({"user":postobj.user}) + "&apiKey=lBpKrUWZkeKfyPVPJHMTv6nw12hpQ49T",
                    async: false
                }).responseText;
                if(exist>0){
                    alertm("El usuario ya existe","alert-danger");
                    break;
                }
                
                case "Guardar":
                    postobj.update_user=$('#user')[0].value;
                    postobj.update_date={
                        "$date":new Date()
                    }
                    postObject(collection, postobj, function(){
                        alertm("Los datos han sido actualizados","alert-success");
                        reload();
                    }); 
                    $scope.users[self.currkey] = $scope.selectedUser;
                    break;
                case "Eliminar":
                    deleteDocument(collection, postobj._id.$oid, function(){
                        alertm("Los datos han sido borrados","alert-success");
                        reload();
                    });
                    $scope.users.splice(self.currkey,1);   
                    break;
            }
            reload();
        
        }
        
        self.updatePass= function(user){
            var postobj = user;
            delete postobj.$$hashKey;
            postobj.pass="a9c5c54a0bed5ecd0340dbc718225efc";
            postObject(collection, postobj, function(){
                        alertm("Los datos han sido actualizados","alert-success");
                        reload();
                    }); 
        };
        
    }])
    .controller('UserController', ['$http',function($http){  
        var self = this;
        self.combos={};
        self.query={};
        self.orderby="user";
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
                case "user":
                    sorder={
                        "user":1
                    };
                    break;
                case "userd":
                    sorder={
                        "user":-1
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
    });