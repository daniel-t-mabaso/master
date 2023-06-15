<?php
    include_once("./assets/php/session.php");
    include_once("./assets/php/user_auth.php");
    $public_page = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once("./assets/php/dependencies.php")?>
    <title>My Stocks | Alerts</title>
</head>
<body>
    <?php include_once("./assets/php/header.php")?>
    <div id="body">
        <div class="content">
            <h1>My Alerts</h1>
            <div id="alerts-panel">
            <?php
            $condition = "`user_email` = '" . $current_user->get_email() . "'";
            display('alerts', '*', $condition);
            ?>
            </div>





















            <div class="circular-button bottom-right" onclick="showAddForm();"><img class="full-size-icon" src="assets/images/plus_icon.png"/></div>
            <div id="add-panel-container" class="hide">
                <div class="background-blur"></div>
                <div id="add-panel" class="hide">
                    <div class="close-parent">&#10005;</div>
                    <h1 id="add-panel-title">New Alert</h1>
                    <div id="add-panel-content">
                        <label>Stock to track:</label><br>
                        <input type="text" name="symbol" id="symbol" class="form-input alert-input" placeholder="Stock symbol" autosuggest="off">
                        <label>When price is (ZAR):</label><br>
                        <input type="number" name="low" id="low" class="small-form-input alert-input" placeholder="Price below" min="0">
                        <input type="number" name="high" id="high" class="small-form-input alert-input" placeholder="Price above" min="0">
                        <input type="hidden" name="alert-id" class="alert-input">
                        <div onclick="addAlert();" id="add-alert-button" class="button">CREATE</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'assets/php/footer.php'?>
</body>
</html>