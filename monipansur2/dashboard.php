<?php
	require_once("php_tools/auth.php"); 
	
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');
	header( "Last-Modified: " . gmdate( "D, j M Y H:i:s" ) . " GMT" );

	date_default_timezone_set("Asia/Jakarta");
	$date = new DateTime();
	$current_timestamp = $date->getTimestamp();
?>

<html>
	<head>
		<?php include("html/head.html"); ?>
		<link rel="stylesheet"  type="text/css" href="lib/dashboard.css">
		<link href="lib/calender.css" rel="stylesheet">
	</head>
	<body class="main_body" >
		<header class="titleContainer">
			<?php include("html/title.html"); ?>
		</header>
		<section>
		<div>
			<nav class="sidenav" id="side_nav">
							<?php include("./html/sidebar.html"); ?>
			</nav>
			<div class="main_content" id="page_content">
							<?php include("./html/dashboard.html"); ?>
			</div>
		</div>
		</section>
	</body>
</html>
<script>
	document.getElementById("username").innerHTML = <?php echo json_encode($_SESSION["username"]);?>;
	document.getElementById("p_nama").innerHTML = <?php echo json_encode($_SESSION['name']);?>;
	document.getElementById("p_email").innerHTML = <?php echo json_encode($_SESSION["email"]);?>;
	document.getElementById("p_contact").innerHTML = <?php echo json_encode($_SESSION["contact"]);?>;
	var userID = <?php echo json_encode($_SESSION["userID"]);?>;
	var img = "<img src=\"php_tools/getimage.php?userID=" + userID + "\" style=\"height:100%;\"/>";
	document.getElementById("profilePhoto").innerHTML = img;
	// document.getElementById("profilePhoto").innerHTML = "<img src=\""+<?php echo json_encode($_SESSION["foto"]);?> + "\" style=\"height:100%;\"/>";
	
	var sbElemen = document.getElementById("NB_dashboard");
	sbElemen.className += "active";
	sbElemen.removeAttribute('onclick');
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

	function reqData(){
		var dt = new Date();
		var dd = dt.getDate();
		var mm = dt.getMonth() + 1;
		var yy = dt.getFullYear();
		var dateStr = "";
		dateStr = yy + "-" + mm + "-" + dd + " 00:00:00";
		var url = "php_tools/getdata.php?ID=0&L=1";//&T=" + dateStr;
		var callback = {
			success: function(req) {
				var str_ = "";
				var lineStr = req.responseText.split("\n");
				var len = lineStr.length;
				if(len>0)	{
					var slotStr = lineStr[0].split(" | ");
					 if(slotStr.length>6)	{
						document.getElementById("v_dc_val").innerHTML = slotStr[1];
						document.getElementById("i_dc_val").innerHTML = slotStr[2];
						document.getElementById("v_ac_val").innerHTML = slotStr[3];
						document.getElementById("i_ac_val").innerHTML = slotStr[4];
						document.getElementById("temp_val").innerHTML = slotStr[5];
						document.getElementById("lux_val").innerHTML = slotStr[6];
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
	setInterval(function(){scriptClock();},100);
	
	function scriptClock()	{
		var d = new Date();
		var s = d.getSeconds();
		if(last_s!=s)	{
			last_s = s;
			displayCalendar();
			if(timer>0)	timer--;
			else {
				timer = 10;
				reqData();
			}
		}
	}
	
	// Get the modal
	var modal = document.getElementById('loginForm');

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
	
	scriptClock();
	displayCalendar();
</script> 
