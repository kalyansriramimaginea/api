(function(){

    var app = angular.module('admin');

    var vendorCategoryDetailController = function($location, vendorCategoryResource, imageResource, $sce, $routeParams){

        var ctrl = this;

        ctrl.vendorCategory = {"name": "", "photoUrl": "", "sortOrder": 10000};

        vendorCategoryResource.getItems('vendor_category').then(function() {
            var listItem = vendorCategoryResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.vendorCategory = listItem;
            }

        });


        ctrl.save = function(){

            imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.vendorCategory.photoUrl = fileName;
                }
    			vendorCategoryResource.saveItem('vendor_category', ctrl.vendorCategory).then(function() {
    				$location.path('/vendor_category');
    			});
            });

    	};

        ctrl.cancel = function(){
            $location.path('/vendor_category');
		};

		ctrl.removePhotoUrl = function(){
        	ctrl.vendorCategory.photoUrl = "";
	        vendorCategoryResource.saveItem('/vendor_category', ctrl.vendorCategory);
	    };

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'vendor_category';
	}

    app.controller('vendorCategoryDetailController', vendorCategoryDetailController);

}())
