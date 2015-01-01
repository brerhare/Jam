angular.module('stock', ['ngAnimate', 'ngCookies', 'ngTouch', 'ngSanitize', 'ngResource', 'ui.router', 'smart-table'])

	.factory('itemFactory', function ($http) {
		return {
			getItem: function (id) {
				id = typeof id !== 'undefined' ? id : "";
				return $http.get(url + id);
			},
			addItem: function (item) {
				return $http.post(url, item);
			},
			deleteItem: function (item) {
				return $http.delete(url + item.id);
			},
			updateItem: function (item) {
				return $http.put(url + item.id, item);
			}
		};
	})

	.factory('notificationFactory', function () {
		return {
			success: function () {
//alert('success - hold for log');
				toastr.success("Success");
			},
			error: function (text) {
//alert('error - hold for log');
				toastr.error(text, "Error!");
			}
		};
	})


  .controller('appCtrl', function ($scope) {
  });

