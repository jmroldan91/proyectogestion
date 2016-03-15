"use strict"
/*global angular*/
var app = angular.module('installer', ['ui.router']);
app.config(['$locationProvider', '$stateProvider', function ($locationProvider, $stateProvider) {
        $stateProvider
        .state('step1', {
                            url: '',
                            views: {
                                'content': {
                                    templateUrl: 'step1.html'
                                }
                        },
                controller: 'ctrlInstall'
        });
        $stateProvider
        .state('step2', {
                            url: '/step2',
                            views: {
                                'content': {
                                    templateUrl: 'step2.html'
                                }
                        },
                controller: 'ctrlInstall'
        });
        $stateProvider
        .state('step3', {
                            url: '/step3',
                            views: {
                                'content': {
                                    templateUrl: 'step3.html'
                                }
                        },
                controller: 'ctrlInstall'
        });
}]);
app.controller('ctrlInstall', ['$http',function($http){
    this.step = 1;
    this.testOk = false;
    this.installing = false;
    this.prog = "Generando ficheros";
    this.console = "";
    this.conn = {};
    this.admin = {};    
    this.checkConn = function(){
        var that = this;
        var conn = this.conn;
        $http.get('index.php?op=checkConn&dbhost='+conn.server+'&database='+conn.db+'&dbuser='+conn.user+'&dbpass='+conn.pass)
            .success(function(data) {
                if(data['status'] === 'error'){
                    alert(data['result']);
                }else{
                    var dbok = false;
                    var i=0;
                    while(dbok === false && i<data.length){
                        if(data[i][0] === that.conn.db){
                            dbok = true
                        }
                        i++;
                    }
                    if(dbok){
                        alert('Conexión establecida');
                        that.testOk = true;
                    }else{
                        alert('No existe la base de datos en el servidor'+JSON.stringify(data));
                    }
                }                
            } 
        );
    };
    this.install = function(){
        var that = this;
        this.installing=true;
        var conn = this.conn;
        var admin = this.admin;
        var query = 'index.php?op=installP1&dbhost='+conn.server+'&dbdatabase='+conn.db+'&dbuser='+conn.user+'&dbpass='+conn.pass+'&firstName='+admin.firstName+'&lastName='+admin.lastName+'&email='+admin.email+'&login='+admin.login+'&pass='+admin.pass;
        $http.get(query)
            .success(function(data) {
                that.prog = "Generando tablas del sistema";
                that.console += JSON.stringify(data);
                $http.get('index.php?op=installP2')
                    .success(function(data) {
                        that.prog = "Cargando módulos";
                        that.console += JSON.stringify(data);
                        $http.get('index.php?op=installP3')
                            .success(function(data) {
                                that.prog = "Terminando la instalación";
                                that.console += JSON.stringify(data);
                                $http.get('index.php?op=installP4&firstName='+admin.firstName+'&lastName='+admin.lastName+'&email='+admin.email+'&login='+admin.login+'&pass='+admin.pass+'&appRole=1&disabled=0')
                                    .success(function(data) {
                                        that.console += JSON.stringify(data);
                                        that.prog = "Instalacion terminada imprima esta página para guardar los datos de instalación \n elimine la carpeta install por seguridad";
                                    }
                                );
                            }
                        );
                    }
                );
            }
        );
    };
}]);




