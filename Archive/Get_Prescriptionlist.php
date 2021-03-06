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

    td,
    tr {
      padding-right: 10px;
      padding-top: 10px;
      padding-bottom: 10px;
      text-align: left;
    }

    th {
      text-align: left;
      text-decoration: underline;
    }

    table {
      border-collapse: collapse;
      margin: 10px;
    }

    #journal {
      margin-bottom: 10px;
      background-color: yellow;
      border: 1px solid black;
      padding: 5px;
      padding-left: 15px;

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

  $ch = curl_init($baseurl . 'api/resource/Patient%20Encounter');
  //?fields=["appointment_time"]&filters=[["Patient%20Appointment","appointment_date","=","'.$hardCodedDay.'"]]');
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
  echo print_r($arr_encounter);

  // create array to hold drug names for patient
  $drugNames = array();
  echo "<div id='journal'>";
  echo "<table>";
  echo '<th>Medicin</th><th></th><th>Dosering</th><th>Antal förskrivna uttag - Giltigt t.o.m</th>';
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



    // Set session ID
    if ($response['data']['patient'] == 'Benny') {
      //get size of array
      $lengthDrugPr = (sizeof($response['data']['drug_prescription']));
      //echo print_r($response['data']['drug_prescription']['0']['drug_name']);
      foreach ($response['data']['drug_prescription'] as $prescription) {
        echo "<tr>";
        echo "<td>" . $prescription['drug_name'] . "</td>";
        echo "<td>" . $prescription['dosage_form'] . "</td>";
        echo "<td>" . $prescription['dosage'] . "</td>";
        echo "<td>" . $prescription['comment'] . "</td>";
        //echo "<td>" . substr($prescription['creation'], 0, 11) . "</td>";
        if (!isset($_POST['drug_name'])) {
          echo "<td style='padding:0px; vertical-align:center;'>";
          echo "<form method='POST' action='Get_Prescriptionlist.php'>";
          echo "<input type=hidden name='drug_name' value=" . $prescription['drug_name'] . "/>";
          echo "<input type=hidden name='dosage' value=" . $prescription['dosage'] . "/>";
          echo "<input type=hidden name='creation' value=" . $prescription['creation'] . "/>";
          echo "<input style='padding:0px; margin-top:10px;' type=submit name=submit value=submit>";
          echo "</form></td>";
        }
      }
      echo "</tr>";
    }
  }
  echo "</table>";
  /* echo "<pre>";
  echo print_r($_POST);
  echo "</pre>"; */

  // could use info in $response['data']['drug_prescription'][$i]['period'] to calculate when Rx is no longer active
  // and only display active Rx - NOT IMPLEMENTED NOW
  // ---- TO DO ------ create hidden input for sending patient to prescription
  echo "</div>";

  $pdo = new PDO('mysql:dbname=a18micar_dbk2020;host=localhost', 'sqllab', 'Tomten2009');

  if (isset($_POST['submit'])) {

    $querystring = 'INSERT INTO PrescriptionRequest (patient,drug_name,dosage,creation) VALUES(:patient,:drug_name,:dosage,:creation);';
    $stmt = $pdo->prepare($querystring);
    $stmt->bindParam(':patient', $_POST['patient']);
    $stmt->bindParam(':drug_name', $_POST['drug_name']);
    $stmt->bindParam(':dosage', $_POST['dosage']);
    $stmt->bindParam(':creation', $_POST['creation']);
    $stmt->execute();

    echo "<div id='journal'>";
    echo "<p>Din receptförfrågan är skickad till läkare</p>";
    echo "<p>Du meddelas när ditt recept finns att hämta på närmaste apotek</p>";
    echo "<form method='POST' action='Get_Prescriptionlist.php'>";
    echo "<label>Vill du förnya ytterligare recept? </label><button onClick='window.location.reload();'>JA</button>";
    echo "<button onClick='window.location.reload();'>NEJ</button>";
    echo "</form>";
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