(function(){

	var app = angular.module('admin', ['LocalStorageModule', 'ngRoute']);

	// ROUTING
	app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
		$locationProvider.hashPrefix();

		$locationProvider.html5Mode(true);
	}]);

  // VARIABLES
  app.value('config', {
    	baseURL: 'https://creativeone.imaginea-mobility.com/api',
  });

  // LOCAL STORAGE
	app.config(function(localStorageServiceProvider) {
		localStorageServiceProvider.setStorageType('sessionStorage');
	});

	app.run(function ($location, config, localStorageService) {

	  // CHECK FOR USER
	  var user = localStorageService.get('user');
	  if(user){
		  window.location = '/admin/dashboard';
	  } else {
		  window.location = '/admin/login';
	  }

	})

}())
