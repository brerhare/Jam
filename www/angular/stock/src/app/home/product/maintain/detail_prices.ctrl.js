angular.module('stock')
	.controller('ProductDetailPricesCtrl', function ($scope, restFactory, notificationFactory) {

		var urlMarkupGroup  = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';	// for price tab
		var urlProductPrice = 'http://stock.wireflydesign.com/server/api/stock_product_price/';	// for price tab

		var maxLevels = 3;		// @TODO hardcoded. Same in group maint
		$scope.rowCollection = [];

		$scope.levelGroups = new Array(maxLevels);	// An array ix for each level's <select>. Has 'parentId', 'selectedGroup', 'items[]'
		$scope.markupGroups = {};					// for group tab

		$scope.getMaxLevels = function() {
			return new Array(maxLevels);
		};


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
			var price = markupGroup.manual == '' ? 0 : parseFloat(markupGroup.manual);
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
			alert(data.value);
			notificationFactory.error(data.ExceptionMessage);
		};


		// Start


		getMarkupGroups();

	});
