<?php
    require_once('include/authorize.php');
    $title = 'SideKicK - Sign Up';
    require_once('structure/header.php');
    require_once('include/connect.php');

    // Connect to database
    $dbh = new PDO("mysql:host=$db_hostname;dbname=sidekick", $db_username, $db_password);

    if (isset($_GET['user_id'])) {
        // The user is viewing a different profile
        $user_id = $_GET['user_id'];
    } else {
        // The user is viewing their own profile
        $user_id = $_SESSION['user_id'];
    }

    $query = 'SELECT * FROM user_info WHERE user_id = :user_id';
    $stmt = $dbh->prepare($query);
    $stmt->execute(array('user_id' => $user_id));
    $result = $stmt->fetchAll();
    $count = $stmt->rowCount();

    if ($count == 1) {
        // Then a unique user was found
        $row = $result[0];
        if (!empty($row['username'])) {
            echo $row['username'];
        }
        if (!empty($row['birthdate'])) {
            echo $row['birthdate'];
        }
        if (!empty($row['picture'])) {
            echo '<img src="uploads/' . $row['picture'] . '"" />';
        } else {
            echo '<img src="images/nopic.png" />';
        }
    }
?>