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

  var addOrUpdateReceipt = function(formdata, token, action) {
	    var url = 'http://54.190.12.210:8000/api/receipts' ;
		var method = "POST";
		if ( action === 'Update') {
			method = "PUT";
			var url = 'http://54.190.12.210:8000/api/receipts/' +  formdata.bill_number;
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

	
  var getAuditTrail = function(bill_number , token) {
	    var url = 'http://54.190.12.210:8000/api/audit/' + bill_number ;
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
	addOrUpdateReceipt: addOrUpdateReceipt,
	getReceiptsByDate: getReceiptsByDate,
	getAuditTrail: getAuditTrail
  };
}]);
