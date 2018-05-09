(function(){

var app = angular.module('admin');

var dashboardController = function($location, $sce, $http, $routeParams, config) {

	var ctrl = this;

	ctrl.load = function() {


	}

  }

  // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
		return 'dashboard';
	}

	app.controller('dashboardController', dashboardController);

}())
