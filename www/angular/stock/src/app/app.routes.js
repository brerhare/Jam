/*
 * 'state' is the the sref link itself eg 'customer.invoices'
 * 'url' is for display/accept on the url-bar only, Can be anything
 * States are nested. The page is drawn from root to leaf, ie 'customer.maintain' first renders 'customer', then 'maintain' into its ui-view
 */

angular.module('stock')
	.config(function ($stateProvider, $urlRouterProvider) {
		$stateProvider

      .state('login', {
        url: '/',
        templateUrl: 'app/login/login.html',
        controller: 'LoginCtrl'
      })
      .state('home', {
        url: '/home',
        templateUrl: 'app/home/home.html',
        controller: 'HomeCtrl'
      })

// Transactions
// ------------
      .state('home.purchase-order', {
        url: '/transaction/purchaseOrder',
        templateUrl: 'app/home/transaction/purchaseOrder/purchaseOrder.html',
        controller: 'PurchaseOrderCtrl'
      })


// Suppliers
// ---------
      .state('home.supplier-maintain', {
        url: '/supplier/maintain',
        templateUrl: 'app/home/supplier/maintain/maintain.html',
        controller: 'SupplierMaintainCtrl'
      })

// Customers
// ---------
      .state('home.customer-maintain', {
        url: '/customer/maintain',
        templateUrl: 'app/home/customer/maintain/maintain.html',
        controller: 'CustomerMaintainCtrl'
      })
		.state('home.customer-list', {
			url: '/customer/list',
			templateUrl: 'app/home/customer/maintain/list.html',
			controller: 'CustomerListCtrl'
		})
        .state('home.customer-detail', {
        	url: '/customer/detail',
        	templateUrl: 'app/home/customer/maintain/detail.html',
        	controller: 'CustomerDetailCtrl'
        })
		  .state('home.customer-detail.notes', {
		    url: '/notes',
		    templateUrl: 'app/home/customer/maintain/detail_notes.html',
		    controller: 'CustomerDetailNotesCtrl'
		  })
		  .state('home.customer-detail.orders', {
		    url: '/notes',
		    templateUrl: 'app/home/customer/maintain/detail_orders.html',
		    controller: 'CustomerDetailOrdersCtrl'
		  })

      .state('home.customer-area', {
        url: '/customer/areas',
        templateUrl: 'app/home/customer/area/customerArea.html',
        controller: 'CustomerAreaCtrl'
      })
      .state('home.customer-markup-group', {
        url: '/customer/markup-groups',
        templateUrl: 'app/home/customer/markupGroup/customerMarkupGroup.html',
        controller: 'CustomerMarkupGroupCtrl'
      })
      .state('home.customer-invoice', {
        url: '/customer/invoices',
        templateUrl: 'app/home/customer/invoice/customerInvoice.html',
        controller: 'CustomerInvoiceCtrl'
      })
      .state('home.customer-return', {
                url: '/anything-I-like-here/or-even-there',
                templateUrl: 'app/home/customer/return/customerReturn.html',
                controller: 'CustomerReturnCtrl'
      })

// Products
// --------
      .state('home.product-maintain', {
          url: '/product/maintain',
          templateUrl: 'app/home/product/maintain/maintain.html',
          controller: 'ProductMaintainCtrl'
      })
		.state('home.product-list', {
			url: '/product/list',
			templateUrl: 'app/home/product/maintain/list.html',
			controller: 'ProductListCtrl'
		})
		.state('home.product-detail', {
			url: '/product/detail',
			templateUrl: 'app/home/product/maintain/detail.html',
			controller: 'ProductDetailCtrl'
		})
		  .state('home.product-detail.notes', {
		    url: '/notes',
		    templateUrl: 'app/home/product/maintain/detail_notes.html',
		    controller: 'ProductDetailNotesCtrl'
		  })
          .state('home.product-detail.prices', {
		    url: '/prices',
		    templateUrl: 'app/home/product/maintain/detail_prices.html',
		    controller: 'ProductDetailPricesCtrl'
		  })
          .state('home.product-detail.dimensions', {
		    url: '/dimensions',
		    templateUrl: 'app/home/product/maintain/detail_dimensions.html',
		    controller: 'ProductDetailDimensionsCtrl'
		  })
		  .state('home.product-detail.barcodes', {
		    url: '/barcodes',
		    templateUrl: 'app/home/product/maintain/detail_barcodes.html',
		    controller: 'ProductDetailBarcodesCtrl'
		  })
		  .state('home.product-detail.deliveries', {
		    url: '/deliveries',
		    templateUrl: 'app/home/product/maintain/detail_deliveries.html',
		    controller: 'ProductDetailDeliveriesCtrl'
		  })
		  .state('home.product-detail.label', {
		    url: '/labels',
		    templateUrl: 'app/home/product/maintain/detail_label.html',
		    controller: 'ProductDetailLabelCtrl'
		  })
		  .state('home.product-detail.pack', {
		    url: '/uom',
		    templateUrl: 'app/home/product/maintain/detail_pack.html',
		    controller: 'ProductDetailPackCtrl'
		  })

      .state('home.product-group', {
          url: '/product/groups',
          templateUrl: 'app/home/product/group/productGroup.html',
          controller: 'ProductGroupCtrl'
      })

      .state('home.product-location', {
        url: '/product/locations',
        templateUrl: 'app/home/product/location/productLocation.html',
        controller: 'ProductLocationCtrl'
      })

      .state('home.product-container', {
        url: '/supplier/containers',
        templateUrl: 'app/home/product/container/productContainer.html',
        controller: 'ProductContainerCtrl'
      })

// Settings
// --------
        .state('home.settings-vat', {
            url: '/settings/vat',
            templateUrl: 'app/home/setting/vat/vat.html',
            controller: 'SettingVatCtrl'
        });


//      $urlRouterProvider.otherwise('/invoices');	// redirect to url

    })

   .run(function($state) {
      $state.go('login'); //make a transition to login state when app starts
    });


/*****
//angular.module('stock')   // This isn't necessary here
  .controller('MainMenuCtrl', function ($scope, $location) {
    $scope.isActive = function (viewLocation) {
      $scope.classVal=$location.path();
      return '/' + viewLocation === $location.path() ? 'active' : '';
    };

  });
*****/

