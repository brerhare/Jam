angular.module('stock')
	.controller('ProductDetailBarcodesCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

		var url = 'http://stock.wireflydesign.com/server/api/custom_product_maintain_tab_barcode_getall/' + $scope.$parent.item.id;
		$scope.items = [];
		$scope.addMode = false;

		$scope.toggleAddMode = function () {
			$scope.addMode = !$scope.addMode;
			if ($scope.addMode)
				$scope.uneditAllBut(null);
		};

		$scope.uneditAllBut = function(item) {
			for (var i = 0; i < $scope.items.length; i++) {
				if ($scope.items[i] != item) {
					$scope.items[i].editBarcode = false;
				}
			}
		};

		$scope.toggleEditBarcode = function (item) {
			if (!$scope.addMode)
			{
				item.editBarcode = !item.editBarcode;
				if (item.editBarcode)
					$scope.uneditAllBut(item);
			}
		};

		$scope.editBarcodeEnd = function(keyEvent, item) {
			if (event.keyCode == 13 && item.barcode){
				$scope.toggleEditBarcode(item);
				$scope.updateItem(item);
			}
		};

// ----------------------------------------------------------------------------------------

		var getItemSuccessCallback = function (data, status) {
			$scope.items = data;
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
			notificationFactory.error("");
			//notificationFactory.error(data.ExceptionMessage);
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
