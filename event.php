<?php
require 'steamauth/steamauth.php';
require 'includes/mysql_login.php';
require 'includes/config.php';
//require 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Laikiux daily quiz</title>
<link rel="icon" href="laikiuxfavicon.png" type="image/png" sizes="16x16">
<link href="/stylesheet-event.css" type="text/css" rel="stylesheet" />
</head>

<body id="laikiuxEvent">


<?php

	//Admin prisijungimas per Steam
	if(!isset($_SESSION['steamid']))
	{
		echo "Konkursų organizatoriaus prisijungimas<br>";
		loginbutton("rectangle"); //login button
		echo "<br>";
	}  
	else
	{
		include ('steamauth/userInfo.php'); //To access the $steamprofile array
		//Protected content
		logoutbutton(); //Logout Button
		echo "<br>";
	}
?>



<?php


	/*Reikalingi pavadinimai databazeje
	LaikiuxAdmins
	id, ip, nickname, level, steamid
	
	LaikiuxHints
	id, hinttext
	
	LaikiuxSubmission
	id, question, answer, prize, HasAnyoneWon, photolink, organizer
	
	LaikiuxWinners
	id, Nickname, organizer, winnerIp*/

	
	echo "Sveiki atvykę į Laikiux daily quiz<br>";
	echo "<a href='".$WebDomain."/salygos.txt'>Konkurso taisyklės bei bendra informacija</a><br>";

	//Nuotraukos, klausimo bei organizatoriaus įkėlimas į svetainę iš duombazės..
	$sqlReadDataBefore ="select * from LaikiuxSubmission";
	$CurrentDataStatus = mysqli_query($conn, $sqlReadDataBefore);
	// "Globalūs" kintamieji apie klausimą
	$EventQuestion = null;
	$EventAnswer = null;
	$EventPrize = null;
	$EventStatus = null; // 0 - dar niekas nelaimėjo, 1 - jau laimėta.
	$EventPhotoUrl = null;
	$EventOrganizer = null;     

	$WhichAdminLevel = 0;
	$AdminNickname = null;
	$sqlCheckIfAdmin = "select * from LaikiuxAdmins";
	$laikiuxAdmins = mysqli_query($conn, $sqlCheckIfAdmin);
	
	if(mysqli_num_rows($laikiuxAdmins) > 0)
	{
		while($row = mysqli_fetch_assoc($laikiuxAdmins))
		{
			if($row['ip'] == $_SERVER['REMOTE_ADDR'])
			{
				$WhichAdminLevel = $row['level'];
				$AdminNickname = $row['nickname'];
			}
			if(isset($steamprofile['steamid']))
			{
				if($row['steamid'] == $steamprofile['steamid'])
				{
					$WhichAdminLevel = $row['level'];
					$AdminNickname = $row['nickname'];
				}
			}
		}
	}
	
	if(mysqli_num_rows($CurrentDataStatus) == 1)
	{
		//Privalo būti 1 row bei jo ID turėtu būti 1!!!!!!
		while($row = mysqli_fetch_assoc($CurrentDataStatus))
		{
			$EventQuestion = $row['question'];
			$EventAnswer = $row['answer'];
			$EventPrize = $row['prize'];
			$EventStatus = $row['HasAnyoneWon'];
			$EventPhotoUrl = $row['photolink'];
			$EventOrganizer = $row['organizer'];
			
		}
	}
	else
	{
		echo "ERROR. Nepavyko įkelti nuotraukos, klausimo bei organizatoriaus problema, kad ne 1 row arba jo id ne 1 LaikiuxSubmission db";
	}
	
	echo '<img src="'.$EventPhotoUrl.'" alt="Evento nuotrauka"><br>';
	echo "Dabartinio/paskutinio konkurso organizatorius: ".$EventOrganizer."<br>";
	echo "<font color='red'>Klausimas: ".$EventQuestion."</font><br>";
	
	//Egzistuoja kvailas bugas kuris pjaunasi su SQL komandomis, jeigu submissionuose naudoja apostrofą, gali viskas susipjauti.
	//Todėl šita kodo dalis, tai apsaugo.
	//UPDATE. Kaip rašiau kodą, nežinojau apie mysqli espace string dalyką, todel jo kaip ir nebereik.
	/*if(isset($_COOKIE["submissionNickname"]))
	{
		$temporaryCookie = $_COOKIE["submissionNickname"];
		for($i = 0; $i < strlen($temporaryCookie); $i++)
		{
			if($temporaryCookie[$i] == "'")
			{
				setcookie("submissionNickname", null);
				break;
			}
		}
	}*/
	
	//Tikrina ar jau kažkas laimėjo, jeigu ne atkuria spėjimo "mechanizmą", jeigu jau laimėjo, jo nesukuria bei praneša apie tai.
	if($EventStatus == 0)
	{
		//Submission pateikimo formą.
		echo "Prizas jeigu atspėsite: ".$EventPrize."<br><br>";
		echo 'Įveskite atsakymą:<br>';
		echo '<form method="POST">';
		//Apsaugo nuo bereikalingų spaminamų errorų error liste. Jeigu cookie nėra nustatytas, tai spamins errorus ir vistiek priskirs null.
		if(isset($_COOKIE["submissionNickname"]))
			echo '<input name="nickname" placeholder="Slapyvardis" value='.$_COOKIE["submissionNickname"].'></input><br>';
		else
			echo '<input name="nickname" placeholder="Slapyvardis"></input><br>';
		echo '<input name="submission" placeholder="Atsakymas"></input><br>';
		echo '<button id="submitbutton" name="submitbutton">Pateikti</button>';
		echo '</form>';
		//Išsaugo tinkamą atsakymą, kad poto galima būti patikrinti ar jis teisingas. Ir išsaugo prizą.
		//$EventAnswer = strtolower($EventAnswer); // Atmintyje sumažina žodį į mažasias, kad vėliau galėtu įvykdyti sklandus patikrinimas.
			
	}
	else
	{
		echo "Jau kažkas atspėjo, prašome sugrįžti vėliau. Paskutinį laimėtoją galima patikrinti svetainės apačioje<br>";
		echo "Atsakymas į praeitą klausimą buvo: <font color='red'>".$EventAnswer."</font><br>";
	}
	
	
	//Tikrina ar įvestas nick bei atsakymas
	if(isset($_POST['submitbutton']))
	{
		//Išsaugo nicką, kad poto nebereikėto per naujo rašinėti.
		setcookie("submissionNickname", $_POST['nickname']);
		
		//Praneša jeigu blogai įvestas nick arba submission
		$shouldWeCheckIfItsCorrectAnswer = true;
		if($_POST['nickname'] == null)
		{
			$shouldWeCheckIfItsCorrectAnswer = false;
			echo "<font color='red'>Neįrašėte savo slapyvardžio!</font><br>";
		}
		if($_POST['submission'] == null)
		{
			$shouldWeCheckIfItsCorrectAnswer = false;
			echo "<font color='red'>Neįrašėte savo spėjamo atsakymo!</font><br>";
		}
		
		//Vyksta pats tikrinimas iš databazės
		if($shouldWeCheckIfItsCorrectAnswer)
		{
			$nickname = $_POST['nickname'];
			$submission = $_POST['submission']; 
			//Tikrinimas vėl iš duombazės ar nėra laimėtojo jau.
			$sqlCheckIfThereIsAWinner = "select HasAnyoneWon from LaikiuxSubmission";
			$checkIfThereIsAWinnerInCaseOfDuplicate = mysqli_query($conn, $sqlCheckIfThereIsAWinner);
			$canICheckAnswer = true;


			//Chekina nuo laimetoju dubliu t.y. kaip yra laimetojas ir kasnros nepareloadines page gali vel laimeti.
			if(mysqli_num_rows($checkIfThereIsAWinnerInCaseOfDuplicate) > 0)
			{
				while($row = mysqli_fetch_assoc($checkIfThereIsAWinnerInCaseOfDuplicate))
				{
					if($row['HasAnyoneWon'] == 1)
						$canICheckAnswer = false;
				}
			}

			if(strtolower($EventAnswer) == strtolower($submission) && $canICheckAnswer) // "sklandus patikrinimas" t.y. sumažina abi reikšmes į mažąsias. Ir chekinimas del dubliu.
			{
				$sqlSomebodyHasWon = "update LaikiuxSubmission SET HasAnyoneWon='1' where id='1'";
				mysqli_query($conn, $sqlSomebodyHasWon);
				//$winnerText = $nickname.' '.$EventPrize.' kreditai';
				$winnerText = $nickname.' '.$EventPrize;
				$winnerText = mysqli_real_escape_string($conn, $winnerText); //apsaugo nuo sql injection
				$winnerIpForPrizeProtection = $_SERVER['REMOTE_ADDR'];
				$sqlInsertNewWinner = "insert into LaikiuxWinners (Nickname, organizer, winnerIp) VALUES ('$winnerText','$EventOrganizer', '$winnerIpForPrizeProtection')";
				
				if(mysqli_query($conn, $sqlInsertNewWinner))
				{
					echo "<font color='red'>SVEIKINAME LAIMĖJUS!!!!!!!!!!!</font>";
					header("Refresh:0");
				}
				else
				{
					//sitas error neturetu niekada suveikt, nes escapinam stringa, gal
					echo "Error: ".mysqli_error($conn);
					echo "<font color='red'>Atsakėte teisingai, tačiau atsakymas neužskaitytas. Savo nickname nenaudokite apostrofo.</font>";
				}
			}
			else
			{
				echo "<font color='red'>Deja, atsakymas nėra teisingas</font><br>";
			}
		}
	}
	
	//Spausdins hintus, tik jeigu dar niekas nelaimėjo konkurso.
	if($EventStatus == 0)
	{
		//Spausdins esamus hintus.
		$sqlReadHints = "select * from LaikiuxHints";
		$laikiuxHints = mysqli_query($conn, $sqlReadHints);
		echo "<br>Hints:<br>";
		if(mysqli_num_rows($laikiuxHints) > 0)
		{
			while($row = mysqli_fetch_assoc($laikiuxHints))
			{
				echo "<font color='red'>".$row['hinttext']."</font><br>";
			}
		}
		else
		{
			echo "Hintų kolkas nėra<br>";
		}
	}
	
	//Spausdina laimėtojus iš visos databazės.
	$sqlReadWinners = "select * from LaikiuxWinners order by id desc";
	$winners = mysqli_query($conn, $sqlReadWinners);
	
	if(mysqli_num_rows($winners) > 0)
	{
		echo "<br>Paskutiniai laimėtojai:<br>";
		//Eina per DB eilučių masyvą
		while($row = mysqli_fetch_assoc($winners))
		{
			echo $row['Nickname'];
			if($WhichAdminLevel > 1)
			{
				echo " (".$row['winnerIp'].")<br>"; //sitas reikalinga del to, kad kartais zaidejai savinasi kitu zaideju nickus ir bando apgaut, kad tipo jie laimejo.
			}
			else
			{
				echo "<br>";
			} 
		}
	}
	else
	{
		echo "<br>Paskutiniai laimėtojai:<br>";
		echo "Laimėtojų dar nebuvo";
	}
	
	echo "<br>";
	
	//Admin Panelė. Admin privilegijos, jeigu "1", tai paprastas adminas, jeigu "2", tai gali pridėti ir kitus adminus.
	if($WhichAdminLevel > 0)
	{
		echo "Admin panel (tai mato tik administratorius)<br>";
		echo "Jūs prisijungęs, kaip <font color='red'>".$AdminNickname."</font><br>";
		
		//Tikrina ar galite peržiūrėti konkurso atsakymą t.y. ar esate 2 ar didesnio lygio arba esate konkurso kurėjas.
		if($WhichAdminLevel > 1 || $AdminNickname == $EventOrganizer)
			echo "Esamas atsakymas į konkursą: ".$EventAnswer;
		else
			echo "Esamas atsakymas į konkursą: negalite peržiūrėti";
		
		//Naujo konkurso sukurimas
		echo '<form method="POST">';
		echo '<input name="newquestion" placeholder="Question"></input><br>';
		echo '<input name="newanswer" placeholder="Answer"></input><br>';
		echo '<input name="newprize" placeholder="Prize"></input><br>';
		echo '<input name="newphoto" placeholder="Photo url"></input><br>';
		echo '<button name="submitneweventbutton">Sukurti naują konkursą</button>';
		echo '</form>';
		echo '<br>';
		
		if(isset($_POST['submitneweventbutton']))
		{
			$shouldWeAddNewEvent = true;
			if($_POST['newquestion'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujo klausimo</font><br>";
			}
			if($_POST['newanswer'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujo atsakymo</font><br>";
			}
			if($_POST['newprize'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujo prizo</font><br>";
			}
			//Tikrina ar prizas yra skaičius IŠJUNGTA
	//		if(!is_numeric($_POST['newprize']))
	//		{
	//			$shouldWeAddNewEvent = false;
	//			echo "<font color='red'>Prizas turi būti skaičius</font><br>";
		//	}
			if($_POST['newphoto'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujos nuotraukos URL. Pastaba: privalo baigtis su .png .jpg etc</font><br>";
			}
			if($shouldWeAddNewEvent)
			{
				$NewQuestion = mysqli_real_escape_string($conn, $_POST['newquestion']);
				$NewAnswer = mysqli_real_escape_string($conn, $_POST['newanswer']);
				$NewPrize = mysqli_real_escape_string($conn, $_POST['newprize']);
				$NewPhotoUrl = mysqli_real_escape_string($conn, $_POST['newphoto']);
				$sqlCreateNewEvent = "update LaikiuxSubmission SET HasAnyoneWon='0', answer='$NewAnswer', prize='$NewPrize', photolink='$NewPhotoUrl', question='$NewQuestion', organizer='$AdminNickname' where id='1';";
				if(mysqli_query($conn, $sqlCreateNewEvent))
				{
					echo "Konkursas sukurtas"; 
					header("Refresh:0");
				}
				else
					echo "Error: ".mysqli_error($conn); //Šitas erroras neturetu suveikt, nes escapinam stringa
				//Senų hintų ištrinimas
				$sqlDeleteOldHints = "delete from LaikiuxHints";
				if(!mysqli_query($conn, $sqlDeleteOldHints))
					echo "Error: ".mysqli_error($conn); 
			}
		}
		
		//Pridėjimas naujo hinto
		echo '<form method="POST">';
		echo '<input name="newhint" placeholder="New Hint"></input><br>';
		echo '<button name="submitnewhint">Pridėti naują hintą</button>';
		echo '</form>';
		
		if(isset($_POST['submitnewhint']))
		{
			if($_POST['newhint'] == null)
				echo "Neįrašėte hinto<br>";
			if($_POST['newhint'] != null)
			{
				$NewHint = mysqli_real_escape_string($conn, $_POST['newhint']);
				$sqlSubmitNewHint = "insert into LaikiuxHints(hinttext) VALUES ('$NewHint')";
				if(mysqli_query($conn, $sqlSubmitNewHint))
				{
					echo "Pridėtas naujas hintas";
					header("Refresh:0");
				}
				else
					echo "Error: ".mysqli_error($conn);
			}
		}
		echo "<br>";
		
		//Hints trinimas
		echo '<form method="POST">';
		echo '<button name="deleteallhintsbutton">Ištrinti hintus</button>';
		echo '</form>';
		
		if(isset($_POST['deleteallhintsbutton']))
		{
			$sqlDeleteAllHints = "delete from LaikiuxHints";
			if(mysqli_query($conn, $sqlDeleteAllHints))
			{
				echo "Ištrinta";
				header("Refresh:0");
			}
			else
				echo "Error: ".mysqli_error($conn);
		}
		
		echo "<br>";
		
		//Sustabdyti/atkurti konkursą konkursą
		//Jeigu konkursas dar nelaimėtas, tai jį galima sustabdyti.
		if($EventStatus == 0)
		{
			echo '<form method="POST">';
			echo '<button name="cancelevent">Sustabdyti konkursą</button>';
			echo '</form>';
			if(isset($_POST['cancelevent']))
			{
				//Dalis kodo kuri neleidžia kitiems konkursų organizatoriams stabdyti NE SAVO konkursų.
				$canIStopTheEvent = false;
				if($WhichAdminLevel > 1 || $AdminNickname == $EventOrganizer)
					$canIStopTheEvent = true;

				if($canIStopTheEvent)
				{
					$sqlCancelEvent = "update LaikiuxSubmission SET HasAnyoneWon='1' where id='1'";
					if(mysqli_query($conn, $sqlCancelEvent))
					{
						echo "Sustabdytas";
						header("Refresh:0");
					}
					else
						echo "Error: ".mysqli_error($conn);
				}
				else
				{
					echo "Negalite sustabdyti ne savo konkursą!";
				}
			}
		}
		//Jeigu konkursas jau laimėtas, jį galima atkurti.
		else
		{
			echo '<form method="POST">';
			echo '<button name="renewevent">Atkurti konkursą</button>';
			echo '</form>';
			if(isset($_POST['renewevent']))
			{
				$sqlRenewEvent = "update LaikiuxSubmission SET HasAnyoneWon='0' where id='1'";
				if(mysqli_query($conn, $sqlRenewEvent))
				{
					echo "Atkurtas";
					header("Refresh:0");
				}
				else
					echo "Error: ".mysqli_error($conn);
			}
		}
		
		echo "<br>";
		
		//Pridėjimas/ištrinimas naujo admin. Mato tik adminai su 2 arba daugiau leveliu.
		if($WhichAdminLevel > 1)
		{
			echo '<form method="POST">';
			echo '<input name="newadminip" placeholder="Admin IP"></input>';
			echo '<input name="newadminnick" placeholder="Admin Nick"></input>';
			echo '<input name="newadminlevel" placeholder="Admin Level"></input>';
			echo '<input name="newadminsteamid" placeholder="Admin SteamID64"></input><br>';
			echo '<button name="addadmin">Pridėti admin</button><br>';
			echo '</form>';
		
			//Admin pridėjimas
			if(isset($_POST['addadmin']))
			{
				$canIAddNewAdmin = true;
				if($_POST['newadminip'] == null)
				{
					$canIAddNewAdmin = false;
					echo "<font color='red'>Neįrašėte admin IP</font><br>";
				}
				if($_POST['newadminnick'] == null)
				{
					$canIAddNewAdmin = false;
					echo "<font color='red'>Neįrašėte admin nick</font><br>";
				}
				if($_POST['newadminlevel'] == null)
				{
					$canIAddNewAdmin = false;
					echo "<font color='red'>Neįrašėte admin level</font><br>";
				}
				if($_POST['newadminsteamid'] == null)
				{
					$canIAddNewAdmin = false;
					echo "<font color='red'>Neįrašėte admin SteamID64</font><br>";
				}
				if($canIAddNewAdmin)
				{
					$newAdminIp = mysqli_real_escape_string($conn, $_POST['newadminip']);
					$newAdminNick = mysqli_real_escape_string($conn, $_POST['newadminnick']);
					$newAdminLevel = mysqli_real_escape_string($conn, $_POST['newadminlevel']);
					$newAdminSteamId = mysqli_real_escape_string($conn, $_POST['newadminsteamid']);
					$addAdminSql = "insert into LaikiuxAdmins (ip, nickname, level, steamid) VALUES ('$newAdminIp', '$newAdminNick', '$newAdminLevel', '$newAdminSteamId')";
					if(mysqli_query($conn, $addAdminSql))
						echo "Admin pridėtas<br>";
					else
						echo "Error: ".mysqli_error($conn);
				}
			}
			
			echo "<br>";
			
			echo '<form method="POST">';
			echo '<input name="oldadminnick" placeholder="Admin Nick"></input><br>';
			echo '<button name="removeadmin">Ištrinti admin</button><br>';
			echo '</form>';
			
			//Admin ištrinimas
			if(isset($_POST['removeadmin']))
			{
				$canIRemoveAdmin = true;
				
				if($canIRemoveAdmin)
				{
					$oldAdminNick = mysqli_real_escape_string($conn, $_POST['oldadminnick']);
					$removeAdminSql = "delete from LaikiuxAdmins where nickname='$oldAdminNick'";
					if(mysqli_query($conn, $removeAdminSql))
						echo "Admin ištrintas<br>";
					else
						echo "Error: ".mysqli_error($conn);
				}
			}
		}
		
	}
	
	echo "<br>";
	
	//Atspausdina esamu admin sąrašą
	$sqlGetAdminlist = "select * from LaikiuxAdmins";
	$laikiuxAdminsList = mysqli_query($conn, $sqlCheckIfAdmin);
	echo "Konkursų organizatoriai:<br>";
	if(mysqli_num_rows($laikiuxAdminsList) > 0)
	{
		//Kai į admin nickname įrašytas brukšniukas arba nieko neįrašyta, tada jo nespausdina.
		while($row = mysqli_fetch_assoc($laikiuxAdminsList))
		{
			if($row['nickname'] == '-' || $row['nickname'] == null)
				continue;
			echo $row['nickname']."<br>";
		}
	}
	


	echo "<p align='right'>Svetainės kūrėjas © <a href='https://steamcommunity.com/id/tortonas' target='_blank'>Tortonas</a>, ";
	echo "<p align='right'><a href='https://github.com/Tortonas/Event-quiz' target='_blank'>Github open source</a>, ";
	echo date("Y")."</p>";
	mysqli_close($conn);
?>

</body>

</html>