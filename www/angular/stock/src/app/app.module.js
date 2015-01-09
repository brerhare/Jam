angular.module('stock', ['ngAnimate', 'ngCookies', 'ngTouch', 'ngSanitize', 'ngResource', 'ui.router', 'smart-table'])

	.factory('restFactory', function ($http) {
		return {
			getItem: function (url, id) {
				id = typeof id !== 'undefined' ? id : "";
				return $http({
					url: url + id,
					method: "GET",
					//data: JSON.stringify(requestData),
					data: '',
					//withCredentials: true,
					headers: {
					}
				});
			},
			addItem: function (url, item) {
				return $http({
					url: url,
					method: "POST",
					data: item
				});
			},
			deleteItem: function (url, id) {
				return $http({
					url: url + id,
					method: "DELETE",
					data: id
				});
			},
			updateItem: function (url, id, item) {
				return $http({
					url: url + id,
					method: "PUT",
					data: item
				});
			}
			//updateItem: function (item) {
				//return $http.put(url + item.id, item);
			//}
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

