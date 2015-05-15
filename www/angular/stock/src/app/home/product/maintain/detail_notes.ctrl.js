angular.module('stock')
	.controller('ProductDetailNotesCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

		$scope.notes = $scope.$parent.item.notes;

		$scope.$watch('notes', function () {
			$scope.$parent.item.notes = $scope.notes;
		});

	});
