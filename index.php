<?php
$title = 'SideKicK - Home';
require_once('structure/header.php');

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
    <!-- JQuery Slider [responsiveslides.com] -->
    <div id="slider">
        <ul class="rslides">
            <li><img src="slider1.jpg" alt="slider1"></li>
            <li><img src="slider2.jpg" alt="slider2"></li>
            <li><img src="slider3.jpg" alt="slider3"></li>
        </ul>
    </div>
    <br/><hr/><br/>
    <!-- End of slider -->


</div>

<footer class="container-fluid text-center">
    <p>Footer Text</p>
</footer>

<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Slider -->
<script>
    $(function() {
        $(".rslides").responsiveSlides(); // Starts slider
    });
</script>

</body>
</html>