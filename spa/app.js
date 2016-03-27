//'use strict';

var app = angular.module('app', [
  'ngRoute',
  'app.controllers',
  'app.services',
  'app.directives'
]).config(['$locationProvider', function($locationProvider) {

$routeProvider
    .when('/testimonials', {
    templateUrl: globals.baseUrl + '/js/partials/testimonials.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/communications', {
    templateUrl: globals.baseUrl + '/js/partials/communications.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/book-reviews', {
    templateUrl: globals.baseUrl + '/js/partials/book-reviews.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/communications', {
    templateUrl: globals.baseUrl + '/js/partials/communications.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/project-management', {
    templateUrl: globals.baseUrl + '/js/partials/project-management.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/grants-development', {
    templateUrl: globals.baseUrl + '/js/partials/grants-development.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/home', {
    templateUrl: globals.baseUrl + '/js/partials/home.html', 
    controller: 'Home',
    reloadOnSearch: false
  });
// $routeProvider
$locationProvider.html5Mode(true);
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