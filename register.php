<?php
require 'header.php'; // This imports the header so we do not have to rewrite it for every page.
?>

<?php
if (isset($_SESSION['user_id'])) { // Checks if the user is currently logged in. If they are redirect to dashboard instead.
	header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Checks to see if the current page is a GET or POST request

	require 'dbconn.php'; // Imports the database connection where we establish the PDO

	try {
		$hashedpassword = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashes the password for security purposes.
        $lowerUsername = strtolower($_POST["username"]);

		$stmt = $pdo->prepare("INSERT INTO users (username, password, name) VALUES (?, ?, ?)");
		$stmt->execute([$lowerUsername, $hashedpassword, $_POST["name"],]);

		header("location:login.php"); // Redirects to the login page once account has been created without any errors
	} catch (PDOException $e) { // Catches errors thrown by SQL and prints it to the webpage so debugging is easier. Shouldn't be executed on production.
		echo "Connection failed: " . $e->getMessage();
	}

	$pdo = null; // Clears the PDO connection
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
?>
<div class="centered-content">

        <div class="create-account-container">
            <form action="" method="post">
            <div class="field">
                <label class="label">Name</label>
                <div class="control">
                <input class="input" type="text" placeholder="Name" name="name">
                </div>
            </div>
            
            <div class="field">
                <label class="label">Username</label>
                <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Username" id="username-form" name="username">
                <span class="icon is-small is-left">
                    <i class="fas fa-user"></i>
                </span>
                </div>
            </div>

            <div class="field">
                <label class="label">Password</label>
                <p class="control has-icons-left">
                <input class="input" type="password" placeholder="Password" name="password">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                </p>
            </div>
            
            <div class="field is-grouped">
                <div class="control">
                <button type=submit id="green-text">Continue</a>
                </div>
                <div class="control">
                <a href="index.php" id="red-text">Cancel</a>
                </div>
            </div>
        </form>
    </div>

<?php
}

include 'footer.php'; // This imports the footer so we do not have to rewrite it for every page.
?>