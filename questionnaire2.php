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

// If this user has never answered the questionnaire, insert empty responses into the database
$query = "SELECT * FROM responses WHERE user_id = '" . $_SESSION['user_id'] . "'";
$stmt = $dbh->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll();

if (count($results) == 0) {
    // First grab the list of topic IDs from the topic table
    $query = "SELECT id FROM questions ORDER BY topics_id";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    $topicIDs = array();
    foreach ($results as $row) {
        array_push($topicIDs, $row['topic_id']);
    }
    // Insert empty response rows into the response table, one per topic
    foreach ($topicIDs as $topic_id) {
        echo $topic_id;
        $query = "INSERT INTO responses (user_id, questions_id) VALUES ('" . $_SESSION['user_id'] . "', :questions_id)";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array('questions'=>$topic_id));
    }
}
// If the questionnaire form has been submitted, write the form responses to the database
if (isset($_POST['submit'])) {
    // Write the questionnaire response rows to the response table
    foreach ($_POST as $response_id => $response) {
        $query = "UPDATE responses SET response = :response WHERE response_id = :response_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array("response" => $response, "response_id" => $response_id));
    }
    echo '<p>Your responses have been saved.</p>';
}
// Grab the response data from the database to generate the form
$query = "SELECT id, questions_id, response FROM responses WHERE user_id = '" . $_SESSION['user_id'] . "'";
$stmt = $dbh->prepare($query);
$stmt->execute();
$responses = array();
$results = $stmt->fetchAll();
foreach ($results as $row) {
    // Look up the topic name for the response from the topic table
    $query2 = "SELECT r.id AS response_id, r.questions_id, r.response, q.question AS question_name, t.topic AS topic_name " .
        "FROM responses AS r " .
        "INNER JOIN questions AS q USING (id) " .
        "INNER JOIN topic AS t USING (id) " .
        "WHERE r.user_id = '" . $_SESSION['user_id'] . "'";
    $stmt2 = $dbh->prepare($query2);
    $stmt2->execute();
    $results2 = $stmt2->fetchAll();

    $responses = array();
    foreach ($results2 as $row) {
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
            <?php
            if (isset($_SESSION['email']) && ($_SESSION['email']) == 'admin@sidekick.com') {
                echo '<li><a href="admin1.php">Admin Page</a></li>';
            }
            ?>
        </ul>
    </div>
</div>
<!---  END OF NAVIGATION BAR -->

<div id="main" class="container text-center">
    <div class="row">

        <?php
        // Generate the questionnaire form by looping through the response array
        echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
        echo '<p>How do you feel about each topic?</p>';
        $category = $responses[0]['topic_name'];
        echo '<fieldset><legend>' . $responses[0]['topic_name'] . '</legend>';
        foreach ($responses as $response) {
            // Only start a new fieldset if the category has changed
            if ($category != $response['topic_name']) {
                $category = $response['topic_name'];
                echo '</fieldset><fieldset><legend>' . $response['topic_name'] . '</legend>';
            }
            // Display the topic form field
            echo '<label ' . ($response['response'] == NULL ? 'class="error"' : '') . ' for="' . $response['response_id'] . '">' . $response['question_name'] . ':</label>';
            echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . '" value="1" ' . ($response['response'] == 1 ? 'checked="checked"' : '') . ' />Love ';
            echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . '" value="2" ' . ($response['response'] == 2 ? 'checked="checked"' : '') . ' />Hate<br />';
        }
        echo '</fieldset>';
        echo '<input type="submit" value="Save Questionnaire" name="submit" />';
        echo '</form>';
        ?>

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
