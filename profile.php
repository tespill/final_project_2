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
        <div class="col-sm-7">

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default text-left">
                        <div class="panel-body">
                            <h1 align="center">Matches:</h1>
                        </div>
                    </div>
                </div>
            </div>
            <?php foreach($matches as $match){ ?>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="well">
                            <p><a href="profile.php?user_id=<?php echo $match['user_id'] ?>"><?php if (!empty($match['username'])) echo $match['username']; else echo '<i>No Username</i>' ?></a></p>
                            <?php if (!empty($match['picture'])) {
                                echo "<img src='uploads/" . $match['picture'] . " class='img-circle' height='55' width='55' alt='Avatar' />";
                            } else {
                                echo '<img src="images/nopic.jpg" class="img-circle" height="55" width="55" alt="Avatar" />';
                            } ?>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="well">
                            <p><?php if (!empty($match['bio'])) echo $match['bio']; else echo '<i>No Bio</i>' ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
        <div id="right" class="col-sm-2 well">
            <div class="thumbnail">
                <?php if (!empty($row['picture'])) {
                    echo "<img src='$path' />";
                } else {
                    echo '<img src="images/nopic.jpg" />';
                } ?>
                <p><strong><?php if (!empty($username)) echo $username ?></strong></p>
                <p><strong><?php if (!empty($name)) echo $name ?></strong></p>
            </div>
            <div class="well">
                <p><?php if (!empty($bio)) echo $bio; ?></p>
            </div>
            <div class="well">
                <?php if ($_SESSION['user_id'] == $user_id_pull) {
                    // The user is logged in
                    // Show the button to edit their profile
                    ?>
                    <a href="edit_profile.php"><button class="btn btn-primary">Edit Profile</button></a>
                    <a href="questionnaire.php"><button class="btn btn-primary">Questionnaire</button></a>
                    <?php
                } ?>

            </div>
        </div>
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

