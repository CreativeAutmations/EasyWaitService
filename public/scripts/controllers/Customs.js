/* scripts/controllers/TimeEntry.js */
    
(function() {
    
    'use strict';
    angular
        .module('customsregister')
        .controller('CustomsRecordManager', CustomsRecordManager);

    function CustomsRecordManager(customs, $sce,  $window, $timeout, $cookies) {

            // vm is our capture variable
            var vm = this;
            vm.init = function () {
                vm.email = 'accountant@mydomain.com';
                vm.password = 'Test@123';
                vm.token = '';
				
				vm.expireDate = new Date();
                vm.expireDate.setDate(vm.expireDate.getDate() + 365);
				
				vm.token = $cookies.get('auth_token');

         	}
            
			// ++ Sign In Function Started
            vm.signin = function(email,pwd) {
				customs.signin(email,pwd).then(function(results) {
                	if ( results.data ) {
						vm.token = results.data.token ;
						
						// Setting a cookie
						$cookies.put('auth_token',vm.token, {'expires': vm.expireDate});
						vm.email = '';
						vm.password = '';
					} else {
						bootbox.alert("Sign In Failed" , function() {});
					}
                    console.log(results);
                }, function(error) {
                  console.log(error);
                });
            }
			// -- Sign In Function Ended
			
			
    }
})();
