
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

      .state('home.customer-maintain', {
        url: '/customers',
        templateUrl: 'app/home/customer/maintain/maintain.html',
        controller: 'CustomerListCtrl'
      })
      .state('home.customer-invoice', {
        url: '/invoices',
        templateUrl: 'app/home/customer/customerInvoice.html',
        controller: 'CustomerInvoiceCtrl'
      })
      .state('home.customer-return', {
        url: '/anything-I-like-here/or-even-there',
        templateUrl: 'app/home/customer/customerReturn.html',
        controller: 'CustomerReturnCtrl'
      });

    $urlRouterProvider.otherwise('/');
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

