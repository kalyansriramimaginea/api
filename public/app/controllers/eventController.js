(function(){

    var app	= angular.module('admin');

    var eventController = function($location, eventResource, config, $http, $sce){

        var ctrl	= this;

        eventResource.getItems('event').then(function() {
            ctrl.events = eventResource.items;
        });

        ctrl.create = function(){
			$location.path('/event/create');
        };

        ctrl.edit = function(object){
			$location.path('/event/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                eventResource.deleteItem('event', object).then(function() {
                    ctrl.events = eventResource.items;
                    $location.path('/event');
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
		return 'event';
	}

	app.controller('eventController', eventController);

}())
