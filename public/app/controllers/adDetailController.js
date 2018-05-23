(function(){

    var app = angular.module('admin');

    var adDetailController = function($location, adResource, imageResource, $sce, $routeParams){

        var ctrl = this;

        ctrl.ad = {"name": "", "url": "", "photoUrl": ""};


        adResource.getItems('ad').then(function() {
            var listItem = adResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.ad = listItem;
            }

        });


        ctrl.save = function(){

			imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.ad.photoUrl = fileName;
                }

				adResource.saveItem('ad', ctrl.ad).then(function() {
					$location.path('/ad');
				});
			});

    	};

        ctrl.cancel = function(){
            $location.path('/ad');
		};

		ctrl.removePhotoUrl = function(){
        	ctrl.ad.photoUrl = "";
	        adResource.saveItem('ad', ctrl.ad);
	    };

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'ad';
	}

    app.controller('adDetailController', adDetailController);

}())
