angular.module('stock')
	.controller('CustomerMaintainCtrl', function ($scope, restFactory, notificationFactory) {
		var url = 'http://stock.wireflydesign.com/server/api/stock_customer/';



		/*****
		$scope.schema = {
			type: "object",
			properties: {
				name: {
					type: "string",
					minLength: 2,
					title: "Name",
					description: "Name or alias",
					required: true
				},
				"student": {
					type: "string",
					title: "studentname",
					description: "Name or student",
					required: false
				},

				"email": {
					"title": "Email",
					"type": "string",
					"minLength": 2,
					"pattern": "^\\S+@\\S+$",
					onChange: function(modelValue,form) {
						console.log("Password is"+modelValue);
					},
					validationMessage: {
						200: "Address is too short, man.",
						"default": "Just write a proper address, will you?" //Special catch all error message
					},
					"description": "Email will be used for evil.",
					required: true
				},
				title: {
					type: "string",
					required: true,
					enum: ['dr', 'jr', 'sir', 'mrs', 'mr', 'NaN', 'dj']
				}
			}
		};

		$scope.form = [
			"*", {
				type: "submit",
				title: "Save"
			}
		];
		 *****/




		$scope.schema = {
			type: "object",
			properties: {
				name: {
					title: "Name",
					type: "string"
				},
				nick: {
					title: "Nick",
					type: "string"
				},
				alias: {
					title: "Alias",
					type: "string"
				},
				tag: {
					title: "Tag",
					type: "string"
				}
			}
		};

		$scope.form = [
			"name",
			{
				type: "tabs",
				tabs: [
					{
						title: "Tab 1",
						items: [
							"nick",
							"alias"
						]
					},
					{
						title: "Tab 2",
						items: [
							"tag"
						]
					}
				]
			}
		];


		$scope.model = {};


		$scope.rowCollection = [];
		$scope.displayMode = "list";
		$scope.formMode = "";
		$scope.item = {};

		$scope.addItem = function()
		{ return; /*kim*/
			$scope.formMode = "add";
			$scope.displayMode = "form";
		};

		$scope.editItem = function(id)
		{
			if (id != 317) return; /*kim*/
			restFactory.getItem(url, id)
				.success(function(data, status) {
					$scope.item = data;
					$scope.formMode = "edit";
					$scope.displayMode = "form";
				})
				.error(errorCallback);
		};

		$scope.cancelItem = function()
		{
			$scope.displayMode = "list";
		};

		$scope.saveItem = function()
		{
			alert('submitted=' + $scope.item.name);
			$scope.displayMode = "list";
		};

		var getItemSuccessCallback = function (data, status) {
			$scope.rowCollection = data;
			$scope.displayedCollection = [].concat($scope.rowCollection);
		};

		var errorCallback = function (data, status, headers, config) {
			notificationFactory.error(data.ExceptionMessage);
		};

		restFactory.getItem(url).success(getItemSuccessCallback).error(errorCallback);




  $scope.onSubmit = function(form) {
	  alert('form submitted');
    // First we broadcast an event so all fields validate themselves
    $scope.$broadcast('schemaFormValidate');

    // Then we check if the form is valid
    if (form.$valid) {
      // ... do whatever you need to do with your data.
		alert('form is valid');
    }
	else
		alert('form is NOT valid');
  };


	});

/*	.controller('CustomerAddCtrl', function($scope, $state, $stateParams) {
		$scope.addItem = function() {
			//$scope.rowCollection.push({'id': 999, 'name':'kim', 'discount_percent':100, 'telephone':'07899752030', 'forma_de_pago':'el-nino'});
			alert('added customer');
			$state.go('home.customer-maintain');
		};

	}); */

