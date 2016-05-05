<?php
    require_once('include/start_session.php');
    $title = 'SideKicK - Edit Profile';
    require_once('structure/header.php');
    require_once('include/connect.php');
    $message = '';
    // Connect to database
    $dbh = new PDO("mysql:host=$db_hostname;dbname=sidekick", $db_username, $db_password);

    if (isset($_POST['submit'])) {
        // User is updating the profile
        // Grab the new data from the form
        $name_first  = trim($_POST['name_first']);
        $name_last   = trim($_POST['name_last']);
        $username    = trim($_POST['username']);
        //$new_picture = trim($_FILES['new_picture']);

        $query = "UPDATE user_info SET name_first = :name_first, name_last = :name_last, username = :username WHERE user_id = :user_id";
        $stmt = $dbh->prepare($query);
        $stmt->execute(array(
           'name_first' => $name_first,
            'name_last' => $name_last,
            'username'  => $username,
            'user_id'   => $_SESSION['user_id']
        ));
        $message = 'User updated.';
    } else {
        // The user is viewing their own profile
        $user_id = $_SESSION['user_id'];

        // Grab the data from the database
        $query = 'SELECT * FROM user_info WHERE user_id = :user_id';
        $stmt = $dbh->prepare($query);
        $stmt->execute(array('user_id' => $user_id));
        $result = $stmt->fetchAll();
        $count = $stmt->rowCount();

        if ($result != NULL) {
            // The user was found
            $row = $result[0];
            $name_first = $row['name_first'];
            $name_last = $row['name_last'];
            $username = $row['username'];
            $picture = $row['picture'];
            $birthdate = $row['birthdate'];
        } else {
            // The user was not found
            echo '<p>There was a problem accessing your profile.</p>';
        }
    }
    echo $message;
?>
    <body>
        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" id="name_first"  name="name_first"   value="<?php if(!empty($name_first)) echo $name_first ?>" />
            <input type="text" id="name_last"   name="name_last"    value="<?php if(!empty($name_last)) echo $name_last ?>" />
            <input type="text" id="username"    name="username"     value="<?php if(!empty($username)) echo $username ?>" />
            <input type="date" id="date"        name="date" />
            <input type="file" id="new_picture" name="new_picture" />
            <input type="submit" value="Save Changes" name="submit" />
        </form>
    </body>
</html>