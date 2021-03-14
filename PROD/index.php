<?php
require 'header.php'; // This imports the header so we do not have to rewrite it for every page.
?>

<link rel="stylesheet" href="resources/dashboard-styles.css">
<title>Dashboard</title>

<?php
if (isset($_SESSION['user_id'])) { // Checks if the user is currently logged in. If they are redirect to dashboard instead.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Checks to see if the current page is a GET or POST request
    
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      
      require 'dbconn.php'; // Imports the database connection where we establish the PDO

        try {
          $stmt = $pdo->prepare("SELECT preference FROM entertainment WHERE uid = ?");
          $stmt->execute([$_SESSION["user_id"],]);
          $values = $stmt->fetch(PDO::FETCH_ASSOC);
		      $entertainment = $stmt->fetchAll();

		      
          header("location:index.php");
        } catch (PDOException $e) { // Catches errors thrown by SQL and prints it to the webpage so debugging is easier. Shouldn't be executed on production.
            echo "Connection failed: " . $e->getMessage();
        }

        $pdo = null; // Clears the PDO connection
    
?>


        <div id="map"></div>
        <script src="resources/app.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkned5ZWpqlTpQUxkT8CFlQpLTah5Ardk&callback=initMap&libraries=visualization,places&v=weekly" async></script>

    <div class="top-right-menu">
        <img src="resources/images/party-baloons.png" alt="" id="logo">
        <a href="#" onclick="navSlide()" id="toggle-sidebar">Open Menu</a>
        <a href="preferences.php" id="preferences">Preferences</a>
        <a href="logout.php" id="logout">Logout</a>
    </div>

    <div class="nav-bar">
        
        <section class="col-1">

            <h1 class="title">
                Places to eat
            </h1>
            <div id="foodCards"></div>
            <!--
            <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/rest1.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/rest2.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/nyc.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>

              
          -->
        </section>

        <section class="col-2">

            <h1 class="title">
                Places to visit
            </h1>
            <div id="funCards"></div>
            <!--
            <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/park1.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Park</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/rest2.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/nyc.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>
              
              -->
        </section>

        <section class="col-3">

            <h1 class="title">
                Go Loco!
            </h1>


            <div id="randomCards"></div>
          <!--
            <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/rest1.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/park1.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="images/park2.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>
          -->
        </section>

    </div>

<?php
    }

    include 'footer.php'; // This imports the footer so we do not have to rewrite it for every page.

} else {
    header("Location: login.php");
}
?>