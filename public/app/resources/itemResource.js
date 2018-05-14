(function(){

    var app	= angular.module('admin');

    var itemResource	= function(config, $http, $filter, $q){

        var service = {
            items: [],
            item: {}
        };

        service.reset = function() {
            service.items = [];
            service.item = {};
        }

        service.getItems = function(path) {
            if (service.items.length > 0) {
                return $q.when(service);
            } else {
                var baseHeaders	= { headers: {'Accept': 'application/vnd.p3.v1+json', 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + config.user.token} }
                return $http.get(config.baseURL + '/classes/' + path, baseHeaders)
                    .then(
                        function(response){
                            service.items = response.data.data.map(flattenObject);
                            return;
                        },
                        function(response){
                            service.items = [];
                            return;
                        }
                    );
            }

        }

        service.getItem = function(itemId) {

            var idItems = $filter('filter')(service.items, { id: itemId });
            if (idItems.length > 0) {
                return idItems[0];
            } else {
                return null;
            }

        }

        service.saveItem = function(path, object) {

            var headers = { headers: {'Accept': 'application/vnd.p3.v1+json', 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + config.user.token} }
            return $http.post(config.baseURL + '/classes/' + path, object, headers)
                .then(
                    function(response){
                        var newObject = flattenObject(response.data.data);
                        service.item = newObject;
                        if (service.getItem(newObject.id) != null) {
                            angular.forEach(service.items, function(obj, i) {
                                if (obj.id === newObject.id) {
                                    service.items[i] = newObject;
                                }
                            });
                        } else {
                            service.items.push(newObject);
                        }
                        return newObject;
                    },
                    // ERROR
                    function(response){
                        if(response.status == 401 && response.data.error == 'invalid_credentials') {
                            service.item = {};
                            service.items = [];
                            return service.getItems(path);
                        } else if(response.status == 401 && response.statusText == 'Unauthorized'){
                            //window.location = 'login.html';
                        }
                        if(response.status == 500){
                            service.item = {};
                            service.items = [];
                            return service.getItems(path);
                        }
                    }
                );

        }

        service.deleteItem = function(path, object) {

            var headers = { headers: {'Accept': 'application/vnd.p3.v1+json', 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + config.user.token}, data: object}
            return $http.delete(config.baseURL + '/classes/' + path, headers)
                .then(
                    function(response){
                        service.item = {};
                        service.items = [];
                        return service.getItems(path);
                    },
                    // ERROR
                    function(response){
                        if(response.status == 401 && response.data.error == 'invalid_credentials') {
                            service.item = {};
                            service.items = [];
                            return;
                        } else if(response.status == 401 && response.statusText == 'Unauthorized'){
                            //window.location = 'login.html';
                        }
                        if(response.status == 500){
                            service.item = {};
                            service.items = [];
                            return;
                        }
                    }
                );

        }

        return service;

    }

    app.factory('messageResource', itemResource);
    app.factory('messageTypeResource', itemResource);
    app.factory('helpResource', itemResource);
    app.factory('menuSlideResource', itemResource);
    app.factory('sponsorResource', itemResource);
    app.factory('tierResource', itemResource);
    app.factory('vendorResource', itemResource);
    app.factory('mapResource', itemResource);
    app.factory('eventResource', itemResource);
    app.factory('vendorCategoryResource', itemResource);
    app.factory('eventCategoryResource', itemResource);

}())
