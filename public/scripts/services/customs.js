angular.module('customsregister')
.factory('customs', ['$http', function($http) {
  
  var signin = function(email,pwd) {
	    var url = 'http://54.190.12.210:8000//api/signin' ;
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

  var addreceipt = function(formdata, token) {
	    var url = 'http://54.190.12.210:8000/api/receipts' ;
		return $http({
			url: url,
			method: "POST",
			data: formdata,
			headers: {
				'Authorization': "Bearer ".concat(token)
			}
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

  var getReceiptsByDate = function(bill_date , token) {
	    var url = 'http://54.190.12.210:8000/api/receipts/search' ;
		return $http({
			url: url,
			method: "POST",
			data: {'bill_date' : {'value': bill_date, 'operation': '>='}},
			headers: {
				'Authorization': "Bearer ".concat(token)
			}
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
    signin: signin,
	addreceipt: addreceipt,
	getReceiptsByDate: getReceiptsByDate
  };
}]);
