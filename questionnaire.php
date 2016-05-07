<?php
// Start the session
require_once('include/start_session.php');
// Insert the page header
$title = 'Questionnaire';
require_once('include/connect.php');
require_once('structure/header.php');

if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Please <a href="sign_up.php">log in</a> to access this page.</p>';
    exit();
}

$dbh = new PDO("mysql:host=$db_hostname;dbname=sidekick", $db_username, $db_password);

$query = "SELECT * FROM responses WHERE user_id = '" . $_SESSION['user_id'] . "'";
$stmt = $dbh->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll();
if (count($results) == 0) {

    $query = "SELECT topics_id FROM topics ORDER BY category";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    $topicIDs = array();
    foreach ($results as $row) {
        array_push($topicIDs, $row['topics_id']);
    }

    foreach ($topicIDs as $topic_id) {
        echo $topic_id;
        $query = "INSERT INTO responses (user_id, topics_id) VALUES ('" . $_SESSION['user_id'] . "', :topics_id)";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array('topics_id' => $topics_id));
    }
}

if (isset($_POST['submit'])) {

    foreach ($_POST as $responses_id => $response) {
        $query = "UPDATE responses SET responses = :responses WHERE responses_id = :responses_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array("responses" => $responses, "responses_id" => $responses_id));
    }
    echo '<p>Your responses have been saved.</p>';
}

$query = "SELECT responses_id, topics_id, response FROM responses WHERE user_id = '" . $_SESSION['user_id'] . "'";
$stmt = $dbh->prepare($query);
$stmt->execute();
$responses = array();
$results = $stmt->fetchAll();
foreach ($results as $row) {

    $query2 = "SELECT name, category FROM topics WHERE topics_id = :topics_id";
    $stmt2 = $dbh->prepare($query2);
    $result = $stmt2->execute(array('topics_id' => $row['topics_id']));
    $responses = array();
    $results2 = $stmt2->fetchAll();
    if (count($results2) == 1) {
        $row2 = $results2[0];
        $row['topic_name'] = $row2['name'];
        $row['category_name'] = $row2['category'];
        array_push($responses, $row);
    }
}
?>

<body>
<!-- START OF NAVIGATION BAR -->
<div id="navigation">
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">
                    <img src="">
                </a>
            </li>
            <li>
                <a href="index.php">Home</a>
            </li>
            <li>
                <a href="sign_in.php">Log In</a>
            </li>
            <li>
                <a href="sign_up.php">Sign Up</a>
            </li>
            <li>
                <a href="profile.php">My Profile</a>
            </li>
            <li>
                <a href="edit_profile.php">Edit Profile</a>
            </li>
            <li>
                <a href="logout.php">Log Out</a>
            </li>
        </ul>
    </div>
</div>
<!---  END OF NAVIGATION BAR -->

<div id="main" class="container text-center">
    <div class="row">

        <?php
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
        echo '<p>How do you feel about each topic?</p>';
        $category = $responses[0]['category_name'];
        echo '<fieldset><legend>' . $responses[0]['category_name'] . '</legend>';
        foreach ($responses as $responses) {

            if ($category != $responses['category_name']) {
                $category = $responses['category_name'];
                echo '</fieldset><fieldset><legend>' . $responses['category_name'] . '</legend>';
            }

            echo '<label ' . ($responses['responses'] == NULL ? 'class="error"' : '') . ' for="' . $responses['responses_id'] . '">' . $responses['topic_name'] . ':</label>';
            echo '<input type="radio" id="' . $responses['responses_id'] . '" name="' . $responses['responses_id'] . '" value="1" ' . ($responses['responses'] == 1 ? 'checked="checked"' : '') . ' />Love ';
            echo '<input type="radio" id="' . $responses['responses_id'] . '" name="' . $responses['responses_id'] . '" value="2" ' . ($responses['responses'] == 2 ? 'checked="checked"' : '') . ' />Hate<br />';
        }
        echo '</fieldset>';
        echo '<input type="submit" value="Save Questionnaire" name="submit" />';
        echo '</form>';
        ?>


    </div>
</div>

<footer class="container-fluid text-center">
    <p>Footer Text</p>
</footer>

<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>
</html>
