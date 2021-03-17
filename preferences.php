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
                $param_name = str_replace("", " ", $param_name);
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
                            <label class="checkbox"><input type="checkbox" placeholder="Amusement Park" name="amusement park"><span class="checkbox-span"> Amusement Parks</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Aquarium" name="aquarium"><span class="checkbox-span"> Aquariums</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Art Gallery" name="art gallery"><span class="checkbox-span"> Art Galleries</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Bowling Alley" name="bowling alley"><span class="checkbox-span"> Bowling Alleys</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Casino" name="casino"><span class="checkbox-span"> Casinos</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Gym" name="gym"><span class="checkbox-span"> Gyms</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Library" name="library"><span class="checkbox-span"> Libraries</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Mall" name="mall"><span class="checkbox-span"> Malls</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Movie Rental" name="movie rental"><span class="checkbox-span"> Movie Rentals</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Movie Theater" name="movie theater"><span class="checkbox-span"> Movie Theaters</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Night Club" name="night club"><span class="checkbox-span"> Night Clubs</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Park" name="park"><span class="checkbox-span"> Parks</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Stadium" name="stadium"><span class="checkbox-span"> Stadiums</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Tourist Attraction" name="tourist attraction"><span class="checkbox-span"> Tourist Attractions</span></label>
                            <label class="checkbox"><input type="checkbox" placeholder="Zoo" name="zoo"><span class="checkbox-span"> Zoos</span></label>
                            
                        </div>

                    </section>

                    <section class="food">

                        <h1 class="title">
                            Food
                        </h1>

                        <div class="choices">
                        <label class="checkbox"><input type="checkbox" placeholder="Bakery" name="bakery"><span class="checkbox-span"> Bakeries</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Bar" name="bar"><span class="checkbox-span"> Bars</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Cafe" name="cafe"><span class="checkbox-span"> Cafes</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Convenience Store" name="convenience store"><span class="checkbox-span"> Convenience Stores</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Liquor Store" name="liquor store"><span class="checkbox-span"> Liquor Stores</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Meal Deliveryk" name="meal delivery">Meal Deliveries</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Meal Takeaway" name="meal takeaway">Meal Takeaways</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Restaurant" name="restaurant"><span class="checkbox-span"> Restaurants</span></label>
                        <label class="checkbox"><input type="checkbox" placeholder="Supermarket" name="supermarket"><span class="checkbox-span"> Supermarkets</span></label>
                        
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