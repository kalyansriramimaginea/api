(function(){

    var app = angular.module('admin');

    var sponsorDetailController = function($location, sponsorResource, tierResource, imageResource, $sce, $routeParams){

        var ctrl = this;

        ctrl.sponsor = {"tierId": "", "name": "", "siteUrl": "", "photoUrl": "", "sortOrder": 10000};

		tierResource.getItems('tier').then(function() {
			ctrl.tiers = tierResource.items;

        sponsorResource.getItems('sponsor').then(function() {
            var listItem = sponsorResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.sponsor = listItem;
            }
			ctrl.sponsor.tierId = (ctrl.sponsor.tierId == "" ? tierResource.items[0].id : ctrl.sponsor.tierId);
        });

		});


        ctrl.save = function(){

			imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.sponsor.photoUrl = fileName;
                }

				sponsorResource.saveItem('sponsor', ctrl.sponsor).then(function() {
					$location.path('/sponsor');
				});
			});

    	};

        ctrl.cancel = function(){
            $location.path('/sponsor');
		};

		ctrl.removePhotoUrl = function(){
        	ctrl.sponsor.photoUrl = "";
	        sponsorResource.saveItem('/sponsor', ctrl.sponsor);
	    };

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'sponsor';
	}

    app.controller('sponsorDetailController', sponsorDetailController);

}())
