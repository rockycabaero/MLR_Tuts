main.config(function($routeProvider) {
    $routeProvider.when('/customer/signup', {
        templateUrl: 'ng_views/customer/register.php',
        controller: 'mainController'
    });
});
main.controller('customerSignUpController', function($http, $scope, $rootScope) {

    $scope.custUsername;
    $scope.custPassword;
    $scope.custAddress.building;
    $scope.custAddress.street;
    $scope.custAddress.province;
    $scope.custAddress.country;
    $scope.custPerson.firstname;
    $scope.custPerson.lastname;
    $scope.custPerson.birthMonth;
    $scope.custPerson.birthDay;
    $scope.custPerson.birthYear;
    $scope.custPerson.gender;
    $scope.custEmail;
    $scope.custContact = [];
    
    $scope.register = function() {
        
    };
});


