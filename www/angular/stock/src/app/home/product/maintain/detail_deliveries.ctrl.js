angular.module('stock')
	.controller('ProductDetailDeliveriesCtrl', function ($scope, restFactory, notificationFactory, ngDialog) {

//$scope.id = $scope.$parent.item.id;
//alert('parent id is '+ $scope.id);

$scope.iframeUrl="http://stock.wireflydesign.com/run/deliveryTab?id="+$scope.$parent.item.id;
//alert('url='+$scope.iframeUrl);

	});
