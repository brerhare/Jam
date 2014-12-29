var url = 'http://stock.wireflydesign.com/server/api/stock_markup_group/';

angular.module('stock')

  .factory('personFactory', function ($http) {
    return {
        getPeople: function () {
            return $http.get(url);
        },
        addPerson: function (person) {
            return $http.post(url, person);
        },
        deletePerson: function (person) {
            return $http.delete(url + person.id);
        },
        updatePerson: function (person) {
            return $http.put(url + person.id, person);
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


  .controller('CustomerMarkupGroupCtrl', function ($scope, personFactory, notificationFactory) {
    $scope.people = [];
    $scope.addMode = false;
 
    $scope.toggleAddMode = function () {
        $scope.addMode = !$scope.addMode;
    };
 
    $scope.toggleEditMode = function (person) {
        person.editMode = !person.editMode;
    };
 
    var getPeopleSuccessCallback = function (data, status) {
        $scope.people = data;
    };
 
    var successCallback = function (data, status, headers, config) {
        notificationFactory.success();
        return personFactory.getPeople().success(getPeopleSuccessCallback).error(errorCallback);
    };
 
    var successPostCallback = function (data, status, headers, config) {
        successCallback(data, status, headers, config).success(function () {
            $scope.toggleAddMode();
            $scope.person = {};
        });
    };
 
    var errorCallback = function (data, status, headers, config) {
        notificationFactory.error(data.ExceptionMessage);
    };
 
 
    personFactory.getPeople().success(getPeopleSuccessCallback).error(errorCallback);
//$scope.people = [{'Id':1, 'Name':'aaa'},{'Id':2, 'Name':'bbb'},{'Id':3, 'Name':'ccc'}];
 
    $scope.addPerson = function () {
        personFactory.addPerson($scope.person).success(successPostCallback).error(errorCallback);
    };
 
    $scope.deletePerson = function (person) {
        personFactory.deletePerson(person).success(successCallback).error(errorCallback);
    };
 
    $scope.updatePerson = function (person) {
        personFactory.updatePerson(person).success(successCallback).error(errorCallback);
    };
});

