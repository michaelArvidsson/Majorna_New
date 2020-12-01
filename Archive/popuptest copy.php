<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Majorna vårdcentral</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: lightgrey;
      background-size: cover;
      height: 100vh;
    }

    h1 {
      text-align: center;
      font-family: Tahoma, Arial, sans-serif;
      color: #06D85F;
      margin: 80px 0;
    }
  </style>

</head>

<body>
  <h1>välkommen till Majornas Vårdcentral</h1>

  <h1>Popup/Modal Windows without JavaScript</h1>
  <div class="box">
    <form method="POST" action="popuptest copy.php" onsubmit="myFunction()">
      Enter name: <input type="text" name="fname">
      <input type="submit" value="Submit">
    </form>
    <!-- <form action="popuptest.php" method="POST">
      <a class="button" href="#popup1">
        <button type="submit" value="submit" name="submit">Visit Google</button>
      </a>
    </form> -->
  </div>

  <!-- <div id="popup1" class="overlay">
    <div class="popup">
      <h2>Here i am</h2>
      <a class="close" href="#">&times;</a>
      <div class="content">
        Thank to pop me out of that button, but now i'm done so you can close this window.
      </div>
    </div>
  </div> -->
  <script>
    function myFunction() {
      //alert("The form was submitted");
      alert("My alert message body", "Alert Title");
    }
  </script>
  <?php print_r($_POST); ?>
</body>

</html>