/* scripts/controllers/TimeEntry.js */
    
(function() {
    
    'use strict';
	
    angular
    .module('customsregister').directive('authentication', function() {
        var directive = {};

        directive.restrict = 'E';

        directive.templateUrl = "./html-templates/customs-authentication.html";

        directive.scope = {
            vm : "=vm"
        }

        return directive;
    });    


    angular
    .module('customsregister').directive('receiptupdate', function() {
        var directive = {};

        directive.restrict = 'E';

        directive.templateUrl = "./html-templates/customs-recipts-update.html";

        directive.scope = {
            vm : "=vm"
        }

        return directive;
    });    

    angular
    .module('customsregister').directive('receiptsearch', function() {
        var directive = {};

        directive.restrict = 'E';

        directive.templateUrl = "./html-templates/customs-search.html";

        directive.scope = {
            vm : "=vm"
        }

        return directive;
    });    
	
	
    angular
    .module('customsregister').directive('reportforcustoms', function() {
        var directive = {};

        directive.restrict = 'E';

        directive.templateUrl = "./html-templates/customs-report.html";

        directive.scope = {
            vm : "=vm"
        }

        return directive;
    });    
	
    angular
    .module('customsregister').directive('audittrail', function() {
        var directive = {};

        directive.restrict = 'E';

        directive.templateUrl = "./html-templates/customs-audit-trail.html";

        directive.scope = {
            vm : "=vm"
        }

        return directive;
    });    
	
	
	
    angular
        .module('customsregister')
        .controller('CustomsRecordManager',  CustomsRecordManager);

    function CustomsRecordManager(customs, $sce,  $window, $timeout, $cookies) {

            // vm is our capture variable
            var vm = this;
            vm.init = function () {
				vm.email = '';
                vm.password = '';
				
				vm.expireDate = new Date();
                vm.expireDate.setDate(vm.expireDate.getDate() + 365);
				
				vm.initAuthentication();
				vm.initAuditTrail();
				vm.initSearch();
				vm.retrieved = {};
				vm.retrieved.receipt = {};	
				vm.resetAddEditWindow();
				vm.setCurrentView('Search');
         	}

			vm.setCurrentView = function(view_name){
				vm.activeView = view_name;
			}
			
			// ++ Initialization Routines
			vm.initAuthentication = function(){
				vm.token = $cookies.get('auth_token');
				if ( vm.token ) {
					vm.isAuthenticated = true;
				} else {
					vm.isAuthenticated = false;
				}
			}

			vm.togggleReportView = function(){
				if ( vm.showReport ) {
					vm.showReport = false;
				} else {
					vm.showReport = true;	
				}
			}
			
			vm.initAuditTrail = function(){
				vm.auditTrail = {};
				vm.auditTrailHeaders = {
					user_id: "User Id",
					action: "Action",
					category: "Section",
					update_date: "Updated At",
					bill_number: "Bill of Entry Number",
					bill_date: "Bill Date",
					customs_station: "Customs Station of import, if applicable",
					warehouse_details: "Code and address of Warehouse",
					eou_details: "Name & Address of EoU",
					other_procurement_source: "Others (in case of any other source of procurement)",
					description: "Description",
					unit: "Unit",
					quantity: "Quantity",
					weight: "Weight",
					value: "Value",
					duty: "Duty",
					balance_quantity: "Balance Quantity",
					balance_value: "Balance Value",
					b17_debit: "Details of B-17 Bond/ Amount debited",
					invoice_date: "Invoice Date",
					invoice_no: "Invoice No",
					procurement_certificate: "Procurement Certificate",
					procurement_date: "Procurement Date",
					transport_registration: "Transport Registration",
					receipt_timestamp: "Receipt Timestamp",
				};
			};
			
			vm.initSearch = function(){
					var default_date = new Date();
					vm.receipt_to_search = {};
					vm.receipt_to_search.bill_date =  new Date(default_date.getFullYear() -1 , default_date.getMonth() , 1);

					vm.searchByDateResults = {};
					vm.searchByDateResultsHeaders = {
						bill_number: "Bill of Entry Number",
						bill_date: "Bill Date",
						customs_station: "Customs Station of import, if applicable",
						warehouse_details: "Code and address of Warehouse",
						eou_details: "Name & Address of EoU",
						other_procurement_source: "Others (in case of any other source of procurement)",
						description: "Description",
						unit: "Unit",
						quantity: "Quantity",
						weight: "Weight",
						value: "Value",
						duty: "Duty",
						balance_quantity: "Balance Quantity",
						balance_value: "Balance Value",
						b17_debit: "Details of B-17 Bond/ Amount debited",
						invoice_date: "Invoice Date",
						invoice_no: "Invoice No",
						procurement_certificate: "Procurement Certificate",
						procurement_date: "Procurement Date",
						transport_registration: "Transport Registration",
						receipt_timestamp: "Receipt Timestamp",
					};
				};
			
			vm.isActiveView = function (view_name) {
				return vm.activeView === view_name;
				
			}
			// -- Initialization Routines
            
			// ++ Sign In Function Started
            vm.signin = function(email,pwd) {
				customs.signin(email,pwd).then(function(results) {
                	if ( results.data ) {
						// Setting a cookie
						$cookies.put('auth_token',results.data.token, {'expires': vm.expireDate});
						vm.initAuthentication();
					} else {
						bootbox.alert("Sign In Failed" , function() {});
					}
                    console.log(results);
                }, function(error) {
                  console.log(error);
                });
            }
			// -- Sign In Function Ended
			
			// ++ Sign Out Function Started
            vm.signOut = function(email,pwd) {
				$cookies.remove('auth_token');
				vm.initAuthentication();
            }
			// -- Sign Out Function Ended
			
			// ++ Create Receipt
            vm.update = function(receipt) {
				
				customs.addOrUpdateReceipt(receipt , vm.token, vm.addUpdateAction ).then(function(results) {
                	if ( results.data ) {
						bootbox.alert(results.data.message , function() {});
						vm.retrieved.receipt = vm.receipt_to_add;
						vm.resetAddEditWindow();
					} else {
						bootbox.alert("Recipt Creation Failed" , function() {});
						vm.retrieved.receipt = {};
					}
                    console.log(results);
                }, function(error) {
                  console.log(error);
                });
				
				console.log("OK");
            };
			// -- Sign In Function Ended

			vm.resetAddEditWindow = function() {
				vm.addUpdateAction = 'Add';
				vm.receipt_to_add = {};
				vm.readonlyBillNumber = false;
			};

			// ++ Prepare For Edit
            vm.prepareForEdit = function(receipt_data) {
				if ( receipt_data.bill_number ) {
					vm.addUpdateAction = 'Update';
					vm.receipt_to_add = angular.copy(receipt_data);
					vm.readonlyBillNumber = true;
				}
					vm.setCurrentView('AddEdit');
            }
			// -- Prepare For Edit

			
			// ++ Get Receipts By Date Starts
            vm.getReceiptsByDate = function(bill_date) {
				var mm = bill_date.getMonth() + 1;
				var dd = bill_date.getDate();
				var yyyy = bill_date.getFullYear();
				mm = (mm < 10) ? '0' + mm : mm;
				dd = (dd < 10) ? '0' + dd : dd;
				
				var date = yyyy + '-' + mm + '-' + dd ;

				vm.reportDate = new Date();
				
				customs.getReceiptsByDate(date , vm.token).then(function(results) {
					// If Status 200 then set this to data
					if ( results.status === 200 ) {
						// Got results
						vm.searchByDateResults = results.data;
						console.log(results.data);
					} else if (  results.status === 401 ){
						// Authorization Problem
						bootbox.alert(results.data.details.message , function() {});
						vm.isAuthenticated = false;
					} else if ( results.status === 404 ){
						// Empty Result Set
						bootbox.alert(results.data.details.message , function() {});
						vm.searchByDateResults = {};
						
					} else if ( results.status === 500 ) {
						bootbox.alert(results.data.details.message , function() {});
					}
	            }, function(error) {
                  console.log(error);
                });
            }
			// -- Get Receipts By Date Ends
			
			// ++ Get Audit Trail For a Bill Starts
            vm.getAuditTrail = function(bill_number) {
				customs.getAuditTrail(bill_number , vm.token).then(function(results) {
                	if ( results.data ) {
						vm.auditTrail = results.data;
						vm.setCurrentView('Audit');
						console.log(results.data);
					} else {
						bootbox.alert("Audit Trail Could Not Be Fetched" , function() {});
					}
                    console.log(results);
                }, function(error) {
                  console.log(error);
                });
				
				console.log("OK");
            }
			// -- Get Audit Trail For a Bill Ends
    }
})();
