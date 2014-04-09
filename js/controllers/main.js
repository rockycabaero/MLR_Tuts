var main = angular.module('main', ['ngRoute', 'ngAnimate']);

main.controller('mainController', function($rootScope, $location) { 
    $rootScope.baseURL  = $location.protocol() + ':\/\/' + $location.host() + '\/index.php\/';
    $rootScope.rootURL  = $location.protocol() + ':\/\/' + $location.host() + '\/index.php\/';
    
    $rootScope.activeUser;
    $rootScope.pageBusy = false;
});

main.config(function($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: 'ng_views/index.php',
        controller: 'mainController'
    });
});