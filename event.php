<?php
require 'steamauth/steamauth.php';
require 'includes/mysql_login.php';
require 'includes/config.php';
//require 'includes/laikiuxduombaze.php';
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

	//Automatinis atsinaujinimas klausimo kasdien ant 18 : 00

	$arReikiaKeistiKlausima = true;
	$dabartineData  = date('Y-m-d H:i:s');
	$dabartineDataBeValandu = date('Y-m-d', strtotime("+1 day")) ; //dabartine data be valandu ir plius 1 diena


	//Kadangi sitas scriptas turi buti parunintas anksciau negu pats uploadinimas klausimo, o dar duomenu apie current klausima nera, tai reik parunint
	$sqlReadDataBefore ="select * from LaikiuxSubmission";
	$CurrentDataStatus = mysqli_query($conn, $sqlReadDataBefore);
	if(mysqli_num_rows($CurrentDataStatus) == 1)
	{
		//Privalo būti 1 row bei jo ID turėtu būti 1!!!!!!
		while($row = mysqli_fetch_assoc($CurrentDataStatus))
		{
			$EventStatus = $row['HasAnyoneWon'];
			$EventDateTime = $row['dateTime'];
		}
	}

	//chekina ar klausimas atsakymas, skipins tik tada kada jis atsakytas
	if($EventStatus == 0)
		$arReikiaKeistiKlausima = false;

	//chekina current data ir paskutinio keisto klausimo data
	if($dabartineData < $EventDateTime)
	{
		$arReikiaKeistiKlausima = false;
	}

	if($arReikiaKeistiKlausima)
	{


		$sqlGetRandomQuestionFromDB = "select * from LaikiuxQuestions";

		$resultsSqlGetRandomQuestionFromDB = mysqli_query($conn, $sqlGetRandomQuestionFromDB);

		$NewAnswer = null;
		$NewPrize = null;
		$NewPhotoUrl = null;
		$NewQuestion = null;
		$AdminNickname = null;

		$arYraNaujasKlausimasDuomenuBazeje = false;

		if(mysqli_num_rows($resultsSqlGetRandomQuestionFromDB) > 0)
		{
			while($row = mysqli_fetch_assoc($resultsSqlGetRandomQuestionFromDB))
			{
				$NewAnswer = $row['answer'];
				$NewPrize = $row['prize'];
				$NewPhotoUrl = $row['photolink'];
				$NewQuestion = $row['question'];
				$AdminNickname = $row['organizer'];

				$idOfThisQuestoin = $row['id'];

				$arYraNaujasKlausimasDuomenuBazeje = true;

				$sqlDeleteThisQuestionFromDB = "delete from LaikiuxQuestions where id='$idOfThisQuestoin'";
				mysqli_query($conn, $sqlDeleteThisQuestionFromDB);

				break;
			}
		}

		$naujaData = $dabartineDataBeValandu." 18:00:00";


		if($arYraNaujasKlausimasDuomenuBazeje)
		{
			//perkels dabartini klausima i panaudoju klausimu table
			//pradzioje nuskaito sena klausima
			$sqlCopyQuestionToUsedOnes ="select * from LaikiuxSubmission";
			$resultsCopyQuestionToUsedOnes = mysqli_query($conn, $sqlCopyQuestionToUsedOnes);
			$row = mysqli_fetch_assoc($laikiuxAdmins);
			$oldAnswer = $row['answer'];
			$oldPrize = $row['prize'];
			$oldPhotoUrl = $row['photolink'];
			$oldQuestion = $row['question'];
			$oldAdminNickname = $row['organizer'];

			//veliau issiuncia i kita table
			//$sqlSendToUsedQuestions = "insert into LaikiuxUsedQuestions (question, answer, prize, photolink, organizer) VALUES 
			//('$oldQuestion','$oldAnswer', '$oldPrize', '$oldPhotoUrl', '$oldAdminNickname')";
			//mysqli_query($conn, $sqlSendToUsedQuestions); // NESUTVARKYTAS


			$sqlCreateNewEvent = "update LaikiuxSubmission SET HasAnyoneWon='0', answer='$NewAnswer', prize='$NewPrize', photolink='$NewPhotoUrl',
				question='$NewQuestion', organizer='$AdminNickname', dateTime='$naujaData' where id='1';";
			mysqli_query($conn, $sqlCreateNewEvent);
			$sqlDeleteAllHints = "delete from LaikiuxHints";
			mysqli_query($conn, $sqlDeleteAllHints);
		}
	}

	//---------------------------------------------------------------------------------------------------------------------

	echo "Sveiki atvykę į Laikiux daily quiz<br>";
	echo "<font color='red'>Kadangi buvau pašalintas iš S. administratorių, nekontroliuoju paslaugų duomenų bazės. Svetainė suksis, bet paslaugų užsidėt bus neįmanoma.</font><br>";
	echo "<a href='".$WebDomain."/salygos.txt'>Konkurso taisyklės bei bendra informacija</a><br>";

	date_default_timezone_set("Europe/Vilnius");

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
	$EventDateTime = null;

	$WhichAdminLevel = 0;
	$AdminNickname = null;

	$UserioIP = $_SERVER['REMOTE_ADDR'];

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
			$EventDateTime = $row['dateTime'];
		}
	}
	else
	{
		echo "ERROR. Nepavyko įkelti nuotraukos, klausimo bei organizatoriaus problema, kad ne 1 row arba jo id ne 1 LaikiuxSubmission db";
	}
	

	echo '<img src="'.$EventPhotoUrl.'" alt="Evento nuotrauka"><br>';
	echo "Dabartinio/paskutinio konkurso organizatorius: ".$EventOrganizer."<br>";
	echo "<font color='red' size='6'><b>Klausimas: ".$EventQuestion."</b></font><br>";
	
	
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
			$resultIfWrongAnswer = "<font color='red'>Deja, atsakymas nėra teisingas</font><br>";
			$nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
			$submission = mysqli_real_escape_string($conn, $_POST['submission']); 
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

			//Patikrinimas ar žaidėjas nėra laimėjas per paskutiniasias 25 valandas.
			$sqlCheckPreviousLaikiuxWinners = "select * from LaikiuxCredits where ip='$UserioIP'";
			$resultsSqlCheckPreviousLaikiuxWinners = mysqli_query($conn, $sqlCheckPreviousLaikiuxWinners);
			if(mysqli_num_rows($resultsSqlCheckPreviousLaikiuxWinners) > 0)
			{
				while($row = mysqli_fetch_assoc($resultsSqlCheckPreviousLaikiuxWinners))
				{
					if($dabartineData < $row['bannedTill'])
					{
						$canICheckAnswer = false;
						echo "<font color='red'>Atsakyti į klausimą galima tik kartą per 25 valandas. Galėsite: ".$row['bannedTill']."</font>";
						$resultIfWrongAnswer = null;
						break;
					}
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
				
				
				//kreditu davimas

				$sqlGetCreditCount = "select * from LaikiuxCredits where ip='$UserioIP'";
				$resultSqlGetCreditCount = mysqli_query($conn, $sqlGetCreditCount);

				$arIPEgzistuojaDuombazeje = false;
				$currentCreditCount = 0;

				if (mysqli_num_rows($resultSqlGetCreditCount) > 0) 
				{
				    while($row = mysqli_fetch_assoc($resultSqlGetCreditCount))
				    {
				        if($UserioIP == $row['ip'])
			        	{
			        		$currentCreditCount = $row['credits'];
			        		$arIPEgzistuojaDuombazeje = true;
			        	}
				    }
				}

				//jeigu egzistuoja, tai ji paupdatina
				$bannedTillDate = date('Y-m-d H:i:s', strtotime("+25 hours"));
				if($arIPEgzistuojaDuombazeje)
				{
					$newCreditCount = $currentCreditCount + 1;
					$sqlUpdateCreditOwner = "update LaikiuxCredits set credits='$newCreditCount', nick='$nickname', bannedTill='$bannedTillDate' where ip='$UserioIP'";
					mysqli_query($conn, $sqlUpdateCreditOwner);
				}
				//jeigu ne, tada sukuria nauja
				else
				{
					$sqlCreateNewCreditOwner = "insert into LaikiuxCredits (ip, credits, nick, bannedTill) VALUES ('$UserioIP', '1', '$nickname', '$bannedTillDate')";
					mysqli_query($conn, $sqlCreateNewCreditOwner);
				}

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
				echo $resultIfWrongAnswer;
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
		echo "<br>Paskutiniai 7 laimėtojai:<br>";
		//Eina per DB eilučių masyvą
		$maximumas = 7;
		$currentKiekis = 0;
		while($row = mysqli_fetch_assoc($winners))
		{
			if($currentKiekis >= $maximumas)
				break;
			echo $row['Nickname'];
			$currentKiekis++;
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


	//Kodas kuris parodys vartotojui kiek jis turi galimybių aktyvuoti vipą.
	$creditCount = 0;

	$sqlGetCreditCount = "select * from LaikiuxCredits where ip='$UserioIP'";
	$resultSqlGetCreditCount = mysqli_query($conn, $sqlGetCreditCount);

	if (mysqli_num_rows($resultSqlGetCreditCount) > 0) 
	{
	    while($row = mysqli_fetch_assoc($resultSqlGetCreditCount))
	    {
	        if($UserioIP == $row['ip'])
	        	$creditCount = $row['credits'];
	    }
	}

	echo "<font color='red'>VIP aktyvacija.</font> Vipas trunka 2 dienas įskaitant šią likusią dieną bei visą rytojų<br>";
	echo "Kiek kartų galite aktyvuoti VIP: <font color='red'>".$creditCount.'</font>';
	echo '<form method="POST">';
	echo "Pasirinkite serverį: ";
	echo '<select name="serveris">';
    echo '<option value="jailbreak">Jailbreak</option>';
    echo '<option value="forfun">Forfun</option>';
    echo '<option value="surf">Surf</option>';
	echo '</select><br>';
	echo "Pasirinkite ant ko norite VIP: ";
	echo '<select name="authTipas">';
    echo '<option value="ip">IP</option>';
    echo '<option value="steamid">SteamID</option>';
	echo '</select><br>';
	//kaip zmogus tures virs 0 kreditu, jam rasys jo dabartini IP
	if($creditCount > 0)
		echo '<input name="steamidorip" placeholder="SteamID arba IP" value="'.$UserioIP.'"></input><br>';
	else
		echo '<input name="steamidorip" placeholder="SteamID arba IP"></input><br>';
	echo '<button name="getVip">Aktyvuoti VIP</button>';
	if($creditCount > 0)
	{
		echo "<br><font color='red'>Paspaudus aktyvuoti VIP luktelkite kokias ~7 sekundes kol refreshinsis puslapis</font><br>";
	}
	echo '</form>';

	if(isset($_POST['getVip']))
	{
		//patikrina ar irase steamid arba ip
		if($_POST['steamidorip'] != null)
		{
			$nicknameOfUser = null;
			//Tai turi padaryti is naujo, kad nebugintu su daug tabu.
			$sqlGetCreditCount = "select * from LaikiuxCredits where ip='$UserioIP'";
			$resultSqlGetCreditCount = mysqli_query($conn, $sqlGetCreditCount);
			if (mysqli_num_rows($resultSqlGetCreditCount) > 0) 
			{
			    while($row = mysqli_fetch_assoc($resultSqlGetCreditCount))
			    {
			        if($UserioIP == $row['ip'])
			        {
			        	$creditCount = $row['credits'];
			        	$nicknameOfUse = mysqli_real_escape_string($conn, $row['nick']);
			        }
			    }
			}

			if($creditCount > 0)
			{
				include_once("includes/rcon.class.php");  
			
				//uzdeda vipa, decreasina counta
				$newCreditCount = $creditCount - 1;
				$sqlDecreaseCount = "update LaikiuxCredits set credits='$newCreditCount' where ip='$UserioIP'";
				mysqli_query($conn, $sqlDecreaseCount);

				$steamIDarbaIP = mysqli_real_escape_string($conn, $_POST['steamidorip']);
				$twoDaysAfterToday = date("Y-m-d", time() + 172800);

				$arGaliuUzdetPaslauga = true;
				//Patikrinimas ar IP/STEAMID turi paslauga. Jeigu turi, naujos neuzdeda, bet kreditas buna vis tiek nuskaiciuotas kaip bausme (nes noreta uzsidet ne sau).
				$sqlCheckIfAuthHasPrivilege = "SELECT * FROM sm_admins WHERE identity='$steamIDarbaIP'";
				$resultsCheckIfAuthHasPrivilege = mysqli_query($connJailbreak, $sqlCheckIfAuthHasPrivilege);
				$row = mysqli_fetch_assoc($resultsCheckIfAuthHasPrivilege);

				if($row['identity'] == null)
				{
					$arGaliuUzdetPaslauga = true; //nieko nekeicia realiai
				}
				else
				{
					echo "<font color='red'>Šis auth turi paslaugą, draudžiama dėt kitiems paslaugas! Kreditas buvo nuskaičiuotas.</font><br>";
					$arGaliuUzdetPaslauga = false;
				}

				if($arGaliuUzdetPaslauga)
				{
					if($_POST['serveris'] == "jailbreak")
					{
						if($_POST['authTipas'] == "ip")
						{
							$sqlInsertIntoLaikiuxDatabase = "insert into sm_admins (authtype, identity, flags, name, immunity, gr_time_left) 
								VALUES ('ip', '$steamIDarbaIP', 'ap', 'vipas is tortonas.eu', '98', '$twoDaysAfterToday')";

							mysqli_query($connJailbreak, $sqlInsertIntoLaikiuxDatabase);
						}
						//steamid
						else
						{
							
							$sqlInsertIntoLaikiuxDatabase = "insert into sm_admins (authtype, identity, flags, name, immunity, gr_time_left) 
								VALUES ('steam', '$steamIDarbaIP', 'ap', 'vipas is tortonas.eu', '98', '$twoDaysAfterToday')";

							mysqli_query($connJailbreak, $sqlInsertIntoLaikiuxDatabase);
						}
						echo "<font color='red'>VIP sėkmingai aktyvuotas ant ".$steamIDarbaIP." Jailbreak serveryje!</font><br>";
						$jailbreak = new rcon($BendrasServeriuIP, $JailbreakPort, $JailbreakRCON);
						$jailbreak->Auth();  
						var_dump($jailbreak->rconCommand("sm_reloadadmins"));
						var_dump($jailbreak->rconCommand("sm_reloadccc"));
						var_dump($jailbreak->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
						var_dump($jailbreak->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
						var_dump($jailbreak->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
					}

					if($_POST['serveris'] == "forfun")
					{
						if($_POST['authTipas'] == "ip")
						{
							$sqlInsertIntoLaikiuxDatabase = "insert into sm_admins (authtype, identity, flags, name, immunity, gr_time_left) 
								VALUES ('ip', '$steamIDarbaIP', 'ap', 'vipas is tortonas.eu', '98', '$twoDaysAfterToday')";

							mysqli_query($connForfun, $sqlInsertIntoLaikiuxDatabase);
						}
						//steamid
						else
						{
							
							$sqlInsertIntoLaikiuxDatabase = "insert into sm_admins (authtype, identity, flags, name, immunity, gr_time_left) 
								VALUES ('steam', '$steamIDarbaIP', 'ap', 'vipas is tortonas.eu', '98', '$twoDaysAfterToday')";

							mysqli_query($connForfun, $sqlInsertIntoLaikiuxDatabase);
						}
						echo "<font color='red'>VIP sėkmingai aktyvuotas ant ".$steamIDarbaIP." Forfun serveryje!</font><br>";
						$forfun = new rcon($BendrasServeriuIP, $ForfunPort, $ForfunRCON);
						$forfun->Auth();
						var_dump($forfun->rconCommand("sm_reloadadmins"));
						var_dump($forfun->rconCommand("sm_reloadccc"));
						var_dump($forfun->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
						var_dump($forfun->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
						var_dump($forfun->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
					}

					if($_POST['serveris'] == "surf")
					{
						if($_POST['authTipas'] == "ip")
						{
							$sqlInsertIntoLaikiuxDatabase = "insert into sm_admins (authtype, identity, flags, name, immunity, gr_time_left) 
								VALUES ('ip', '$steamIDarbaIP', 'ap', 'vipas is tortonas.eu', '98', '$twoDaysAfterToday')";

							mysqli_query($connSurf, $sqlInsertIntoLaikiuxDatabase);
						}
						//steamid
						else
						{
							
							$sqlInsertIntoLaikiuxDatabase = "insert into sm_admins (authtype, identity, flags, name, immunity, gr_time_left) 
								VALUES ('steam', '$steamIDarbaIP', 'ap', 'vipas is tortonas.eu', '98', '$twoDaysAfterToday')";

							mysqli_query($connSurf, $sqlInsertIntoLaikiuxDatabase);
						}
						echo "<font color='red'>VIP sėkmingai aktyvuotas ant ".$steamIDarbaIP." Jailbreak serveryje!</font><br>";
						$surf = new rcon($BendrasServeriuIP, $SurfPort, $SurfRCON);  
						$surf->Auth(); 
						var_dump($surf->rconCommand("sm_reloadadmins"));
						var_dump($surf->rconCommand("sm_reloadccc"));
						var_dump($surf->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
						var_dump($surf->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
						var_dump($surf->rconCommand("say [tortonas.eu/event] - ".$nicknameOfUse." aktyvavosi vipą!"));
					}
				}
			}
			else
			{
				echo "<font color='red'>Neturite aktyvacijos kreditų!</font><br>";
			}
		}
		else
		{
			echo "<font color='red'>Neįrašėte SteamID arba IP!</font><br>";
		}
	}

	echo "<br>";
	
	//Admin Panelė. Admin privilegijos, jeigu "1", tai paprastas adminas, jeigu "2", tai gali pridėti ir kitus adminus.
	// --------------------------- Admin sistemos pradžia ---------------------------------------
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

				$dataPakeitimui = date('Y-m-d', strtotime("+1 day"))." 18:00:00";

				$sqlCreateNewEvent = "update LaikiuxSubmission SET HasAnyoneWon='0', answer='$NewAnswer', prize='$NewPrize', photolink='$NewPhotoUrl',
				 question='$NewQuestion', organizer='$AdminNickname', dateTime='$dataPakeitimui' where id='1';";
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

		echo "Klausimų pridėjimas į automatizuotai atsinaujinančią duomenų bazę:";
		echo '<form method="POST">';
		echo '<input name="newquestionQueue" placeholder="Question"></input><br>';
		echo '<input name="newanswerQueue" placeholder="Answer"></input><br>';
		echo '<input name="newprizeQueue" placeholder="Prize"></input><br>';
		echo '<input name="newphotoQueue" placeholder="Photo url"></input><br>';
		echo '<input name="newOrganizerQueue" placeholder="Organizer" value="'.$AdminNickname.'"></input><br>';
		echo '<button name="submitneweventbuttonQueue">Pridėti naują klausimą į klausimų duomenų bazę</button>';
		echo '</form>';
		echo '<br>';
		
		if(isset($_POST['submitneweventbuttonQueue']))
		{
			$shouldWeAddNewEvent = true;
			if($_POST['newquestionQueue'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujo klausimo</font><br>";
			}
			if($_POST['newanswerQueue'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujo atsakymo</font><br>";
			}
			if($_POST['newprizeQueue'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujo prizo</font><br>";
			}
			if($_POST['newphotoQueue'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte naujos nuotraukos URL. Pastaba: privalo baigtis su .png .jpg etc</font><br>";
			}
			if($_POST['newphotoQueue'] == null)
			{
				$shouldWeAddNewEvent = false;
				echo "<font color='red'>Neįrašėte organizatoriaus slapyvardžio!</font><br>";
			}
			if($shouldWeAddNewEvent)
			{
				$NewQuestion = mysqli_real_escape_string($conn, $_POST['newquestionQueue']);
				$NewAnswer = mysqli_real_escape_string($conn, $_POST['newanswerQueue']);
				$NewPrize = mysqli_real_escape_string($conn, $_POST['newprizeQueue']);
				$NewPhotoUrl = mysqli_real_escape_string($conn, $_POST['newphotoQueue']);
				$NewOrganizer = mysqli_real_escape_string($conn, $_POST['newOrganizerQueue']);


				$sqlUploadNewQuestion = "insert into LaikiuxQuestions (question, answer, prize, photolink, organizer) 
					VALUES ('$NewQuestion', '$NewAnswer', '$NewPrize', '$NewPhotoUrl', '$NewOrganizer')";
				if(mysqli_query($conn, $sqlUploadNewQuestion))
				{
					echo "Klausimas sėkmingai pridėtas!<br>"; 
				}
				else
					echo "Error: ".mysqli_error($conn); //Šitas erroras neturetu suveikt, nes escapinam stringa
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
	// --------------------------- Admin sistemos pabaiga ---------------------------------------
	
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

	echo "<br>";
	
	if($EventStatus == 1)
	{
		$newDate = date($EventDateTime, strtotime("+1 day"));
		echo "<br>Naujas klausimas automatiškai susigeneruos: ".date($newDate, strtotime("+1 day"))."<br>";
	}
	$sqlGetQuestionCount = "select * from LaikiuxQuestions";
	$resultsGetQuestionCount = mysqli_query($conn, $sqlGetQuestionCount);
	if(mysqli_num_rows($resultsGetQuestionCount) > 0)
	{
		echo "Duomenų bazėje liko <font color='green'>".mysqli_num_rows($resultsGetQuestionCount)."</font> klausimai!";
	}
	else
	{
		echo "Duomenų bazėje liko <font color='red'>".mysqli_num_rows($resultsGetQuestionCount)."</font> klausimų!";
	}

	echo "<p align='right'>Svetainės kūrėjas © <a href='https://steamcommunity.com/id/tortonas' target='_blank'>Tortonas</a>, ";
	echo "<p align='right'><a href='https://github.com/Tortonas/Event-quiz' target='_blank'>Github open source</a>, ";
	echo date("Y")."</p>";

	mysqli_close($conn);
?>

</body>

</html>