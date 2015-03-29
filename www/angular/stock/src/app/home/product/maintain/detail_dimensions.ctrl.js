angular.module('stock')
	.controller('ProductDetailDimensionsCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

		$scope.height = $scope.$parent.item.height;
		$scope.width  = $scope.$parent.item.width;
		$scope.depth  = $scope.$parent.item.depth;
		$scope.volume = $scope.$parent.item.volume;
		$scope.weight = $scope.$parent.item.weight;

		$scope.$watch('height', function () {
			$scope.$parent.item.height = $scope.height;
			calcVolume();
		});

		$scope.$watch('width', function () {
			$scope.$parent.item.width = $scope.width;
			calcVolume();
		});

		$scope.$watch('depth', function () {
			$scope.$parent.item.depth = $scope.depth;
			calcVolume();
		});

		$scope.$watch('weight', function () {
			$scope.$parent.item.weight = $scope.weight;
		});

		var calcVolume = function() {
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
