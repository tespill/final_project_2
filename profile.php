<?php
    require_once('include/start_session.php');
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

    $query = "SELECT i.username, u.id, i.picture, CONCAT(name_first, ' ', name_last) FROM user u LEFT JOIN user_info i ON u.id = i.user_id WHERE u.id = :user_id";
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
        $user_id_pull = $row['id'];
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
        <div class="col-sm-7">

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default text-left">
                        <div class="panel-body">
                            <p contenteditable="true">Status: Feeling Blue</p>
                            <button type="button" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-thumbs-up"></span> Like
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="well">
                        <p>John</p>
                        <img src="bird.jpg" class="img-circle" height="55" width="55" alt="Avatar">
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="well">
                        <p>Just Forgot that I had to mention something about someone to someone about how I forgot something, but now I forgot it. Ahh, forget it! Or wait. I remember.... no I don't.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="well">
                        <p>Bo</p>
                        <img src="bandmember.jpg" class="img-circle" height="55" width="55" alt="Avatar">
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="well">
                        <p>Just Forgot that I had to mention something about someone to someone about how I forgot something, but now I forgot it. Ahh, forget it! Or wait. I remember.... no I don't.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="well">
                        <p>Jane</p>
                        <img src="bandmember.jpg" class="img-circle" height="55" width="55" alt="Avatar">
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="well">
                        <p>Just Forgot that I had to mention something about someone to someone about how I forgot something, but now I forgot it. Ahh, forget it! Or wait. I remember.... no I don't.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="well">
                        <p>Anja</p>
                        <img src="bird.jpg" class="img-circle" height="55" width="55" alt="Avatar">
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="well">
                        <p>Just Forgot that I had to mention something about someone to someone about how I forgot something, but now I forgot it. Ahh, forget it! Or wait. I remember.... no I don't.</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="right" class="col-sm-2 well">
            <div class="thumbnail">
                <?php if (!empty($row['picture'])) {
                    echo "<img src='$path' />";
                } else {
                    echo '<img src="images/nopic.png" />';
                } ?>
                <p><strong><?php echo $username ?></strong></p>
                <p>Fri. 27 November 2015</p>
            </div>
            <div class="well">
                <p>ADS</p>
            </div>
            <div class="well">
                <p>ADS</p>
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

