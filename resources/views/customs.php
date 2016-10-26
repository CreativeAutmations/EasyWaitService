<!DOCTYPE html>
<html>
	<head>
		<title>Customs Register</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<!-- Bootstrap -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<!-- Application Dependencies -->
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular-route.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular-sanitize.js"></script>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap-tpls.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-resource/1.5.8/angular-resource.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.5/angular-cookies.min.js"></script>

		<!-- Application Scripts -->
		<script type="text/javascript" src="scripts/app.js"></script>
		<script type="text/javascript" src="scripts/services/customs.js"></script>
		<script type="text/javascript" src="scripts/controllers/Customs.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/bootbox/4.4.0/bootbox.min.js"></script>

		</head>
	<body ng-app="customsregister" ng-controller="CustomsRecordManager as vm" data-ng-init="vm.init()">
		<div id="navbar"></div>
		<!-- Grid System      ====================================== -->
		<div class="container-fluid">
			<div class="row">
				<h3></h3>
				<h3></h3>
			</div>
			<!-- Authentication      ====================================== -->
			<div class="row">
				<div class="col-md-4">
					<div class="input-group">
						<input title="email" class="form-control" ng-model="vm.email" placeholder="user@domain.com" ng-minlength=6 ng-maxlength=255>
						<input title="password" class="form-control" ng-model="vm.password" placeholder="password" ng-minlength=6 ng-maxlength=32>
						<button class="btn btn-primary" ng-click="vm.signin(vm.email, vm.password)">Sign In</button>
					</div>
					<!-- /input-group -->
				</div>
			</div>
			<!-- Receipt Creation      ====================================== -->
			<hr>
			<div class="row">
				<div class="col-md-12">
			<form novalidate class="simple-form">
				*Bill Number: <input type="text" ng-model="vm.receipt_to_add.bill_number" />
				*Bill Date: <input type="text" ng-model="vm.receipt_to_add.bill_date" />
				*B17 Debit: <input type="text" ng-model="vm.receipt_to_add.b17_debit" />
				*Description: <input type="text" ng-model="vm.receipt_to_add.description" /><br/><p></p>
				Invoice No: <input type="text" ng-model="vm.receipt_to_add.invoice_no" />
				Invoice Date: <input type="text" ng-model="vm.receipt_to_add.invoice_date" /><br /><p></p>
				Procurement Certificate: <input type="text" ng-model="vm.receipt_to_add.procurement_certificate" />
				Procurement Date: <input type="text" ng-model="vm.receipt_to_add.procurement_date" /><br /><p></p>
				Unit Weight: <input type="text" ng-model="vm.receipt_to_add.unit_weight" />
				Unit Quantity: <input type="text" ng-model="vm.receipt_to_add.unit_quantity" />
				*Value: <input type="text" ng-model="vm.receipt_to_add.value" />
				*Duty: <input type="text" ng-model="vm.receipt_to_add.duty" /><br/><p></p>
				Transport Registration: <input type="text" ng-model="vm.receipt_to_add.transport_registration" />
				Receipt Timestamp: <input type="text" ng-model="vm.receipt_to_add.receipt_timestamp" /><br /><p></p>
				*Balance Quantity: <input type="text" ng-model="vm.receipt_to_add.balance_quantity" />
				*Balance Value: <input type="text" ng-model="vm.receipt_to_add.balance_value" /><br /><p></p>
				<input type="button" ng-click="vm.reset()" value="Reset" />
				<input type="submit" ng-click="vm.update(vm.receipt_to_add)" value="Save" />
			</form>

			<!-- Receipt Retrieval      ====================================== -->
			<hr>
				<h3>Receipt Created:</h3>
				<table border>
					<thead>
						<tr>
						<td>	BILL NUMBER	</td>
						<td>	BILL DATE	</td>
						<td>	B17 DEBIT	</td>
						<td>	DESCRIPTION	</td>
						<td>	INVOICE NO	</td>
						<td>	INVOICE DATE	</td>
						<td>	PROCUREMENT CERTIFICATE	</td>
						<td>	PROCUREMENT DATE	</td>
						<td>	UNIT WEIGHT	</td>
						<td>	UNIT QUANTITY	</td>
						<td>	VALUE	</td>
						<td>	DUTY	</td>
						<td>	TRANSPORT REGISTRATION	</td>
						<td>	RECEIPT TIMESTAMP	</td>
						<td>	BALANCE QUANTITY	</td>
						<td>	BALANCE VALUE	</td>
						</tr>
					
					</thead>
					<tbody>
					<tr>
					<td>	{{ vm.retrieved.receipt.bill_number }}	</td>
					<td>	{{  vm.retrieved.receipt.bill_date }}	</td>
					<td>	{{  vm.retrieved.receipt.b17_debit }}	</td>
					<td>	{{  vm.retrieved.receipt.description }}	</td>
					<td>	{{  vm.retrieved.receipt.invoice_no }}	</td>
					<td>	{{  vm.retrieved.receipt.invoice_date }}	</td>
					<td>	{{  vm.retrieved.receipt.procurement_certificate }}	</td>
					<td>	{{  vm.retrieved.receipt.procurement_date }}	</td>
					<td>	{{  vm.retrieved.receipt.unit_weight }}	</td>
					<td>	{{  vm.retrieved.receipt.unit_quantity }}	</td>
					<td>	{{  vm.retrieved.receipt.value }}	</td>
					<td>	{{  vm.retrieved.receipt.duty }}	</td>
					<td>	{{  vm.retrieved.receipt.transport_registration }}	</td>
					<td>	{{  vm.retrieved.receipt.receipt_timestamp }}	</td>
					<td>	{{  vm.retrieved.receipt.balance_quantity }}	</td>
					<td>	{{  vm.retrieved.receipt.balance_value }}	</td>
					</tr>
					</tbody>
				</table>

		</div>
		</div>

			<!-- Receipt List      ====================================== -->
			
			
			
			
			<!-- Include all compiled plugins (below), or include individual files            as needed -->
	</body>
</html>



