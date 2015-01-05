angular.module('stock')
	.controller('CustomerMaintainCtrl', function ($scope, restFactory, notificationFactory) {
		var url     = 'http://stock.wireflydesign.com/server/api/stock_customer/';
		var urlArea = 'http://stock.wireflydesign.com/server/api/stock_area/';					// for <select>
		var urlMarkupGroup = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';	// for <select>

		$scope.rowCollection = [];
		$scope.displayMode = "list";
		$scope.formMode = "";
		$scope.item = {};
		$scope.areas = {};			// for <select>
		$scope.markupGroups = {};	// for <select>

		var getAreas = function()	// for <select>
		{
			restFactory.getItem(urlArea)
				.success(function(data, status) {
					$scope.areas = data;
					if ($scope.formMode == 'add') {
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

		var getMarkupGroups = function()	// for <select>
		{
			restFactory.getItem(urlMarkupGroup)
				.success(function(data, status) {
					$scope.markupGroups = data;
					for (var i = 0; i < $scope.markupGroups.length; i++) {
						if ($scope.formMode == 'add') {
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

		$scope.addItem = function()
		{
			$scope.item = {};
			$scope.formMode = "add";
			$scope.displayMode = "form";
			getAreas();
			getMarkupGroups();
		};

		$scope.editItem = function(id)
		{
			restFactory.getItem(url, id)
				.success(function(data, status) {
					$scope.item = data;
					$scope.formMode = "edit";
					$scope.displayMode = "form";
					getAreas();
					getMarkupGroups();
				})
				.error(errorCallback);
		};

		$scope.cancelItem = function()
		{
			$scope.displayMode = "list";
		};

		$scope.saveItem = function()
		{
			alert('submitted=' + $scope.item.name);
			$scope.displayMode = "list";
		};

		var getItemSuccessCallback = function (data, status) {
			$scope.rowCollection = data;
			$scope.displayedCollection = [].concat($scope.rowCollection);
		};

		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};

		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);


// This was textalk - not used - not yet deleted in case there is some use in this?
  $scope.onSubmit = function(form) {
	  alert('form submitted');
    // First we broadcast an event so all fields validate themselves
    $scope.$broadcast('schemaFormValidate');

    // Then we check if the form is valid
    if (form.$valid) {
      // ... do whatever you need to do with your data.
		alert('form is valid');
    }
	else
		alert('form is NOT valid');
  };



	});

/*	.controller('CustomerAddCtrl', function($scope, $state, $stateParams) {
		$scope.addItem = function() {
			//$scope.rowCollection.push({'id': 999, 'name':'kim', 'discount_percent':100, 'telephone':'07899752030', 'forma_de_pago':'el-nino'});
			alert('added customer');
			$state.go('home.customer-maintain');
		};

	}); */

