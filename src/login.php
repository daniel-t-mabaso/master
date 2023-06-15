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
    <div id="body">
        <div class="content">
            <form action="" method="post">
                <h2>LOGIN</h2>
                <P>Login using your credentials</S></P>
                <input class="form-input" type="email" name="email" id="email-address" placeholder="Email" value="<?php if (isset($_POST['email'])){echo $_POST['email'];}?>">
                <input class="form-input" type="password" name="password" id="password" placeholder="Password">
            <?php include_once("./error.php");?>
                <input class="button" type="submit" value="LOGIN" name="login">
                <p><a href="forgot.php">Forgot password</a></p>
                <p>Don't have an account? <a href="register.php">Sign up</a></p>
            </form>
        </div>
    </div>
    <?php include './assets/php/footer.php'?>
</body>
</html>