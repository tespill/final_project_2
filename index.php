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
                <a href="#">Dashboard</a>
            </li>
            <li>
                <a href="profile.php">Profile</a>
            </li>
            <li>
                <a href="sign_up.php">Sign Up</a>
            </li>
            <li>
                <a href="#">Events</a>
            </li>
            <li>
                <a href="#">About</a>
            </li>
            <li>
                <a href="#">Services</a>
            </li>
            <li>
                <a href="#">Contact</a>
            </li>
        </ul>
    </div>
</div>
<!---  END OF NAVIGATION BAR -->

<div id="main" class="container text-center">
    <div class="row">
        <div id="left" class="col-sm-3 well">
            <div class="well">
                <p><a href="#">My Profile</a></p>
                <img src="bird.jpg" class="img-circle" height="65" width="65" alt="Avatar">
            </div>
            <div class="well">
                <p><a href="#">Interests</a></p>
                <p>
                    <span class="label label-default">News</span>
                    <span class="label label-primary">W3Schools</span>
                    <span class="label label-success">Labels</span>
                    <span class="label label-info">Football</span>
                    <span class="label label-warning">Gaming</span>
                    <span class="label label-danger">Friends</span>
                </p>
            </div>
            <div class="alert alert-success fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <p><strong>Ey!</strong></p>
                People are looking at your profile. Find out who.
            </div>
            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>
        </div>
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
                <p>Upcoming Events:</p>
                <img src="paris.jpg" alt="Paris" width="400" height="300">
                <p><strong>Paris</strong></p>
                <p>Fri. 27 November 2015</p>
                <button class="btn btn-primary">Info</button>
            </div>
            <div class="well">
                <p>ADS</p>
            </div>
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
