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


$scope.method = 2;


if (($scope.method == 1) || ($scope.method == 9))
{
  // Get the full customer list from the server
    $scope.url = "http://stock.wireflydesign.com/server/customer.json";
    $scope.total = "loading ";
alert('sending 1');
    $http.get($scope.url).success(function(response) {
      $scope.rowCollection = response;
      alert($scope.rowCollection);
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
				$scope.rowCollection = data;
      	        alert($scope.rowCollection);
            }).error(function (data, status) {
                alert("error status: " + status + ". data: " + data);// alerted error
                alert(data); // alerts 'undefined'
            });
}

if (($scope.method == 3) || ($scope.method == 9))
{
alert('sending 3');
    $scope.rowCollection = [
      {
        "customerId":647,
        "companyName":"7 Days 2",
        "rate":"5%",
        "contactTitle":"",
        "contactFirstName":"",
        "contactLastName":"",
        "billingAddress1":"Calle Sabandeos",
        "billingAddress2":"Edificio Marte,No.7",
        "billingAddress3":"Los Cristianos",
        "postCode":"",
        "phoneNumber":"922862289",
        "faxNumber":"",
        "emailAddress":"",
        "CIF:":"F-38619771",
        "notes":"",
        "formaDePago":"1 por otro",
        "mobile:":"",
        "inactive":"0"
      },
      {
        "customerId":867,
        "companyName":"Store by the Shore",
        "rate":"5%",
        "contactTitle":"",
        "contactFirstName":"",
        "contactLastName":"",
        "billingAddress1":"Edif.Cristian Mar L-2",
        "billingAddress2":"Los Cristianos,Arona",
        "billingAddress3":"Tenerife,España",
        "postCode":"38650",
        "phoneNumber":"",
        "faxNumber":"",
        "emailAddress":"",
        "CIF:":"X-8299514-X",
        "notes":"",
        "formaDePago":"Contado",
        "mobile:":"",
        "inactive":"0"
      },
      {
        "customerId":598,
        "companyName":"7 days vista-sur",
        "rate":"5%",
        "contactTitle":"",
        "contactFirstName":"",
        "contactLastName":"",
        "billingAddress1":"c.c. Oasis 36g   19",
        "billingAddress2":"avd. las americas",
        "billingAddress3":"",
        "postCode":"38680",
        "phoneNumber":"",
        "faxNumber":"",
        "emailAddress":"",
        "CIF:":"e3 8974390",
        "notes":"",
        "formaDePago":"1 por otro",
        "mobile:":"",
        "inactive":"1"
      },
      {
        "customerId":33,
        "companyName":"Costcutter Las Floritas",
        "rate":"5%",
        "contactTitle":"Yelmis Jasa",
        "contactFirstName":"",
        "contactLastName":"",
        "billingAddress1":"Dipau Dorta SL",
        "billingAddress2":"Apto Las Floritas",
        "billingAddress3":"Las Americas",
        "postCode":"",
        "phoneNumber":"618700431",
        "faxNumber":"",
        "emailAddress":"",
        "CIF:":"B38769774",
        "notes":"30 Days From Factura",
        "formaDePago":"Contado",
        "mobile:":"",
        "inactive":"1"
      },
      {
        "customerId":290,
        "companyName":"Express Baguette",
        "rate":"5%",
        "contactTitle":"Noelle Chelsea",
        "contactFirstName":"",
        "contactLastName":"",
        "billingAddress1":"Coral Mar Edf Cristian Mar L2",
        "billingAddress2":"Los Cristianos",
        "billingAddress3":"Arona",
        "postCode":"38650",
        "phoneNumber":"922 788 527",
        "faxNumber":"",
        "emailAddress":"",
        "CIF:":"E38819942",
        "notes":"",
        "formaDePago":"Contado",
        "mobile:":"676 583 927",
        "inactive":"1"
      },
      {
        "customerId":389,
        "companyName":"Express Baguette 2",
        "rate":"5%",
        "contactTitle":"",
        "contactFirstName":"",
        "contactLastName":"",
        "billingAddress1":"",
        "billingAddress2":"",
        "billingAddress3":"Pueblo Canario",
        "postCode":"",
        "phoneNumber":"",
        "faxNumber":"",
        "emailAddress":"",
        "CIF:":"",
        "notes":"",
        "formaDePago":"Contado",
        "mobile:":"",
        "inactive":"1"
      }
    ];
      	        alert($scope.rowCollection);
} // if


});
