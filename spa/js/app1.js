//'use strict';

var app = angular.module('app', [
  'ngRoute',
  'app.controllers',
  'app.services',
  'app.directives'
]).config(function ($routeProvider) {

$routeProvider
    .when('/home', {
    templateUrl: globals.baseUrl + '/js/partials/home.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/project-management', {
    templateUrl: globals.baseUrl + '/js/partials/project-management.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/curriculum-and-assessment-design', {
    templateUrl: globals.baseUrl + '/js/partials/curriculum-and-assessment-design.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/communications', {
    templateUrl: globals.baseUrl + '/js/partials/communications.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/grants-development', {
    templateUrl: globals.baseUrl + '/js/partials/grants-development.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/book-reviews', {
    templateUrl: globals.baseUrl + '/js/partials/book-reviews.html', 
    controller: 'Home',
    reloadOnSearch: false
  });$routeProvider
    .when('/testimonials', {
    templateUrl: globals.baseUrl + '/js/partials/testimonials.html', 
    controller: 'Home',
    reloadOnSearch: false
  });

$routeProvider
  .otherwise({
    redirectTo: '/home'
  });
});

app.run(['', '', function(, ) {
    .('', function (event, current, previous) {

        if (current.hasOwnProperty('$')) {

            .title = current.$.title;
        }
    });
}]);
