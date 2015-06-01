'use strict';

/*
 * 'url' is for display/accept on the url-bar only, Can be anything
 * states are nested. The page is drawn from root to leaf, ie 'customer.maintain' first renders 'customer', then 'maintain' into its ui-view
 */

angular.module('newbuild')
  .config(function ($stateProvider, $urlRouterProvider) {
    $stateProvider
      .state('home', {
        url: '/',
        templateUrl: 'app/main/main.html',
        controller: 'MainCtrl'
      })

      .state('customer', {  /* stub */
        url: '/customer',
        templateUrl: 'app/main/main.html',
        controller: 'MainCtrl'
      })
      .state('customer.maintain', {
        url: '/maintain',
        templateUrl: 'app/customer/maintain/maintain.html',
        controller: 'CustomerMaintainCtrl'
      })
      .state('customer.invoice', {
        url: '/invoicing',
        templateUrl: 'app/customer/invoice/invoice.html'
      })
      .state('customer.return', {
        url: '/anything-I-like-here/or-even-there',
        templateUrl: 'app/customer/return/return.html'
      })

      .state('stock', {
        url: '/stock',
        templateUrl: 'app/stock/stock.html'
      })
      .state('stock.list', {
        url: '/list',
        templateUrl: 'app/stock/list/list.html'
      });

    $urlRouterProvider.otherwise('/');
  })


//angular.module('stock')   // This isn't necessary
  .controller('MainMenuCtrl', function ($scope, $location) {
    $scope.isActive = function (viewLocation) {
      $scope.classVal=$location.path();
      return '/' + viewLocation === $location.path() ? 'active' : '';
    };

  });

