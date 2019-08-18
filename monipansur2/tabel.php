<?php
	require_once("php_tools/auth.php"); 
	
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');
	header( "Last-Modified: " . gmdate( "D, j M Y H:i:s" ) . " GMT" );

	date_default_timezone_set("Asia/Jakarta");
	$date = new DateTime();
	$current_timestamp = $date->getTimestamp();
	
	$nb_id = "NB_M_V_DC";
	if(isset($_GET["par"])){
		$nb_id = $_GET["par"];
	}
	$data_id = 1;
	if(isset($_GET["id"])){
		$data_id = $_GET["id"];
	}
?>
<html>
	<head>
		<?php include("html/head.html"); ?>
		<link rel="stylesheet"  type="text/css" href="lib/tabel.css">
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<script type="text/javascript">
				var tableToExcel = (function () {
						var uri = 'data:application/vnd.ms-excel;base64,'
								, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
								, base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
								, format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
						return function (table, name) {
								if (!table.nodeType) table = document.getElementById(table)
								var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
								var blob = new Blob([format(template, ctx)]);
								var blobURL = window.URL.createObjectURL(blob);

								if (ifIE()) {
										csvData = table.innerHTML;
										if (window.navigator.msSaveBlob) {
												var blob = new Blob([format(template, ctx)], {
														type: "text/html"
												});
												navigator.msSaveBlob(blob, '' + name + '.xls');
										}
								}
								else
								window.location.href = uri + base64(format(template, ctx))
						}
				})()

				function ifIE() {
						var isIE11 = navigator.userAgent.indexOf(".NET CLR") > -1;
						var isIE11orLess = isIE11 || navigator.appVersion.indexOf("MSIE") != -1;
						return isIE11orLess;
				}
		</script>
	</head>
	<body class="main_body">
		<header class="titleContainer">
			<?php include("html/title.html"); ?>
		</header>
		<section>
		<nav class="sidenav" id="side_nav">
						<?php include("./html/sidebar.html"); ?>
		</nav>
		<div class="main_content" id="page_content">
						<?php include("html/tabel.html");?>
		</div>
		</section>
		<?php //include("html/loginForm.html"); ?>
	</body>
</html>

