<?php
	$pg = 0;
	$cek = TRUE;
	$content = "";
	$imgArray = array(
		"dashboard", 
		"monitoring", "monitoring", "monitoring", "monitoring", "monitoring", 
		"tabel", "tabel", "tabel", "tabel"
		);
	$textArray = array(
		"Dashboard", 
		"Monitoring DC Current", 
		"Monitoring DC Voltage", 
		"Monitoring AC Voltage", 
		"Monitoring AC Current", 
		"Monitoring Suhu", 
		"Tabel View DC Current", 
		"Tabel View DC Voltage", 
		"Tabel View Beban", 
		"Tabel View Suhu"
		);
	if(isset($_GET['pg'])){
		$pg = $_GET['pg'];
		for($i=0; $i<10; $i++)	{
			$content .= "<button id=\"NB";
			$content .= $i;
			$content .= "\" onclick=\"reqNavBar(";
			$content .= $i;
			$content .= ")\"><img src=\"img/";
			$content .= $imgArray[$i];
			$content .= ".png\"><span>";
			$content .= $textArray[$i];
			$content .= "</span></button>\n";
		}
	}
	echo $content;
?>