angular.module('stock')
	.controller('CustomerDetailCtrl', function ($scope, $state, $stateParams, restFactory, notificationFactory, ngDialog) {
		var url            = 'http://stock.wireflydesign.com/server/api/stock_customer/';
		var urlArea        = 'http://stock.wireflydesign.com/server/api/stock_area/';			// for <select>
		var urlMarkupGroup = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';	// for <select>

		$scope.rowCollection = [];
		$scope.item = {};
		$scope.areas = {};			// for <select>
		$scope.markupGroups = {};	// for <select>

		$scope.showTab = function(name) {
			$state.go(name, $stateParams, {location: 'replace'});
		};

		var getAreas = function() {	// for <select>
			restFactory.getItem(urlArea)
				.success(function(data, status) {
					$scope.areas = data;
					if ($scope.$parent.editMode == 'add') {
						$scope.selectedArea = $scope.areas[0];
						$scope.item.stock_area_id = $scope.areas[0].id;
					}
					else {
						for (var i = 0; i < $scope.areas.length; i++) {
							if ($scope.areas[i].id == $scope.item.stock_area_id) {
								$scope.selectedArea = $scope.areas[i];
							}
						}
					}
				})
				.error(errorCallback);
		};

		var getMarkupGroups = function() {	// for <select>
			restFactory.getItem(urlMarkupGroup)
				.success(function(data, status) {
					$scope.markupGroups = data;
					for (var i = 0; i < $scope.markupGroups.length; i++) {
						if ($scope.editMode == 'add') {
							if ($scope.markupGroups[i].is_default == 1) {
								$scope.selectedMarkupGroup = $scope.markupGroups[i];
								$scope.item.stock_markup_group_id = $scope.markupGroups[i].id;
								break;
							}
						}
						else {
							if ($scope.markupGroups[i].id == $scope.item.stock_markup_group_id) {
								$scope.selectedMarkupGroup = $scope.markupGroups[i];
							}
						}
					}
				})
				.error(errorCallback);
		};

		$scope.cancelItem = function() {
			window.history.back();
		};

		var validInput = function() {
			$scope.errorMsg = '';
			if ($scope.item.code === '')
				$scope.errorMsg = 'Code cant be blank';
			else if ($scope.item.name === '')
				$scope.errorMsg = 'Name cant be blank';

			if ($scope.errorMsg) {
				ngDialog.openConfirm({
					template: 'errorDialogTemplate',
					closeByEscape: true,
					scope: $scope //Pass the scope object if you need to access in the template
				});
				return 0;
			}
			return 1;
		};

		$scope.saveItem = function() {
        	if (!validInput())
        		return;
			if ($scope.$parent.editMode == "add") {
				restFactory.addItem(url, $scope.item)
					.success(function (data, status) {
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

		// Processing

		var initCustomerEditing = function() {
			getAreas();
			getMarkupGroups();
		};

		if ($scope.$parent.editMode == "add") {
			initCustomerEditing();
			$scope.item.code = "";
			$scope.item.name = "";
			$scope.item.address1 = "";
			$scope.item.address2 = "";
			$scope.item.address3 = "";
			$scope.item.post_code = "";
			$scope.item.contact = "";
			$scope.item.telephone = "";
			$scope.item.mobile = "";
			$scope.item.fax = "";
			$scope.item.email = "";
			$scope.item.discount_percent = 0.00;
			$scope.item.balance = 0.00;
			$scope.item.link_field = "";
			$scope.item.notes = "";
			$scope.status = 0;
			$scope.item.tax_reference = "";
			$scope.item.payment_method = "";
			$scope.item.stock_markup_group_id = 0;
			$scope.item.stock_area_id = 0;
		}
		else {
			restFactory.getItem(url, $scope.$parent.itemId)
				.success(function (data, status) {
					$scope.item = data;
					initCustomerEditing();
				})
				.error(errorCallback);

			if ($scope.$parent.editMode == "delete") {
				ngDialog.openConfirm({
					template: 'confirmDialogTemplate',
					closeByEscape: true,
					scope: $scope //Pass the scope object if you need to access in the template
				}).then(
					function(value) {			// OK
						deleteItem($scope.$parent.itemId);
					},
					function(value) {			// Cancel or do nothing
						window.history.back();
					}
				);
			}
		}

		$scope.selchangeArea = function() {
			$scope.item.stock_area_id = $scope.selectedArea.id;
		};
		$scope.selchangeMarkupGroup = function() {
			$scope.item.stock_markup_group_id = $scope.selectedMarkupGroup.id;
		};

		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};


	});
