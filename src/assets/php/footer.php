<div id="footer-panel">
    <div class="content">
        <?php
                echo '<div><a href="index.php"><img class="small-icon" src="assets/images/search_icon.png"/>Search</a></div>';
            if ($_SESSION['mystocks_auth']){
                if($current_user->get_type() == "root" || $current_user->get_type() == "admin" || $current_user->get_type() == "support")
                {
                    echo '<div><a href="dashboard.php"><img class="small-icon" src="assets/images/setting-lines_icon.png"/>Dashboard</a></div>';
                }else{
                    echo '<div><a href="alerts.php"><img class="small-icon" src="assets/images/bell_icon.png"/>My Alerts</a></div>';
                }
                echo '<div><a href="profile.php"><img class="small-icon" src="assets/images/user_icon.png"/>Profile</a></div>';
                echo '<div><a href="logout"><img class="small-icon" src="assets/images/login_icon.png"/>Logout</a></div>';
            }
            else{
                echo '<div class="disabled"><img class="small-icon" src="assets/images/bell_icon.png"/>My Alerts</div><div><a href="register.php"><img class="small-icon" src="assets/images/user_icon.png"/>Register</a></div>';
                echo '<div><a href="login.php"><img class="small-icon" src="assets/images/login_icon.png"/>Login</a></div>';
            }
        ?>
    </div>
</div>

<?php include_once('assets/php/small-toast.php')?>