(function(){

	var app = angular.module('admin', ['LocalStorageModule', 'ngRoute', 'angularjs-datetime-picker']);

	// ROUTING
	app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
		$locationProvider.hashPrefix();

		$routeProvider
			.when('/dashboard', {
                templateUrl		: '/app/views/dashboard.html',
                controller		: 'dashboardController',
                controllerAs	: 'ctrl'
            })
						.when('/ad', {
							templateUrl : '/app/view/ad.html',
							controller : 'adController',
							controllerAs : 'ctrl'
						})
						.when('/ad/:id', {
                templateUrl		: '/app/views/ad-detail.html',
                controller		: 'adDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/message', {
                templateUrl		: '/app/views/message.html',
                controller		: 'messageController',
                controllerAs	: 'ctrl'
            })
            .when('/message/push/:id', {
                templateUrl		: '/app/views/message-push.html',
                controller		: 'messagePushController',
                controllerAs	: 'ctrl'
            })
            .when('/message/:id', {
                templateUrl		: '/app/views/message-detail.html',
                controller		: 'messageDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/help', {
                templateUrl		: '/app/views/help.html',
                controller		: 'helpController',
                controllerAs	: 'ctrl'
            })
            .when('/help/:id', {
                templateUrl		: '/app/views/help-detail.html',
                controller		: 'helpDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/event', {
                templateUrl		: '/app/views/event.html',
                controller		: 'eventController',
                controllerAs	: 'ctrl'
            })
            .when('/event/:id', {
                templateUrl		: '/app/views/event-detail.html',
                controller		: 'eventDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/menu_slide', {
                templateUrl		: '/app/views/menu-slide.html',
                controller		: 'menuSlideController',
                controllerAs	: 'ctrl'
            })
            .when('/menu_slide/:id', {
                templateUrl		: '/app/views/menu-slide-detail.html',
                controller		: 'menuSlideDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/sponsor', {
                templateUrl		: '/app/views/sponsor.html',
                controller		: 'sponsorController',
                controllerAs	: 'ctrl'
            })
            .when('/sponsor/:id', {
                templateUrl		: '/app/views/sponsor-detail.html',
                controller		: 'sponsorDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/tier', {
                templateUrl		: '/app/views/tier.html',
                controller		: 'tierController',
                controllerAs	: 'ctrl'
            })
            .when('/tier/:id', {
                templateUrl		: '/app/views/tier-detail.html',
                controller		: 'tierDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/event_category', {
                templateUrl		: '/app/views/event-category.html',
                controller		: 'eventCategoryController',
                controllerAs	: 'ctrl'
            })
            .when('/event_category/:id', {
                templateUrl		: '/app/views/event-category-detail.html',
                controller		: 'eventCategoryDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/vendor_category', {
                templateUrl		: '/app/views/vendor-category.html',
                controller		: 'vendorCategoryController',
                controllerAs	: 'ctrl'
            })
            .when('/vendor_category/:id', {
                templateUrl		: '/app/views/vendor-category-detail.html',
                controller		: 'vendorCategoryDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/vendor', {
                templateUrl		: '/app/views/vendor.html',
                controller		: 'vendorController',
                controllerAs	: 'ctrl'
            })
            .when('/vendor/:id', {
                templateUrl		: '/app/views/vendor-detail.html',
                controller		: 'vendorDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/map', {
                templateUrl		: '/app/views/map.html',
                controller		: 'mapController',
                controllerAs	: 'ctrl'
            })
            .when('/map/:id', {
                templateUrl		: '/app/views/map-detail.html',
                controller		: 'mapDetailController',
                controllerAs	: 'ctrl'
            })
            .when('/ad', {
                templateUrl		: '/app/views/ad.html',
                controller		: 'adController',
                controllerAs	: 'ctrl'
            })
            .when('/ad/:id', {
                templateUrl		: '/app/views/ad-detail.html',
                controller		: 'adDetailController',
                controllerAs	: 'ctrl'
            })
            .otherwise({
                redirectTo: '/admin/dashboard'
            });

		$locationProvider.html5Mode(true);

	}]);

  	// VARIABLES
  	app.value('config', {
    	baseURL: 			'https://c1whistler.festeng.com',
		bucket: 			'files.festivalengine.co',
		cdnBaseURL:			'http://files.festivalengine.co',
		appBucket:			'c1whistler',
		fileSizeLimit: 		3000000
  	});

  	// LOCAL STORAGE
	app.config(function(localStorageServiceProvider) {
		localStorageServiceProvider.setStorageType('sessionStorage');
	});

	app.config(['$httpProvider', function($httpProvider) {
	    //initialize get if not there
	    if (!$httpProvider.defaults.headers.get) {
	        $httpProvider.defaults.headers.get = {};
	    }
	    //disable IE ajax request caching
	    $httpProvider.defaults.headers.get['If-Modified-Since'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
	    $httpProvider.defaults.headers.get['Cache-Control'] = 'no-cache';
	    $httpProvider.defaults.headers.get['Pragma'] = 'no-cache';
	}]);

	app.run(function ($location, config, localStorageService) {

    	// CHECK FOR USER
  		var user = localStorageService.get('user');
		var role = localStorageService.get('role');
  		if(user){
  			config.user = user;
  			config.role = role;
  		} else {
  			window.location = '/admin/login';
  		}

  	});

	app.directive("ngFileModel", [function () {
      	return {
          	scope: {
              	ngFileModel: "="
          	},
         	link: function (scope, element, attributes) {
        		element.bind("change", function (changeEvent) {
                	var reader = new FileReader();
                  		reader.onload = function (loadEvent) {
                      		scope.$apply(function () {
                          	scope.ngFileModel = changeEvent.target.files[0];
                      	});
                  	}
                  	reader.readAsDataURL(changeEvent.target.files[0]);
              	});
          	}
      	}
  	}]);

	app.directive('hideon', function() {
	    return function(scope, element, attrs) {
	        scope.$watch(attrs.hideon, function(value, oldValue) {
	            if(value) {
	                element.hide();
	            } else {
	                element.show();
	            }
	        }, true);
	    }
	});

	app.filter('range', function() {
		return function(input, min, max) {
	    	min = parseInt(min); //Make string input int
	    	max = parseInt(max);
	    	for (var i=min; i<max; i++)
	      		input.push(i);
	    	return input;
	  	};
	});

	app.filter('momentTime', function() {
	  	return function(date) {
	    	return moment.unix(date).format('MMM D, h:mm A');
		}
  	});

}())
