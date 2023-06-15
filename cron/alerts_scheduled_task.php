
<?php
    include_once('assets/php/fetch.php');
    include_once('assets/php/class_lib.php');
    include_once('assets/php/controller.php');
    $root = new User;
    $root->get_item_from_db('webmaster@mystocks.co.za');
    if ($root->get_type() != 'root'){
        exit;
    }
    $alerts = $root->fetchAlerts();
    $profiles = array();
    while($alerts && $row = $alerts -> fetch_array()){
        $user_email = $row[1];
        $symbol = $row[2];
        $low = $row[3];
        $high = $row[4];
        $status = $row[5];
        if (!isset($profiles[$user_email])){
            $profiles[$user_email] = array();
        }
        array_push($profiles[$user_email], array(
            "symbol" => $symbol,
            "low" => $low,
            "high" => $high,
            "status" => $status
        ));
    }
    foreach ($profiles as $email=>$stocks){
        $sells = [];
        $buys = [];
        $imageurl = htmlentities("https://www.mystocks.co.za/assets/images/mystocks_Logo_full_colour.png");
        $html = '
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <body style="position: absolute; width: 100%; height: 100%; margin: 0px; padding: 0px; top: 0; left: 0; background-color: #fcfcfc; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif; color: #1d1d1d; overflow-x: hidden;">
            <div style="width: 300px; margin: 0 auto; margin-top: 50px;">
            <a href="https://www.mystocks.co.za"><img src="' . $imageurl. '" style="width: 100%;"/></a>
            <h1 style="margin: 0px; color: #541d89; text-align: center;">STOCK ALERTS</h1>
            ';
        foreach ($stocks as $stock){
            $symbol = $stock['symbol'];
            $response = getYahoo($symbol);
            $meta = $response['chart']['result'][0]['meta'];
            $currentPrice = $meta['regularMarketPrice'];
            $low = $stock['low'];
            $high = $stock['high'];
            if ($currentPrice < $low*100){
                array_push($buys, "<b>". strtoupper($symbol) . " - <small>R " . number_format($currentPrice/100, 2) . "/share</b><br> (-R" . number_format(($low * 100 - $currentPrice)/100, 2) ." of your set 'Buy At' price of R". number_format($low, 2) .")</small>");
            }
            else if (($currentPrice > $high*100) && ($high > 0)){
                array_push($sells, "<b>". strtoupper($symbol) . " - <small>R " . number_format($currentPrice/100, 2) . "/share</b><br> (+R". number_format(($currentPrice - $high * 100)/100, 2) ." of your set 'Sell At' price of R" . number_format($high, 2) .")</small>");
            }
        }
        $html .= "<h2>To sell:</h2><ul>";
        $updates = 0;
        if (count($sells) > 0){
            foreach ($sells as $sell){
                $html .=  "<li>$sell</li>";
                $updates++;
            }
        } else {
            $html .=  "<li>None</li>";
        }
        $html .=  "</ul><h2>To buy:</h2><ul>";
        if (count($buys) > 0){
            foreach ($buys as $buy){
                $html .=  "<li>$buy</li>";
                $updates++;
            }
        } else {
            $html .=  "<li>None</li>";
        }
        $html .=  "</ul></div></body></html>";
        $date = date("d M Y h:m");
        $headers = "From: My Stocks <noreply@mystocks.co.za>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if ($updates){
            mail($email, "My Stocks Update $date", $html, $headers);
        }
    }
?>