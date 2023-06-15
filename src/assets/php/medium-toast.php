<?php
    // if (((!isset($_SESSION['subscribed']) || !$_SESSION['subscribed']))): 
    if (0): 
?>

<div id='medium-toast' class='shadow'>
    <div class="close-parent">&#10005;</div>
    <h2>SUBSCRIBE</h2>
    <p>Subscribe below to receive our monthly newsletters.</p>
    <form action='' method='post'>
        <!-- Use ajax to fulfil request -->
        <?php
            if(isset($_SESSION['mystocks_auth'])){
                $email = $current_user->get_email();
                echo "<input class='form-input' type='email' name='subscriber-email' placeholder='Email' value='$email'/>";
            }
            else{
                echo "<input class='form-input' type='email' name='subscriber-email' placeholder='Email'/>";
            }
        ?>
        <input class="button primary-bg" type='submit' value='Subscribe' name='subscribe'/>
    </form>
    <?php include_once("./error.php");?>
</div>
<?php endif?>
