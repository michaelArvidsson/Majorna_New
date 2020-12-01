<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Majorna vårdcentral</title>
  <style>
    body {
      background-color: #1fa6a1;
    }

    h1 {
      text-align: center;
      padding: 15px;
      text-transform: uppercase;
      letter-spacing: 5;
      font-size: 50px;
    }

    #journal {
      margin: auto;
      margin-bottom: 10px;
      background-color: yellow;
      border: 1px solid black;
      padding: 5px;
      padding-left: 15px;
      width: 600px;

    }
  </style>
</head>

<body>
  <h1>välkommen till Majornas Vårdcentral</h1>
  <pre>
  <?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $cookiepath = "/tmp/cookies.txt";
  $tmeout = 3600; // (3600=1hr)

  // här sätter ni er domän
  $baseurl = 'https://ateam.erpnext.com/';

  try {
    $ch = curl_init($baseurl . 'api/method/login');
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
  }

  curl_setopt($ch, CURLOPT_POST, true);

  // Här sätter ni era login-data
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"usr":"michael.arvidsson@gmail.com", "pwd":"Erpnext4044!"}');

  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiepath);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiepath);
  curl_setopt($ch, CURLOPT_TIMEOUT, $tmeout);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  $response = json_decode($response, true);


  $error_no = curl_errno($ch);
  $error = curl_error($ch);
  curl_close($ch);

  if (!empty($error_no)) {
    echo "<div style='background-color:red'>";
    echo '$error_no<br>';
    var_dump($error_no);
    echo "<hr>";

    echo '$error<br>';
    var_dump($error);
    echo "<hr>";
    echo "</div>";
  }

  // Get Vital signs api/resource/Vital Signs [date][patient][BP]
  // filter and match date against encounters to display
  $ch = curl_init($baseurl . 'api/resource/Patient%20Encounter');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiepath);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiepath);
  curl_setopt($ch, CURLOPT_TIMEOUT, $tmeout);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  $response = json_decode($response, true);


  $error_no = curl_errno($ch);
  $error = curl_error($ch);
  curl_close($ch);

  // Get Patient encounters
  $ch = curl_init($baseurl . 'api/resource/Patient%20Encounter');
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiepath);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiepath);
  curl_setopt($ch, CURLOPT_TIMEOUT, $tmeout);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);
  $response = json_decode($response, true);


  $error_no = curl_errno($ch);
  $error = curl_error($ch);
  curl_close($ch);


  //create an array of encounter names
  $lengthEncArr = sizeof($response['data']);
  $arr_encounter = array();
  for ($i = 0; $i < $lengthEncArr; $i++) {
    echo $response["data"][$i]["name"];
    array_push($arr_encounter, $response["data"][$i]["name"]);
  }


  // create array to hold drug names for patient
  $drugNames = array();

  foreach ($arr_encounter as $key => $value) {
    // assign variable url to pull out each encounter
    $ch = curl_init($baseurl . 'api/resource/Patient%20Encounter/' . $value);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiepath);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiepath);
    curl_setopt($ch, CURLOPT_TIMEOUT, $tmeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $response = json_decode($response, true);


    $error_no = curl_errno($ch);
    $error = curl_error($ch);
    curl_close($ch);


    $Journals = array();
    // ---- TO DO ------ if loop to check patient from GET
    if ($response['data']['name'] == $_GET['journalID']) {
      // Get patients data
      //if (isset($_GET['patient']))
      echo "<div id='journal'>";
      $encounterJournal = $response['data'];
      array_push($Journals, $response['data']['name']);
      array_push($Journals, $response['data']['practitioner_name']);
      array_push($Journals, $response['data']['patient']);
      array_push($Journals, $response['data']['encounter_date']);
      array_push($Journals, $response['data']['symptoms']['0']['complaint']);
      if (!empty($response['data']['diagnosis']['0']['diagnosis'])) {
        array_push($Journals, $response['data']['diagnosis']['0']['diagnosis']);
      }
      $lengthDrugPr = (sizeof($response['data']['drug_prescription']));
      //echo print_r($response['data']['drug_prescription']['0']['drug_name']);
      for ($i = 0; $i < $lengthDrugPr; $i++) {
        array_push($drugNames, $response['data']['drug_prescription'][$i]['drug_name']);
        array_push($drugNames, $response['data']['drug_prescription'][$i]['dosage_form']);
        //add more info that is displayed with drug name
        array_push($drugNames, $response['data']['drug_prescription'][$i]['dosage']);
        array_push($drugNames, substr($response['data']['drug_prescription'][$i]['creation'], 0, 11));
      }
      $lengthDrugNames = sizeof($drugNames);
      // Add if empty - Show nothing
      echo '<h4>Journal: ' . $encounterJournal['name'] . '</h4>';
      echo '<p>Besöksdatum: ' . $encounterJournal['encounter_date'] . '</p>';
      echo '<p>Vårdgivare: ' . $encounterJournal['practitioner_name'] . '</p>';
      echo '<p>Patient: ' . $encounterJournal['patient'] . '</p>';
      if (!empty($Journals)) {
        echo '<p>Symptom: ' . $Journals['4'] . '</p>';
        echo '<p>Diagnos: ' . $Journals['5'] . '</p>';
      }
      if (!empty($drugNames)) {
        echo '<span>Förskrivet recept: ' . $drugNames['0'] . '</span>';
        echo '<span> - </span>';
        echo '<span>' . $drugNames['1'] . '</span>';
        echo '<p>Förskriven dos: ' . $drugNames['2'] . '</p>';
        echo '<p>Förskrivningsdatum: ' . $drugNames['3'] . '</p>';
      }
      if ($response['data']['docstatus'] == '1') {
        echo "<h4>Vidimerad</h4></tr>";
      } else {
        echo "<h4 style='color:red;'>Ovidimerad</h4></tr>";
      }
    }

    echo "</div>";
  }



  if (!empty($error_no)) {
    echo "<div style='background-color:red'>";
    echo '$error_no<br>';
    var_dump($error_no);
    echo "<hr>";

    echo '$error<br>';
    var_dump($error);
    echo "<hr>";
    echo "</div>";
  }

  ?>
</pre>


</body>

</html>