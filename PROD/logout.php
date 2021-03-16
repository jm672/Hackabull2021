<?php
session_start(); // Starts a session if it hasn't already been started so there isn't an error.

session_destroy(); // Destroys the existing session so any stored variables are removed.

header("Location: index.php"); // Redirects user to the main webpage.
