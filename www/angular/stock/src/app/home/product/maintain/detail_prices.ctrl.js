angular.module('stock')
	.controller('ProductDetailPricesCtrl', function ($scope, restFactory, notificationFactory) {

		var urlMarkupGroup  = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';
		var urlProductPrice = 'http://stock.wireflydesign.com/server/api/stock_product_price/';
		var urlGetAll       = 'http://stock.wireflydesign.com/server/api/custom_product_maintain_tab_prices_getall/' + $scope.$parent.item.id;
		var urlSaveAll      = 'http://stock.wireflydesign.com/server/api/custom_product_maintain_tab_prices_saveall/' + $scope.$parent.item.id;

		$scope.rowCollection = [];

		$scope.markupGroups = {};					// for group tab

		// Product price (for markup groups - loaded for product being edited/added)
		// -------------

		var getMarkupGroupsProductPrices = function() {	// for group tab
			for (var i = 0; i < $scope.markupGroups.length; i++)
				$scope.markupGroups[i].manual = '';
			restFactory.getItem(urlProductPrice)
				.success(function(data, status) {
					if (data.length > 0) {
						for (i = 0; i < data.length; i++) {
							var thisMarkupGroup = getMarkupGroupItemById(data[i].id);
							if (thisMarkupGroup) {
								thisMarkupGroup.manual[i] = data;
							}
						}
					}
					getManualPrices();
				})
				.error(errorCallback);
		};

		// Customer markup group (loaded at startup)
		// ---------------------

		var getMarkupGroupItemById = function (id) {
			for (var i = 0; i < $scope.markupGroups.length; i++) {
				if ($scope.markupGroups[i].id == id)
					return $scope.markupGroups[i];
			}
			return null;
		};

		var getMarkupGroups = function() {	// for group tab
			restFactory.getItem(urlMarkupGroup)
				.success(function(data, status) {
					$scope.markupGroups = {};
					$scope.markupGroups = data;
					getMarkupGroupsProductPrices();
				})
				.error(errorCallback);
		};

		// Tab area - Prices tab
		// ---------------------
		var pricetabGetPrice = function(markupGroup){
			var price = markupGroup.manual === '' ? 0 : parseFloat(markupGroup.manual);
			if (price === 0) {
				price = $scope.pricetabGetCalculatedPrice(markupGroup);
			}
			return price;
		};

		$scope.pricetabGetCalculatedPrice = function(markupGroup) {
			var val =  (parseFloat($scope.$parent.item.cost) + ($scope.$parent.item.cost * markupGroup.percent / 100));
			return val.toFixed(2);
		};
		$scope.pricetabGetVariance = function(markupGroup) {
			return ((pricetabGetPrice(markupGroup) - $scope.pricetabGetCalculatedPrice(markupGroup)) / $scope.pricetabGetCalculatedPrice(markupGroup) * 100).toFixed(2) ;
		};

		$scope.pricetabGetProfit = function(markupGroup) {
			return (pricetabGetPrice(markupGroup) - $scope.$parent.item.cost);
		};
		$scope.pricetabGetGrossPrice = function(markupGroup) {
			var price = pricetabGetPrice(markupGroup);
			var val =  (parseFloat(price) + ((price * parseFloat($scope.$parent.selectedVat.rate) / 100))).toFixed(2);
			return val;
		};


		// Ending off


		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error("");
			//notificationFactory.error(data.ExceptionMessage);
		};

		var getManualPricesSuccessCallback = function (data, status) {
			for (var i = 0; i < data.length; i++) {
				for (var j = 0; j < $scope.markupGroups.length; j++) {
					if (data[i].stock_markup_group_id == $scope.markupGroups[j].id)
						$scope.markupGroups[j].manual = data[i].price;
				}
			}
		};

		var saveManualPricesSuccessCallback = function (data, status) {
			notificationFactory.success();
		};

		var getManualPrices = function() {
			restFactory.getItem(urlGetAll).success(getManualPricesSuccessCallback).error(errorCallback);
		};

		$scope.saveManualPrices = function() {
			var sendItems = [];
			for (var k = 0; k < $scope.markupGroups.length; k++) {
				sendItems[k] = {};
				sendItems[k].stock_markup_group_id = $scope.markupGroups[k].id;
				sendItems[k].price = $scope.markupGroups[k].manual;
			}
//			alert(JSON.stringify(sendItems));
			restFactory.addItem(urlSaveAll, sendItems).success(saveManualPricesSuccessCallback).error(errorCallback);
		};

		// Start

		getMarkupGroups();

	});
