<?php
session_start();
session_destroy();
header('location:loginForm.php');
?>
<html>
<head>

		<?php include("html/head.html"); ?>

		<link rel="stylesheet"  type="text/css" href="./lib/loginForm.css">

	</head>
</html>