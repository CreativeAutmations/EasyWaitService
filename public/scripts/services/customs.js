angular.module('customsregister')
.factory('customs', ['$http', function($http) {
  
  var signin = function(email,pwd) {
	    var url = 'api/signin' ;
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

  var signup = function(name,email,pwd) {
	    var url = 'api/signup' ;
		return $http({
			url: url,
			method: "POST",
			data: { 'name': name, 'email' : email,  'password' : pwd}
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

	
  var addOrUpdateOrganization = function(formdata, token, action) {
	    var url = 'api/organizations' ;
		var method = "POST";
		if ( action === 'Update') {
			method = "PUT";
		}
		return $http({
			url: url,
			method: method,
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
	
  var addOrUpdateReceipt = function(formdata, token, action) {
	    var url = 'api/receipts' ;
		var method = "POST";
		if ( action === 'Update') {
			method = "PUT";
			var url = 'api/receipts/' +  formdata.bill_number;
		}
		return $http({
			url: url,
			method: method,
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
	    var url = 'api/receipts/search' ;
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

	  var getOrganization = function(token) {
	    var url = 'api/organizations' ;
		return $http({
			url: url,
			method: "GET",
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
	


  var getAuditTrail = function(bill_number , token) {
	    var url = 'api/audit/' + bill_number ;
		return $http({
			url: url,
			method: "GET",
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
	signup: signup,
	getOrganization: getOrganization,
	addOrUpdateOrganization: addOrUpdateOrganization,
	addOrUpdateReceipt: addOrUpdateReceipt,
	getReceiptsByDate: getReceiptsByDate,
	getAuditTrail: getAuditTrail
  };
}]);
