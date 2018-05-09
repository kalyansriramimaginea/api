(function(){

    var app	= angular.module('admin');

    var vendorController = function($location, vendorResource, config, $http, $sce){

        var ctrl	= this;

        vendorResource.getItems('vendor').then(function() {
            ctrl.vendors = vendorResource.items;
        });

        ctrl.create = function(){
			$location.path('/vendor/create');
        };

        ctrl.edit = function(object){
			$location.path('/vendor/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                vendorResource.deleteItem('vendor', object).then(function() {
                    ctrl.vendors = vendorResource.items;
                    $location.path('/vendor');
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
		return 'vendor';
	}

	app.controller('vendorController', vendorController);

}())
