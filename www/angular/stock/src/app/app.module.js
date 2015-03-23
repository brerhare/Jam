angular.module('stock', ['ngAnimate', 'ngCookies', 'ngTouch', 'ngSanitize', 'ngResource', 'ui.router', 'smart-table', 'fcsa-number', 'ngDialog'])

	.factory('restFactory', function ($http) {
		
		var appendUrl = function (url, part) {			// Append to url, ensuring '/' separation of parts
			var newUrl = url;
			if (newUrl.charAt(newUrl.length-1) != '/')
				newUrl += '/';
			newUrl += ("" + part);
			//newUrl = url + "?offset=1&limit=20";
			return newUrl;
		};

		return {
			getItem: function (url, id) {
				id = typeof id !== 'undefined' ? id : "";
				return $http({
					url: appendUrl(url, id),
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
					url: appendUrl(url, id),
					method: "DELETE",
					data: id
				});
			},
			updateItem: function (url, id, item) {
				return $http({
					url: appendUrl(url, id),
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

