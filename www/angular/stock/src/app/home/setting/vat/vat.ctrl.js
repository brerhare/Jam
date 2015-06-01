
//toastr.options.timeOut = 1500;
//toastr.options.positionClass = 'toast-bottom-right';
//////toastr.options.progressBar = true;

angular.module('stock')
	.controller('SettingVatCtrl', function ($scope, restFactory, notificationFactory) {
		var url = 'http://stock.wireflydesign.com/server/api/stock_vat/';
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
					$scope.items[i].editRate = false;
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

		$scope.toggleEditRate = function (item) {
			if (!$scope.addMode)
			{
				item.editRate = !item.editRate;
				if (item.editRate)
					$scope.uneditAllBut(item);
			}
		};
 
		$scope.editDescriptionEnd = function(keyEvent, item) {
			if (event.keyCode == 13 && item.description){
				$scope.toggleEditDescription(item);
				$scope.updateItem(item);
			}
		};

		$scope.editRateEnd = function(keyEvent, item) {
			if (event.keyCode == 13 && item.rate){
				$scope.toggleEditRate(item);
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
//alert(JSON.stringify($scope.items));
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

