angular.module('stock')
	.controller('CustomerMaintainCtrl', function ($scope, $location, restFactory, notificationFactory) {
//alert('maintain');
		$location.path('/home/customer/list');
        $location.replace();
/*
		var url            = 'http://stock.wireflydesign.com/server/api/stock_customer/';
		var urlArea        = 'http://stock.wireflydesign.com/server/api/stock_area/';			// for <select>
		var urlMarkupGroup = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';	// for <select>
*/

	/*	$scope.rowCollection = [];
		$scope.displayMode = "list";
		$scope.formMode = "";
		$scope.item = {};
		$scope.areas = {};			// for <select>
		$scope.markupGroups = {};	// for <select>*/



		/*
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
		*/


	});
