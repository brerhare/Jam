angular.module('stock')
	.controller('ProductDetailDimensionsCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

		$scope.height = $scope.$parent.item.height;
		$scope.width = $scope.$parent.item.width;
		$scope.depth = $scope.$parent.item.depth;
		$scope.volume = $scope.$parent.item.volume;
		$scope.weight = $scope.$parent.item.weight;

		$scope.$watch('height', function () {
			$scope.$parent.item.height = $scope.height;
		});

		$scope.$watch('width', function () {
			$scope.$parent.item.width = $scope.width;
		});

		$scope.$watch('depth', function () {
			$scope.$parent.item.depth = $scope.depth;
		});

		$scope.$watch('volume', function () {
					$scope.$parent.item.volume = $scope.volume;
		});

		$scope.$watch('weight', function () {
			$scope.$parent.item.weight = $scope.weight;
		});

	});
