<?php
// Include the ShoppingCart class.  Since the session contains a
// ShoppingCard object, this must be done before session_start().
require "../application/cart.php";
session_start();
print_r($_SESSION);
echo "<br>after starting a session in viewcart...";
?>

<!DOCTYPE html>

<?php
// If this session is just beginning, store an empty ShoppingCart in it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new ShoppingCart();
}

if(isset($_POST['variety']) && isset($_POST['quantity'])){
  if($_POST['quantity']>=0){
    $_SESSION['cart']->changeQuantity($_POST['variety'], $_POST['quantity']);
  }
}

?>

<html lang="en">

<head>
<title>Girl Scout Cookie Shopping Cart</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Girl Scout Cookie Shopping Cart</h2>

<table>
<?php

$_SESSION['cart']->displayCartNice();

?></table>

<p><a href="index4.php">Resume shopping</a></p>

<p><a href="checkout.php">Check out</a></p>

</body>
<script>
  $(document).ready(function(){

    //update the cookie to the given quantity
    $('.update').click(function(){
      $('.edit').each(function(){
        var variety = $(this).attr('name');
        var quantity = $(this).val();
        var carturl = 'viewcart.php';
        console.log(quantity);
        var data = {'variety': variety, 'quantity':quantity};
        //only post if there was a change made
        if(quantity !== ''){
          $.post(carturl, data);
        }
      });
    });

    //delete the item whose delete function was pressed
    $('.delete').click(function(){
      var variety = $(this).attr('name');
      var carturl = 'viewcart.php';
      var data = {"variety": variety, 'quantity':0}
      $.post(carturl, data);
    })
  });
</script>
</html>
