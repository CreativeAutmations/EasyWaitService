angular.module('customsregister')
.factory('customs', ['$http', function($http) {
  
  var signin = function(email,pwd) {
	    var url = 'http://54.190.12.210:8000//api/signin' ;
	    // return $http.post(url).success(function(data) { });
		return $http({
			url: url,
			method: "POST",
			data: { 'email' : email,  'password' : pwd}
		})
		.then(function(response) {
				// success
				return response;
		}, 
		function(response) { // optional
				// failed
				return response;
		});
	};

	
	return {
    signin: signin
  };
}]);
