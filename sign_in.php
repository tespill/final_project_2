<?php
    require_once('include/connect.php');
    $title = 'SideKicK - Login';
    require_once('structure/header.php');

    // Start the session
    session_start();

    // Clear the error message
    $error_msg = "";

    // If the user isn't logged in, try to log them in
    if (!isset($_SESSION['user_id'])) {
        if (isset($_POST['submit'])) {
            // Connect to the database
            $dbh = new PDO("mysql:host=$db_hostname;dbname=sidekick", $db_username, $db_password);

            // Grab the user-entered log-in data
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (!empty($email) && !empty($password)) {
                // Look up the username and password in the database
                $query = "SELECT id, email FROM user WHERE email = :email AND password = SHA(:password)";
                $stmt = $dbh->prepare($query);
                $stmt->execute(array(
                    'email'  =>  $email,
                    'password'  =>  $password
                ));
                $result = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count == 1) {
                    // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                    $email = $result['email'];
                    $user_id = $result['id'];
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $email;
                    setcookie('user_id', $user_id, time() + (60 * 60 * 24 * 30));    // expires in 30 days
                    setcookie('email', $email, time() + (60 * 60 * 24 * 30));  // expires in 30 days
                    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                    header('Location: ' . $home_url);
                } else {
                    // The username/password are incorrect so set an error message
                    $error_msg = 'Sorry, you must enter a valid username and password to log in.';
                }
            } else {
                $error_msg = 'Sorry, you must enter your username and password to log in.';
            }
        }
    }
    $page_title = 'Log In';
    require_once('structure/header.php');

    if (isset($_SESSION['user_id'])) {
        echo $_SESSION['user_id'];
    }
    if (empty($_SESSION['user_id'])) {
        ?>
        <html>
        <head>
        <script src="sign_in.js"></script>
        <link rel="stylesheet" type="text/css" href="sign_in.css">
        </head>
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
<?php echo $_SERVER['PHP_SELF']; ?>
<?php if (!empty($email)) echo $email; ?>

    <div class="container">
        <div id="cardcontainer" class="card card-container">
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-signin">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="email" id="inputEmail" class="form-control" placeholder="Email" value="<?php if (!empty($email)) echo $email; ?>" required autofocus>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
            </form>
            <a href="#" class="forgot-password">
                Forgot the password?
            </a>
        </div>
    </div>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>

        </body>
        </html>



        <?php
    } else {
        echo('<p class="login">You are logged in as ' . $_SESSION['email'] . '.</p>');
        echo('<a href="logout.php">Log Out</a>');
    }
?>

