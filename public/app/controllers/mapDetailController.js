(function(){

    var app = angular.module('admin');

    var mapDetailController = function($location, mapResource, imageResource, $sce, $routeParams){

        var ctrl = this;

        ctrl.map = {"name": "", "photoUrl": "", "sortOrder": 10000};


        mapResource.getItems('map').then(function() {
            var listItem = mapResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.map = listItem;
            }

        });


        ctrl.save = function(){

            imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.map.photoUrl = fileName;
                }

                mapResource.saveItem('map', ctrl.map).then(function() {
                    $location.path('/map');
                });
            });

        };

        ctrl.cancel = function(){
            $location.path('/map');
        };

        ctrl.removePhotoUrl = function(){
            ctrl.map.photoUrl = "";
            mapResource.saveItem('/map', ctrl.map);
        };

    }

    // RETURNS WHICH SECTION THE APP IS ON
    app.getDisplaySection	= function(){
        return 'map';
    }

    app.controller('mapDetailController', mapDetailController);

}())
