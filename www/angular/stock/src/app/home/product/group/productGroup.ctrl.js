
toastr.options.timeOut = 1500;
toastr.options.positionClass = 'toast-bottom-right';
//toastr.options.progressBar = true; 

angular.module('stock')
	.controller('ProductGroupCtrl', function ($scope, restFactory, notificationFactory) {
		var url = 'http://stock.wireflydesign.com/server/api/stock_group/';
		var maxLevels = 3;		// @TODO hardcoded. Same in product maint
		$scope.levels = [];
		var ajaxLevel = 0;

		// Set initial values
		for (var i = 0; i < maxLevels; i++) {
			$scope.levels[i] = {};
			$scope.levels[i].addMode = false;
			$scope.levels[i].selectedItemIx = -1;		// There are 2 'selected' fields. This one to quickly indicate the ix of the selected item, and a 'selected' property within each item for html class checking
			$scope.levels[i].items = {};
			$scope.levels[i].levelNo = i;
		}

		$scope.toggleAddMode = function (level) {
			if ((level > 0) && ($scope.levels[level-1].selectedItemIx == -1)) {
				toastr.warning("Which group?");
				$scope.levels[level].addMode = false;
				return;
			}
			$scope.levels[level].addMode = !$scope.levels[level].addMode;
			if ($scope.levels[level].addMode)
				uneditAllBut(level, null);
		};

		var uneditAllBut = function(level, item) {
			for (var i = 0; i < $scope.levels[level].items.length; i++) {
				if ($scope.levels[level].items[i] != item) {
					$scope.levels[level].items[i].editName = false;
				}
			}
		};

		$scope.toggleEditName = function (level, item) {
			if (($scope.editName) || ($scope.addMode))
				return;
			item.editName = !item.editName;
			if (item.editName)
				uneditAllBut(level, item);
		};

		$scope.editNameEnd = function(level, keyEvent, item) {
			if (event.keyCode == 13 && item.name){
				$scope.toggleEditName(level, item);
				$scope.updateItem(level, item);
			}
		};

		$scope.selectName = function(level, item) {
			if (($scope.editName) || ($scope.addMode))
				return;
			for (var i = 0; i < $scope.levels[level].items.length; i++) {
				if ($scope.levels[level].items[i] == item) {
					item.selected = true;
					$scope.levels[level].selectedItemIx = i;
					clearChildLevels(level, item);
					if (level < maxLevels) {
						ajaxLevel = level + 1;
						restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);
					}
				}
				else {
					$scope.levels[level].items[i].selected = false;
				}
			}
			uneditAllBut(level, null);
		};

		var clearChildLevels = function(parentLevel, parentItem) {
			for (var i=(parentLevel+1); i < maxLevels; i++) {
				$scope.levels[i].selectedItemIx = -1;
				$scope.levels[i].addMode = false;
				if (i == (parentLevel+1)) {	// one immediately below us
					$scope.levels[i].parentId = parentItem.id;
				}
				else {
					$scope.levels[i].parentId = null;
					$scope.levels[i].items = {};
				}
			}
		};

// ----------------------------------------------------------------------------------------

		var getItemSuccessCallback = function (data, status) {
			$scope.levels[ajaxLevel].items = [];
			for (var i = 0; i < data.length; i++) {
				if (ajaxLevel === 0) {									// top level
					if (data[i].parent_id === null)	{
						$scope.levels[ajaxLevel].items.push(data[i]);
					}
				}
				else {													// not top level
					if (data[i].parent_id == $scope.levels[ajaxLevel].parentId)
						$scope.levels[ajaxLevel].items.push(data[i]);
				}
			}
			//$scope.levels[ajaxLevel].items = data;
		};

// ----------------------------------------------------------------------------------------

		var successCallback = function (data, status, headers, config) {
			notificationFactory.success();
			return restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);
		};
 
		var successPostCallback = function (data, status, headers, config) {
			successCallback(data, status, headers, config).success(function () {
				$scope.toggleAddMode(ajaxLevel);
				$scope.levels[ajaxLevel].item = {};
			});
		};
 
		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};
 
		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);
 
		$scope.addItem = function (level) {
			ajaxLevel = level;
			if (level === 0) {
				$scope.levels[level].item.parent_id = null;
			}
			else {
				$scope.levels[level].item.parent_id = $scope.levels[level - 1].items[$scope.levels[level - 1].selectedItemIx].id;
			}
			restFactory.addItem(url, $scope.levels[level].item).success(successPostCallback).error(errorCallback);
		};
 
		$scope.deleteItem = function (level, item) {
			ajaxLevel = level;
			restFactory.deleteItem(url, item.id).success(successCallback).error(errorCallback);
		};
 
		$scope.updateItem = function (level, item) {
			ajaxLevel = level;
			restFactory.updateItem(url, item.id, item).success(successCallback).error(errorCallback);
		};
	})

	.directive('sglclick', ['$parse', function($parse) {
		return {
			restrict: 'A',
			link: function(scope, element, attr) {
				var fn = $parse(attr.sglclick);
				var delay = 300, clicks = 0, timer = null;
				element.on('click', function (event) {
					clicks++;  //count clicks
					if(clicks === 1) {
						timer = setTimeout(function() {
							scope.$apply(function () {
								fn(scope, { $event: event });
							});
							clicks = 0;             //after action performed, reset counter
						}, delay);
					} else {
						clearTimeout(timer);    //prevent single-click action
						clicks = 0;             //after action performed, reset counter
					}
				});
			}
		};
	}]);
