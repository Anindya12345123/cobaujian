<?php
  require_once("auth.php"); 
	include("dbLink.php");
	$link=Connection();

	$username = $_POST['username'];
	$pass     = $_POST['password'];
	
	// "SELECT `userID`, `username`, `name`, `email`, `contact`, `foto` FROM `user_info` WHERE `username` = 'maulana.kharisma' AND `password` = 'maulana88'"
	
	$tabel = "`User_Info`";
	$selector = "`userID`, `username`, `name`, `email`, `contact`, `foto`";
	$filter = "`username` = '".$username."' AND `password` = '".$pass."'";
	
	$queryCmd = "SELECT ".$selector." FROM ".$tabel." WHERE ".$filter;
	
	$login = mysqli_query($link,$queryCmd);
	if($login)	{
		if($row = mysqli_fetch_array($login)) {
			session_start();
			$_SESSION['username'] = $row['username'];
			$_SESSION['userID'] = $row['userID'];
			$_SESSION['name'] = $row['name'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['contact'] = $row['contact'];
			$_SESSION['foto'] = $row['foto'];
			header('location:../dashboard.php');
		}
		else	header('location:../loginForm.php');
	}
?>
