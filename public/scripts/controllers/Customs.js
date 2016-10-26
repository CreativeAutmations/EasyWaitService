/* scripts/controllers/TimeEntry.js */
    
(function() {
    
    'use strict';
    angular
        .module('customsregister')
        .controller('CustomsRecordManager',  CustomsRecordManager);

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
				
				vm.retrieved = {};
				vm.retrieved.receipt = {};	

				vm.searchByDateResults = {};
				vm.searchByDateResultsHeaders = {
					bill_number: "Bill Number",
					bill_date: "Bill Date",
					description: "Description",
					unit_quantity: "Unit Quantity",
					unit_weight: "Unit Weight",
					value: "Value",
					duty: "Duty",
					balance_quantity: "Balance Quantity",
					balance_value: "Balance Value",
					b17_debit: "B17 Debit",
					invoice_date: "Invoice Date",
					invoice_no: "Invoice No",
					procurement_certificate: "Procurement Certificate",
					procurement_date: "Procurement Date",
					transport_registration: "Transport Registration",
					receipt_timestamp: "Receipt Timestamp"
				};
				
				
				vm.auditTrail = {};
				vm.auditTrailHeaders = {
					user_id: "User Id",
					action: "Action",
					category: "Section",
					update_date: "Updated At",
					bill_number: "Bill Number",
					bill_date: "Bill Date",
					description: "Description",
					unit_quantity: "Unit Quantity",
					unit_weight: "Unit Weight",
					value: "Value",
					duty: "Duty",
					balance_quantity: "Balance Quantity",
					balance_value: "Balance Value",
					b17_debit: "B17 Debit",
					invoice_date: "Invoice Date",
					invoice_no: "Invoice No",
					procurement_certificate: "Procurement Certificate",
					procurement_date: "Procurement Date",
					transport_registration: "Transport Registration",
					receipt_timestamp: "Receipt Timestamp"
				};
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
			
			// ++ Create Receipt
            vm.update = function(receipt) {
				vm.retrieved.receipt = receipt;
				customs.addreceipt(receipt , vm.token).then(function(results) {
                	if ( results.data  && results.data.message === "Receipt Recorded") {
						bootbox.alert("Recipt Created" , function() {});
						vm.receipt_to_add = {};
					} else {
						bootbox.alert("Recipt Creation Failed" , function() {});
						vm.retrieved.receipt = {};
					}
                    console.log(results);
                }, function(error) {
                  console.log(error);
                });
				
				console.log("OK");
            }
			// -- Sign In Function Ended
						
			// ++ Get Receipts By Date Starts
            vm.getReceiptsByDate = function(bill_date) {
				customs.getReceiptsByDate(bill_date , vm.token).then(function(results) {
                	if ( results.data ) {
						vm.receipt_to_search.bill_date = '';
						vm.searchByDateResults = results.data;
						console.log(results.data);
					} else {
						bootbox.alert("Recipt Search Failed" , function() {});
					}
                    console.log(results);
                }, function(error) {
                  console.log(error);
                });
				
				console.log("OK");
            }
			// -- Get Receipts By Date Ends
			
			// ++ Get Audit Trail For a Bill Starts
            vm.getAuditTrail = function(bill_number) {
				customs.getAuditTrail(bill_number , vm.token).then(function(results) {
                	if ( results.data ) {
						vm.auditTrail = results.data;
						console.log(results.data);
					} else {
						bootbox.alert("Recipt Search Failed" , function() {});
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
