<?php
// Include the ShoppingCart class.  Since the session contains a
// ShoppingCard object, this must be done before session_start().
require "../application/cart.php";
session_start();
?>

<!DOCTYPE html>

<?php
// If this session is just beginning, store an empty ShoppingCart in it.
  if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = new ShoppingCart();
  }

  //validate the girl-_scout form
  function moreThan3($input){
    $val = str_split($input);
    return sizeof($val) >= 3;
  }

  //validate the zip code
  function isZip($input){
    $acceptableNum = str_split("1234567890");
    $val = str_split($input);
    for($i=0;$i<sizeof($val); $i++){
      if(!in_array($val[$i], $acceptableNum)){
        return false;
      }
    }
    return sizeof($val) == 5;
  }

  //check that each char of the value is alphabetical
  function isAlphabetical($input){
    $acceptableChar = str_split("abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ");
    $val = str_split($input);
    for($i = 0; $i<sizeOf($val); $i++){
      if(!in_array($val[$i], $acceptableChar)){
        return false;
      }
    }
    return true;
  }

  //check if field is non-empty
  function isNonEmpty($input){
    return $input != "";
  }

  function isPhone($input){
    $acceptableNum = str_split("1234567890");
    $val = str_split($input);
    if(sizeOf($val) != 10){
      return false;
    }
    for($i = 0; $i<sizeOf($val); $i++){
      if(!in_array($val[$i], $acceptableNum)){
        return false;
      }
    }
    return true;
  }

  //server-side validation
  if($_SERVER["REQUEST_METHOD"] =="POST"){
    $clean = true;
    //all the name of all fields we need to validate
    $checkFields = Array('name', 'city', 'state', 'scout_name', 'street', 'zip', 'email',
      'phone', 'troop_name');
    //validate each field based on its name
    foreach($checkFields as $index => $field){
      $userInput = $_POST[$field];
      if($field == 'name' || $field == 'city' || $field == 'state' || $field == 'troop_name'){
        if(!isAlphabetical($userInput)){
          echo '<span class = "info">'.$field.' field must have only alphabetical chars</span>';
          echo '<br><br>';
          $clean = false;
        }
      }else if($field == 'street'){
        if(!isNonEmpty($userInput)){
          echo '<span class = "info">Street field cannot be empty</span>';
          echo '<br><br>';
          $clean = false;
        }
      }else if($field == 'zip'){
        if(!isZip($userInput)){
          echo '<span class = "info">Zip is invalid</span>';
          echo '<br><br>';
          $clean = false;
        }
      }else if($field == 'email'){
        if(!filter_var($userInput, FILTER_VALIDATE_EMAIL)){
          echo '<span class = "info">Email is invalid</span>';
          echo '<br><br>';
          $clean = false;
        }
      }else if($field == 'phone'){
        if(!isPhone($userInput)){
          echo '<span class = "info">Phone number is invalid</span>';
          echo '<br><br>';
          $clean = false;
        }
      }else if($field == 'scout_name'){
        if(!moreThan3($userInput)){
          echo '<span class = "info">Scout name myst be more than 3 chars</span>';
          echo '<br><br>';
          $clean = false;
        }
      }
    }

    //if all fields are correct, post to the database, allow user to shop for more and then unset/destroy the session
    if($clean){
      echo '<p id = "more">Your credit card will be billed.  Thanks for the order!</p>';
      echo '<p id = "more"><a href="index4.php">Shop some more!</a></p>';
      echo '<p id = "more"><a href="reports.php">See MySQL Reports!</a></p>';

      //connected ot GSC databse
      $db_connection = new mysqli("localhost", "root", "root", "GSC") or
                        die("connect failed: ".mysqli_connect_error());

      //insert new Girl Scout to GirlScout table only if they don't already exist
      $girl_scout = mysqli_escape_string($db_connection, $_POST['scout_name']);
      $troop_name = mysqli_escape_string($db_connection, $_POST['troop_name']);

      //check if girl scout already exists in DB
      $result = mysqli_query($db_connection, "SELECT * FROM GirlScout WHERE name = '$girl_scout'");

      if(mysqli_num_rows($result)==0){
        $sql = "INSERT INTO GirlScout (gsid, name, troop_name) VALUES (0, '$girl_scout', '$troop_name')";
        if($result = mysqli_query($db_connection, $sql)){
          echo "--New record in GirlScout--";
        }else{
          echo "--Error inserting to GirlScout--";
        }
      }else{
        echo "--Duplicate in GirlScout--";
      }

      //insert new customer to Customer table only if they don't already exist
      $customer_name = mysqli_escape_string($db_connection, $_POST['name']);
      $street = mysqli_escape_string($db_connection, $_POST['street']);
      $state = mysqli_escape_string($db_connection, $_POST['state']);
      $zip = mysqli_escape_string($db_connection, $_POST['zip']);
      $city = mysqli_escape_string($db_connection, $_POST['city']);

      //check if Customer already exists in db
      $result = mysqli_query($db_connection, "SELECT * FROM Customer WHERE name = '$customer_name'");

      if(mysqli_num_rows($result)==0){
        $sql = "INSERT INTO Customer (cid, name, street_address, city, state, zip) VALUES (0, '$customer_name', '$street', '$city', '$state', '$zip')";
        if(mysqli_query($db_connection, $sql)){
          echo "New record in Customer--";
        }else{
          echo "Error inserting to Customer--";
        }
      }else{
        echo "Duplicate in Customer--";
      }

      //get info about the gsid and cid associated with the order
      $result = mysqli_query($db_connection, "SELECT gsid FROM GirlScout WHERE name = '$girl_scout'");
      $gsid = mysqli_fetch_assoc($result)['gsid'];

      $result = mysqli_query($db_connection, "SELECT cid FROM Customer WHERE name = '$customer_name'");
      $cid = mysqli_fetch_assoc($result)['cid'];

      //insert new Order into Orders table
      $sql = "INSERT INTO  Orders (gsid, orderid, cid) VALUES ($gsid, 0, $cid)";
      if($result = mysqli_query($db_connection, $sql)){
        echo "New order in Orders--";

        //insert Cookies associated with the order into table Cookies by passing a PDO to a function of the Shopping Cart class
        $stmt = $db_connection->prepare("INSERT INTO Cookies (orderid, type, quantity, price) VALUES (LAST_INSERT_ID(), ?, ?, ?);");

        $sql = $_SESSION['cart']->getQuery($stmt);

      }else{
        echo "Error inserting to Orders--";
      }

      session_unset();  // remove all session variables
      session_destroy(); //destroy session
    }
  }

