(function(){

    var app	= angular.module('admin');

    var menuSlideController = function($location, menuSlideResource, config, $http, $sce){

        var ctrl	= this;

        menuSlideResource.getItems('menu_slide').then(function() {
            ctrl.menuSlides = menuSlideResource.items;
        });

        ctrl.create = function(){
			$location.path('/menu_slide/create');
        };

        ctrl.edit = function(object){
			$location.path('/menu_slide/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                menuSlideResource.deleteItem('menu_slide', object).then(function() {
                    ctrl.menuSlides = menuSlideResource.items;
                    $location.path('/menu_slide');
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
		return 'menu_slide';
	}

	app.controller('menuSlideController', menuSlideController);

}())
