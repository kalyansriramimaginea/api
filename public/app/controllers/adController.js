(function(){

    var app	= angular.module('admin');

    var adController = function($location, adResource, config, $http, $sce){

        var ctrl	= this;

        adResource.getItems('ad').then(function() {
            ctrl.ads = adResource.items;
        });

        ctrl.create = function(){
			       $location.path('/ad/create');
        };

        ctrl.edit = function(object){
			   $location.path('/ad/' + object.id);
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
      	    if (deleted) {
                adResource.deleteItem('ad', object).then(function() {
                    ctrl.ads = adResource.items;
                    $location.path('/ad');
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
		return 'ad';
	}

	app.controller('adController', adController);

}())
x
