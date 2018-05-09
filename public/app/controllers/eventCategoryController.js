(function(){

    var app	= angular.module('admin');

    var eventCategoryController = function($location, eventCategoryResource, config, $http, $sce){

        var ctrl	= this;

        eventCategoryResource.getItems('event_category').then(function() {
            ctrl.eventCategories = eventCategoryResource.items;
        });

        ctrl.create = function(){
			$location.path('/event_category/create');
        };

        ctrl.edit = function(object){
			$location.path('/event_category/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                eventCategoryResource.deleteItem('event_category', object).then(function() {
                    ctrl.eventCategories = eventCategoryResource.items;
                    $location.path('/event_category');
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
		return 'event_category';
	}

	app.controller('eventCategoryController', eventCategoryController);

}())
