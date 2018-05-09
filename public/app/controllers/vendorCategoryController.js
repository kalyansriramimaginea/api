(function(){

    var app	= angular.module('admin');

    var vendorCategoryController = function($location, vendorCategoryResource, config, $http, $sce){

        var ctrl	= this;

        vendorCategoryResource.getItems('vendor_category').then(function() {
            ctrl.vendorCategories = vendorCategoryResource.items;
        });

        ctrl.create = function(){
			$location.path('/vendor_category/create');
        };

        ctrl.edit = function(object){
			$location.path('/vendor_category/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                vendorCategoryResource.deleteItem('vendor_category', object).then(function() {
                    ctrl.vendorCategories = vendorCategoryResource.items;
                    $location.path('/vendor_category');
                });
            }
    	};

        ctrl.child = function(array, itemId) {
            if (!array) { return {}; }
            var values = array.filter(function(item) { return itemId === item.id });
            var value = values.length > 0 ? values[0] : {};
            return value;
        };

    }

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
		return 'vendor_category';
	}

	app.controller('vendorCategoryController', vendorCategoryController);

}())
