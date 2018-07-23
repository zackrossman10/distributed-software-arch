<?php
// Represents the shopping cart for a single session.
class ShoppingCart {

    // List of products that is used to generate the HTML menu.
    public static $cookieTypes = Array("thinmints" => "Thin Mints",
                                       "samoas" => "Samoas",
                                       "trefoils" => "Trefoils",
                                       "lemoncreme" => "Lemon Chalet Cremes",
                                       "dosidos" => "Do-Si-Dos",
                                       "dulce" => "Dulce de Leche",
                                       "thanks" => "Thank U Berry Munch",
                                       "tagalongs" => "Tagalongs"
                                       );

    // The array that contains the order
    private $order;

    // Initially, the cart is empty
    public function __construct() {
        $this->order = Array();
    }

    // Adds an order to the shopping cart.
    public function order($variety, $quantity) {
        $currentQuantity = $this->order[$variety];
        $currentQuantity += $quantity;
        $this->order[$variety] = $currentQuantity;
    }

    // Display the order for debugging purposes.
    public function display() {
        print_r($this->order);
    }

    //display cart as a table for "Viewcart" page
    public function displayCartNice(){
      global $totalQuantity, $totalPrice;
      $totalQuantity = 0;
      $totalPice = 0;
      $variety = "";
      //create table header
      echo "<tr><th>Picture</th>
                <th>Cookie</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Edit</th></tr>";
      foreach($this->order as $variety => $quantity){
        $price = $quantity *5;
        $totalPrice += $price;
        $totalQuantity += $quantity;
        $formal_name = ShoppingCart::$cookieTypes[$variety];
        echo "<tr><td class = image><img src = 'cookies/$variety.jpg'></td>
                  <td class = 'variety'>$formal_name</td>
                  <td class = 'quantity'>$quantity</td>
                  <td class = 'price'>$$price</td>
                  <td class='alterations'><form method = 'post'>
                  <input class= 'edit' type = 'text' name = '$variety'>
                  <br></br>
                  <input class= 'delete' type = 'submit' name = '$variety' value = 'delete'>
                  </form>
                  </td></tr>";
      }
      echo "<tr><td><strong>Total</strong></td>
                <td></td>
                <td><strong>$totalQuantity</strong></td>
                <td><strong>$$totalPrice</strong></td>
                <td><form method='post'>
                <input class= 'update' type = 'submit' value = 'update'></td>
                </form>";
    }

    public function changeQuantity($variety, $quantity){
      if($quantity == 0){
        unset($this->order[$variety]);
      }else{
        $this->order[$variety] = $quantity;
      }
    }

    //display the finalized order nicely for the "checkout" page
    public function displayOrderNice(){
      global $totalQuantity, $totalPrice;
      $totalQuantity = 0;
      $totalPice = 0;
      $variety = "";
      //create table header
      foreach($this->order as $variety => $quantity){
        $price = $quantity *5;
        $totalPrice += $price;
        $totalQuantity += $quantity;
        $formal_name = ShoppingCart::$cookieTypes[$variety];
        echo "<tr><td class = image><img src = 'cookies/$variety.jpg'></td>
                  <td class = 'variety'>$formal_name</td>
                  <td class = 'quantity'>$quantity</td>
                  <td class = 'price'>$$price</td></tr>";

      }
      echo "<tr><td><strong>Total</strong></td>
                <td></td>
                <td><strong>$totalQuantity</strong></td>
                <td><strong>$$totalPrice</strong></td>
                </form>";
    }

    //get the query string(s) for the Cookies table
    public function getQuery($stmt){
      //accumulator string
      $stmt->bind_param("sii", $type, $quantity, $price);
      $sql = "";
      //Create an insert statement for each cookie in the order

      foreach($this->order as $variety => $quantity1){
        $price = $quantity1*5;
        // $sql .= "INSERT INTO Cookies (orderid, type, quantity, price) VALUES (LAST_INSERT_ID(), '$variety', $quantity, $price);";
        $type = $variety;
        $quantity = $quantity1;
        $stmt->execute();
      }
      return $sql;
    }

}
?>
