<?php


	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$cookiepath = "/tmp/cookies.txt";
	$tmeout=3600; // (3600=1hr)
// här sätter ni er domän
	$baseurl= 'https://vcmajorna.erpnext.com/';
	try{
	  $ch = curl_init($baseurl.'api/method/login');
	} catch (Exception $e) {
	  echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	curl_setopt($ch,CURLOPT_POST, true);
// Här sätter ni era login-data
	curl_setopt($ch,CURLOPT_POSTFIELDS, '{"usr":"apitest@vcmajorna.com", "pwd":"hanifesangels"}');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
	curl_setopt($ch,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($ch,CURLOPT_COOKIEJAR, $cookiepath);
	curl_setopt($ch,CURLOPT_COOKIEFILE, $cookiepath);
	curl_setopt($ch,CURLOPT_TIMEOUT, $tmeout);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	$response = json_decode($response,true);
	$error_no = curl_errno($ch);
	$error = curl_error($ch);
	curl_close($ch);
	if(!empty($error_no)){
		echo "<div style='background-color:red'>";
		echo '$error_no<br>';
		var_dump($error_no);
		echo "<hr>";
		echo '$error<br>';
		var_dump($error);
		echo "<hr>";
		echo "</div>";
	}
	echo "<div style='background-color:lightgray; border:1px solid black'>";
	echo '$response<br><pre>';
	echo print_r($response)."</pre><br>";
	echo "</div>";
	
	//-------------------------------
	
		// --------------------------- hämta schemalagda tider
	
	$ch = curl_init($baseurl.'/api/resource/Practitioner%20Schedule/HLC-PRAC-2020-00001');
	curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'GET');
	
	curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
	curl_setopt($ch,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($ch,CURLOPT_COOKIEJAR, $cookiepath);
	curl_setopt($ch,CURLOPT_COOKIEFILE, $cookiepath);
	curl_setopt($ch,CURLOPT_TIMEOUT, $tmeout);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response = json_decode($response,true);
	$error_no = curl_errno($ch);
	$error = curl_error($ch);
	curl_close($ch);
	if(!empty($error_no)){
		echo "<div style='background-color:red'>";
		echo '$error_no<br>';
		var_dump($error_no);
		echo "<hr>";
		echo '$error<br>';
		var_dump($error);
		echo "<hr>";
		echo "</div>";
	}
	//echo "<div style='background-color:lightgray; border:1px solid black'>";
	//echo '$response<br><pre>';
	//echo print_r($response)."<br>";
	//echo print_r($response["data"]["time_slots"]);
	
	// $time innehåller start-slut schemalagda tider
	$slot=$response["data"]["time_slots"];
	foreach ($slot as $time){
		$time_2[] = $time["from_time"];
		echo "<div>";
		echo $time["from_time"];
		echo " - ";
		echo $time["to_time"];
		echo "</div>";
	}

	//echo "</pre></div>";
	
	//------------------------ hämta bokade tider
	
	$ch = curl_init($baseurl.'/api/resource/Patient%20Appointment?fields=["patient_name","practitioner_name","appointment_date","appointment_time"]');
	curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'GET');
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
	curl_setopt($ch,CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($ch,CURLOPT_COOKIEJAR, $cookiepath);
	curl_setopt($ch,CURLOPT_COOKIEFILE, $cookiepath);
	curl_setopt($ch,CURLOPT_TIMEOUT, $tmeout);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	$response = json_decode($response,true);
	$error_no = curl_errno($ch);
	$error = curl_error($ch);
	curl_close($ch);
	if(!empty($error_no)){
		echo "<div style='background-color:red'>";
		echo '$error_no<br>';
		var_dump($error_no);
		echo "<hr>";
		echo '$error<br>';
		var_dump($error);
		echo "<hr>";
		echo "</div>";
	}
	echo "<div style='background-color:lightgray; border:1px solid black'>";
	echo '$response<br><pre>';
	echo print_r($response)."<br>";	
	
/* 	foreach ($response["data"] as $app){
		$oc_time[] = $app["appointment_time"];
		echo "<div>";
		echo $app["appointment_date"]." - ";
		echo $app["appointment_time"];
		echo "</div>";
	}
	
	print_r($oc_time);
	$oc_time2 = $oc_time["appointment_time"];
	print_r($oc_time2);
	
	
	foreach ($response["data"] as $app){
		foreach ($slot as $time){
			if ($app["appointment_time"] == $time["from_time"]){
			echo "<div style='background-color:red'>";
			echo $time["from_time"];
			echo " - ";
			echo $time["to_time"];
			echo "</div>";
			
			}
			else{
			echo "<div style='background-color:green'>";
			echo $time["from_time"];
			echo " - ";
			echo $time["to_time"];
			echo "</div>";
			}
		}
	} */
	// Skapa formulär för bokningsbara tider
	/* $av_time = array_diff($time_2,$oc_time);
	print_r($av_time); */
	echo "<form action=''>";
	echo "<label for=''>Tider:</label>";
	echo "<select id='' name=''>";
	foreach ($slot as $time){
		$upptag=FALSE;
		foreach ($response["data"] as $app){
			if ($app["appointment_time"]==$time["from_time"]){
			$upptag=TRUE;
			break;
			}			
		}
		if ($upptag==FALSE){
			echo "<option value=''>".$time['from_time']."</option>";
		}
	}
	echo "</select>";
	echo "<input type='submit'>";
	echo "</form>";
	
	

	echo "</pre></div>";
	

?>
