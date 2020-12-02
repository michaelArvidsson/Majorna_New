<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

  <form method="POST" action="popuptestCopy_2.php" data-js-validate="true" data-js-highlight-state-msg="true" data-js-show-valid-msg="true">
    <input type="text" name="amount[]" placeholder="Amount" class="inputChangeVal" data-js-input-type="number" />
    <button class="btn" type="submit" name="confirm" id="payBtn">Pay Now</button>
  </form>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Proccess Payment</h4>
        </div>
        <div class="modal-body">
          "Your about to make a online payment. Click 'Edit' to review the data before proceeding or click 'Continue' to confirm the details for payment."
          <button class="btn btn-default" data-dismiss="modal">Edit</button>
          <button class="btn btn-primary" id="continuebtn">Continue</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php print_r($_POST); ?>

  <script>
    $('document').ready(function() {

      $('#payBtn').on('click', function(e) {
        e.preventDefault();
        $('#myModal').modal('toggle');

      });

      $('#continuebtn').on('click', function() {

        $('form').submit();
      });
    });
  </script>
</body>

</html>