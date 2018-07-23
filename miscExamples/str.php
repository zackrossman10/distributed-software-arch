<?php

$firstName = "Pablo";
$lastName = "Picasso";

/*
Example one:
These two lines are equivalent. Notice that you can reference PHP variables within a string literal defined with double quotes. The resulting output for both lines is: <em>Pablo Picasso</em>
*/

echo "<em>" . $firstName . " ". $lastName. "</em>";
echo "<em> $firstName $lastName </em>";

?>