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
    <?php
        $success = false;
        if(isset($_POST['stock-code'])){
            $code = trim(strtoupper($_POST['stock-code']));
            $response = getYahoo($code);
            if (is_string($response) && strpos($response, "cURL")){
                echo "There was an error searching for the code you provided. Please check that it is correct and try again.";
            }else{
                // $success = true;
                // $financial_data = $response['financialData'];
                // $current_price = number_format($financial_data['currentPrice']['raw']/100, 2);
                // $currency = $financial_data['financialCurrency'];
                // $recommendation = $financial_data['recommendationKey'];
                // $highest_price = $financial_data['targetHighPrice']['fmt'];
                // $lowest_price = $financial_data['targetLowPrice']['fmt'];
                $success = true;
                $financial_data =$response['chart']['result'][0]['meta'];
                $current_price = number_format($financial_data['regularMarketPrice']/100, 2);
                $currency = 'ZAR';
                $date = date("d M Y H:i:s", $financial_data['regularMarketTime']);
                $type = $financial_data['instrumentType'];
                $name = $financial_data['exchangeName'];
                $previous_close = number_format($financial_data['previousClose']/100 , 2);
            }
        }
    ?>
    <div id="stock-results">
        <?php if ($success):?>
        <h2><?=$code?>.JO SNAPSHOT</h2>
            <b>Stock Exchange:</b>
            <?=$name?><br>
            <b>Current Price:</b>
           <?=$currency?> <?=$current_price?><br>
           <b>Previous Closing Price:</b>
           <?=$currency?> <?=$current_price?><br>
           <b>Type:</b> <?=$type?></br>
        <b>Last update:</b> <?=$date?>


        <?php endif;?>
    </div>    
    <?php include_once("assets/php/footer.php")?>
</body>
</html>