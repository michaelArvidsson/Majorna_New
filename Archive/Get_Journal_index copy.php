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

  echo "<div style='background-color:lightgray; border:1px solid black'>";
  echo '$response<br><pre>';
  echo print_r($response) . "</pre><br>";
  echo "</div>";
  print_r($_POST);
  // Change HArdcoded "Benny" to Session
  if ($_POST['vidimera'] == '1') {
    $ch = curl_init($baseurl . 'api/resource/Patient%20Encounter?filters=[["patient","=","Benny"],["docstatus","=","1"]]');
  } else {
    $ch = curl_init($baseurl . 'api/resource/Patient%20Encounter?filters=[["patient","=","Benny"]]');
  }
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

  echo print_r($response);
  echo "<div style='background-color:yellow; border:1px solid black'>";
  echo "<h3>Patientjournaler för: INSERT_POST </h3>";
  echo "<table>";
  echo '<tr>';
  echo '<th>Journal</th><th>Vårdgivare</th><th>besöksdatum</th>';

  $Journalinfo = array();
  foreach ($response['data']['name'] as $journalID) {
    echo "<div id='journal'>";

    array_push($Journalinfo, $response['data']['name']);
    array_push($Journalinfo, $response['data']['practitioner_name']);
    array_push($Journalinfo, $response['data']['encounter_date']);

    echo "<tr>";
    echo "<td><a href='Get_Journal.php?journalID=" . $journalID . "'>" . $Journalinfo['0'] . "</td></a>";
    echo "<td><a href='Get_Journal.php?journalID=" . $journalID . "'>" . $Journalinfo['1'] . "</td></a>";
    echo "<td><a href='Get_Journal.php?journalID=" . $journalID . "'>" . $Journalinfo['2'] . "</td></a></tr>";
    echo '</tr>';
    //}
    //}
    echo '</tr>';
    echo "</table>";
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