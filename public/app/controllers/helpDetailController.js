(function(){

    var app = angular.module('admin');

    var helpDetailController = function($location, helpResource, imageResource, $sce, $routeParams){

        var ctrl = this;
		
        ctrl.help = {"question": "", "answer": "", "photoUrl": "", "sortOrder": 10000};

		
        helpResource.getItems('help').then(function() {
            var listItem = helpResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.help = listItem;
            }
			
        });
		

        ctrl.save = function(){
			
			imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.help.photoUrl = fileName;
                }

				helpResource.saveItem('help', ctrl.help).then(function() {
					$location.path('/help');
				});
			});
			
    	};

        ctrl.cancel = function(){
            $location.path('/help');
		};

		ctrl.removePhotoUrl = function(){
        	ctrl.help.photoUrl = "";
	        helpResource.saveItem('/help', ctrl.help);
	    };
			
	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'help';
	}

    app.controller('helpDetailController', helpDetailController);

}())
