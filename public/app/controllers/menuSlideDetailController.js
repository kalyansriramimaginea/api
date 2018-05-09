(function(){

    var app = angular.module('admin');

    var menuSlideDetailController = function($location, menuSlideResource, imageResource, $sce, $routeParams){

        var ctrl = this;

        ctrl.menuSlide = {"title": "", "about": "", "url": "", "sortOrder": 10000};


        menuSlideResource.getItems('menu_slide').then(function() {
            var listItem = menuSlideResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.menuSlide = listItem;
            }

        });


        ctrl.save = function(){

			menuSlideResource.saveItem('menu_slide', ctrl.menuSlide).then(function() {
				$location.path('/menu_slide');
			});

    	};

        ctrl.cancel = function(){
            $location.path('/menu_slide');
		};

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'menu_slide';
	}

    app.controller('menuSlideDetailController', menuSlideDetailController);

}())
