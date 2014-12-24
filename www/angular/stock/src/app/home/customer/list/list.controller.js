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


$scope.method = 1;


if (($scope.method == 1) || ($scope.method == 9))
{
  // Get the full customer list from the server
    $scope.url = "http://stock.wireflydesign.com/server/data.php";
    $scope.total = "loading ";
alert('sending 1');
    $http.get($scope.url)
		.success(function(data, status, headers, config) {
alert('rcvd 1');
	$scope.rowCollection = data;
//alert(data.stock_customer[0].name);
//	alert($scope.rowCollection);
alert(JSON.stringify(data));
alert(data.length);
    });
}

if (($scope.method == 2) || ($scope.method == 9))
{
alert('sending 2');
            $http({
                method: 'POST',
                //url: 'http://stock.wireflydesign.com/server/customer.json',
                url: 'http://stock.wireflydesign.com/server/data.php',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    //'Authorization': 'Splunk ' + $scope.sessionKey
                },
                data: {search: 'customer', output_mode: 'json'}
            }).success(function (data, status) {                    
alert('back 2 - now to fill table');
				$scope.rowCollection = data;
alert($scope.rowCollection);
alert('xx');
            }).error(function (data, status) {
                alert("error status: " + status + ". data: " + data);// alerted error
                alert(data); // alerts 'undefined'
            });
}

if (($scope.method == 3) || ($scope.method == 9))
{
alert('sending 3');

$scope.rowCollection = [{"id":"647","name":"7 Days 2","discount_percent":"5.00","telephone":"12345","forma_de_pago":"1 por otro"}];
return;

$scope.rowCollection = [{"id":"647","name":"7 Days 2","discount_percent":"5.00","telephone":"12345","forma_de_pago":"1 por otro"},{"id":"58","name":"Cust2","discount_percent":"0.00","telephone":"911","forma_de_pago":"2 por otro"},{"id":"133","name":"A customer","discount_percent":"2.75","telephone":"67890","forma_de_pago":"3 por otro"}];
return;

    $scope.XrowCollection = [
      {
        "id":"647",
        "name":"7 Days 2",
        "discount_percent":"5.00",
        "telephone":"12345",
        "forma_de_pago":"1 por otro"
      },
      {
        "id":"58",
        "name":"Cust2",
        "discount_percent":"0.00",
        "telephone":"911",
        "forma_de_pago":"2 por otro"
      },
      {
        "id":"133",
        "name":"A customer",
        "discount_percent":"2.75",
        "telephone":"67890",
        "forma_de_pago":"3 por otro"
      }
    ];
//      	        alert($scope.rowCollection);
alert(JSON.stringify($scope.rowCollection));
} // if


});
