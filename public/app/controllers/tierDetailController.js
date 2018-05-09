(function(){

    var app = angular.module('admin');

    var tierDetailController = function($location, tierResource, imageResource, $sce, $routeParams){

        var ctrl = this;
		
        ctrl.tier = {"name": "", "sortOrder": 10000};

		
        tierResource.getItems('tier').then(function() {
            var listItem = tierResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.tier = listItem;
            }
			
        });
		

        ctrl.save = function(){
			
			tierResource.saveItem('tier', ctrl.tier).then(function() {
				$location.path('/tier');
			});
		
    	};

        ctrl.cancel = function(){
            $location.path('/tier');
		};

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'tier';
	}

    app.controller('tierDetailController', tierDetailController);

}())
