angular.module('todoApp.api', ['ngResource'])
	.factory('todoApp.api', ['$http', function () {
		
		var getLists = function () {
			return $http({
				method: 'GET', 
				url: '/api/v1/lists'
			});
		}
		
		var addList = function(list) {
			return $http({
				method: 'POST',
				url: '/api/v1/lists',
				data: list
			});
		}
		
		var updateList = function(list) {
			return http({
				method: 'PUT',
				url: '/api/v1/lists/' + list.id,
				data: list
			});
		}
		
		return {
			getLists: getLists,
			addList: addList,
			updateList: updateList
		};
	}])