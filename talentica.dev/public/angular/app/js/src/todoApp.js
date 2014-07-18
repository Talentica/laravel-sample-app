/**
 * 
 */

'use strict';


// Declare app level module which depends on filters, and services
angular.module('todoApp', [
	  'ngRoute',
	//  'myApp.filters',
	//  'myApp.services',
	//  'myApp.directives',
	  'todoApp.controllers'
	])
	.config(['$routeProvider', function($routeProvider) {
		$routeProvider.when('/login', {templateUrl: 'angular/app/partials/login.html', controller: 'todoApp.controllers.login'});
		$routeProvider.when('/lists', {templateUrl: 'angular/app/partials/lists.html', controller: 'todoApp.controllers.lists'});
		$routeProvider.otherwise({redirectTo: '/login'});
	}])
;