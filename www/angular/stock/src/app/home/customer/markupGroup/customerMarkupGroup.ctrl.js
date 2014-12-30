var url = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';

toastr.options.timeOut = 1500;
toastr.options.positionClass = 'toast-bottom-right';
//toastr.options.progressBar = true; 

angular.module('stock')

  .factory('itemFactory', function ($http) {
    return {
        getItems: function () {
            return $http.get(url);
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
alert('success - hold for log');
            toastr.success("Success");
        },
        error: function (text) {
alert('error - hold for log');
            toastr.error(text, "Error!");
        }
    };
})

  .controller('CustomerMarkupGroupCtrl', function ($scope, itemFactory, notificationFactory) {
    $scope.items = [];
    $scope.addMode = false;
 
    $scope.toggleAddMode = function () {
        $scope.addMode = !$scope.addMode;
    };
 
    $scope.toggleEditDescription = function (item) {
        item.editDescription = !item.editDescription;
    };
 
    $scope.toggleEditPercent = function (item) {
        item.editPercent = !item.editPercent;
    };
 
	$scope.editDescriptionEnd = function(item){
		if (event.keyCode == 27 )
			$scope.toggleEditDescription();
		if (event.keyCode == 13 && item.description){
			$scope.toggleEditDescription();
		}
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

/***
app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});
***/
