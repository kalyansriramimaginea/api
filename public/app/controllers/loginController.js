(function(){

	var app	= angular.module('admin');

	var loginController	= function($location, localStorageService, $sce, $http, config){
		var ctrl	= this;

		// LOG USER IN
		ctrl.logIn	= function(){

			var headers = {'Accept': 'application/vnd.p3.v1+json', 'Content-Type': 'application/json'};
	      	var body = {'email': ctrl.user.email, 'password': ctrl.user.password};

			$http.post(config.baseURL + '/users/login', body, headers)
			.then(
				function(response){

					var data = response.data;
					var token = response.data.token;

					if (token){

						// SET USER OBJECT
						config.user = data;
						localStorageService.set('user', data);

						// var headers = {'Accept': 'application/vnd.p3.v1+json', 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token};
						// $http.post(config.baseURL + '/roles', {}, {"headers": headers})
						// .then(
						// 	function(response){
						//
						// 		var role = response.data.role;
						//
						// 		if(role){

									// SET USER OBJECT
									config.role = 'admin';
									localStorageService.set('role', 'admin');

									window.location = '/admin/dashboard';
						//
						// 		}
						//
						// 	},
						// 	function(response){}
						// );

					} else {
						document.getElementById('login').reset();
						document.getElementById('messageText').innerHTML = "Invalid login. Please try again.";
					}

				},
				function(response){
					document.getElementById('login').reset();
					document.getElementById('messageText').innerHTML = "Invalid login. Please try again.";
				}
			);

		}

	}

	app.controller('loginController', loginController);

}())
