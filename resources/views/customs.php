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
			<authentication vm="vm"></authentication>
			<!-- Receipt Creation      ====================================== -->
			<hr>
			<receiptupdate vm="vm"></receiptupdate>


			<!-- Receipt List      ====================================== -->
			<hr>
			<h3>Receipt Search</h3>
			<receiptsearch vm="vm"></receiptsearch>

			<!-- Export For Report      ====================================== -->

			<reportforcustoms vm="vm"></reportforcustoms>

			
			<!-- Audit Trail      ====================================== -->
			<hr>
			<h3>Audit Trail</h3>
			<br/><br/>
			<audittrail vm="vm"></audittrail>
			
			
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



