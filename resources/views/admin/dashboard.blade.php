<!DOCTYPE html>
<html>
<head>
	<base href="/admin/">
	<meta charset="utf-8">

	<title>{{ env('APP_NAME') }} Admin</title>
	<meta name="description" content="">
	<meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="format-detection" content="telephone=no">
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
	<meta http-equiv="CACHE-CONTROL" content="NO-STORE">
		<!-- <link rel="shortcut icon" href="images/favicon.ico"> -->
		<!--<link rel="apple-touch-icon" href="/apple-touch-icon.png">-->
	<link rel="stylesheet" href="/app/css/app.css">
	<link rel="stylesheet" href="/app/css/datetime.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>

<body>

	<div ng-app="admin">

		<!-- WRAPPER -->
	  <div class="wrapper brand-txt-main-color maxwidth">

	      <div class="inner box-shadow">

	        <!-- SIDE NAV -->
	        <div class="side_nav relative">

				<div navigation></div>

	        </div>
	        <!-- END SIDE NAV -->

	        <!-- PAGE CONTENT -->
	        <div class="content brand-bg-light-color">

	          <div ng-view></div>

	        </div>
	        <!-- END PAGE CONTENT -->

	        <div class="clear"></div>
	      </div>

				<!-- FOOTER -->
	      <div class="footer">
	        <div class="half">
	          &nbsp;
	        </div>
	        <div class="half txt-right">
	          &copy <script>document.write(new Date().getFullYear())</script> Propaganda3
	        </div>
	      </div>
	      <!-- END FOOTER -->

	  </div>
	  <!-- WRAPPER -->

	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-route.min.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-local-storage/0.2.2/angular-local-storage.min.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-route.min.js" defer="defer"></script>
	<script src="https://maps.google.com/maps/api/js?sensor=false&libraries=places" defer="defer"></script>
	<script src="https://cdn.firebase.com/js/client/2.2.4/firebase.js" defer="defer"></script>
	<script src="https://cdn.firebase.com/libs/angularfire/1.2.0/angularfire.min.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.min.js"></script>
	<script src="/app/common.js" defer="defer"></script>
	<script src="/app/libs/aws/aws-sdk.min.js" defer="defer"></script>
	<script src="/app/libs/jquery/locationpicker.jquery.min.js" defer="defer"></script>
	<script src="/app/libs/dates/datetime.js" defer="defer"></script>
	<script src="/app/app.js" defer="defer"></script>
	<script src="/app/controllers/navigationController.js" defer="defer"></script>
	<script src="/app/controllers/dashboardController.js" defer="defer"></script>
	<script src="/app/controllers/messageController.js" defer="defer"></script>
	<script src="/app/controllers/messageDetailController.js" defer="defer"></script>
	<script src="/app/controllers/messagePushController.js" defer="defer"></script>
	<script src="/app/resources/imageResource.js" defer="defer"></script>
	<script src="/app/resources/itemResource.js" defer="defer"></script>

    <script src="/app/controllers/eventController.js" defer="defer"></script>
	<script src="/app/controllers/eventDetailController.js" defer="defer"></script>
    <script src="/app/controllers/helpController.js" defer="defer"></script>
	<script src="/app/controllers/helpDetailController.js" defer="defer"></script>
    <script src="/app/controllers/menuSlideController.js" defer="defer"></script>
	<script src="/app/controllers/menuSlideDetailController.js" defer="defer"></script>
	<script src="/app/controllers/sponsorController.js" defer="defer"></script>
	<script src="/app/controllers/sponsorDetailController.js" defer="defer"></script>
    <script src="/app/controllers/tierController.js" defer="defer"></script>
	<script src="/app/controllers/tierDetailController.js" defer="defer"></script>
	<script src="/app/controllers/vendorController.js" defer="defer"></script>
	<script src="/app/controllers/mapController.js" defer="defer"></script>
	<script src="/app/controllers/mapDetailController.js" defer="defer"></script>
	<script src="/app/controllers/vendorDetailController.js" defer="defer"></script>
	<script src="/app/controllers/vendorCategoryController.js" defer="defer"></script>
	<script src="/app/controllers/vendorCategoryDetailController.js" defer="defer"></script>
	<script src="/app/controllers/eventCategoryController.js" defer="defer"></script>
	<script src="/app/controllers/eventCategoryDetailController.js" defer="defer"></script>

</body>
</html>
