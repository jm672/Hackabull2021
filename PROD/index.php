<?php
require 'header.php'; // This imports the header so we do not have to rewrite it for every page.
?>

<?php
if (isset($_SESSION['user_id'])) { // Checks if the user is currently logged input php in javascript. If they are redirect to dashboard instead.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Checks to see if the current page is a GET or POST request
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      
      require 'dbconn.php'; // Imports the database connection where we establish the PDO

        try { // Sets the javascript preferences variables to be used in the app.js script.
          $stmt = $pdo->prepare("SELECT preference FROM entertainment WHERE uid = ?");
          $stmt->execute([$_SESSION["user_id"],]);
		  $entertainment = $stmt->fetchAll(PDO::FETCH_OBJ);
          $entertainment_count = $stmt->rowCount();?>
		  <script type="text/javascript">
		    var entertainmentArray=<?php echo json_encode($entertainment);?>;
		  </script><?php
		  $stmt = $pdo->prepare("SELECT preference FROM food WHERE uid = ?");
          $stmt->execute([$_SESSION["user_id"],]);
		  $food = $stmt->fetchAll(PDO::FETCH_OBJ);
          $food_count = $stmt->rowCount();?>
		  <script type="text/javascript">
		    var foodArray= <?php echo json_encode($food);?>;
		  </script><?php
          if ($entertainment_count + $food_count == 0) {
              header("Location: preferences.php");
          }
        } catch (PDOException $e) { // Catches errors thrown by SQL and prints it to the webpage so debugging is easier.
            echo "Connection failed: " . $e->getMessage();
        }

        $pdo = null; // Clears the PDO connection
        ?>

        <div id="map"></div>

    <div class="top-right-menu">
        <a href="index.php"><img src="resources/images/party-baloons.png" alt="" id="logo-dashboard"></a>
        <a href="#" onclick="navSlide()" id="toggle-sidebar">Toggle Menu</a>
        <a href="preferences.php" id="preferences">Preferences</a>
        <a href="logout.php" id="logout">Logout</a>
    </div>

    <div class="nav-bar">
        
        <section class="col">

            <h1 class="title">
                Places to eat
            </h1>
            <div id="foodCards"></div>
           
        </section>

        <section class="col">

            <h1 class="title">
                Places to visit
            </h1>
            <div id="funCards"></div>
            
        </section>

        <section class="col">

            <h1 class="title">
                Go Loco!
            </h1>


            <div id="randomCards"></div>
        </section>

    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkned5ZWpqlTpQUxkT8CFlQpLTah5Ardk&callback=initMap&libraries=visualization,places&v=weekly" async></script>

<?php
    }

    include 'footer.php'; // This imports the footer so we do not have to rewrite it for every page.

} else {
    header("Location: login.php");
}
?>