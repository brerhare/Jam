angular.module('stock')
    .controller('CustomerListCtrl', function ($scope, $http) {
/*
    $scope.addItem = function() {
        $location.path("add.html");
    };
    $scope.editItem = function(id) {
      $location.path("edit.html");
    };
    $scope.deleteItem = function(id) {
      $location.path("app/home/customer/list/delete.html");
    };
    $scope.outputPDF = function() {
      $location.path("pdf.html");
    };

      $scope.getData();
      */
      /*
      $scope.addCustomer = function() {
        alert('add customer');
      };

      $scope.editCustomer = function(id) {
        alert('edit customer' + id);
      };

      $scope.deleteCustomer = function(id) {
        alert('delete customer' + id);
      };

      $scope.outputPDF = function() {
        alert('pdf');
      };
*/

  // Get the full customer list from the server
  $scope.url = "http://stock.wireflydesign.com/server/customer.json";
  $scope.total = "loading ";
  $http.get($scope.url).success(function(response) {
    $scope.rowCollection = response;
    $scope.total = $scope.rowCollection.length;
  });


});
