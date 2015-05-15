angular.module('stock')
	.controller('ProductLocationCtrl', function ($scope, restFactory, notificationFactory) {
		var url = 'http://stock.wireflydesign.com/server/api/stock_location/';
		$scope.items = [];
		$scope.addMode = false;
		$scope.default_item = null;
 
		$scope.toggleAddMode = function () {
			$scope.addMode = !$scope.addMode;
			if ($scope.addMode)
				$scope.uneditAllBut(null);
		};
 
		$scope.uneditAllBut = function(item) {
			for (var i = 0; i < $scope.items.length; i++) {
				if ($scope.items[i] != item) {
					$scope.items[i].editName = false;
				}
			}
		};

		$scope.toggleEditName = function (item) {
			if (!$scope.addMode)
			{
				item.editName = !item.editName;
				if (item.editName)
					$scope.uneditAllBut(item);
			}
		};

		$scope.editNameEnd = function(keyEvent, item) {
			if (event.keyCode == 13 && item.name){
				$scope.toggleEditName(item);
				$scope.updateItem(item);
			}
		};

		$scope.setDefaultItem = function(item) {
			for (var i = 0; i < $scope.items.length; i++) {
				if ($scope.items[i].id == $scope.default_item) {
					$scope.items[i].is_default = 0;
					$scope.updateItem($scope.items[i]);
				}
			}
			item.is_default = 1;
			$scope.updateItem(item);	
		};

// ----------------------------------------------------------------------------------------

		var getItemSuccessCallback = function (data, status) {
			$scope.items = data;
		};
 
// ----------------------------------------------------------------------------------------

		var successCallback = function (data, status, headers, config) {
			notificationFactory.success();
			return restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);
		};
 
		var successPostCallback = function (data, status, headers, config) {
			successCallback(data, status, headers, config).success(function () {
				$scope.toggleAddMode();
				$scope.item = {};
			});
		};
 
		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};
 
		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);
 
		$scope.addItem = function () {
			restFactory.addItem(url, $scope.item).success(successPostCallback).error(errorCallback);
		};
 
		$scope.deleteItem = function (item) {
			restFactory.deleteItem(url, item.id).success(successCallback).error(errorCallback);
		};
 
		$scope.updateItem = function (item) {
			restFactory.updateItem(url, item.id, item).success(successCallback).error(errorCallback);
		};
	});

