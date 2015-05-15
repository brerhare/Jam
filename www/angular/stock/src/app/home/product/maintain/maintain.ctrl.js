angular.module('stock')
	.controller('ProductMaintainCtrl', function ($scope, $location, restFactory, notificationFactory) {
		$location.path('/home/product/list');
		$location.replace();
	});
