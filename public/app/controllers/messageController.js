(function(){

    var app	= angular.module('admin');

    var messageController = function($location, messageResource, messageTypeResource, config, $http, $sce){

        var ctrl = this;

        messageResource.getItems('message').then(function() {
            ctrl.messages = messageResource.items;
        });

        ctrl.create = function(path){
			$location.path('/message' + path + '/create');
        };

        ctrl.edit = function(object){
			if (object.kind == "location") {
				$location.path('/message/location/' + object.id);
			} else if (object.kind == "beacon") {
				$location.path('/message/beacon/' + object.id);
			} else if (object.kind == "group-push") {
                $location.path('/message/push/' + object.id);
            } else if (object.kind == "group-push") {
                $location.path('/message/group-push/' + object.id);
            } else {
				$location.path('/message/' + object.id);
			}
		};

        ctrl.delete = function(object){
            var deleted = window.confirm("Are you sure you want to delete " + object.message + "?");
      	    if (deleted) {
                messageResource.deleteItem('message', object).then(function() {
                    ctrl.messages = messageResource.items;
					$location.path('/message');
                });
            }
    	};

        ctrl.child = function(array, itemId) {
            var values = array.filter(function(item) { return itemId === item.id });
            var value = values.length > 0 ? values[0] : {};
            return value;
        };

    }

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
		return 'message';
	}

	app.controller('messageController', messageController);

}())
