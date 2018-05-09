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

		<!-- <link rel="shortcut icon" href="images/favicon.ico"> -->
		<!--<link rel="apple-touch-icon" href="/apple-touch-icon.png">-->
    <link rel="stylesheet" href="/app/css/app.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>

<body>

	<div ng-app="admin">

    <div ng-view></div>

	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-route.min.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-local-storage/0.2.2/angular-local-storage.min.js" defer="defer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular-route.min.js" defer="defer"></script>
	<script src="/app/app.login.js" defer="defer"></script>
	<script src="/app/common.js" defer="defer"></script>
  	<script src="/app/controllers/loginController.js" defer="defer"></script>

</body>
</html>
