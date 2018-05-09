(function(){

    var app	= angular.module('admin');

    var helpController = function($location, helpResource, config, $http, $sce){

        var ctrl	= this;

        helpResource.getItems('help').then(function() {
            ctrl.helps = helpResource.items;
        });

        ctrl.create = function(){
			$location.path('/help/create');
        };

        ctrl.edit = function(object){
			$location.path('/help/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                helpResource.deleteItem('help', object).then(function() {
                    ctrl.helps = helpResource.items;
                    $location.path('/help');
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
		return 'help';
	}

	app.controller('helpController', helpController);

}())
