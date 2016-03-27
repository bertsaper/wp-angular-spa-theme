'use strict';

var app = angular.module('app', [
  'ngRoute',
  'app.controllers',
  'app.services',
  'app.directives'
]).config(function($locationProvider, $routeProvider) {


$locationProvider.html5Mode(true);
$routeProvider
    .when('/sample-page', {
    templateUrl: globals.baseUrl + '/js/partials/sample-page.html', 
    controller: 'Home',
    reloadOnSearch: false
  });

$routeProvider
  .otherwise({
    redirectTo: '/home'
  });
});
app.run(['$location', '$rootScope', function($location, $rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {

        if (current.hasOwnProperty('$$')) {

            $rootScope.title = current.$$route.title;
        }
    });
}]);