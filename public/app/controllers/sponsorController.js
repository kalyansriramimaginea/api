(function(){

    var app	= angular.module('admin');

    var sponsorController = function($location, sponsorResource, config, $http, $sce){

        var ctrl	= this;

        sponsorResource.getItems('sponsor').then(function() {
            ctrl.sponsors = sponsorResource.items;
        });

        ctrl.create = function(){
			$location.path('/sponsor/create');
        };

        ctrl.edit = function(object){
			$location.path('/sponsor/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                sponsorResource.deleteItem('sponsor', object).then(function() {
                    ctrl.sponsors = sponsorResource.items;
                    $location.path('/sponsor');
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
		return 'sponsor';
	}

	app.controller('sponsorController', sponsorController);

}())
