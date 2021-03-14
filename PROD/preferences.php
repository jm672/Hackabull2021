<?php
require 'header.php'; // This imports the header so we do not have to rewrite it for every page.
?>

<link rel="stylesheet" href="resources/preferences-styles.css">
<title>Preferences</title>

<?php
if (isset($_SESSION['user_id'])) { // Checks if the user is currently logged in. If they are redirect to dashboard instead.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Checks to see if the current page is a GET or POST request

        require 'dbconn.php'; // Imports the database connection where we establish the PDO

        try {
            foreach ($_POST as $param_name => $param_val) {
                if ($param_name == 'bakery' || $param_name == 'bar' || $param_name == 'cafe' || $param_name == 'convenience_store' || $param_name == 'liquor_store' || $param_name == 'meal_delivery' || $param_name == 'meal_takeaway' || $param_name == 'restaurant' || $param_name == 'supermarket') {
                    $stmt = $pdo->prepare("INSERT INTO food (uid, preference, value) VALUES (?, ?, ?)");
                    $stmt->execute([$_SESSION["user_id"], $param_name, true,]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO entertainment (uid, preference, value) VALUES (?, ?, ?)");
                    $stmt->execute([$_SESSION["user_id"], $param_name, true,]);
                }
            } header("location:index.php");
        } catch (PDOException $e) { // Catches errors thrown by SQL and prints it to the webpage so debugging is easier. Shouldn't be executed on production.
            echo "Connection failed: " . $e->getMessage();
        }

        $pdo = null; // Clears the PDO connection
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

        <div class="centered-content">

            <div class="preferences-container">

                <form action="" method="post">

                    <section class="entertainment">

                        <h1 class="title">
                            Entertainment
                        </h1>

                        <div class="choices">

                            <input type="checkbox" placeholder="Amusement Park" name="amusement_park" value="true"> <label for="amusement_park">Amusement Parks</label>
                            <input type="checkbox" placeholder="Aquarium" name="aquarium" value="true"> <label for="aquarium">Aquariums</label>
                            <input type="checkbox" placeholder="Art Gallery" name="art_gallery" value="true"> <label for="art_gallery">Art Galleries</label>
                            <input type="checkbox" placeholder="Bowling Alley" name="bowling_alley" value="true"> <label for="bowling_alley">Bowling Alleys</label>
                            <input type="checkbox" placeholder="Casino" name="casino" value="true"> <label for="casinos">Casinos</label>
                            <input type="checkbox" placeholder="Gym" name="gym" value="true"> <label for="gym">Gyms</label>
                            <input type="checkbox" placeholder="Library" name="library" value="true"> <label for="library">Libraries</label>
                            <input type="checkbox" placeholder="Mall" name="mall" value="true"> <label for="mall">Malls</label>
                            <input type="checkbox" placeholder="Movie Rental" name="movie_rental" value="true"> <label for="movie_rental">Movie Rentals</label>
                            <input type="checkbox" placeholder="Movie Theater" name="movie_theater" value="true"> <label for="movie_theater">Movie Theaters</label>
                            <input type="checkbox" placeholder="Night Club" name="night_club" value="true"> <label for="night_club">Night Clubs</label>
                            <input type="checkbox" placeholder="Park" name="park" value="true"> <label for="park">Parks</label>
                            <input type="checkbox" placeholder="Stadium" name="stadium" value="true"> <label for="stadium">Stadiums</label>
                            <input type="checkbox" placeholder="Tourist Attraction" name="tourist_attraction" value="true"> <label for="tourist_attraction">Tourist Attractions</label>
                            <input type="checkbox" placeholder="Zoo" name="zoo" value="true"> <label for="zoo">Zoos</label>
                        </div>

                    </section>

                    <section class="food">

                        <h1 class="title">
                            Food
                        </h1>

                        <div class="choices">
                        <input type="checkbox" placeholder="Bakery" name="bakery" value="true"> <label for="bakery">Bakeries</label>
                        <input type="checkbox" placeholder="Bar" name="bar" value="true"> <label for="bar">Bars</label>
                        <input type="checkbox" placeholder="Cafe" name="cafe" value="true"> <label for="cafe">Cafes</label>
                        <input type="checkbox" placeholder="Convenience Store" name="convenience_store" value="true"> <label for="convenience_store">Convenience Stores</label>
                        <input type="checkbox" placeholder="Liquor Store" name="liquor_store" value="true"> <label for="liquor_store">Liquor Stores</label>
                        <input type="checkbox" placeholder="Meal Deliveryk" name="meal_delivery" value="true"> <label for="meal_delivery">Meal Deliveries</label>
                        <input type="checkbox" placeholder="Meal Takeaway" name="meal_takeaway" value="true"> <label for="meal_takeaway">Meal Takeaways</label>
                        <input type="checkbox" placeholder="Restaurant" name="restaurant" value="true"> <label for="restaurant">Restaurants</label>
                        <input type="checkbox" placeholder="Supermarket" name="supermarket" value="true"> <label for="<supermarket">Supermarkets</label>
                      </div>

                        <div class="field is-grouped" id="group-button">
                            <div class="control">
                                <button type=submit id="green-text">Continue</a>
                            </div>
                        </div>

                    </section>

                </form>

            </div>

        </div>

<?php
    }

    include 'footer.php'; // This imports the footer so we do not have to rewrite it for every page.

} else {
    header("Location: login.php");
}
?>