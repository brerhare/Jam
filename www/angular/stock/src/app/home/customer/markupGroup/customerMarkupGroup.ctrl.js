var url = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';

angular.module('stock')

  .factory('itemFactory', function ($http) {
    return {
        getItems: function () {
            return $http.get(url);
        },
        addItem: function (item) {
alert(item);
            //return $http.post(url, "{'daata':'23'}");
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
			alert('success');
            //toastr.success("Success");
        },
        error: function (text) {
			alert('error');
            //toastr.error(text, "Error!");
        }
    };
})


  .controller('CustomerMarkupGroupCtrl', function ($scope, itemFactory, notificationFactory) {
    $scope.items = [];
    $scope.addMode = false;
 
    $scope.toggleAddMode = function () {
        $scope.addMode = !$scope.addMode;
    };
 
    $scope.toggleEditMode = function (item) {
        item.editMode = !item.editMode;
    };
 
    var getItemsSuccessCallback = function (data, status) {
        $scope.items = data;
    };
 
    var successCallback = function (data, status, headers, config) {
        notificationFactory.success();
        return itemFactory.getItems().success(getItemsSuccessCallback).error(errorCallback);
    };
 
    var successPostCallback = function (data, status, headers, config) {
        successCallback(data, status, headers, config).success(function () {
            $scope.toggleAddMode();
            $scope.item = {};
        });
    };
 
    var errorCallback = function (data, status, headers, config) {
        notificationFactory.error(data.ExceptionMessage);
    };
 
 
    itemFactory.getItems().success(getItemsSuccessCallback).error(errorCallback);
 
    $scope.addItem = function () {
        itemFactory.addItem($scope.item).success(successPostCallback).error(errorCallback);
    };
 
    $scope.deleteItem = function (item) {
        itemFactory.deleteItem(item).success(successCallback).error(errorCallback);
    };
 
    $scope.updateItem = function (item) {
        itemFactory.updateItem(item).success(successCallback).error(errorCallback);
    };
});