?>

<html lang="en">

<head>
<title>Checkout</title>
<link rel="stylesheet" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="valgirlscout.js"></script>
</head>

<body>
  <h2>Checkout</h2>

  <!-- form to collect customer informatino to store in DB -->
  <fieldset id = 'customer_info'>
    <form action = "checkout.php" method = 'POST' >
      <h4>Customer Info</h4>

      <legend name = 'name'><label>Name</label>
      <input type = "text" name = "name" value = "<?php
        if(isset($_POST['name'])){
          echo $_POST['name'];
        }else{
          echo '';
        }?>"/>
      </legend>

      <legend name = 'street'><label>Street</label>
      <input type = 'text' name = 'street' value = "<?php
        if(isset($_POST['street'])){
          echo $_POST['street'];
        }else{
          echo '';
        }?>"/>
      </legend>

      <legend name = 'city'><label>City</label>
      <input type = 'text' name = 'city' value = "<?php
        if(isset($_POST['city'])){
          echo $_POST['city'];
        }else{
          echo '';
        }?>"/>
      </legend>

      <legend name = 'state'><label>State</label>
      <input type = 'text' name = 'state' value = "<?php
        if(isset($_POST['state'])){
          echo $_POST['state'];
        }else{
          echo '';
        }?>" onkeyup = "ajaxSuggest(this.value)"/>
      </legend>
      <p id = 'suggestion'></p>
      </br>

      <legend name = 'zip'><label>Zip Code</label>
      <input type = 'text' name = 'zip' value = "<?php
      if(isset($_POST['zip'])){
        echo $_POST['zip'];
      }else{
        echo '';
      }?>"/>
    </legend>

      <legend name = 'email'><label>Email</label>
      <input type = 'text' name = 'email' value = "<?php
        if(isset($_POST['email'])){
          echo $_POST['email'];
        }else{
          echo '';
        }?>"/>
    </legend>

      <legend name = 'phone'><label>Phone</label>
      <input type = 'text' name = 'phone' value = "<?php
        if(isset($_POST['phone'])){
          echo $_POST['phone'];
        }else{
          echo '';
        }?>"/>
      </legend>

      <h4>Girl Scout Info</h4>
      <legend name = 'scout_name'><label>Girl Scout Name</label>
      <input type = 'text' name = 'scout_name' value = "<?php
        if(isset($_POST['scout_name'])){
          echo $_POST['scout_name'];
        }else{
          echo '';
        }?>"/>
      </legend></legend>

      <legend name = 'troop_name'><label>Troop Name</label>
      <input type = 'text' name ='troop_name' value = "<?php
        if(isset($_POST['troop_name'])){
          echo $_POST['troop_name'];
        }else{
          echo '';
        }?>"/>
      </legend>

      <input id = 'submit' type = 'submit' name = 'submit' onclick = "test()"/>
    </form>
  </fieldset>

  <table id = 'order'>
    <?php
    //display order nicely if cart is set
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart']->displayOrderNice();
    }
  ?></table>
</body>
<script>

  //JS function for suggesting input using AJAX
  function ajaxSuggest(val){
    console.log(val);
    $.ajax({
        method: "POST",
        url: "suggestions.php",
        data: {'suggest': val}
    })
    .done(function(msg){
      $('#suggestion').html('<strong>Suggestions: </strong> '+msg);
    });
  }

</script>
</html>
