<?php
// Start the session
require_once('include/start_session.php');
// Insert the page header
$page_title = 'Questionnaire';
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
        $query = "UPDATE responses SET response = :response WHERE response_id = :response_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array("response" => $response, "responses_id" => $responses_id));
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

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
echo '<p>How do you feel about each topic?</p>';
$category = $responses[0]['category_name'];
echo '<fieldset><legend>' . $responses[0]['category_name'] . '</legend>';
foreach ($responses as $response) {

    if ($category != $response['category_name']) {
        $category = $response['category_name'];
        echo '</fieldset><fieldset><legend>' . $response['category_name'] . '</legend>';
    }

    echo '<label ' . ($response['response'] == NULL ? 'class="error"' : '') . ' for="' . $response['response_id'] . '">' . $response['topic_name'] . ':</label>';
    echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . '" value="1" ' . ($response['response'] == 1 ? 'checked="checked"' : '') . ' />Love ';
    echo '<input type="radio" id="' . $response['response_id'] . '" name="' . $response['response_id'] . '" value="2" ' . ($response['response'] == 2 ? 'checked="checked"' : '') . ' />Hate<br />';
}
echo '</fieldset>';
echo '<input type="submit" value="Save Questionnaire" name="submit" />';
echo '</form>';

?>