angular.module('stock')
	.controller('CustomerListCtrl', function ($scope, $http) {

/*
    $scope.addItem = function() {
        $location.path("add.html");
    };
    $scope.editItem = function(id) {
      $location.path("edit.html");
    };
    $scope.deleteItem = function(id) {
      $location.path("app/home/customer/maintain/delete.html");
    };
    $scope.outputPDF = function() {
      $location.path("pdf.html");
    };

      $scope.getData();
      */
      /*
      $scope.addCustomer = function() {
        alert('add customer');
      };

      $scope.editCustomer = function(id) {
        alert('edit customer' + id);
      };

      $scope.deleteCustomer = function(id) {
        alert('delete customer' + id);
      };

      $scope.outputPDF = function() {
        alert('pdf');
      };
*/


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

});
