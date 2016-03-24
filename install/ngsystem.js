"use strict";
/*global angular*/
var appSystem = angular.module('ngsystem', ['ui.router']);
appSystem.tplPath = "modules/system/tpl/";
appSystem.config(['$locationProvider', '$stateProvider', function ($locationProvider, $stateProvider) {
        $stateProvider
        .state('company', {
                            url: '/company',
                            views: {
                                'content': {
                                    templateUrl: app.tplPath+'company.html'
                                }
                        },
                controller: 'ctrlCompany'
        });
        $stateProvider
        .state('invoiceSerial', {
                            url: '/invoiceSerial',
                            views: {
                                'content': {
                                    templateUrl: app.tplPath+'invoiceSerial.html'
                                }
                        },
                controller: 'ctrlInvoiceSerial'
        });
        $stateProvider
        .state('paymentMethod', {
                            url: '/paymentMethod',
                            views: {
                                'content': {
                                    templateUrl: app.tplPath+'paymentMethod.html'
                                }
                        },
                controller: 'ctrlPaymentMethod'
        });
        $stateProvider
        .state('cash', {
                            url: '/cash',
                            views: {
                                'content': {
                                    templateUrl: tplPath+'cash.html'
                                }
                        },
                controller: 'ctrlcash'
        });
}]);
appSystem.controller('mainCtrl', ['$http',function($http){
    this.navbarItems = [];    
    this.getModuleData = function(){
        var that = this;
        $http.get('modules/system/system.json').success(function(data){
                that.navbarItems = data.navbarItems;
            });
    };
    this.getModuleData();    
}]);
appSystem.controller('ctrlCompany', ['$http',function($http){
    this.company = {};
    this.fields = {};
    this.get = function(){
        var that = this;
        $http.get('index.php?table=company&op=read').success(function(data){
                that.company = data[0];
        });
    };
    this.set = function(){
        var that = this;
        var cData = this.readForm();
        $http.get('index.php?table=company&op=set'+cData).success(function(data){
                that.company = data[0];
        });
    };
    this.insert = function(){
        var that = this;
        var cData = this.readForm();
        $http.get('index.php?table=company&op=insert'+cData).success(function(data){
                that.company = data[0];
        });
    };
    this.getData = function(){
        var that = this;
        $http.get('modules/system/system.json').success(function(data){
                that.fields = data.tableColums.company;
            });
    };
    this.readForm = function(){
        var i;
        var str = "";
        for(i in this.fields){
            str += "&"+this.fields+"="+this.company[this.fields[i]];
        }
        return str;
    }
    this.getData();    
}]);
appSystem.controller('ctrlInvoiceSerial', ['$http',function($http){
        
}]);
appSystem.controller('ctrlPaymentMethod', ['$http',function($http){
        
}]);
appSystem.controller('ctrlcash', ['$http',function($http){
        
}]);

