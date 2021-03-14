<?php
require 'header.php'; // This imports the header so we do not have to rewrite it for every page.
?>

<title>Login</title>

<?php
if (isset($_SESSION['user_id'])) { // Checks if the user is currently logged in. If they are redirect to dashboard instead.
	header("Location: dashboard.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require 'dbconn.php'; // Imports the database connection where we establish the PDO

	try {
		$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->execute([$_POST["username"]]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$count = $stmt->rowCount();

		if ($count > 0) { // Checks to see if the user has been found on the database.
			if (password_verify($_POST["password"], $user["password"])) { // Checks to see if the password on the form is the same as the hashed password on the database.
				$_SESSION["user_id"] = $user["uid"]; // Sets the user's id so its easy to keep track of
				$_SESSION["firstname"] = $user["firstname"]; // Sets the user's firstname so we can welcome them on the dashboard.
				if ($user["reset"] == true) {
				    $_SESSION["reset"] == true;
					header("location:preferences.php"); 		// Should direct user to preference page if first time or dashboard if not
				}
				header("location:index.php");
			} else {
				$message = 'Credentials are invalid.<br>Please try again.';
			}
		} else {
			$message = 'Credentials are invalid.<br>Please try again.';
		}
	} catch (PDOException $e) { // Catches errors thrown by SQL and prints it to the webpage so debugging is easier. Shouldn't be executed on production.
		echo "Connection failed: " . $e->getMessage();
	}
	
	if (isset($message)) { // Catches messages we want to send to the user.
	echo '<div class="msgbox">';
	echo '<label style="color:red;">' . $message . '</label>';
	echo '</div>';
	}

	$pdo = null; // Clears the PDO connection
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	?>
	<div class="centered-content">

        <form action="" method="post"> 
            <div class="field">
                <p class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Username" name="username">
                <span class="icon is-small is-left">
                    <i class="fas fa-user"></i>
                </span>
                </p>
            </div>
            <div class="field">
                <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Password" name="password">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                </p>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button type="submit" id="green-text">Login</a>
                </div>
                <div class="control">
                    <a href="register.php" id="blue-text">Create Account</a>
                </div>
            </div>
        </form>

<?php

}

include 'footer.php'; // This imports the footer so we do not have to rewrite it for every page.
?>