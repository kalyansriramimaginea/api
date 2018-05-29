(function(){

    var app = angular.module('admin');

    var messageGroupPushController = function($location, messageResource, messageTypeResource, imageResource, $sce, $routeParams){

        var ctrl = this;
        ctrl.sendAt = moment().format('M/DD/YYYY h:mm A');
        ctrl.message = {"messageType": "News", "locationId": "", "channels": "", "kind": "group-push", "message": "", "deepLink": "", "photoUrl": "", "push": 1, "locationOnly": 0, "sendAt": 0};
        ctrl.channels = [{'name': 'None', 'value': ''}];

        messageResource.getItems('message').then(function() {
            var listItem = messageResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.message = listItem;
                ctrl.sendAt = moment.unix(ctrl.message.sendAt).format('M/DD/YYYY h:mm A');
            }
        });

        ctrl.save = function(){

            imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.message.photoUrl = fileName;
                }

                ctrl.message.sendAt = moment(ctrl.sendAt).format('X');
                messageResource.saveItem('message', ctrl.message).then(function() {
                    $location.path('/message');
                });

            });

        };

        ctrl.cancel = function(){
            $location.path('/message');
        };

        ctrl.removePhotoUrl = function(){
            ctrl.message.photoUrl = "";
            messageResource.saveItem('message', ctrl.message);
        };

    }

    // RETURNS WHICH SECTION THE APP IS ON
    app.getDisplaySection	= function(){
        return 'message';
    }

    app.controller('messageGroupPushController', messageGroupPushController);

}())
