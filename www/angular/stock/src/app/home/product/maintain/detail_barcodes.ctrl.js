angular.module('stock')
	.controller('ProductDetailBarcodesCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

		var urlGetAll = 'http://stock.wireflydesign.com/server/api/custom_product_maintain_tab_barcode_getall/' + $scope.$parent.item.id;
		var urlDelete = 'http://stock.wireflydesign.com/server/api/custom_product_maintain_tab_barcode_delete/';
		var urlAdd    = 'http://stock.wireflydesign.com/server/api/custom_product_maintain_tab_barcode_add/';

		$scope.tabitems = [];
		$scope.newitem = {};
		$scope.addMode = false;

		$scope.toggleAddMode = function () {
			$scope.addMode = !$scope.addMode;
		};

// ----------------------------------------------------------------------------------------

		var getItemSuccessCallback = function (data, status) {
			$scope.tabitems = data;

		};

// ----------------------------------------------------------------------------------------

		var successCallback = function (data, status, headers, config) {
			notificationFactory.success();
			return restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);
		};

		var successPostCallback = function (data, status, headers, config) {
			//successCallback(data, status, headers, config).success(function () {
				$scope.toggleAddMode();
				$scope.newitem = {};
			//});
			restFactory.getItem(urlGetAll).success(getItemSuccessCallback).error(errorCallback);	// reload all after adding
		};

		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error("");
			//notificationFactory.error(data.ExceptionMessage);
		};

		restFactory.getItem(urlGetAll).success(getItemSuccessCallback).error(errorCallback);

		$scope.addItem = function () {
			// Prepend the product code so the backend can update the link file too...
			$scope.newitem['product_id'] = $scope.$parent.item.id;
			restFactory.addItem(urlAdd, $scope.newitem).success(successPostCallback).error(errorCallback);
		};

		$scope.deleteItem = function (tabitem) {
			restFactory.deleteItem(urlDelete, tabitem.id).success(successCallback).error(errorCallback);
		};

	});
