(function(){

	var app = angular.module('admin', ['LocalStorageModule', 'ngRoute']);

	// ROUTING
	app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
		$locationProvider.hashPrefix();

		$routeProvider.when('/login', {
			templateUrl: '/app/views/login.html',
			controller: 'loginController',
			controllerAs: 'ctrl'
		})
		.otherwise({
			redirectTo: '/admin/login'
		});

		$locationProvider.html5Mode(true);
	}]);

  // VARIABLES
  app.value('config', {
    	baseURL: 'http://creativeone.imaginea-mobility.com',
  });

  // LOCAL STORAGE
	app.config(function(localStorageServiceProvider) {
		localStorageServiceProvider.setStorageType('sessionStorage');
	});

  app.run(function ($location, config, localStorageService) {

    // CHECK FOR USER
  	var user = localStorageService.get('user');
  	if(user){
  		config.user = user;
  	}

  })

}())
