<?php
	include("dbLink.php");
	$link=Connection();
	$cek = TRUE;
	if(isset($_GET['userID'])){
		if (empty($_GET["userID"]))		$cek = FALSE;
		if($cek == TRUE)	{
			$queryCmd = "SELECT `foto` FROM `User_Info` WHERE `userID` = ".$_GET["userID"];
			$result=mysqli_query($link,$queryCmd);
			if($result != FALSE)	{
                                if($row = mysqli_fetch_array($result)){
                                        header("Content-type: image/png");
                                        print $row['foto'];
                                }
                                else $cek = FALSE;
			}
			else $cek = FALSE;
			mysqli_free_result($result);
		}
	}
	if($cek == FALSE)	{
                echo "<img src=\"./img/default_user.png\"/>";
        }
	mysqli_close($link);
?>
