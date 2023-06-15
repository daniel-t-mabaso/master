<?php include_once("./assets/php/session.php");
    include_once("./assets/php/user_auth.php");
    $public_page = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("./assets/php/dependencies.php")?>
    <title>My Stocks | <?=$current_user->get_first_name()?></title>
</head>
<body>
    <?php include_once("./assets/php/header.php")?>
    <div id="body">
        <div class="content">
            <div class="profile-picture"><img src="assets/images/user_icon.png" alt="placeholder profile picture."></div>
            <div class="profile-details">
                <h2><?=$current_user->get_full_name()?></h2>
                <?=$current_user->get_email()?><br>
                <small><b>Joined: </b><?=$current_user->get_creation_date()?></small>
</div>
        </div>
    </div>
    <?php include 'assets/php/footer.php'?>
</body>
</html>