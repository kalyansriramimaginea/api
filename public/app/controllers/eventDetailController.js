(function(){

    var app = angular.module('admin');

    var eventDetailController = function($location, eventResource, eventCategoryResource, imageResource, $sce, $routeParams){

        var ctrl = this;
		ctrl.startAt = moment().format('M/DD/YYYY h:mm A');
        ctrl.event = {"name": "", "eventCategoryId": "", "photoUrl": "", "about": "", "fbUrl": "", "twUrl": "", "inUrl": "", "siteUrl": "", "startAt": 0, "endAt": 0, "sponsorPhotoUrl": "", "allDay": 0};


		eventCategoryResource.getItems('event_category').then(function() {
			ctrl.eventCategories = eventCategoryResource.items;
	        eventResource.getItems('event').then(function() {
	            var listItem = eventResource.getItem($routeParams.id);
	            if (listItem != null) {
	                ctrl.event = listItem;
					ctrl.startAt = moment.unix(ctrl.event.startAt).format('M/DD/YYYY h:mm A');
					ctrl.endAt = moment.unix(ctrl.event.endAt).format('M/DD/YYYY h:mm A');
	            }
				ctrl.event.eventCategoryId = (ctrl.event.eventCategoryId == "" ? eventCategoryResource.items[0].id : ctrl.event.eventCategoryId);

	        });
		});

        ctrl.save = function(){
    			imageResource.upload(ctrl.photoUrlFile, function(fileName) {
            if (fileName != "") {
        			ctrl.event.photoUrl = fileName;
            }
            imageResource.upload(ctrl.sponsorPhotoUrlFile, function(sponsorFileName) {
              if(sponsorFileName != "") {
                ctrl.event.sponsorPhotoUrl = sponsorFileName;
              }else {
                ctrl.event.sponsorPhotoUrl = "File not found :("
              }
              ctrl.event.startAt = moment(ctrl.startAt).format('X');
              ctrl.event.endAt = moment(ctrl.endAt).format('X');
              eventResource.saveItem('event', ctrl.event).then(function() { $location.path('/event'); });
            });
          });

    	  };

        ctrl.cancel = function(){
            $location.path('/event');
		};

  		ctrl.removePhotoUrl = function(){
          	ctrl.event.photoUrl = "";
  	        eventResource.saveItem('/event', ctrl.event);
  	    };
      ctrl.removeSponsorPhotoUrl = function() {
        ctrl.event.sponsorPhotoUrl = "";
      }

	}

    // RETURNS WHICH SECTION THE APP IS ON
	app.getDisplaySection	= function(){
        return 'event';
	}

    app.controller('eventDetailController', eventDetailController);

}())
