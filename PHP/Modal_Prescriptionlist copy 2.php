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

    /* The Modal (background) */
    .modal {
      display: none;
      /* Hidden by default */
      position: fixed;
      /* Stay in place */
      z-index: 1;
      /* Sit on top */
      padding-top: 50px;
      /* Location of the box */
      left: 0;
      top: 0;
      width: 100%;
      /* Full width */
      height: 100%;
      /* Full height */
      overflow: auto;
      /* Enable scroll if needed */
      background-color: rgb(0, 0, 0);
      /* Fallback color */
      background-color: rgba(0, 0, 0, 0.4);
      /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 40%;
      box-shadow: 0, 0, 3, 5;
    }

    /* The Close Button */
    /*    .close {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    } */

    .close:hover,
    .close:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
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
        echo "<span>" . $prescription['comment'] . "</span><br>";
      }
      echo "<button id='myBtn' style='margin-top:5px;'>Förnya</button>";
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
  <!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <!-- <span class="close">&times;</span> -->
    <?php
    echo "<h3 style='text-align:center;'>Din receptförfrågan är skickad till läkare</h3>";
    echo "<h3 style='text-align:center;'>Du meddelas när ditt recept finns att hämta på närmaste apotek</h3>";
    echo "<form method='POST' action='Modal_Prescriptionlist copy 2.php'>";
    echo "<input type=hidden name='drug_name' value=" . $prescription['drug_name'] . "/>";
    echo "<input type=hidden name='dosage' value=" . $prescription['dosage'] . "/>";
    echo "<input type=hidden name='creation' value=" . $prescription['creation'] . "/>";
    echo "<input style='padding:0px; margin-top:10px; margin:auto;' type=submit name=submit value=Stäng recept>";
    echo "</form></td>";
    ?>
  </div>

</div>

</pre>
  <script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    //var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
      modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>


</body>

</html>