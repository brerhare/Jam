
toastr.options.timeOut = 1500;
toastr.options.positionClass = 'toast-bottom-right';
//toastr.options.progressBar = true; 

angular.module('stock')
	.controller('ProductGroupCtrl', function ($scope, restFactory, notificationFactory) {
		var url = 'http://stock.wireflydesign.com/server/api/stock_group/';
		var maxLevels = 3;
		$scope.levels = [];
		var ajaxLevel = 0;

		// Set initial values
		for (var i = 0; i < maxLevels; i++) {
			$scope.levels[i] = {};
			$scope.levels[i].parent = null;
			$scope.levels[i].addMode = false;
			$scope.levels[i].selectedItem = -1;		// There are 2 'selected' fields. This one to quickly indicate the ix of the selected item, and a 'selected' property within each item for html class checking
			$scope.levels[i].items = {};
			$scope.levels[i].levelNo = i;
		}
console.log($scope.levels);

		$scope.toggleAddMode = function (level) {
			$scope.levels[level].addMode = !$scope.levels[level].addMode;
			if ($scope.levels[level].addMode)
				uneditAllBut(level, null);
		};

		uneditAllBut = function(level, item) {
			for (var i = 0; i < $scope.levels[level].items.length; i++) {
				if ($scope.levels[level].items[i] != item) {
					$scope.levels[level].items[i].editName = false;
				}
			}
		};

		$scope.toggleEditName = function (level, item) {
			if (!$scope.addMode)
			{
				for (var i = 0; i < $scope.levels[level].items.length; i++) {
					if ($scope.levels[level].items[i] == item) {
						item.selected = true;
						$scope.levels[level].selected = i;
					}
					else {
						$scope.levels[level].items[i].selected = false;
					}
				}
				item.selected = true;

return;
				item.editName = !item.editName;
				if (item.editName)
					uneditAllBut(level, item);
			}
		};

		$scope.editNameEnd = function(level, keyEvent, item) {
			if (event.keyCode == 13 && item.name){
				$scope.toggleEditName(level, item);
				$scope.updateItem(level, item);
			}
		};

// ----------------------------------------------------------------------------------------

		var getItemSuccessCallback = function (data, status) {
			$scope.levels[ajaxLevel].items = [];
			console.log(data);
			for (var i = 0; i < data.length; i++) {
				if (ajaxLevel == 0) {									// top level
					if (data[i].parent_id == null)	{
						$scope.levels[ajaxLevel].items.push(data[i]);
					}
				}
				else {													// not top level
					if (data[i].parent_id == $scope.levels[ajaxLevel-1].item[$scope.levels[ajaxLevel-1].selected])
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
	});
