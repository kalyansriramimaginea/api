(function(){

    var app	= angular.module('admin');

    var mapController = function($location, mapResource, config, $http, $sce){

        var ctrl	= this;

        mapResource.getItems('map').then(function() {
            ctrl.maps = mapResource.items;
        });

        ctrl.create = function(){
            $location.path('/map/create');
        };

        ctrl.edit = function(object){
            $location.path('/map/' + object.id);
        };

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.id + "?");
            if (deleted) {
                mapResource.deleteItem('map', object).then(function() {
                    ctrl.maps = mapResource.items;
                    $location.path('/map');
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
        return 'map';
    }

    app.controller('mapController', mapController);

}())
