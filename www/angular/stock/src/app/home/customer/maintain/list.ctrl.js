angular.module('stock')
	.controller('CustomerListCtrl', function ($scope, $state, restFactory, notificationFactory) {

		var url            = 'http://stock.wireflydesign.com/server/api/stock_customer/';

		$scope.addItem = function()	{
			$scope.$parent.editMode = "add";
			$state.go('home.customer-detail');
		};

		$scope.editItem = function(id) {
			$scope.$parent.editMode = "edit";
			$scope.$parent.itemId = id;
			$state.go('home.customer-detail');
		};

		$scope.deleteItem = function(id) {
			$scope.$parent.editMode = "delete";
			$scope.$parent.itemId = id;
			$state.go('home.customer-detail');
		};

		var getItemSuccessCallback = function (data, status) {
			$scope.rowCollection = data;
			$scope.displayedCollection = [].concat($scope.rowCollection);
		};

		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};

		$scope.rowCollection = [];
		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);

	});
