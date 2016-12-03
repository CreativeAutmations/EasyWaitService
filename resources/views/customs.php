<!DOCTYPE html>
<html>
	<head>
		<title>Customs Register</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<!-- Bootstrap -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">


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

<script type="text/javascript" src="https://code.jquery.com/ui/1.12.0-beta.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.1.135/jspdf.min.js"></script>
<script type="text/javascript" src="http://cdn.uriit.ru/jsPDF/libs/adler32cs.js/adler32cs.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>



<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.addimage.js"></script>
<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.standard_fonts_metrics.js"></script>
<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.split_text_to_size.js"></script>
<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.from_html.js"></script>
<script type="text/javascript" src="scripts/testpdf.js"></script>
		
		
		
		
		</head>
	<body ng-app="customsregister" ng-controller="CustomsRecordManager as vm" data-ng-init="vm.init()">
				<button id="cmd">generate PDF</button>
		<div id="navbar">
		</div>
		<!-- Grid System      ====================================== -->
		<div id="crap" class="container-fluid">
				<h1>TOBE REMOVED Import Record Keeper</h1>
			<div class="row">
				<h3></h3>
				<h3></h3>
			</div>

			<div ng-if="!vm.showReport">
				<h1>Import Record Keeper</h1>
				<!-- Show Profile Button  ====================================== -->
				<div ng-if="vm.isAuthenticated">
					<a ng-click="vm.toggleProfileView()" class="btn btn-primary">Profile</a>
					<a ng-click="vm.signOut()" class="btn btn-danger">Sign Out</a>
					<!-- Organization Management ====================================== -->
					<div ng-if="vm.viewProfile">
						<organization vm="vm"></organization>
						<hr>
					</div>
				</div>
				<p></p>
			
				<!-- Authentication      ====================================== -->
				<div ng-if="!vm.isAuthenticated">
					<authentication vm="vm"></authentication>
					<hr>
				</div>


				<div ng-if="vm.isAuthenticated && vm.isMemberOfAnOrganization && !vm.viewProfile">

					
					<div class="btn-group btn-group-justified">
						<a href="#" ng-click="vm.setCurrentView('AddEdit')" class="btn btn-primary">Add</a>
						<a href="#" ng-click="vm.setCurrentView('Search')" class="btn btn-primary">Search</a>
						<a href="#" ng-click="vm.setCurrentView('Audit')" class="btn btn-primary">Audit</a>
						<a ng-click="vm.togggleReportView()" class="btn btn-primary">Report</a>
					</div>
					<p></p>
					
					<!-- Receipt Creation      ====================================== -->
					<div ng-if="vm.isActiveView('AddEdit')">
						<h3>Add/Edit Receipt</h3>
						<receiptupdate vm="vm"></receiptupdate>
						<hr>
					</div>
					
					<!-- Receipt List      ====================================== -->
					<div ng-if="vm.isActiveView('Search')">
						<h3>Receipt Search</h3>
						<receiptsearch vm="vm"></receiptsearch>
						<!-- Audit Trail      ====================================== -->
						<hr>
					</div>

					<div ng-if="vm.isActiveView('Audit')">
						<h3>Audit Trail</h3>
						<br/><br/>
						<audittrail vm="vm"></audittrail>
						<hr>
					</div>

					<!-- Export For Report      ====================================== -->
				</div>
			</div>

			<div ng-if="vm.showReport">
				#<reportforcustoms vm="vm"></reportforcustoms>
				<h3>Audit Trail</h3>
				<br/><br/><br/><br/><br/>
				<hr>
				<table><tr ng-click="vm.togggleReportView()"><td><h4>Report generated on -- {{ vm.reportDate }}</h4></td></tr></table>
			</div>
		</div>	
	</body>
</html>



