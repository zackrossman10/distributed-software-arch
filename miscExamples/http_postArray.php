<!DOCTYPE html>
<html>
<body>
	<?php 
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST["uname"]) && isset($_POST["pass"])) {
				//handle the poasted data
				echo "handling user login now ... ";
				echo "... here we cound redirect or authenticate";
				echo " and hide login form or something else";
			}
		}

	 ?>


	 <h1> Some page that has a login form </h1>
	 <form action="http_postArray.php" method="POST">
	 	Name <input type ="text" name="uname"/> <br/>
	 	Pass <input type ="password" name="pass" /> <br>
	 	<input type ="submit">
	 </form>
</body>
</html>







