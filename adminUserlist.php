<?php
require_once('include/start_session.php');
$title = 'SideKicK - Sign Up';
require_once('structure/header.php');
require_once('include/connect.php');

// Make sure the user is logged in before going any further.
if (!isset($_SESSION['user_id'])) {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/sign_in.php';
    header('Location: ' . $home_url);
    exit();
}

// Connect to database
$dbh = new PDO("mysql:host=$db_hostname;dbname=sidekick", $db_username, $db_password);

if (isset($_GET['user_id'])) {
    // The user is viewing a different profile
    $user_id = $_GET['user_id'];
} else {
    // The user is viewing their own profile
    $user_id = $_SESSION['user_id'];
}

$query = "SELECT i.username, u.id as user_id, i.picture, i.bio, CONCAT(name_first, ' ', name_last) as name FROM user u LEFT JOIN user_info i ON u.id = i.user_id WHERE u.id = :user_id";
$stmt = $dbh->prepare($query);
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetchAll();
$count = $stmt->rowCount();

if ($count == 1) {
    // Then a unique user was found
    $row = $result[0];
    $path = "uploads/". $row['picture'];
    if (!empty($row['username'])) {
        $username = $row['username'];
    }
    if (!empty($row['name'])) {
        $name = $row['name'];
    }
    if (!empty($row['bio'])) {
        $bio = $row['bio'];
    }
    $user_id_pull = $row['user_id'];
}

// Pull all of their matches from the database
$query = "SELECT i.username, u.id as user_id, i.picture, i.bio, CONCAT(name_first, ' ', name_last) as name FROM user u LEFT JOIN user_info i ON u.id = i.user_id WHERE 1 = 1";
$stmt = $dbh->prepare($query);
$stmt->execute();
$matches = $stmt->fetchAll();
?>

<html>
<head>
</head>
<body>
<h1>List of Users</h1>
<table>

</table>
</body>
</html>