<script>
	var nbID = <?php echo json_encode($nb_id);?>;
	var dataID = <?php echo json_encode($data_id);?>;
	var sbElemen = document.getElementById(nbID);
	sbElemen.className += "active";
	sbElemen.removeAttribute('onclick');
	document.getElementById("username").innerHTML = <?php echo json_encode($_SESSION["username"]);?>;
	var userID = <?php echo json_encode($_SESSION["userID"]);?>;
	if(userID!=1)	{
		document.getElementById("datePick").style.display = "none";
		document.getElementById("btnExport").style.display = "none";
	}
	
	var titleStrArray = ["DC Voltage","DC Current","","","Suhu","DC Panel Surya","Beban"];
	var tabelStrArray = ["Output Tegangan DC Pada Panel Surya","Input Arus DC Pada Inverter","","","","Suhu Lingkungan Sekitar Panel Surya","Input Tegangan AC Pada Beban Lampu"];
	var titleIdx = dataID - 1;
	document.getElementById("content_title").innerHTML += titleStrArray[titleIdx];
	document.getElementById("tabel_title").innerHTML += tabelStrArray[titleIdx];
	
	/* makeXMLHttpRequest function
	 * required arguments: url, callback
	 * optional arguments: method, postData, and dataType
	 */
	function makeXMLHttpRequest( url, callback, method, postData, dataType ) {
		var req = new XMLHttpRequest();										// create request object
		try	{req=new XMLHttpRequest();}										// Firefox, Opera 8.0+, Safari
		catch (e)	{
			try	{req=new ActiveXObject("Msxml2.XMLHTTP");}	// Internet Explorer
			catch (e)	{
				try	{req=new ActiveXObject("Microsoft.XMLHTTP");}
				catch (e)	{
					alert("Your browser does not support AJAX!");
					return false;
				}
			}
		}
		// assign defaults to optional arguments
		method = method || 'GET';
		postData = postData || null;
		dataType = dataType || 'text/plain';
		
		req.open( method, url );													// pass method and url to open method
		req.setRequestHeader('Content-Type', dataType);		// set Content-Type header 
		
		// handle readystatechange event
		req.onreadystatechange = function() {							// check readyState property
			if ( req.readyState === 4 ) { 									// signifies DONE
				if ( req.status === 200 ) {										// success
					callback.success(req); 
				} else { 																			// handle request failure
					callback.failure(req); 
				}
			}
		}
		req.send( postData ); // send request
		return req; // return request object
	}
	
	var last_s = 0;
	var timer = 0;
	setInterval(function(){scriptClock();},100);
	
	var dataCounter = 0;
	var totalData = 0;
	var totalPage = 0;
	var pageIdx = 0;
	
	var limiter = 0;
	var interval = 86400;
	var tabelhead = "<th>No</th><th>Waktu</th>";
	
	if((dataID==1) || (dataID==6))		tabelhead += "<th>Tegangan DC (V)</th>";
	if((dataID==2) || (dataID==6))		tabelhead += "<th>Arus DC (A)</th>";
	if((dataID==3) || (dataID==7))		tabelhead += "<th>Tegangan AC (V)</th>";
	if((dataID==4) || (dataID==7))		tabelhead += "<th>Arus AC (A)</th>";
	if(dataID==5)											tabelhead += "<th>Suhu (C)</th>";
	if(dataID==6)											tabelhead += "<th>Lux</th>";
	document.getElementById("tabel_head").innerHTML = tabelhead;
	
	var pickedDate = new Date();
	var pickedDay = pickedDate.getDate();
	var pickedMonth = pickedDate.getMonth() + 1;
	var pickedYear = pickedDate.getFullYear();
	var pickedDateStr = pickedYear + "-" + pickedMonth + "-" + pickedDay;
	document.getElementById("datePick").value = pickedDate;
	
	function reqData(){
		var dd = pickedDate.getDate();
		var mm = pickedDate.getMonth() + 1;
		var yy = pickedDate.getFullYear();
		var dateStr = "";
		dateStr = yy + "-" + mm + "-" + dd + " 00:00:00";
		var url = "php_tools/getdata.php?L=" + limiter + "&I=" + interval + "&ID=" + dataID + "&T=" + dateStr;
		
		var callback = {
			success: function(req) {
				var str_ = "";
				var lineStr = req.responseText.split("\n");
				var len = lineStr.length;
				totalData = len;
				totalPage = totalData / 25;
				var sIdx = pageIdx * 25;
				var eIdx = sIdx + 25;
				if(len>0)	{
					var i;
					for(i=sIdx; ((i<len)&(i<eIdx)); i++)	{
						var slotStr = lineStr[i].split(" | ");
						var s = "";
						if(slotStr.length>1)	{
							str_ += ("<tr><td>" + (i+1) + "</td>");
							for(s of slotStr)	str_ += ("<td>" + s + "</td>");
							str_ += "</tr>";
						}
					}
					document.getElementById("tabel_data").innerHTML = str_;
					document.getElementById("dataPage").innerHTML = (pageIdx+1) + " ("+ totalPage.toFixed(0) + ")";
				}
			},
			failure: function(req) {
				alert('Error encountered');
			}
    }
		makeXMLHttpRequest(url, callback);
	}

	function pickDate(formfield) {
		var input = formfield.value;
		pickedDate = new Date(input);
		timer = 0;
	}
	
	function addPage(add){
		pageIdx += add;
		if(pageIdx<0)	pageIdx = 0;
		else if(pageIdx>=totalPage)	pageIdx = totalPage - 1;
		timer = 0;
	}
	
	function scriptClock()	{
		var d = new Date();
		var s = d.getSeconds();
		if(last_s!=s)	{
			last_s = s;
			if(timer>0)	timer--;
			else {
				timer = 10;
				reqData();
			}
		}
	}
	
	// Get the modal
//	var modal = document.getElementById('loginForm');

	// When the user clicks anywhere outside of the modal, close it
	// window.onclick = function(event) {
		// if (event.target == modal) {
			// modal.style.display = "none";
		// }
	// }
	
	scriptClock();
	
</script>
