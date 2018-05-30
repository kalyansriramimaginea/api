(function(){

    var app = angular.module('admin');
    app.config(function(tagsInputConfigProvider) {
        tagsInputConfigProvider
            .setActiveInterpolation('tagsInput', { placeholder: true });
    });

    var messageGroupPushController = function($location, messageResource, messageTypeResource, imageResource, $sce, $routeParams, config, $http){

        var ctrl = this;
        ctrl.sendAt = moment().format('M/DD/YYYY h:mm A');
        ctrl.message = {"messageType": "News", "locationId": "", "channels": "", "kind": "group-push", "message": "", "deepLink": "", "photoUrl": "", "push": 1, "locationOnly": 0, "sendAt": 0};
        ctrl.channels = [{'name': 'None', 'value': ''}];
        ctrl.tags = [];
        messageResource.getItems('message').then(function() {
            var listItem = messageResource.getItem($routeParams.id);
            if (listItem != null) {
                ctrl.message = listItem;
                ctrl.sendAt = moment.unix(ctrl.message.sendAt).format('M/DD/YYYY h:mm A');
            }
             ctrl.getEmails()
        });


        ctrl.getEmails = function() {

            var baseHeaders	= { headers: {'Accept': 'application/vnd.p3.v1+json', 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + config.user.token} }
            return $http.get(config.baseURL + '/users/installs/', baseHeaders)
                .then(
                    function(response){
                        ctrl.tags = response.data;
                        return;
                    },
                    function(response){
                        ctrl.tags = []
                        return;
                    }
                );
        };
        ctrl.save = function(){

            imageResource.upload(ctrl.photoUrlFile, function(fileName) {
                if (fileName != "") {
                    ctrl.message.photoUrl = fileName;
                }

                ctrl.message.sendAt = moment(ctrl.sendAt).format('X');


                ctrl.message.targets = ctrl.emails;

                messageResource.saveItem('message', ctrl.message).then(function() {
                    $location.path('/message');
                });

            });

        };

        ctrl.cancel = function(){
            $location.path('/message');
        };


        ctrl.loadData = function($query) {
            return ctrl.tags.filter(function(email) {
                return email.toLowerCase().indexOf($query.toLowerCase()) != -1;
            });
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
