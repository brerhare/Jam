var url = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';

toastr.options.timeOut = 1500;
toastr.options.positionClass = 'toast-bottom-right';
//toastr.options.progressBar = true; 

angular.module('stock')

	.factory('itemFactory', function ($http) {
		return {
			getItem: function (id) {
				id = typeof id !== 'undefined' ? id : "";
				return $http.get(url + id);
			},
			addItem: function (item) {
				return $http.post(url, item);
			},
			deleteItem: function (item) {
				return $http.delete(url + item.id);
			},
			updateItem: function (item) {
				return $http.put(url + item.id, item);
			}
		};
	})

	.factory('notificationFactory', function () {
		return {
			success: function () {
//alert('success - hold for log');
				toastr.success("Success");
			},
			error: function (text) {
//alert('error - hold for log');
				toastr.error(text, "Error!");
			}
		};
	})

	.controller('CustomerMarkupGroupCtrl', function ($scope, itemFactory, notificationFactory) {
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
					$scope.items[i].editDescription = false;
					$scope.items[i].editPercent = false;
				}
			}
		};

		$scope.toggleEditDescription = function (item) {
			if (!$scope.addMode)
			{
				item.editDescription = !item.editDescription;
				if (item.editDescription)
					$scope.uneditAllBut(item);
			}
		};

		$scope.toggleEditPercent = function (item) {
			if (!$scope.addMode)
			{
				item.editPercent = !item.editPercent;
				if (item.editPercent)
					$scope.uneditAllBut(item);
			}
		};
 
		$scope.editDescriptionEnd = function(keyEvent, item) {
			if (event.keyCode == 13 && item.description){
				$scope.toggleEditDescription(item);
				$scope.updateItem(item);
			}
		};

		$scope.editPercentEnd = function(keyEvent, item) {
			if (event.keyCode == 13 && item.percent){
				$scope.toggleEditPercent(item);
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
			for (var i = 0; i < $scope.items.length; i++) {
				if ($scope.items[i].is_default == 1)
					$scope.default_item = $scope.items[i].id;
			}
		};
 
// ----------------------------------------------------------------------------------------

		var successCallback = function (data, status, headers, config) {
			notificationFactory.success();
			return itemFactory.getItem().success(getItemSuccessCallback).error(errorCallback);
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
 
		itemFactory.getItem().success(getItemSuccessCallback).error(errorCallback);
 
		$scope.addItem = function () {
			itemFactory.addItem($scope.item).success(successPostCallback).error(errorCallback);
		};
 
		$scope.deleteItem = function (item) {
			itemFactory.deleteItem(item).success(successCallback).error(errorCallback);
		};
 
		$scope.updateItem = function (item) {
			itemFactory.updateItem(item).success(successCallback).error(errorCallback);
		};
	});
