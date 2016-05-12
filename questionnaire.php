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

    // PULL ALL QUESTIONS FROM THE DATABASE
    $query = "SELECT * FROM questions ORDER BY topic_id";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $count = $stmt->rowCount();

    // PULL ALL RESPONSES FROM THE DATABASE
    $query = "SELECT * FROM responses WHERE user_id = :user_id";
    $stmt = $dbh->prepare($query);
    $stmt->execute(array('user_id'=>$_SESSION['user_id']));
    $result = $stmt->fetchAll();
    $count = $stmt->rowCount();

    if ($count == 0) {
        // USER IS FILLING OUT THE FORM FOR THE FIRST TIME
        $query = "SELECT id FROM questions ORDER BY topic_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $question_ids = array();

        foreach ($result as $row){
            array_push($question_ids, $row['id']);
        }

        // INPUT EMPTY ROWS FOR EACH RESPONSE
        foreach ($question_ids as $question_id){
            echo $question_id;
            $query = "INSERT INTO responses (user_id, question_id) VALUES ('" . $_SESSION['user_id'] . "', :question_id)";
            $stmt = $dbh->prepare($query);
            $stmt->execute(array('question_id'=>$question_id));
        }
    }

    // THE FORM IS SUBMITTED
    if (isset($_POST['submit'])) {
        // UPDATE EACH OF THE RESPONSES IN THE DB
        foreach ($_POST as $response_id => $response) {
            $query = "UPDATE responses SET response = :response WHERE id = :response_id";
            $stmt = $dbh->prepare($query);
            $stmt->execute(array("response" => $response, "id" => $response_id));
        }
        echo '<p>Your responses have been saved.</p>';
    }

    // PULL THE DATA FROM THE DATABASE TO GENERATE THE FORM
    $query = "SELECT id, question_id, response FROM responses WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $responses = array();
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        // Look up the topic name for the response from the topic table
        $query2 = "SELECT r.id, r.question_id, r.response, t.topic, q.question " .
            "FROM responses AS r " .
            "INNER JOIN questions AS q USING (id) " .
            "INNER JOIN topics AS t USING (id) " .
            "WHERE r.user_id = '" . $_SESSION['user_id'] . "'";
        $stmt2 = $dbh->prepare($query2);
        $stmt2->execute();
        $results2 = $stmt2->fetchAll();

        $responses = array();
        foreach ($results2 as $r) {
            array_push($responses, $r);
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
        <!-- Form -->
        <?php
            // Generate the questionnaire form by looping through the response array
            echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
            echo '<p>How do you feel about each topic?</p>';
            $topic = $responses[0]['topic'];
            echo '<fieldset><legend>' . $responses[0]['topic'] . '</legend>';
            foreach ($responses as $response) {
                // Only start a new fieldset if the category has changed
                if ($topic != $response['topic']) {
                    $topic = $response['topic'];
                    echo '</fieldset><fieldset><legend>' . $response['topic'] . '</legend>';
                }
                // Display the topic form field
                echo '<label ' . ($response['response'] == NULL ? 'class="error"' : '') . ' for="' . $response['id'] . '">' . $response[''] . ':</label>';
                echo '<input type="radio" id="' . $response['id'] . '" name="' . $response['id'] . '" value="1" ' . ($response['response'] == 1 ? 'checked="checked"' : '') . ' />Love ';
                echo '<input type="radio" id="' . $response['id'] . '" name="' . $response['id'] . '" value="2" ' . ($response['response'] == 2 ? 'checked="checked"' : '') . ' />Hate<br />';
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
