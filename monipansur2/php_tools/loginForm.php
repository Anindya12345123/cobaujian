<html>
	<head>
		<?php include("html/head.html"); ?>
		<link rel="stylesheet"  type="text/css" href="./lib/loginForm.css">
	</head>
	<body class="main_body" >
		<header class="titleContainer">
			<div>
				<div class="title_text"><strong>Admin Web Monitoring</strong></div>
			</div>
		</header>
		<section>
			<div id="loginForm" class="loginContent">
				<div class="loginFormBox animate">
					<form action="./php_tools/loginprocessor.php" method="post">
						<div class="loginImgIontainer">
							<img src="./img/default_user.png" alt="Avatar" class="avatar">
						</div>

						<div class="loginDataInput">
							<label for="username"><b>Username</b></label>
							<input type="text" placeholder="Enter Username" name="username" required>

							<label for="password"><b>Password</b></label>
							<input type="password" placeholder="Enter Password" name="password" required>

							<button class="btnLogin" type="submit">Login</button>
							<!-- <label> -->
								<!-- <input type="checkbox" checked="checked" name="remember"> Remember me -->
							<!-- </label> -->
							<!-- <div> -->
								<!-- <button id="btnCancel" class="btnLogin" type="button" onclick="document.getElementById('loginForm').style.display='none'">Cancel</button> -->
								<!-- <span class="psw">Forgot <a href="#">password?</a></span> -->
							<!-- </div> -->
						</div>
					</form>
				</div>
			</div>
		</section>
	</body>
</html>

