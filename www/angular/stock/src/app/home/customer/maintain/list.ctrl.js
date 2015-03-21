angular.module('stock')
	.controller('CustomerListCtrl', function ($scope, $state, restFactory, notificationFactory) {
//alert('list');
		var url            = 'http://stock.wireflydesign.com/server/api/stock_customer/';

		$scope.rowCollection = [];
		$scope.displayMode = "list";
		$scope.formMode = "";
		$scope.item = {};


		$scope.addItem = function()	{
			$scope.$parent.editMode = "add";
			$state.go('home.customer-detail');
		};

		$scope.editItem = function(id) {
			$scope.$parent.editMode = "edit";
			$scope.$parent.itemId = id;
			$state.go('home.customer-detail');
		};

		$scope.deleteItem = function(id) {
			$scope.$parent.editMode = "delete";
			$scope.$parent.itemId = id;
			$state.go('home.customer-detail');
		};


		$scope.cancelItem = function() {
			$scope.displayMode = "list";
		};



		$scope.selchangeArea = function() {
			$scope.item.stock_area_id = $scope.selectedArea.id;
		};
		$scope.selchangeMarkupGroup = function() {
			$scope.item.stock_markup_group_id = $scope.selectedMarkupGroup.id;
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
