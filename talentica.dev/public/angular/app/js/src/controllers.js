angular.module('todoApp.controllers', ['todoApp.services', 'ngResource'])
	.controller('todoApp.controllers.login', 
		['todoApp.services.basicAuth', '$scope', '$location',
		 function (basicAuth, $scope, $location) {
			
			$scope.setLoginCredentials = function () {
				// set login credentials
				basicAuth.setCredentials($scope.username, $scope.password);
				// redirect to lists page
				$location.path('/lists');
			};			
		}]
	)
	
	.controller('todoApp.controllers.lists', ['$scope', '$http', function ($scope, $http) {
		$http({method: 'GET', url: '/api/v1/lists'}).
		    success(function(data, status, headers, config) {
		      $scope.lists = data;
		    }).
		    error(function(data, status, headers, config) {
		    	console.log(data);
		    })
		;
	}])
;