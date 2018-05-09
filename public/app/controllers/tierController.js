(function(){

    var app	= angular.module('admin');

    var tierController = function($location, tierResource, config, $http, $sce){

        var ctrl	= this;

        tierResource.getItems('tier').then(function() {
            ctrl.tiers = tierResource.items;
        });

        ctrl.create = function(){
			$location.path('/tier/create');
        };

        ctrl.edit = function(object){
			$location.path('/tier/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                tierResource.deleteItem('tier', object).then(function() {
                    ctrl.tiers = tierResource.items;
                    $location.path('/tier');
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
		return 'tier';
	}

	app.controller('tierController', tierController);

}())
