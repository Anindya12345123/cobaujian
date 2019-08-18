<?php
function Connection(){
	$server="host_rds";
	$user="root"; 		//Nama user MySQL
	$auth_pass="cobaujian";		//Password MySQL
	$db="cobaujian";		//NamaDB nya
	
	$connection = mysqli_connect($server,$user,$auth_pass,$db);
	return $connection;
}
?>