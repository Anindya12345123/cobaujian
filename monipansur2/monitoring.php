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
		<link rel="stylesheet"  type="text/css" href="./lib/monitoring.css">
    <link href="lib/chartStyles.css" rel="stylesheet" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
	</head>
	<body class="main_body">
		<header class="titleContainer">
			<?php include("html/title.html"); ?>
		</header>
		<section>
		<nav class="sidenav" id="side_nav">
						<?php include("html/sidebar.html"); ?>
		</nav>
		<div class="main_content" id="page_content">
						<?php include("html/monitoring.html");?>
		</div>
		</section>
		 <?php //include("html/loginForm.html"); ?>
		<div style="clear:both;" id="test_content"></div>
	</body>
</html>

<script>
	var nbID = <?php echo json_encode($nb_id);?>;
	var dataID = <?php echo json_encode($data_id);?>;
	var sbElemen = document.getElementById(nbID);
	document.getElementById("username").innerHTML = <?php echo json_encode($_SESSION["username"]);?>;
	var userID = <?php echo json_encode($_SESSION["userID"]);?>;
	if(userID!=1)	{
		document.getElementById("datePick").style.display = "none";
	}

	var maxChartVal = [25,15,240,3,60,450];
	var chartNameStrArray = ["DC Voltage","DC Current","AC Voltage","AC Current","Suhu","Beban"];
	var chartTitleStrArray = ["Output Tegangan DC Pada Panel Surya","Input Arus DC Pada Inverter","Tegangan AC Pada Beban Lampu","Arus AC Pada Beban Lampu","Suhu Lingkungan Sekitar Panel Surya",""];
	var titleIdx = dataID - 1;
	
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
	
	var dataCounter = 0;
	var totalData = 0;
	var totalPage = 0;
	var pageIdx = 0;
	
	var limiter = 0;
	var interval = 86400;
	var tabelhead = "<th>No</th><th>Waktu</th>";
	
	var dates = [];
	var dToH = 24;
	var hToM = 60;
	var mToS = 60;
	var sToMs = 1000;
	
	var ts2 = new Date();
	
	var zommed = 0;
	var clicked = 0;
	
	function clearDataSeries(start, end){
		var len = dates.length;
		if((end>len)|(end==0))	end = len - 1;
		if(start>end) return;
		len = end - start + 1;
		dates.splice(start, len);
	}
	function addDataSeries(xData, yData){
		var innerArr = [xData, yData];
		dates.push(innerArr);
	}
	
	// init chart
	var	chartName = chartNameStrArray[titleIdx];
	var	chartTitle = chartTitleStrArray[titleIdx];
	var	yMin = 0;
	var	yMax = maxChartVal[titleIdx];
	var	yTitle = chartNameStrArray[titleIdx];
	
	for(var i=0; i<dToH; i++){
		var tMs = ts2.getTime() - (hToM*mToS*sToMs*i);
		addDataSeries(tMs, 0);
	}
	var options = {
		chart: {
			type: 'line',
			stacked: false,
			height: 350,
			zoom: {
				type: 'x',
				enabled: true
			},
			toolbar: {
				autoSelected: 'zoom'
			},
			events: {
				zoomed: function(chartContext, { xaxis, yaxis }) {
				 // document.getElementById("test_content").innerHTML = "xMin : " + xaxis.min + "<br>";
				 // document.getElementById("test_content").innerHTML += "xMax : " + xaxis.max + "<br>";
				},
				click: function(event, chartContext, config) {
				 // document.getElementById("test_content").innerHTML = "A : " + Object.keys(chartContext); 
				 // document.getElementById("test_content").innerHTML += "<br>B : " + Object.keys(chartContext.toolbar); 
				 // document.getElementById("test_content").innerHTML += "<br>C : " + Object.keys(chartContext.w); 
				}
			}
		},
		dataLabels: {
			enabled: false
		},
		series: [{
			name: chartName,
			data: dates
		}],
		markers: {
			size: 0,
		},
		title: {
			text: chartTitle,
			align: 'left'
		},
		yaxis: {
			min: yMin,
			max: yMax,
			title: {
				text: yTitle
			},
		},
		xaxis: {
			type: 'datetime',
			rotate: -90,
      tooltip: {
          enabled: false
        },
			},
		tooltip: {
			shared: false,
      x: {
          format: 'HH:mm:ss dd MMM yyyy'
      }
		}
	}
	var	chart = new ApexCharts(document.querySelector("#chartContainer"), options);
	
	function chartUpdate(){
		chart.updateSeries([{data: dates}]);
	}
	
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
				if(len>0)	{
					var i;
					if(lineStr[0].length>1)	{
						clearDataSeries(0,0);
						for(i=0; (i<len); i++)	{
							if(lineStr[i].length>1)	{
								var slotStr = lineStr[i].split(" | ");
								var d = new Date(slotStr[0]);
								var dMs = d.getTime();
								var val = Number(slotStr[1]);
								str_ += ((i+1) + " , ");
								str_ += (dMs + " | ");
								str_ += (val + " | ");
								// for(s of slotStr)	str_ += (s + " | ");
								str_ += "<br>";
								addDataSeries(dMs, val);
							}
						}
						chartUpdate();
						// document.getElementById("test_content").innerHTML = str_;
					}
				}
			},
			failure: function(req) {
				alert('Error encountered');
			}
    }
		makeXMLHttpRequest(url, callback);
	}
	

	var last_s = 0;
	var timer = 0;
	
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
	
	function pickDate(formfield) {
		var input = formfield.value;
		pickedDate = new Date(input);
		timer = 0;
	}
	
	setInterval(function(){scriptClock();},100);
	
//	Get the modal
	// var modal = document.getElementById('loginForm');

//	When the user clicks anywhere outside of the modal, close it
	// window.onclick = function(event) {
		// if (event.target == modal) {
			// modal.style.display = "none";
		// }
	// }
	
	
	document.getElementById("content_title").innerHTML += chartNameStrArray[titleIdx];
	document.getElementById("chart_title").innerHTML += chartTitleStrArray[titleIdx];
	sbElemen.className += "active";
	sbElemen.removeAttribute('onclick');
	
	chart.render();
	
	scriptClock();
	
</script>
