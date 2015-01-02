angular.module('stock')
	.controller('CustomerMaintainCtrl', function ($scope, restFactory, notificationFactory) {
		var url = 'http://stock.wireflydesign.com/server/api/stock_customer/';

		$scope.rowCollection = [];

		var getItemSuccessCallback = function (data, status) {
			$scope.rowCollection = data;
			$scope.displayedCollection = [].concat($scope.rowCollection);
		};

		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};

		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);
	})

	.controller('CustomerAddCtrl', function($scope, $state, $stateParams) {
		$scope.addItem = function() {
			//$scope.rowCollection.push({'id': 999, 'name':'kim', 'discount_percent':100, 'telephone':'07899752030', 'forma_de_pago':'el-nino'});
			alert('added customer');
			$state.go('home.customer-maintain');
		};

	});

