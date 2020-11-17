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

  //$ch = curl_init($baseurl . 'api/resource/Lab%20Test/LP-00004');

  //get array of all lab tests
  $ch = curl_init($baseurl . 'api/resource/Lab%20Test');
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

  //echo print_r($response);

  //create an array of all all lab tests
  //use lab test name in url to pull out each test and check if it matches the logged-in patient
  $lengthLabTestsArr = sizeof($response['data']);
  $arr_labTests = array();
  for ($i = 0; $i < $lengthLabTestsArr; $i++) {
    //echo $response["data"][$i]["name"];
    array_push($arr_labTests, $response["data"][$i]["name"]);
  }
  //echo print_r($arr_labTests);
  echo "<table>";
  echo '<tr>';
  echo '<th>Lab test</th><th>Datum</th><th></th>';
  // create array to hold drug names for patient
  //$drugNames=array();

  foreach ($arr_labTests as $key => $testName) {
    // assign variable url to pull out each encounter
    $ch = curl_init($baseurl . 'api/resource/Lab%20Test/' . $testName);
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

    echo "<div style='background-color:yellow; border:1px solid black'>";

    $labTestInfo = array();
    // ---- TO DO ------ if loop to check patient from POST
    if ($response['data']['patient'] == 'Benny') {
      //get size of array
      //$lengthTests=(sizeof($response['data']['0']));
      //echo print_r($response['data']['drug_prescription']['0']['drug_name']);
      //for ($i = 0; $i < $lengthDrugPr; $i++) {

      array_push($labTestInfo, $response['data']['lab_test_name']);
      array_push($labTestInfo, $response['data']['practitioner']);
      array_push($labTestInfo, $response['data']['result_date']);
      // foreach ($labTestInfo as $key => $value) {
      array_push($labTestInfo, $response['data']['normal_test_items']['0']['result_value']);
      if (!empty($response['data']['normal_test_items']['0']['lab_test_uom'])) {
        array_push($labTestInfo, $response['data']['normal_test_items']['0']['lab_test_uom']);
      }
      if (!empty($response['data']['normal_test_items']['0']['normal_range'])) {
        array_push($labTestInfo, $response['data']['normal_test_items']['0']['normal_range']);
      }
      array_push($labTestInfo, $response['data']['name']);
    }

    if (!empty($labTestInfo)) {
      //echo print_r($labTestInfo);
      foreach ($labTestInfo as $labtest) {
        echo "<p>$labtest</p>";
      }
    }
  }

  echo "</div>";


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
  ?>
</pre>


</body>

</html>