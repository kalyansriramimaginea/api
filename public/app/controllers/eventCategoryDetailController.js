(function(){

    var app = angular.module('admin');

    var eventCategoryDetailController = function($location, eventCategoryResource, imageResource, $sce, $routeParams){

        var ctrl = this;

        ctrl.eventCategory = {"name": "", "sortOrder": 10000};

        eventCategoryResource.getItems('event_category').then(function() {
            var listItem = eventCategoryResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.eventCategory = listItem;
            }

        });


        ctrl.save = function(){

			eventCategoryResource.saveItem('event_category', ctrl.eventCategory).then(function() {
				$location.path('/event_category');
			});

    	};

        ctrl.cancel = function(){
            $location.path('/event_category');
		};

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'event_category';
	}

    app.controller('eventCategoryDetailController', eventCategoryDetailController);

}())
