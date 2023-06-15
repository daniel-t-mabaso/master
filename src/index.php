<?php include_once("assets/php/session.php");
    $public_page = true;
    include_once('assets/php/fetch.php');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("assets/php/dependencies.php")?>
    <title>My Stocks | Search</title>
</head>
<body>        
    <div class="main-logo"><a href='index.php'><img src="assets/images/mystocks_Logo_full_colour.png"/></a></div>
    <div id="body">
        <div class="content">
            <form action="./search_results.php" method="post">
                <?php $greeting = ($_SESSION['mystocks_auth']) ? 'Hi ' . $current_user->get_first_name() . ',<br>' : '';?>
                <h2><?=$greeting?>Search for stock quotes</h2>
                <input class="form-input" type="text" name="stock-code" id="stock-code" placeholder="Enter JSE Symbol..." autocomplete="off"/>
                <input class="button" type="submit" name="search" id="search" value="SEARCH">
            </form>
        </div>
    </div>
    
    <?php include_once("assets/php/footer.php")?>
</body>
</html>