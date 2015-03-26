angular.module('stock')
	.controller('ProductDetailCtrl', function ($scope, $state, $stateParams, $location, restFactory, notificationFactory, ngDialog) {
		var url             = 'http://stock.wireflydesign.com/server/api/stock_product/';
		var urlVat          = 'http://stock.wireflydesign.com/server/api/stock_vat/';			// for <select>
		var urlGroup        = 'http://stock.wireflydesign.com/server/api/stock_group/';			// for <select>

		var maxLevels = 3;		// @TODO hardcoded. Same in group maint
		$scope.rowCollection = [];
		$scope.item = {};
		$scope.vats = {};							// for <select>. Has 'items[]', 'selectedVat
		$scope.selectedVat = { rate : null};
		var groups = [];							// All groups loaded once at startup
		$scope.levelGroups = new Array(maxLevels);	// An array ix for each level's <select>. Has 'parentId', 'selectedGroup', 'items[]'

		$scope.getMaxLevels = function() {
			return new Array(maxLevels);
		};

		$scope.showTab = function(name) {
			$state.go(name, $stateParams, {location: 'replace'});
		}

		// Vat select box. There is a default rate
		// ---------------------------------------

		var setSelectedVat = function() {	// for vat <select>
			for (var i = 0; i < $scope.vats.length; i++) {
				if ($scope.$parent.editMode == "add") {
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
		};

		var getVats = function() {
			restFactory.getItem(urlVat)
				.success(function(data, status) {
					$scope.vats = data;
					setSelectedVat();
				})
				.error(errorCallback);
		};

		$scope.selchangeVat = function() {
			$scope.item.stock_vat_id = $scope.selectedVat.id;
		};

		// Group levels. There is a hierarchy of select boxes
		// --------------------------------------------------

		var getGroupItemById = function (id) {
			for (var i = 0; i < groups.length; i++) {
				if (groups[i].id == id)
					return groups[i];
			}
			return null;
		};

		var getGroupTopParents = function() {
			var arr = [];
			for (var i = 0; i < groups.length; i++) {
				if (groups[i].parent_id < 1)
					arr.push(groups[i]);
			}
			return arr;
		};

		var getGroupChildren = function(item) {
			var arr = [];
			for (var i = 0; i < groups.length; i++) {
				if (groups[i].parent_id == item.id)
					arr.push(groups[i]);
			}
			return arr;
		};

		var getGroupSiblings = function(item) {
			var arr = [];
			for (var i = 0; i < groups.length; i++) {
				if (groups[i].parent_id == item.parent_id)
					arr.push(groups[i]);
			}
			return arr;
		};

		var clearLevelsDown = function(level) {
			for (var i = level; i < maxLevels; i++) {
				$scope.levelGroups[i] = {};
				// @@TODO disable this select seeing as its empty
				$scope.levelGroups[i].parentId = null;
				$scope.levelGroups[i].items = {};
				$scope.levelGroups[i].selectedGroup = null;
			}
		};

		$scope.selchangeGroup = function(level) {
			var id = $scope.levelGroups[level].selectedGroup.id;
			if (level == (maxLevels - 1)) {
				$scope.item.stock_group_id = id;
			}
			else {
				clearLevelsDown(level+1);
				fillGroupLevel(level+1, getGroupChildren(getGroupItemById(id)), getGroupItemById(id));
			}
		};

		var fillGroupLevel = function(level, itemArr, itemSelected) {
			if (itemArr.length > 0) {
				$scope.levelGroups[level].parentId = itemArr[0].parent_id;		// Any ix will do - all have same parent
				$scope.levelGroups[level].items = itemArr;
				if (itemSelected === null)
					itemSelected = itemArr[0];
				$scope.levelGroups[level].selectedGroup = itemSelected;
			}
		};

		var setupGroupSelects = function() {
			clearLevelsDown(0);
			if ($scope.$parent.editMode == "add")							// fill top level
				fillGroupLevel(0, getGroupTopParents(), null);
			else if ($scope.$parent.editMode == "edit") {					// fill all levels from bottom up
				var id = $scope.item.stock_group_id;				// ie the group the product is attached to
				for (var i = (maxLevels - 1); i >=0; i--) {
					fillGroupLevel(i, getGroupSiblings(getGroupItemById(id)), getGroupItemById(id));
					id = getGroupItemById(id).parent_id;
				}
			}
		};

		var getGroups = function() {	// for the group levels <select>'s
			restFactory.getItem(urlGroup)
				.success(function(data, status) {
					for (var i = 0; i < data.length; i++) {
						groups[i] = data[i];
					}
					setupGroupSelects();
				})
				.error(errorCallback);
		};


		// Ending off

		$scope.cancelItem = function() {
			window.history.back();
		};

        $scope.saveItem = function() {
            if ($scope.$parent.editMode == "add") {
                restFactory.addItem(url, $scope.item)
                    .success(function (data, status) {
						// Now update/add any price overrides					 @@WHUT?
						for (var i = 0; i < $scope.markupGroups.length; i++) {
							if ($scope.markupGroups[i].manual !== 0) {
								// delete old
								// add new
							}
						}
                        notificationFactory.success();
                        window.history.back();
                    })
                    .error(errorCallback);
            }
            else if ($scope.$parent.editMode == "edit") {
                restFactory.updateItem(url, $scope.item.id, $scope.item)
                    .success(function (data, status) {
                        notificationFactory.success();
                        window.history.back();
                    })
                    .error(errorCallback);
            }
        };
        var deleteItem = function(id) {
            restFactory.deleteItem(url, id)
                .success(function(data, status) {
                    window.history.back();
                })
                .error(errorCallback);
        };

		var errorCallback = function (data, status, headers, config) {
			alert(data.value);
			notificationFactory.error(data.ExceptionMessage);
		};


		// Start Processing


		var initProductEditing = function() {
			getVats();
			getGroups();
		};

        if ($scope.$parent.editMode == "add") {
			// @@ Initialise fields
			$scope.item.name = "";
			$scope.item.description = "";
			$scope.item.cost   = 0.00;
			$scope.item.weight = 0.00;
			$scope.item.width  = 0.00;
			$scope.item.height = 0.00;
			$scope.item.depth  = 0.00;
			$scope.item.volume = 0.00;
			$scope.item.stock_group_id = 0;
			$scope.item.stock_vat_id = 0;
			initProductEditing();
        }
        else {
            restFactory.getItem(url, $scope.$parent.itemId)
                .success(function (data, status) {
                    $scope.item = data;
					initProductEditing();
                })
                .error(errorCallback);

            if ($scope.$parent.editMode == "delete") {
                ngDialog.openConfirm({
                    template: 'confirmDialogTemplate',
                    closeByEscape: true,
                    scope: $scope //Pass the scope object if you need to access in the template
                }).then(
                    function(value) {           // OK
                        deleteItem($scope.$parent.itemId);
                    },
                    function(value) {           // Cancel or do nothing
                        window.history.back();
                    }
                );
            }
        }

	});
