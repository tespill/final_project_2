<?php
    require_once('include/start_session.php');
    $title = 'SideKicK - Edit Profile';
    require_once('structure/header.php');
    require_once('include/connect.php');
    $message = '';

    // Make sure the user is logged in before going any further.
    if (!isset($_SESSION['user_id'])) {
        echo '<p class="login">Please <a href="login.php">log in</a> to access this page.</p>';
        exit();
    }

    // Connect to database
    $dbh = new PDO("mysql:host=$db_hostname;dbname=sidekick", $db_username, $db_password);

    if (isset($_POST['submit'])) {
        // User is updating the profile
        // Grab the new data from the form
        $name_first  = trim($_POST['name_first']);
        $name_last   = trim($_POST['name_last']);
        $username    = trim($_POST['username']);
        $new_picture = trim($_FILES['new_picture']);
        $bio         = trim($_POST['bio']);

        $query = "UPDATE user_info SET name_first = :name_first, name_last = :name_last, username = :username, bio = :bio WHERE user_id = :user_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
           'name_first' => $name_first,
            'name_last' => $name_last,
            'username'  => $username,
            'bio'       => $bio,
            'user_id'   => $_SESSION['user_id']
        ));
        $message = 'User updated.';
        // Redirect the user to their profile
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/profile.php';
        header('Location: ' . $home_url);
    } else {
        // The user is viewing their own profile
        $user_id = $_SESSION['user_id'];

        // Grab the data from the database
        $query = 'SELECT * FROM user_info WHERE user_id = :user_id';
        $stmt = $dbh->prepare($query);
        $stmt->execute(array('user_id' => $user_id));
        $result = $stmt->fetchAll();
        $count = $stmt->rowCount();

        if ($count == 1) {
            // The user was found
            $row = $result[0];
            $name_first = $row['name_first'];
            $name_last = $row['name_last'];
            $username = $row['username'];
            $picture = $row['picture'];
            $path = "uploads/". $row['picture'];
            $bio = $row['bio'];
        } else {
            // The user was not found
            echo '<p>There was a problem accessing your profile.</p>';
        }
    }
    echo $message;
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
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="text" id="name_first"  name="name_first"   value="<?php if(!empty($name_first)) echo $name_first; else echo 'First Name';?>" />
                        <input type="text" id="name_last"   name="name_last"    value="<?php if(!empty($name_last)) echo $name_last; else echo 'Last Name'; ?>" />
                        <input type="text" id="username"    name="username"     value="<?php if(!empty($username)) echo $username; else echo 'Username'; ?>" />

                </div>
                    <textarea rows="4" cols="20" maxlength="250" name="bio" id="bio">
                        <?php if(!empty($bio)) echo $bio; ?>
                    </textarea>
                <input type="submit" value="Save Changes" name="submit" />
                </form>



                <div class="well">
                    <p>ADS</p>
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