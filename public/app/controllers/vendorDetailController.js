(function(){

    var app = angular.module('admin');

    var vendorDetailController = function($location, vendorResource, vendorCategoryResource, imageResource, $sce, $routeParams){

        var ctrl = this;

        ctrl.vendor = {"vendorCategorId": "", "name": "", "photoUrl": "", "about": "", "siteUrl": "", "fbUrl": "", "twUrl": "", "inUrl": "", "lat": "", "lon": "", "sortOrder": 10000};

		vendorCategoryResource.getItems('vendor_category').then(function() {
			ctrl.vendorCategories = vendorCategoryResource.items;
            vendorResource.getItems('vendor').then(function() {
                var listItem = vendorResource.getItem($routeParams.id);
                if (listItem != null) {
                    ctrl.vendor = listItem;
                }
            });
        });

        ctrl.save = function(){

			imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.vendor.photoUrl = fileName;
                }

				vendorResource.saveItem('vendor', ctrl.vendor).then(function() {
					$location.path('/vendor');
				});
			});

    	};

        ctrl.cancel = function(){
            $location.path('/vendor');
		};

		ctrl.removePhotoUrl = function(){
        	ctrl.vendor.photoUrl = "";
	        vendorResource.saveItem('/vendor', ctrl.vendor);
	    };

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'vendor';
	}

    app.controller('vendorDetailController', vendorDetailController);

}())
