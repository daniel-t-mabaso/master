<?php
if (!$_SESSION['mystocks_auth']){
    echo '<script>
    window.location = "./index.php";
    </script>';
}
?>