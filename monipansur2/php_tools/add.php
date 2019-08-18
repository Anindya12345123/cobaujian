<?php
	include("php_tools/dbLink.php");
	$link=Connection();
	
	date_default_timezone_set("Asia/Jakarta");
	$waktu = date("Y-m-d H:i:s");
	if(isset($_GET["Vac"]) and isset($_GET["Iac"]) and isset($_GET["Vdc"]) and isset($_GET["Idc"]) and isset($_GET["Temp"]) and isset($_GET["User"])){
		if (empty($_GET["Vac"]) or empty($_GET["Iac"]) or empty($_GET["Vdc"]) or empty($_GET["Idc"]) or empty($_GET["Temp"]) or empty($_GET["User"]))	{
			echo "ERROR empty(?Vac=val&Iac=val&Vdc=val&Idc=val&Temp=val&User=val)";
		}
		else	{
			$Vac=$_GET["Vac"];
			$Iac=$_GET["Iac"];
			$Vdc=$_GET["Vdc"];
			$Idc=$_GET["Idc"];
			$Temp=$_GET["Temp"];
			$User=$_GET["User"];
			$comand = "INSERT INTO `Record` ";
			$slot = "(`waktu`, `V_ac`, `I_ac`, `V_dc`, `I_dc`, `Temp`, `User`) ";
			$val = "VALUES 	(\"".$waktu."\",".$Vac.",".$Iac.",".$Vdc.",".$Idc.",".$Temp.",".$User.");";
			$query = $comand . $slot . $val;
			echo "PostOK";
			printf("<br>%s",$query);
			mysqli_query($link,$query);
		}
	}
	else {
		echo "ERROR set(?Vac=&Iac=&Vdc=&Idc=&Temp=&User=)";
	}
?>
