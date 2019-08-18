<?php
	include("dbLink.php");
	$link=Connection();
	
	$cek = TRUE;
	$ID = '0';
	$waktu = "NOW() ";
	$interval = '0';
	$answer = "";
	$limit = '0';
	$selector = "`waktu`";
	$tabel = "`Record`";
	$filter = " `waktu` ";
	$orderBy = "`waktu`";
	$order = " DESC";
	$queryLimiter = "";
	$debug = "off";
	
	if(isset($_GET['ID'])&&(!empty($_GET['ID'])))	$ID = $_GET['ID'];
	if(isset($_GET['L'])&&(!empty($_GET['L'])))		$limit = $_GET['L'];
	if(isset($_GET['I'])&&(!empty($_GET['I'])))		$interval = $_GET['I'];
	if(isset($_GET['T'])&&(!empty($_GET['T'])))		$waktu = "'".$_GET['T']."'";
	if(isset($_GET['debuging'])&&(!empty($_GET['debuging'])))	$debug = $_GET['debuging'];
	
	if($ID=='0')	$selector .= ", `V_dc`, `I_dc`, `V_ac`, `I_ac`, `Temp`, `lux`";
	else {
		if(($ID==1) or ($ID==6))	$selector .= ", `V_dc`";
		if(($ID==2) or ($ID==6))	$selector .= ", `I_dc`";
		if(($ID==3) or ($ID==7))	$selector .= ", `V_ac`";
		if(($ID==4) or ($ID==7))	$selector .= ", `I_ac`";
		if($ID==5)								$selector .= ", `Temp`";
		if($ID==6)								$selector .= ", `lux`";
	}
	
	if($interval=='0')	$filter .= ("< ".$waktu);
	else								$filter .= ("BETWEEN ".$waktu." AND  DATE_ADD(".$waktu." , INTERVAL ".$interval." SECOND)");
	
	if($limit!='0')	$queryLimiter = " LIMIT ".$limit;
	
	$queryCmd = "SELECT ".$selector." FROM ".$tabel." WHERE ".$filter." ORDER BY ".$orderBy.$order.$queryLimiter;
	
	$result = mysqli_query($link,$queryCmd);
	if($result)	{
//		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
		while($row = mysqli_fetch_array($result,MYSQLI_NUM)) {
			foreach ($row as $val)	{
				$answer .= $val;
				$answer .= " | ";
			}
			$answer .= "\n";
			if($debug=="on")	$answer .= "<br>";
		}
	}
	mysqli_close($link);
	
	if($debug=="on")	{
		echo "ID           = ";	echo $ID;							echo "<br>";
		echo "waktu        = ";	echo $waktu;					echo "<br>";
		echo "interval     = ";	echo $interval;				echo "<br>";
		echo "limit        = ";	echo $limit;					echo "<br>";
		echo "selector     = ";	echo $selector;				echo "<br>";
		echo "filter       = ";	echo $filter;					echo "<br>";
		echo "queryLimiter = ";	echo $queryLimiter;		echo "<br>";
		echo "queryCmd     = ";	echo $queryCmd;				echo "<br>";
		echo "<br><br>answer<br><br>";
	}
	echo $answer;
	
	if($result)	{
		mysqli_free_result($result);
		// mysqli_close($link);
	}
?>
