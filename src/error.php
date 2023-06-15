<?php
    if (count($errors)>0): 
?>

<div class="login-error center-txt danger-txt italic" id='login-error'>
    <?php
        foreach ($errors as $error):
    ?>
        <p><?php echo $error;?></p>
    <?php endforeach?>
</div>
<?php endif?>
