angular.module('stock')
	.controller('CustomerMaintainCtrl', function ($scope, $http) {

/****
		// Get the full customer list from the server
		$scope.url = "http://stock.wireflydesign.com/server/data.php";
		$scope.total = "loading ";
		$http.get($scope.url)
		.success(function(data, status, headers, config) {
			$scope.rowCollection = [];
			$scope.rowCollection = data;
//				$scope.rowCollection = angular.fromJson(data);
			$scope.displayedCollection = [].concat($scope.rowCollection);
		});
****/

			$scope.rowCollection = [];

		$http({
			method: 'POST',
			url: 'http://stock.wireflydesign.com/server/data.php',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
				//'Authorization': 'Splunk ' + $scope.sessionKey
			},
			data: {action: 'get', table: 'customer', id: '*'}
		})
		.success(function (data, status, headers, config) {                    
			$scope.rowCollection = data;
//			$scope.rowCollection = angular.fromJson(data);
			$scope.displayedCollection = [].concat($scope.rowCollection);
		})
		.error(function (data, status, headers, config) {
			alert("error status: " + status);
		});

})

.controller('CustomerAddCtrl', function($scope, $state, $stateParams) {
	$scope.addItem = function() {
		$scope.rowCollection.push({'id': 999, 'name':'kim', 'discount_percent':100, 'telephone':'07899752030', 'forma_de_pago':'el-nino'});
        alert('added customer');
  		$state.go('home.customer-maintain');
      };

});
