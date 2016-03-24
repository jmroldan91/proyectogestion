"use strict"
/*global angular*/
var app = angular.module('gest-system', ['ui.router']);

app.config(['$stateProvider', '$httpProvider', function($stateProvider, $httpProvider) {     
        $stateProvider.state('system', {
            url: '/system',
            views: {
                'content': {
                    templateUrl: './modules/system/system.html'
                }
            }
        })
        .state('system.modules', {
            url: '/modules',
            views: {
                'contentSystem': {
                    templateUrl: './modules/system/tpl/modules.html'
                }
            }, 
            controller: 'modulesController',
            controllerAs: 'modulesCtrl'
        })
        .state('system.company', {
            url: '/company',
            views: {
                'contentSystem': {
                    templateUrl: './modules/system/tpl/company.html'
                }
            }, 
            controller: 'companyController',
            controllerAs: 'companyCtrl'
        })
        .state('system.menus', {
            url: '/menus',
            views: {
                'contentSystem': {
                    templateUrl: './modules/system/tpl/menus.html'
                }
            }, 
            controller: 'menusController',
            controllerAs: 'menusCtrl'
        })
        .state('system.roles', {
            url: '/roles',
            views: {
                'contentSystem': {
                    templateUrl: './modules/system/tpl/roles.html'
                }
            }, 
            controller: 'rolesController',
            controllerAs: 'rolesCtrl'
        });
}]);
app.controller('companyController', ['$http', '$rootScope', function($http, $rootScope) {       
    var that = this;
    this.company = $rootScope.company;    
    this.enabledEdit = false;
    
    this.getCompany = function(){
        $http.get('index.php?table=company&op=read').success(function(data){ 
            if(data.pages===1){
                that.company=data.resultset[0];                
            }else{
                that.enabledEdit = true;
            }
        }); 
    };
    this.uploadFile = function() {
        var file = (document.getElementById('logoUrl').files)[0];
        var data = new FormData();
        data.append('file', file, file.name);
        var xHttp = new XMLHttpRequest();
        if(xHttp.upload){
            xHttp.open("POST", "index.php?op=uploadFile&dir=logos", true);
            xHttp.onreadystatechange=function(){
                if(xHttp.readyState==4){
                    if(xHttp.status==200){
                        that.company.logoUrl=file.name;
                    }else{
                        alert('Imagen no subida');
                    }
                }
            }
        };
        xHttp.send(data);
    };
    this.setCompany = function(){
        var file = document.getElementById('logoUrl');
        if(file.value){
            this.company.logoUrl = file.files[0].name;
        }
        var queryString = this.prepareQuery();      
        $http.get('index.php?table=company&op=set'+queryString+'&pkid='+this.company.CIF).success(function(data){
            if(data.result!=='-1'){
                that.enabledEdit = false;
            }else{
                alert("Error: No se han guardado los cambios");
            }            
        }); 
        this.uploadFile();
    };
    this.insertCompany = function(){      
        var queryString = this.prepareQuery();
        $http.get('index.php?table=company&op=insert'+queryString).success(function(data){
            alert(data);
        }); 
    };
    this.enableEdit = function(){
        this.enabledEdit = true;
    }; 
    this.prepareQuery = function(){
        var queryString = "";
        for(var prop in this.company){
            queryString += '&'+prop+'='+this.company[prop];
        }
        return queryString;
    };
    this.getCompany();
}]);
app.controller('modulesController', ['$http', '$rootScope', function($http, $rootScope) {     
    
}]);
app.controller('menusController', ['$http', '$rootScope', function($http, $rootScope) {     
        
}]);
app.controller('rolesController', ['$http', '$rootScope', function($http, $rootScope) {     
        
}]);


