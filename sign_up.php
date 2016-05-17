<?php
    $title = 'SideKicK - Sign Up';
    require_once('structure/header.php');
    require_once('include/connect.php');

    // Do not show the form is the user is logged in
    if (!isset($_SESSION['user_id'])) {
        if (isset($_POST['submit'])) {
            // Grab the profile data from the Sign-Up form
            $email = trim($_POST['email']);
            $password1 = trim($_POST['password1']);
            $password2 = trim($_POST['password2']);

            if (!empty($email) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
                // Connect to the database
                $dbh = new PDO("mysql:host=$db_hostname;dbname=sidekick", $db_username, $db_password);

                // Check is user already exists
                $query = "SELECT * FROM user WHERE email = :email";
                $stmt = $dbh->prepare($query);
                $stmt->execute(array('email' => $email));
                $result = $stmt->fetchAll();
                $count = $stmt->rowCount();

                if ($count == 0) {
                    // The user does not exist, so create an account
                    $query = "INSERT INTO user (email, password) VALUES (:email, SHA(:password))";
                    $stmt = $dbh->prepare($query);
                    $stmt->execute(array(
                        'email'  =>  $email,
                        'password'=>  $password1
                    ));

                    $query = "SELECT id from user WHERE email = :email";
                    $stmt = $dbh->prepare($query);
                    $stmt->execute(array('email' => $email));
                    $user_id = $stmt->fetchColumn();

                    $query = "INSERT INTO user_info (user_id) VALUES (:user_id)";
                    $stmt = $dbh->prepare($query);
                    $stmt->execute(array('user_id' => $user_id));

                    // Confirm success with the user
                    echo '<p>Your new account has been successfully created. You\'re now ready to <a href="sign_in.php">log in</a>.</p>';

                    exit();
                } else {
                    // An account already exists for this username, so display an error message
                    echo '<p class="error">An account already exists for this username. Please use a different address.</p>';
                    $email = "";
                }
            } else {
                echo '<p class="error">You must enter all of the sign-up data, including the desired password twice.</p>';
            }
        }
    ?>
        <html>
        <head>
            <link rel="stylesheet" type="text/css" href="sign_up.css">
            <script src="js/jquery.js"></script>
            <script src="js/bootstrap.min.js"></script>
        </head>
        <body>
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
        
        <div id="main" class="container">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" role="form">
                <h2>Registration Form</h2>
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" id="email" placeholder="Email" value="<?php if (!empty($email)) echo $email; ?>" class="form-control" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" name="password1" id="password1" placeholder="Password" class="form-control" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="col-sm-3 control-label">Confirm Password</label>
                    <div class="col-sm-9">
                        <input type="password" name="password2" id="password2" placeholder="Confirm Password" class="form-control" required>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </div>
            </form>
            <a id="signin" href="sign_in.php">Sign In</a>
        </div>
        </body>
</html>
<?php
    } else {
        // They are already logged in
        echo('<p class="login">You are logged in as ' . $_SESSION['email'] . '.</p>');
        echo('<a href="logout.php">Log Out</a>');
    }
?>