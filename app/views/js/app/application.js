angular
.module('turboship')
.config(['$routeProvider', function($routeProvider) {
	$routeProvider.
	// home route
	when('/', {
		templateUrl: '/templates/index.html',
		controller: 'HomeController'
	}).

	// user routes
	when('/users', {
		templateUrl: '/templates/users/index.html',
		controller: 'UserController'
	}).
	when('/user/:id', {
		templateUrl: '/templates/users/view.html',
		controller: 'UserViewController'
	}).
	when('/users/create', {
		templateUrl: '/templates/users/create.html',
		controller: 'UserCreateController'
	}).

	// order routes
	when('/orders', {
		templateUrl: '/templates/orders/index.html',
		controller: 'OrderController'
	}).
	when('/order/:id', {
		templateUrl: '/templates/orders/view.html',
		controller: 'OrderViewController'
	}).
	when('/orders/create', {
		templateUrl: '/templates/orders/create.html',
		controller: 'OrderCreateController'
	}).

	// product routes
	when('/products', {
		templateUrl: '/templates/products/index.html',
		controller: 'ProductController'
	}).
	when('/product/:id', {
		templateUrl: '/templates/products/view.html',
		controller: 'ProductViewController'
	}).
	when('/products/create', {
		templateUrl: '/templates/products/create.html',
		controller: 'ProductCreateController'
	}).

	// settings routes
	when('/settings', {
		templateUrl: '/templates/settings/index.html',
		controller: 'SettingsController'
	})

	.otherwise({
		redirectTo: '/'
	});
}]);

// Stuff for logins
angular.module('login').config(['$routeProvider', function($routeProvider) {

	$routeProvider
	.when('/', {
		templateUrl: '/templates/users/login.html',
		controller: 'AuthController'
	})
	.when('/register', {
		templateUrl: '/templates/users/register.html',
		controller: 'RegisterController'
	})
}]);

angular.module('shipping').config(['$routeProvider', function($routeProvider) {
	$routeProvider
		.when('/', {
			templateUrl: '/templates/shipping/index.html',
			controller: 'ShippingIndexController'
		})
		.when('/make/:tote', {
			templateUrl: '/templates/shipping/make.html',
			controller: 'ShippingMakeController'
		})
		.when('/refund', {
			templateUrl: '/templates/shipping/refund.html',
			controller: 'ShippingRefundController'
		})
		.when('/reprint', {
			templateUrl: '/templates/shipping/reprint.html',
			controller: 'ShippingReprintController'
		})
		.otherwise({
			redirectTo: '/'
		})
}])