<?php
	$dbServer = "*****";
	$dbUsername = "*****";
	$dbPassword = "*****";
	$dbName = "*****";
	$connJailbreak = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
	
	if($connJailbreak == false)
	{
		//Jeigu prisijungimas blogas, stabdo kodą.
		die("Prisijungimas jailbreak duomenu bazes buvo blogas<br>".mysqli_connect_error());
	}
	$dbServer = "*****";
	$dbUsername = "*****";
	$dbPassword = "*****";
	$dbName = "*****";
	$connForfun = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
	
	if($connForfun == false)
	{
		//Jeigu prisijungimas blogas, stabdo kodą.
		die("Prisijungimas forfun duomenu bazes buvo blogas<br>".mysqli_connect_error());
	}
	$dbServer = "*****";
	$dbUsername = "*****";
	$dbPassword = "*****";
	$dbName = "*****";
	$connSurf = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
	
	if($connSurf == false)
	{
		//Jeigu prisijungimas blogas, stabdo kodą.
		die("Prisijungimas surf duomenu bazes buvo blogas<br>".mysqli_connect_error());
	}
	

	$BendrasServeriuIP = "91.211.247.93";
	$JailbreakPort = 27016;
	$ForfunPort = 27015;
	$SurfPort = 27017;

	$JailbreakRCON = "******";
	$ForfunRCON = "******";
	$SurfRCON = "******";
?>