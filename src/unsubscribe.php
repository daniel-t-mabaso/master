<?php include_once("./assets/php/session.php");
    $public_page = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include_once("./assets/php/dependencies.php");
    ?>
    <title>Login</title>
</head>
<body>
    <?php include './assets/php/header.php'?>
    <div class="content minimum-max-screen">
        <form action="" method="post">
        <h2>UNSUBSCRIBE</h2>
        <P>Do you want to stop receiving email<br>newsletters and announcements?</P>
            <?php 
            if(isset($_SESSION['mystocks_auth'])){
                $email = $current_user->get_email();
                echo "<input class='form-input' type='email' name='email-address' id='email-address' placeholder='Email' value='$email'/>";
            }
            else{
                echo '<input class="form-input" type="email" name="email-address" id="email-address" placeholder="Email">';
            }?>
            <input class="button primary-bg" type="submit" value="Unsubscribe" name="unsubscribe">
        </form>
        <?php include_once("./error.php");?>
    </div>
    <?php include 'assets/php/footer.php'?>
</body>
</html>