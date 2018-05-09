(function(){

    var app	= angular.module('admin');

    var navigationController	= function($location, localStorageService, config, $sce){

        var navCtrl = this;

        navCtrl.config = config;

        navCtrl.isActive = function(route) {
			var pathSubstring = $location.path().split(/[//]/);
			return "/" + pathSubstring[1] == route;
        }

		// UPDATE PAGE LOCATION IN SECTION
		navCtrl.locationPath = function(_path){
			$location.path(_path);
		}

        navCtrl.logOut = function(){
		    // CHECK FOR USER
		    localStorageService.clearAll();
            window.location = '/admin/login';
        }

	}

	app.controller('navigationController', navigationController);

}())

angular.module('admin').directive("navigation", function(){
    return{
        restrict	: 'A',
        templateUrl	: '/app/views/navigation.html',
        scope		: true,
        transclude 	: false,
        controller	: 'navigationController',
        controllerAs: 'navCtrl'
    };
});
