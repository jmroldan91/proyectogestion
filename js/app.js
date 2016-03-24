"use strict"
var $stateProviderRef = null;
/*global angular*/
    var app = angular.module('gest', ['ui.router', 'gest-system']);
    app.config(['$stateProvider', function($stateProvider) {         
            $stateProvider.state('home', {
                url: 'home',
                views: {
                    'content': {
                        templateUrl: './tpl/home.html'
                    }
                }
            });
            $stateProviderRef = $stateProvider;
    }]);
    app.run(['$rootScope', function($rootScope) {
            $rootScope.activeMod = "home";
            $rootScope.isLogged = false;
            $rootScope.loginError = false;
            $rootScope.company = {};
            $rootScope.user = {};
            $rootScope.modules = {};
            /*var $state = $rootScope.$state;
            $http.get("settings.json").success(function(data) {
                var modules = data.modules;
                
                angular.forEach(modules, function(value, key) {
                  
                  var state = {
                    "url": value,
                    "views": {
                          'content': {
                              templateUrl: './modules/'+value+'/'+value+'.html'
                          }
                    }
                  };
                  
                  $stateProviderRef.state(value, state);
                });
                $urlRouter.sync();
                $urlRouter.listen();
            });*/
        }
    ]);    
    app.controller('loginController', ['$http', '$rootScope', function($http, $rootScope) {     
        var that=this;
        this.activeMod = $rootScope.activeMod;
        this.isLogged = $rootScope.isLogged;
        this.loginError = $rootScope.loginError;
        this.user = $rootScope.user;
        this.modules = $rootScope.modules;
        this.login = function(){            
            $http.get('index.php?op=login&login='+this.user.login+'&pass='+this.user.pass).success(function(data){
                if(data.result==='ok'){
                    that.isLogged=true;
                    that.user=data.user; 
                    that.modules=data.modules;
                    that.loginError = false;
                }else{
                    that.loginError = true;
                }                
            });
        };
        this.logOut = function(){
            $http.get('index.php?op=logout').success(function(data){
                that.isLogged=false;
                that.user={};
            });           
        };
        this.checkSession = function(){
            $http.get('index.php?op=checksession').success(function(data){
                if(data.result==='ok'){
                    that.isLogged=true;
                    that.user=data.user; 
                    that.modules=data.modules;
                    that.loginError = false;
                }
            }); 
        }
        this.checkSession();
    }]);
    app.directive('headerNav', ['$http', function($http) {
        return {
            restrict: 'E',
            templateUrl: 'tpl/header-nav.html',
            controller: function() {
                
            },
            controllerAs: 'ctrlHeaderNav'
        };
    }]);
    app.directive('sideNav', ['$http', function($http) {
        return {
            restrict: 'E',
            templateUrl: 'tpl/side-nav.html',
            controller: function() {

            },
            controllerAs: 'ctrlSideNav'
        }
    }]);
    app.directive('mainContent', ['$http', function($http) {
        return {
            restrict: 'E',
            templateUrl: 'tpl/main-content.html',
            controller: function() {

            },
            controllerAs: 'ctrlContent'
        }
    }]);

        

