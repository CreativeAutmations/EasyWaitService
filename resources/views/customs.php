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
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<td>	Bill Number	</td>
							<td>	Bill Date	</td>
							<td>	Description	</td>
							<td>	Unit Quantity	</td>
							<td>	Unit Weight	</td>
							<td>	Value	</td>
							<td>	Duty	</td>
							<td>	Balance Quantity	</td>
							<td>	Balance Value	</td>
							<td>	B17 Debit	</td>
							<td>	Invoice Date	</td>
							<td>	Invoice No	</td>
							<td>	Procurement Certificate	</td>
							<td>	Procurement Date	</td>
							<td>	Transport Registration	</td>
							<td>	Receipt Timestamp	</td>
						</tr>
					
					</thead>
					<tbody>
					<tr>
					<td>	{{ vm.retrieved.receipt.bill_number }}	</td>
					<td>	{{  vm.retrieved.receipt.bill_date }}	</td>
					<td>	{{  vm.retrieved.receipt.description }}	</td>
					<td>	{{  vm.retrieved.receipt.unit_quantity }}	</td>
					<td>	{{  vm.retrieved.receipt.unit_weight }}	</td>
					<td>	{{  vm.retrieved.receipt.value }}	</td>
					<td>	{{  vm.retrieved.receipt.duty }}	</td>
					<td>	{{  vm.retrieved.receipt.balance_quantity }}	</td>
					<td>	{{  vm.retrieved.receipt.balance_value }}	</td>
					<td>	{{  vm.retrieved.receipt.b17_debit }}	</td>
					<td>	{{  vm.retrieved.receipt.invoice_date }}	</td>
					<td>	{{  vm.retrieved.receipt.invoice_no }}	</td>
					<td>	{{  vm.retrieved.receipt.procurement_certificate }}	</td>
					<td>	{{  vm.retrieved.receipt.procurement_date }}	</td>
					<td>	{{  vm.retrieved.receipt.transport_registration }}	</td>
					<td>	{{  vm.retrieved.receipt.receipt_timestamp }}	</td>
					</tr>
					</tbody>
				</table>
				</div>

		</div>
		</div>

			<!-- Receipt List      ====================================== -->
			<hr>
			<h3>Receipt Search</h3>
			<div class="row">
				<div class="col-md-12">
					<form novalidate class="simple-form">
						*Bill Date: <input type="date" ng-model="vm.receipt_to_search.bill_date"
       placeholder="yyyy-MM-dd" required /> 
						<input type="submit" ng-click="vm.getReceiptsByDate(vm.receipt_to_search.bill_date)" value="Search" />
						   
	   
					</form>
				</div>
			</div>
			<br/><br/>
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<td></td>
					<td ng-repeat="column in vm.searchByDateResultsHeaders">{{column}}</td>
				</tr>
				</thead>
			
			
			
				<tbody>
				<tr ng-repeat="row in vm.searchByDateResults">
					<td><button type="button" class="btn btn-default" ng-click="vm.getAuditTrail(row.bill_number)">Audit</button></td>
					<td>{{ row.bill_number }} </td>
					<td>{{ row.bill_date }} </td>
					<td>{{ row.description }} </td>
					<td>{{ row.unit_quantity }} </td>
					<td>{{ row.unit_weight }} </td>
					<td>{{ row.value }} </td>
					<td>{{ row.duty }} </td>
					<td>{{ row.balance_quantity }} </td>
					<td>{{ row.balance_value }} </td>
					<td>{{ row.b17_debit }} </td>
					<td>{{ row.invoice_date }} </td>
					<td>{{ row.invoice_no }} </td>
					<td>{{ row.procurement_certificate }} </td>
					<td>{{ row.procurement_date }} </td>
					<td>{{ row.transport_registration }} </td>
					<td>{{ row.receipt_timestamp }} </td>
				</tr>
				</tbody>
			</table>
			</div>
			
			<!-- Audit Trail      ====================================== -->
			<hr>
			<h3>Audit Trail</h3>
			<br/><br/>
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<td ng-repeat="column in vm.auditTrailHeaders">{{column}}</td>
				</tr>
				</thead>
			
				<tbody>
				<tr ng-repeat="row in vm.auditTrail">
					<td>{{ row.email }} </td>
					<td>{{ row.action }} </td>
					<td>{{ row.category }} </td>
					<td>{{ row.updated_at }} </td>
					<td>{{ row.bill_number }} </td>
					<td>{{ row.change_log.bill_date }} </td>
					<td>{{ row.change_log.description }} </td>
					<td>{{ row.change_log.unit_quantity }} </td>
					<td>{{ row.change_log.unit_weight }} </td>
					<td>{{ row.change_log.value }} </td>
					<td>{{ row.change_log.duty }} </td>
					<td>{{ row.change_log.balance_quantity }} </td>
					<td>{{ row.change_log.balance_value }} </td>
					<td>{{ row.change_log.b17_debit }} </td>
					<td>{{ row.change_log.invoice_date }} </td>
					<td>{{ row.change_log.invoice_no }} </td>
					<td>{{ row.change_log.procurement_certificate }} </td>
					<td>{{ row.change_log.procurement_date }} </td>
					<td>{{ row.change_log.transport_registration }} </td>
					<td>{{ row.change_log.receipt_timestamp }} </td>
				</tr>
				</tbody>
			</table>
			</div>
			
			
			<!-- Include all compiled plugins (below), or include individual files            as needed -->
			<br/><br/><br/><br/><br/>
			<p></p>
			   <div class="footer">
      <div class="container">
      <p class="text-muted">
			Powered By <a href="mailto:allied.service.14@gmail.com?Subject=Contact Us" target="_top">allied.service.14@gmail.com</a>
		</p>
      </div>
    </div>
 
			
	</body>
</html>



