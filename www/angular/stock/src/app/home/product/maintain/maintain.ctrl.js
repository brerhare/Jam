angular.module('stock')
	.controller('ProductMaintainCtrl', function ($scope, restFactory, notificationFactory) {
		var url            = 'http://stock.wireflydesign.com/server/api/stock_product/';
		var urlVat         = 'http://stock.wireflydesign.com/server/api/stock_vat/';			// for <select>
		var urlMarkupGroup = 'http://stock.wireflydesign.com/server/api/stock_markup_group/'; // for group tab

		$scope.rowCollection = [];
		$scope.displayMode = "list";
		$scope.formMode = "";
		$scope.item = {};
		$scope.vats = {};					// for <select>
		$scope.markupGroups = {};			// for group tab

		var getVats = function() {	// for <select>
			restFactory.getItem(urlVat)
				.success(function(data, status) {
					$scope.vats = data;
					for (var i = 0; i < $scope.vats.length; i++) {
						if ($scope.formMode == 'add') {
							if ($scope.vats[i].is_default == 1) {
								$scope.selectedVat = $scope.vats[i];
								$scope.item.stock_vat_id = $scope.vats[i].id;
								break;
							}
						}
						else {
							if ($scope.vats[i].id == $scope.item.stock_vat_id) {
								$scope.selectedVat = $scope.vats[i];
							}
						}
					}
				})
				.error(errorCallback);
		};


		$scope.getMarkupGroups = function() {	// for group tab
			restFactory.getItem(urlMarkupGroup)
				.success(function(data, status) {
					$scope.markupGroups = data;
				})
				.error(errorCallback);
		};
$scope.getMarkupGroups();	// @@TODO: make this only fire when user clicks the applicable tab

		$scope.pricetabGetCalcPrice = function(product, markupGroup) {
			//alert(product.name)
			$scope.priceCalcPrice = (markupGroup.price + (product.price * markupGroup.percent / 100) );
		}

		$scope.addItem = function() {
alert('Cant add more than this test product until groups exist'); return;
			$scope.item = {};
			getVats();
			$scope.formMode = "add";
			$scope.displayMode = "form";
		};

		$scope.editItem = function(id) {
			restFactory.getItem(url, id)
				.success(function(data, status) {
					$scope.item = data;
					getVats();
					$scope.formMode = "edit";
					$scope.displayMode = "form";
				})
				.error(errorCallback);
		};

		$scope.deleteItem = function(id) {
			restFactory.deleteItem(url, id)
				.success(function(data, status) {
					for (var i = 0; i < $scope.rowCollection.length; i++) {
						if ($scope.rowCollection[i].id == id) {
							$scope.rowCollection.splice(i, 1);
							$scope.displayedCollection = [].concat($scope.rowCollection);
							break;
						}
					}
				})
				.error(errorCallback);
		};

		$scope.cancelItem = function() {
			$scope.displayMode = "list";
		};

		$scope.saveItem = function() {
			if ($scope.formMode == "add") {
				restFactory.addItem(url, $scope.item)
					.success(function (data, status) {
						$scope.item.id = data.id;
						$scope.rowCollection.push($scope.item);		// insert new
						$scope.displayedCollection = [].concat($scope.rowCollection);
					})
					.error(errorCallback);
			}
			else if ($scope.formMode == "edit") {
				restFactory.updateItem(url, $scope.item.id, $scope.item)
					.success(function (data, status) {
						for (var i = 0; i < $scope.rowCollection.length; i++) {
							if ($scope.rowCollection[i].id == $scope.item.id) {
								$scope.rowCollection[i] = $scope.item;	// replace old with new
								$scope.displayedCollection = [].concat($scope.rowCollection);
								break;
							}
						}
					})
					.error(errorCallback);
			}
			$scope.displayMode = "list";
		};

		$scope.selchangeVat = function() {
			$scope.item.stock_vat_id = $scope.selectedVat.id;
		};

		var getItemSuccessCallback = function (data, status) {
			$scope.rowCollection = data;
			$scope.displayedCollection = [].concat($scope.rowCollection);
		};

		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};

		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);

	});
