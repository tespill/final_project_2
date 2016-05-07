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


    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" /><br />
            <label for="password1">Password:</label>
            <input type="password" id="password1" name="password1" /><br />
            <label for="password2">Password (retype):</label>
            <input type="password" id="password2" name="password2" /><br />
        <input type="submit" value="Sign Up" name="submit" />
    </form>

    <a href="sign_in.php">Sign In</a>
    <a href="logout.php">Log Out</a>
<?php
    } else {
        // They are already logged in
        echo('<p class="login">You are logged in as ' . $_SESSION['email'] . '.</p>');
        echo('<a href="logout.php">Log Out</a>');
    }
?>