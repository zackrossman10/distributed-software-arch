<?php
  $db_connection = new mysqli("localhost", "root", "root", "GSC") or
                    die("connect failed: ".mysqli_connect_error());
 ?>

<!DOCTYPE html>
  <p>Girl Scout with most referrals:<strong>
    <?php
      //get name of girl scout who is responsible for most orders
      $most_referrals_query = "SELECT G.name
                         FROM GirlScout G
                         INNER JOIN Orders O ON G.gsid = O.gsid
                         GROUP BY O.gsid
                         ORDER BY count(*) DESC
                         LIMIT 1;";
      $result = mysqli_query($db_connection, $most_referrals_query);
      $most_referrals = mysqli_fetch_assoc($result)['name'];
      echo $most_referrals;
     ?>
  </strong></p>
  <br>
  <p>Customer who has made most orders:<strong>
    <?php
      $most_orders_query = "SELECT name
                            FROM Customer C
                            INNER JOIN Orders O ON C.cid = O.cid
                            GROUP BY O.cid
                            ORDER BY count(*) DESC
                            LIMIT 1;";
      $result = mysqli_query($db_connection, $most_orders_query);
      $most_orders = mysqli_fetch_assoc($result)['name'];
      echo $most_orders;
     ?>
  </strong></p>
  <br>
  <p>Customer who has ordered most cookies:<strong>
    <?php
      $most_cookies_query = "SELECT name
                      FROM Customer C
                      INNER JOIN Orders O ON C.cid = O.cid
                      INNER JOIN Cookies K ON K.orderid = O.orderid
                      GROUP BY O.cid
                      ORDER BY SUM(K.quantity) DESC
                      LIMIT 1;";
      $result = mysqli_query($db_connection, $most_cookies_query);
      $most_cookies = mysqli_fetch_assoc($result)['name'];
      echo $most_cookies;
     ?>
  </strong></p>
  <br>
  <p>Most popular cookie:<strong>
    <?php
      $popular_cookie_query = "SELECT type
                         FROM Cookies
                         GROUP BY type
                         ORDER BY SUM(quantity) DESC
                         LIMIT 1;";
      $result = mysqli_query($db_connection, $popular_cookie_query);
      $popular_cookie = mysqli_fetch_assoc($result)['type'];
      echo $popular_cookie;
     ?>
  </strong></p>
  <br>
