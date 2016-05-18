<?php
    require_once('include/start_session.php');
    $title = 'SideKicK - Edit Profile';
    require_once('structure/header.php');
    require_once('include/connect.php');
    $message = '';

    // Make sure the user is logged in before going any further.
    if (!isset($_SESSION['user_id'])) {
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/sign_in.php';
        header('Location: ' . $home_url);
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

    // Pull all of their matches from the database
    $query = "SELECT i.username, u.id as user_id, i.picture, i.bio, CONCAT(name_first, ' ', name_last) as name FROM user u LEFT JOIN user_info i ON u.id = i.user_id WHERE 1 = 1";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $matches = $stmt->fetchAll();
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
                                <h2 align="center">Matches:</h2>
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