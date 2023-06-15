
<?php
    include_once("./assets/php/session.php");
    $public_page = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <?php
        include 'assets/php/dependencies.php';
    ?>
</head>
<body>
    <?php include 'assets/php/header.php'?>
    <div id="body">
        <div class="content">
            <form action="" method="post">
                <h2>Forgot Password</h2>
                <p>After clicking reset, you will receive an email with instructions on how to reset your password.</p>
                <input class="form-input" type="email" name="email" id="email-address" placeholder="Email" value="<?php if (isset($_POST['email'])){echo $_POST['email'];}?>">
                <?php
                    $classes_dir =  __DIR__;
                    include_once($classes_dir .'/error.php');
                ?>
                <input class="button primary-bg" type="submit" value="RESET PASSWORD" name="forgot-password">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
                <p>Don't have an account? <a href="register.php">Sign up</a></p>
            </form>
        </div>
    </div>
    <?php include 'assets/php/footer.php'?>
</body>
</html>