<?php
	$dbServer = "***";
	$dbUsername = "***";
	$dbPassword = "***";
	$dbName = "***";
	$connLaikiux = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
	
	if($connLaikiux == false)
	{
		//Jeigu prisijungimas blogas, stabdo kodą.
		die("Prisijungimas laikiux duomenu bazes buvo blogas<br>".mysqli_connect_error());
	}

	
	
?>