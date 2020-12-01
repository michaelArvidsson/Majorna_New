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

    h4 {
      margin-top: 5px;
      margin-bottom: 5px;
      text-decoration: underline;
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

      margin: auto;
      margin-bottom: 10px;
      background-color: yellow;
      border: 1px solid black;
      padding: 5px;
      padding-left: 15px;
      width: 600px;

    }

    #journal2 {

      margin: auto;
      margin-bottom: 10px;
      background-color: lightgreen;
      border: 1px solid black;
      padding: 5px;
      padding-left: 15px;
      width: 600px;

    }

    .hover_bkgr_fricc {
      background: rgba(0, 0, 0, .4);
      cursor: pointer;
      display: none;
      height: 100%;
      position: fixed;
      text-align: center;
      top: 0;
      width: 100%;
      z-index: 10000;
    }

    .hover_bkgr_fricc .helper {
      display: inline-block;
      height: 100%;
      vertical-align: middle;
    }

    .hover_bkgr_fricc>div {
      background-color: #fff;
      box-shadow: 10px 10px 60px #555;
      display: inline-block;
      height: auto;
      max-width: 551px;
      min-height: 100px;
      vertical-align: middle;
      width: 60%;
      position: relative;
      border-radius: 8px;
      padding: 15px 5%;
    }

    .popupCloseButton {
      background-color: #fff;
      border: 3px solid #999;
      border-radius: 50px;
      cursor: pointer;
      display: inline-block;
      font-family: arial;
      font-weight: bold;
      position: absolute;
      top: -20px;
      right: -20px;
      font-size: 25px;
      line-height: 30px;
      width: 30px;
      height: 30px;
      text-align: center;
    }

    .popupCloseButton:hover {
      background-color: #ccc;
    }

    .trigger_popup_fricc {
      cursor: pointer;
      font-size: 20px;
      margin: 20px;
      display: inline-block;
      font-weight: bold;
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

  if (isset($_POST['submit'])) {
    $pdo = new PDO('mysql:dbname=a18micar_dbk2020;host=localhost', 'sqllab', 'Tomten2009');

    $querystring = 'INSERT INTO PrescriptionRequest (patient,drug_name,dosage,creation) VALUES(:patient,:drug_name,:dosage,:creation);';
    $stmt = $pdo->prepare($querystring);
    $stmt->bindParam(':patient', $_POST['patient']);
    $stmt->bindParam(':drug_name', $_POST['drug_name']);
    $stmt->bindParam(':dosage', $_POST['dosage']);
    $stmt->bindParam(':creation', $_POST['creation']);
    $stmt->execute();

    echo "<div id='journal2'>";
    echo "<p>Din receptförfrågan är skickad till läkare</p>";
    echo "<p>Du meddelas när ditt recept finns att hämta på närmaste apotek</p>";
    echo "<form method='POST' action='Get_Prescriptionlist copy.php'>";
    echo "<label>Vill du förnya ytterligare recept? </label><button onClick='window.location.reload();'>JA</button>";
    echo "<button onClick='window.location.reload();'>NEJ</button>";
    echo "</form>";
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
    //echo $response["data"][$i]["name"];
    array_push($arr_encounter, $response["data"][$i]["name"]);
  }
  //echo print_r($arr_encounter);

  // create array to hold drug names for patient
  $drugNames = array();
  $prescription;

  foreach ($arr_encounter as $key => $value) {
    echo "<div id='journal'>";

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
        echo "<h4>Medicin:</h4>";
        echo "<span>" . $prescription['drug_name'] .  "</span>";
        echo "<span>&nbsp;</span>";
        echo "<span>" . $prescription['dosage_form'] . "</span>";
        echo "<h4>Dosering:</h4>";
        echo "<span>" . $prescription['dosage'] . "</span>";
        echo "<h4>Utskrivet av</h4>";
        echo "<span>Julia Isaias</span>";
        echo "<h4>Totalt förskrivna Uttag - Giltig t.o.m:</h4>";
        echo "<span>" . $prescription['comment'] . "</span>";
        //echo "<td>" . substr($prescription['creation'], 0, 11) . "</td>";
        echo "<a class='trigger_popup_fricc'>Click here</a>";
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
  <a class="trigger_popup_fricc">Click here to show the popup</a>

  <div class="hover_bkgr_fricc">
    <span class="helper"></span>
    <div>
      <div class="popupCloseButton">&times;</div>
      <p>Add any HTML content<br />inside the popup box!</p>
    </div>
  </div>


</body>
<script>
  (window).load(function() {
    (".trigger_popup_fricc").click(function() {
      ('.hover_bkgr_fricc').show();
    });
    ('.hover_bkgr_fricc').click(function() {
      ('.hover_bkgr_fricc').hide();
    });
    ('.popupCloseButton').click(function() {
      ('.hover_bkgr_fricc').hide();
    });
  });
</script>

</html>