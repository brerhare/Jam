angular.module('stock')
	.controller('ProductMaintainCtrl', function ($scope, restFactory, notificationFactory) {
		var url            = 'http://stock.wireflydesign.com/server/api/stock_product/';
		var urlVat         = 'http://stock.wireflydesign.com/server/api/stock_vat/';			// for <select>
		var urlGroup       = 'http://stock.wireflydesign.com/server/api/stock_group/';			// for <select>
		var urlMarkupGroup = 'http://stock.wireflydesign.com/server/api/stock_markup_group/'; // for group tab

		var maxLevels = 3;		// @TODO hardcoded. Same in group maint
		$scope.rowCollection = [];
		$scope.displayMode = "list";
		$scope.formMode = "";
		$scope.item = {};
		$scope.vats = {};							// for <select>
		var groups = [];							// All groups loaded once at startup
		$scope.levelGroups = new Array(maxLevels);	// An array ix for each level's <select>
		$scope.markupGroups = {};					// for group tab

		$scope.getMaxLevels = function() {
			return new Array(maxLevels);
		};

		var getVats = function() {	// for vat <select>
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

		var clearLevelsFrom = function(level) {
			for (var i = level; i < maxLevels; i++) {
				$scope.levelGroups[i] = {};
				// @@TODO disable this select seeing as its empty
				$scope.levelGroups[i].parentId = null;
				$scope.levelGroups[i].items = {};
			}
		};

		var setLevelContent = function(level) {
			$scope.levelGroups[level].items = [];
			for (var i = 0; i < groups.length; i++) {
				if (level == 0) {
					if (!groups[i].parent_id) {
						$scope.levelGroups[level].items.push(groups[i]);
					}
				}
			}

		};

		$scope.getGroups = function() {	// for the group levels <select>'s
			restFactory.getItem(urlGroup)
				.success(function(data, status) {
					for (var i = 0; i < data.length; i++) {
						groups[i] = data[i];
					}
					clearLevelsFrom(0);
					setLevelContent(0);
					//alert('!!! ' + $scope.levelGroups[0].items[0].name)
				})
				.error(errorCallback);
		};
		$scope.getGroups();

		$scope.getMarkupGroups = function() {	// for group tab
			restFactory.getItem(urlMarkupGroup)
				.success(function(data, status) {
					$scope.markupGroups = data;
				})
				.error(errorCallback);
		};
$scope.getMarkupGroups();	// @@TODO: make this only fire when user clicks the applicable tab, its wasteful like this

		$scope.pricetabGetCalcPrice = function(productPrice, markupGroup) {
			return (parseFloat(productPrice) + (productPrice * markupGroup.percent / 100) );
		};

		$scope.addItem = function() {
//alert('Cant add more than this test product until groups exist'); return;
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
alert('saving');
			if ($scope.formMode == "add") {
				restFactory.addItem(url, $scope.item)
					.success(function (data, status) {
alert('add - ok');
						$scope.item.id = data.id;
						$scope.rowCollection.push($scope.item);		// insert new
						$scope.displayedCollection = [].concat($scope.rowCollection);
					})
					.error(errorCallback);
			}
			else if ($scope.formMode == "edit") {
				restFactory.updateItem(url, $scope.item.id, $scope.item)
					.success(function (data, status) {
alert('edit - ok');
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

		$scope.selchangeGroup = function() {
			$scope.item.stock_group_id = $scope.selectedGroup.id;
		};

		var getItemSuccessCallback = function (data, status) {
			$scope.rowCollection = data;
			$scope.displayedCollection = [].concat($scope.rowCollection);
		};

		var errorCallback = function (data, status, headers, config) {
alert('notok');
			notificationFactory.error(data.ExceptionMessage);
		};

		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);

	});
