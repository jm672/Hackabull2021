<?php
require 'header.php'; // This imports the header so we do not have to rewrite it for every page.
?>

<link rel="stylesheet" href="resources/preferences-styles.css">

<?php
if (isset($_SESSION['user_id'])) { // Checks if the user is currently logged in. If they are redirect to dashboard instead.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Checks to see if the current page is a GET or POST request

        require 'dbconn.php'; // Imports the database connection where we establish the PDO

        try {
            $stmt = $pdo->prepare("DELETE FROM food WHERE uid = ?");
            $stmt->execute([$_SESSION["user_id"],]);
            $stmt = $pdo->prepare("DELETE FROM entertainment WHERE uid = ?");
            $stmt->execute([$_SESSION["user_id"],]);
            
            foreach ($_POST as $param_name => $param_val) {
                if ($param_name == 'bakery' || $param_name == 'bar' || $param_name == 'cafe' || $param_name == 'convenience_store' || $param_name == 'liquor_store' || $param_name == 'meal_delivery' || $param_name == 'meal_takeaway' || $param_name == 'restaurant' || $param_name == 'supermarket') {
                    $stmt = $pdo->prepare("INSERT INTO food (uid, preference) VALUES (?, ?)");
                    $stmt->execute([$_SESSION["user_id"], $param_name,]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO entertainment (uid, preference) VALUES (?, ?)");
                    $stmt->execute([$_SESSION["user_id"], $param_name,]);
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
                            
                            <input type="checkbox" placeholder="Amusement Park" name="amusement park"> <label for="amusement park">Amusement Parks</label>
                            <input type="checkbox" placeholder="Aquarium" name="aquarium"> <label for="aquarium">Aquariums</label>
                            <input type="checkbox" placeholder="Art Gallery" name="art gallery"> <label for="art gallery">Art Galleries</label>
                            <input type="checkbox" placeholder="Bowling Alley" name="bowling alley"> <label for="bowling alley">Bowling Alleys</label>
                            <input type="checkbox" placeholder="Casino" name="casino"> <label for="casinos">Casinos</label>
                            <input type="checkbox" placeholder="Gym" name="gym"> <label for="gym">Gyms</label>
                            <input type="checkbox" placeholder="Library" name="library"> <label for="library">Libraries</label>
                            <input type="checkbox" placeholder="Mall" name="mall"> <label for="mall">Malls</label>
                            <input type="checkbox" placeholder="Movie Rental" name="movie rental"> <label for="movie rental">Movie Rentals</label>
                            <input type="checkbox" placeholder="Movie Theater" name="movie theater"> <label for="movie theater">Movie Theaters</label>
                            <input type="checkbox" placeholder="Night Club" name="night club"> <label for="night club">Night Clubs</label>
                            <input type="checkbox" placeholder="Park" name="park"> <label for="park">Parks</label>
                            <input type="checkbox" placeholder="Stadium" name="stadium"> <label for="stadium">Stadiums</label>
                            <input type="checkbox" placeholder="Tourist Attraction" name="tourist attraction"> <label for="tourist attraction">Tourist Attractions</label>
                            <input type="checkbox" placeholder="Zoo" name="zoo"> <label for="zoo">Zoos</label>
                            
                        </div>

                    </section>

                    <section class="food">

                        <h1 class="title">
                            Food
                        </h1>

                        <div class="choices">
                        <input type="checkbox" placeholder="Bakery" name="bakery"> <label for="bakery">Bakeries</label>
                        <input type="checkbox" placeholder="Bar" name="bar"> <label for="bar">Bars</label>
                        <input type="checkbox" placeholder="Cafe" name="cafe"> <label for="cafe">Cafes</label>
                        <input type="checkbox" placeholder="Convenience Store" name="convenience store"> <label for="convenience_store">Convenience Stores</label>
                        <input type="checkbox" placeholder="Liquor Store" name="liquor store"> <label for="liquor store">Liquor Stores</label>
                        <input type="checkbox" placeholder="Meal Deliveryk" name="meal delivery"><label for="meal delivery">Meal Deliveries</label>
                        <input type="checkbox" placeholder="Meal Takeaway" name="meal takeaway"><label for="meal takeaway">Meal Takeaways</label>
                        <input type="checkbox" placeholder="Restaurant" name="restaurant"> <label for="restaurant">Restaurants</label>
                        <input type="checkbox" placeholder="Supermarket" name="supermarket"> <label for="supermarket">Supermarkets</label>
                        
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