angular.module('stock')
	.controller('ProductDetailLabelCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

		$scope.label = $scope.$parent.item.label;

		$scope.$watch('label', function () {
			$scope.$parent.item.label = $scope.label;
		});

	});
