angular.module('stock')
	.controller('ProductDetailDimensionsCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

		$scope.height = $scope.$parent.item.height;
		$scope.width  = $scope.$parent.item.width;
		$scope.depth  = $scope.$parent.item.depth;
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

		$scope.$watch('weight', function () {
			$scope.$parent.item.weight = $scope.weight;
		});

		$scope.$watch('volume', function () {
			$scope.$parent.item.volume = $scope.volume;
		});

		$scope.calcVolume = function() {
			if ((parseFloat($scope.height) !== 0) &&
			    (parseFloat($scope.width) !== 0) &&
			    (parseFloat($scope.depth) !== 0)) {
				var v = $scope.volume = parseFloat($scope.height) * parseFloat($scope.width) * parseFloat($scope.depth);
				if (isNaN(v))
					v = 0.00;
				$scope.volume = v.toFixed(2);
			}
		};
	});
