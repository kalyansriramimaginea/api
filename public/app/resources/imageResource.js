(function(){

    var app	= angular.module('admin');

    var imageResource = function(config, $window, $http){

        var upload = function(file, callback){

            if(file) {
                var fileSize = Math.round(parseInt(file.size));
                if (fileSize > config.fileSizeLimit) {
                    console.log('Sorry, your attachment is too big. <br/> Maximum '  + fileSizeLabel(config.fileSizeLimit) + ' file attachment allowed');
                    return false;
                }

                var headers = { headers: {'Accept': 'application/vnd.p3.v1+json', 'Content-Type': undefined, 'Authorization': 'Bearer ' + config.user.token} }
                var object = new FormData();
                object.append('file', file);

                return $http.post(config.baseURL + '/classes/files', object, headers)
                    .then(
                        function(response){
                            callback(response["data"]);
                        },
                        function(response){
                            callback("");
                        }
                    );
            } else {
                callback("");
            }

        }

        return {
            upload	: upload
        }
    }

    app.factory('imageResource', imageResource);

}())
