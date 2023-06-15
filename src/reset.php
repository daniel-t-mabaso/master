
<?php
    include_once("./assets/php/session.php");
    $public_page = true;
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <?php
        include 'assets/php/dependencies.php';
    ?>
</head>
<body>
    <?php include 'assets/php/header.php'?>
    <div id="body">
        <div class="content">
            <?php
            if(isset($reset_password_html)){
                echo $reset_password_html;}
            else{
                
            echo '<script>
            window.location = "./";
            </script>';
            }
            ?>
        </div>
    </div>
    <?php
        $classes_dir =  __DIR__;
         include_once($classes_dir .'error.php');?>
    <?php include 'assets/php/footer.php'?>
</body>
</html>