<?php include_once("./assets/php/session.php");
    $private_page = true;
    if(!($current_user->get_type() == "root" || $current_user->get_type() == "admin" || $current_user->get_type() == "support")){
        echo "Forbidden...<script>
        window.location = './index.php';
        </script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("./assets/php/dependencies.php")?>
    <title>Dashboard</title>
</head>
<body>
        <div id="dashboard-navigation-panel">
            <div class="content">
                <?php
                        echo '<div class="dashboard-navigation-item"><img class="small-icon" src="assets/images/increasing_graph_icon.png"/>Stocks</div>';
                        echo '<div class="dashboard-navigation-item"><img class="small-icon" src="assets/images/money_icon.png"/>Quotes</div>';
                    if ($_SESSION['mystocks_auth']){
                        
                            $admin_classes = ($current_user->get_type() == "root" || $current_user->get_type() == "admin") ? "dashboard-navigation-item" : "disabled";
                        
                    }
                    echo '<div class="' . $admin_classes . '"><img class="small-icon" src="assets/images/bell_icon.png"/>Alerts</div>';
                    echo '<div class="' . $admin_classes . '"><img class="small-icon" src="assets/images/user_icon.png"/>Users</div>';
                ?>
            </div>
        </div>
    <div id="dashboard-main-panel">
        <h1>Dashboard</h1>
        <p>The dashboard is still in development. Please check again later.</p>
        <div id="messages" class="hide dashboard-main-panel-content">
            <h1>ALL MESSAGES</h1>
            <div id="dashboard-newsletter-container">
                <?php display("newsletters", "*");?>
            </div>
            <div id="add-newsletter-button" class="button primary-bg">+</div>
        </div>
        <div id="subscribers" class="hide dashboard-main-panel-content">
            <h1>ALL SUBSCRIBERS</h1>
            <div id="dashboard-subscribers-container">
                <?php display("subscribers", "*");?>
            </div>
        </div>
        <div id="users" class="hide dashboard-main-panel-content">
            <h1>ALL USERS</h1>
            <div id="dashboard-users-container">
                <?php display("users", "*");?>
            </div>
        </div>
        <div id="dashboard-add-form" class="hide">
            <div class="background-blur"></div>
            <div class="form-panel">
                <h2 id="add-form-title">New Message</h2>
                <div id="close-add-form">&#10005;</div>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="dashboard-type" id="dashboard-type"/>
                    <div id="custom-form-inputs"></div>
                    <?php
                        if ($current_user->get_type() == "admin" || $current_user->get_type() == "root"): 
                    ?>
                    <div id="submit-dasboard-form" class="button active-theme"><img class='small-icon' src='assets/images/icon-send.png'>Publish</div>
                    <?php endif?>
                    <div id="save-dasboard-form" class="button normal-theme"><img class='small-icon' src='assets/images/icon-floppy-disk.png'>Save</div>
                    <div id="discard-dasboard-form" class="button danger-bg"><img class='small-icon' src='assets/images/icon-delete.png'>Close</div>
                </form>
            </div>
        </div>
    </div>
    <?php 
        include_once('assets/php/footer.php');
    ?>
</body>
</html>