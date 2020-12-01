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
  <div id='journal'>
    <span>Information om vidimerade och icke vidimerade</span>
    <p>Space, the final frontier. These are the voyages of the Starship Enterprise. Its five-year mission: to explore strange new worlds,
      to seek out new life and new civilizations, to boldly go where no man has gone before. Many say exploration is part of our destiny,
      but it’s actually our duty to future generations and their quest to ensure the survival of the human species.</p>
    <form method="post" action="getTestResult_index.php">

      <button name="vidimera" type="submit" value="1">Se endast vidimerade</button>
      <button name="vidimera" type="submit" value="0">Se alla</button>
      <!-- <input type="radio" id="Se endast vidimerade journalanteckningar" name="vidimera" value="1">Se endast vidimerade journalanteckningar<br>
      <input type="radio" id="Se alla journalanteckningar" name="vidimera" value="0">Se alla journalanteckningar<br>
      <input type="submit" name="submit" value="Gå vidare"></input> -->

    </form>
  </div>

  <?php print_r($_POST); ?>

</body>

</html>