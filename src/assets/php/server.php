<?php
    if(isset($_REQUEST) && @$_REQUEST['request']){
        session_start();
        $classes_dir =  __DIR__ . '/classes/';
        include_once($classes_dir . "Alert.php");
        include_once($classes_dir . "User.php");
        include_once($classes_dir . "Controller.php");
        $current_user = unserialize($_SESSION['user']);
    }
    $errors = array();
    if(!isset($_REQUEST['request']) && !$_SESSION['mystocks_auth']){
        $ROOT =  __DIR__ ;
        include_once("$ROOT/controller.php");
        include_once("$ROOT/class_lib.php");
        if(isset($_POST["login"])){
            //  Get data from the form
            $email = addslashes(strtolower($_POST['email']));
            $password = addslashes($_POST['password']);
            // Attempt login
            $user = new User();
            $user->set_email($email);
            if($user->login($password)){
                $_SESSION['mystocks_auth'] = true;
                $serialized_user = serialize($user);
                $_SESSION['user'] = $serialized_user;
                // if($user->is_subscribed()){
                //     $_SESSION['subscribed'] = true;
                // }else{
                //     $_SESSION['subscribed'] = false;
                // }
                // Go to home page
                echo '<script>
                window.location = "./";
                </script>';
            }
            else{
                $errors = $user->errors;
            }
        }else if(isset($_POST["register"])){
            // Get data from the form
            $email = addslashes(strtolower($_POST['email']));
            $password1 = addslashes($_POST['password']);
            $password2 = addslashes($_POST['confirm_password']);
            $first_name = addslashes($_POST['first_name']);
            $last_name = addslashes($_POST['last_name']);
            // Create user and attempt registration
            $user = new User();
            $user->set_details($first_name, $last_name, $email);
            if($user->register($password1, $password2)){
                $_SESSION['mystocks_auth'] = true;
                $serialized_user = serialize($user);
                $_SESSION['user'] = $serialized_user;
                // Go to home page
                echo '<script>
                window.location = "./";
                </script>';
            }
            else{
                $errors = $user->errors;
            }
        }else if(isset($_POST["forgot-password"])){
            // Get data from forgot password form
            $email = addslashes(strtolower($_POST['email']));
            // Create user and attempt registration
            $user = new User();
            $user->set_email($email);
            if($user->request_password_reset()){
                $serialized_user = serialize($user);
                $_SESSION['user'] = $serialized_user;
                // Go to home page
                echo '<script>
                alert("password reset link sent.");
                </script>';
                // echo '<script>
                // window.location = "./";
                // </script>';
            }
            else{
                $errors = $user->errors;
            }
        }else if(isset($_GET["reset-password"])){
            // Loading the reset password page
            // Get data from the form
            $email = addslashes(strtolower(@$_GET['email']));
            $token = @$_GET['token'];
            // Create user and attempt registration
            $user = new User();
            $user->set_email($email);
            if(!$user->is_token_expired($token)){
                //display form
                $reset_password_html = "<form action='reset.php' method='post'>
                    <p>You're about to reset your password</p>
                    <input class='form-input' type='password' name='password' placeholder='Enter new password...' id='password'>
                    <input class='form-input' type='password' name='confirm-password' placeholder='Confirm new password...' id='confirm-password'>
                    <input class='form-input' type='hidden' name='email' id='email' value='$email'>
                    <input type='hidden' name='password-reset-token' id='password-reset-token' value='$token'>
                    <input class='button primary-bg' type='submit' value='Reset password' name='reset-password'>
                </form>";
            }
            else{
                // Display error message
                $reset_password_html = "<h2>This link has expired</h2>";
            }
        }else if(isset($_POST["reset-password"])){
            // Loading the reset password page to enter new password
            // Get data from the form
            $email = addslashes(strtolower($_POST['email']));
            $token = $_POST['password-reset-token'];
            $password1 = addslashes($_POST['password']);
            $password2 = addslashes($_POST['confirm-password']);
            // Create user and attempt registration
            $user = new User();
            $user->set_email($email);
            if(!$user->is_token_expired($token)){
                //display form
                if($user->change_password($token, $password1, $password2)){
                    $reset_password_html = "<h2>Password successfully changed</h2>";
                    if($user->login($password1)){
                        $_SESSION['mystocks_auth'] = true;
                        $serialized_user = serialize($user);
                        $_SESSION['user'] = $serialized_user;
                        echo '<script>
                        window.location = "./";
                        </script>';
                    }
                    else{
                        echo "Somthing went wrong while changing the password.";
                    }
                }
                else{
                    $errors = $user->errors;
                    
                $reset_password_html = "<form action='reset.php' method='post'>
                <p>You're about to reset your password</p>
                <input class='form-input' type='password' name='password' placeholder='Enter new password...' id='password'>
                <input class='form-input' type='password' name='confirm-password' placeholder='Confirm new password...' id='confirm-password'>
                <input class='form-input' type='hidden' name='email' id='email' value='$email'>
                <input type='hidden' name='password-reset-token' id='password-reset-token' value='$token'>
                <input class='button primary-bg' type='submit' value='Reset password' name='reset-password'>
            </form>";
                }
            }
            else{
                // Display error message
                $reset_password_html = "<h2>This link has expired</h2>";
            }
        }
    }
// Go to home page
if(isset($public_page)){
    
    $ROOT =  __DIR__ ;
    include_once("$ROOT/controller.php");
    include_once("$ROOT/class_lib.php");
    if(isset($_POST["subscribe"])){
        $email = $_POST['subscriber-email'];
        $subscriber = new User();
        if($subscriber->subscribe($email)){
            // subscription successful
            $_SESSION['subscribed'] = true;
            // toast success
        }else{
            // an error occured
            $errors = $subscriber->errors;
        }
    }
    else if(isset($_POST["unsubscribe"])){
        $email = $_POST['email-address'];
        $subscriber = new User();
        if($subscriber->unsubscribe($email)){
            // unsubscription successful
            $_SESSION['subscribed'] = false;
            echo '<script>
            window.location = "./";
            </script>';
            // toast success
        }else{
            // an error occured
            $errors = $subscriber->errors;
        }
    }
    function display($type, $filter, $condition=""){
        $controller =  new Controller();
        $fetched_data = $controller->get($type, $filter, $condition);
        $html = "";
        $count = 0;
        switch($type){
            case "alerts":
                // how to display alerts
                $active = array();
                $inactive = array();
                while($fetched_data && $row = $fetched_data -> fetch_array()){
                    $id = $row[0];
                    $symbol = strtoupper($row[2]);
                    $low = number_format($row[3], 2);
                    $high = number_format($row[4], 2);
                    $status = $row[5];
                    if ($status == "deleted"){
                        continue;
                    }
                    $creation_date = strtotime($row[6]);
                    $creation_date = date('d F Y h:m:s', $creation_date);
                    $modified = strtotime($row[7]);
                    $modified = date('d F Y h:m:s', $modified);
                    $pipe = ($high> 0) ? " | ": '';
                    $target = ($low > -1) ? "<small>Below <b>ZAR $low</b></small>$pipe": '';
                    $target .= ($high> 0) ? "<small>Above <b>ZAR $high</b></small>": '';
                    $user_email = $row[1];
                    $user = new User();
                    $user->get_item_from_db($user_email);
                    $user = $user->get_full_name();
                    $actions = ($status == 'active') ? "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='pauseAlert(\"$id\");'><img src='assets/images/tick_icon.png'></div>
                    <div class='small-icon' onclick='deleteAlert(\"$id\");'><img src='assets/images/cancel_icon.png'></div>
                    </div>" : "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='restoreAlert(\"$id\")'><img src='assets/images/repeat_icon.png'></div>
                    </div>";
                    $colour = ($status == 'active') ? 'secondary-bg': 'neutral-bg alternative-txt';
                    $html =  "<div class='alert-card $colour'>
                        $actions
                        <div onclick='request(\"fetch-alert-data\", \"$id\", \"populate-alert-editor\");'>
                            <h2>$symbol</h2>
                            <span>$target<span>
                            </div>
                        </div>";
                    if ($status == 'active'){
                        array_push($active, $html);
                    } else if ($status == "inactive"){
                        array_push($inactive, $html);
                    }
                    $count++;
                }
                if($count == 0){
                    echo "<div class='neutral-bg alert-card'>You don't have any alerts.</div><br><div class='alert-car'><small>Add a <b onclick='showAddForm();'>New Alert</b> by clicking the purple '+' at the bottom right.</small></div>";
                }else{
                    if (count($active) > 0){
                        echo "<h2>Upcoming <small>(" . count($active) . ")</small></h2>";
                    }
                    foreach ($active as $alert){
                        echo $alert;
                    }
                    if (count($inactive) > 0){
                        echo "<h2>Expired <small>(" . count($inactive) . ")</small></h2>";
                    }
                    foreach ($inactive as $alert){
                        echo $alert;
                    }

                }
            break;
            echo $html;
        }
    }
}else if(isset($private_page) && $_SESSION['mystocks_auth']){
    $ROOT =  __DIR__ ;
    include_once("$ROOT/controller.php");
    include_once("$ROOT/class_lib.php");
    function display($type, $filter){
        $fetched_data = "";
        $controller =  new Controller();
        $fetched_data = $controller->get($type, $filter);

        $html = "";
        $count = 0;
        switch($type){
            case "alerts":
                // how to display alerts
                $active = array();
                $inactive = array();
                while($fetched_data && $row = $fetched_data -> fetch_array()){
                    $id = $row[0];
                    $symbol = strtoupper($row[2]);
                    $low = number_format($row[3], 2);
                    $high = number_format($row[4], 2);
                    $status = $row[5];
                    if ($status == "deleted"){
                        continue;
                    }
                    $creation_date = strtotime($row[6]);
                    $creation_date = date('d F Y h:m:s', $creation_date);
                    $modified = strtotime($row[7]);
                    $modified = date('d F Y h:m:s', $modified);
                    $pipe = ($high> 0) ? " | ": '';
                    $target = ($low > -1) ? "<small>Below <b>ZAR $low</b></small>$pipe": '';
                    $target .= ($high> 0) ? "<small>Above <b>ZAR $high</b></small>": '';
                    $user_email = $row[1];
                    $user = new User();
                    $user->get_item_from_db($user_email);
                    $user = $user->get_full_name();
                    $actions = ($status == 'active') ? "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='pauseAlert(\"$id\");'><img src='assets/images/tick_icon.png'></div>
                    <div class='small-icon' onclick='deleteAlert(\"$id\");'><img src='assets/images/cancel_icon.png'></div>
                    </div>" : "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='restoreAlert(\"$id\")'><img src='assets/images/repeat_icon.png'></div>
                    </div>";
                    $colour = ($status == 'active') ? 'secondary-bg': 'neutral-bg alternative-txt';
                    $html =  "<div class='alert-card $colour'>
                        $actions
                        <div onclick='request(\"fetch-alert-data\", \"$id\", \"populate-alert-editor\");'>
                            <h2>$symbol</h2>
                            <span>$target<span>
                            </div>
                        </div>";
                    if ($status == 'active'){
                        array_push($active, $html);
                    } else if ($status == "inactive"){
                        array_push($inactive, $html);
                    }
                    $count++;
                }
                if($count == 0){
                    echo "<div class='neutral-bg alert-card'>You don't have any alerts.</div><br><div class='alert-car'><small>Add a <b onclick='showAddForm();'>New Alert</b> by clicking the purple '+' at the bottom right.</small></div>";
                }else{
                    if (count($active) > 0){
                        echo "<h2>Upcoming <small>(" . count($active) . ")</small></h2>";
                    }
                    foreach ($active as $alert){
                        echo $alert;
                    }
                    if (count($inactive) > 0){
                        echo "<h2>Expired <small>(" . count($inactive) . ")</small></h2>";
                    }
                    foreach ($inactive as $alert){
                        echo $alert;
                    }

                }
            break;
            case "subscribers":
                // // how to display subscribers
                while($fetched_data && $row = $fetched_data -> fetch_array()){
                    $email = $row[0];
                    $status = $row[1];
                    $modified = $row[3];
                    $classes = 'active-theme';
                    $actions = "<div class='button unsubscribe' onclick='unsubscribe_item(\"$email\")'>Unsubscribe</div>";
                    if($status == "unsubscribed"){
                        $classes = 'danger-bg';
                        $actions = "<div class='button subscribe' onclick='subscribe_item(\"$email\")'>Resubscribe</div>";
                    }
                    $tmp = "
                        <details class='alert-panel'>
                            <summary class='$classes'>
                                    $email
                                    <small>Modified on $modified</small>
                            </summary>
                            $actions
                        </details>
                    ";
                    $html .= $tmp;
                    $count++;
                }
                if($count==0){
                    $html = "No subscribers available.";
                }
            break;
            case "users":
                // how to display users
                while($fetched_data && $row = $fetched_data -> fetch_row()){
                    $email = $row[0];
                    $first_name = $row[2];
                    $last_name = $row[3];
                    $type = $row[4];
                    $modified = $row[6];
                    $actions = "";
                    if (($GLOBALS["current_user"]->get_email() != $email) && ($GLOBALS["current_user"]->get_type() == "admin" || $GLOBALS["current_user"]->get_type() == "root")){
                        $actions .= "<select onchange = 'changeUserRole(\"$email\", this.value);'>";
                        $actions .="<option selected disabled hidden>Change user Role</option>";
                        if($type != "admin"){
                            $actions .= "<option value='admin'>Make admin</option>";
                        }
                        if($type != "support"){
                            $actions .= "<option value='support'>Make support</option>";
                        }
                        if($type != "regular"){
                            $actions .= "<option value='regular'>Make regular</option>";
                        } 
                        $actions .="</select>";
                    }
                    $who_is_this ="";
                    if($GLOBALS["current_user"]->get_email() == $email){
                        $who_is_this = "[My account]";
                    }
                    $tmp = "
                        <details class='alert-panel'>
                            <summary>
                                <b>$who_is_this</b>
                                    $first_name $last_name ($email)<br>
                                    <small>Modified on $modified</small>
                            </summary>
                            $actions
                        </details>
                    ";
                    $html .= $tmp;
                    $count++;
                }
                if($count==0){
                    $html = "No users available.";
                }
            break;
        }
        echo $html;
    }
}
else if(isset($_REQUEST['request'])){
    
    function display($type, $filter, $condition=""){
        $controller =  new Controller();
        $fetched_data = $controller->get($type, $filter, $condition);
        $html = "";
        $count = 0;
        switch($type){
            case "alerts":
                // how to display alerts
                $active = array();
                $inactive = array();
                while($fetched_data && $row = $fetched_data -> fetch_array()){
                    $id = $row[0];
                    $symbol = strtoupper($row[2]);
                    $low = number_format($row[3], 2);
                    $high = number_format($row[4], 2);
                    $status = $row[5];
                    if ($status == "deleted"){
                        continue;
                    }
                    $creation_date = strtotime($row[6]);
                    $creation_date = date('d F Y h:m:s', $creation_date);
                    $modified = strtotime($row[7]);
                    $modified = date('d F Y h:m:s', $modified);
                    $pipe = ($high> 0) ? " | ": '';
                    $target = ($low > -1) ? "<small>Below <b>ZAR $low</b></small>$pipe": '';
                    $target .= ($high> 0) ? "<small>Above <b>ZAR $high</b></small>": '';
                    $user_email = $row[1];
                    $user = new User();
                    $user->get_item_from_db($user_email);
                    $user = $user->get_full_name();
                    $actions = ($status == 'active') ? "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='pauseAlert(\"$id\");'><img src='assets/images/tick_icon.png'></div>
                    <div class='small-icon' onclick='deleteAlert(\"$id\");'><img src='assets/images/cancel_icon.png'></div>
                    </div>" : "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='restoreAlert(\"$id\")'><img src='assets/images/repeat_icon.png'></div>
                    </div>";
                    $colour = ($status == 'active') ? 'secondary-bg': 'neutral-bg alternative-txt';
                    $html =  "<div class='alert-card $colour'>
                        $actions
                        <div onclick='request(\"fetch-alert-data\", \"$id\", \"populate-alert-editor\");'>
                            <h2>$symbol</h2>
                            <span>$target<span>
                            </div>
                        </div>";
                    if ($status == 'active'){
                        array_push($active, $html);
                    } else if ($status == "inactive"){
                        array_push($inactive, $html);
                    }
                    $count++;
                }
                if($count == 0){
                    echo "<div class='neutral-bg alert-card'>You don't have any alerts.</div><br><div class='alert-car'><small>Add a <b onclick='showAddForm();'>New Alert</b> by clicking the purple '+' at the bottom right.</small></div>";
                }else{
                    if (count($active) > 0){
                        echo "<h2>Upcoming <small>(" . count($active) . ")</small></h2>";
                    }
                    foreach ($active as $alert){
                        echo $alert;
                    }
                    if (count($inactive) > 0){
                        echo "<h2>Expired <small>(" . count($inactive) . ")</small></h2>";
                    }
                    foreach ($inactive as $alert){
                        echo $alert;
                    }

                }
            break;
            echo $html;
        }
    }
    $private_page = true;
    $ROOT =  __DIR__ ;
    include_once("$ROOT/controller.php");
    include_once("$ROOT/class_lib.php");
    $request = $_REQUEST["request"];
    $arguments = explode("~", $_REQUEST["arguments"]);
    if($request == "save-alert" ){
        $symbol = strtoupper(htmlentities($arguments[0]));
        $low = htmlentities($arguments[1]);
        $low = ($low != "") ? $low : -1;
        $high = $arguments[2];
        $high = ($high != "") ? $high : -1;
        $id = $arguments[3];
        $creator_email = $current_user->get_email();

        $alert = new Alert();
        $alert->set_details($symbol, $low, $high, $creator_email, $id);
        $response = $current_user->save_alert($alert);
        if(!$response){
            $errors = join(";", $current_user->errors);
            echo "<b>Error:</b><br>$errors";
        }else{
            if ($id != ""){
                echo "$symbol alert updated.";
            }else{
                echo "New alert created for $symbol.";
            }
        }
    }else if ($request == "display-alerts"){
        $condition = "`user_email` = '" . $current_user->get_email() . "'";
        display('alerts', '*', $condition);
    }else if ($request == "subscribe"){
        $current_user->subscribe($arguments[0]);
        if($current_user->is_subscribed($arguments[0])){
            echo "User successfully subscribed.";
        }else{
            echo "<b>Error:</b> Something went wrong.";
        }
    }else if ($request == "unsubscribe"){
        $current_user->unsubscribe($arguments[0]);
        if(!$current_user->is_subscribed($arguments[0])){
            echo "User successfully unsubscribed.";
        }else{
            echo "<b>Error:</b> Something went wrong.";
        }
    }else if ($request == "delete-alert"){
        $alert = new Alert();
        $id =  $arguments[0];
        $alert->set_id($id);
        return $current_user->delete_alert($alert);
    }else if ($request == "pause-alert"){
        $alert = new Alert();
        $id =  $arguments[0];
        $alert->set_id($id);
        return $current_user->pause_alert($alert);
    }else if ($request == "restore-alert"){
        $alert = new Alert();
        $id =  $arguments[0];
        $alert->set_id($id);
        return $current_user->restore_alert($alert);
    } else if ($request == "fetch-alert-data-with-br"){
        $id =  $arguments[0];
        $alert = new Alert();
        $alert->set_id($id);
        $alert->refresh_from_db_by_id($id);
        $title = $alert->get_title();
        $content = nl2br($alert->get_content());
        $image = $alert->get_image();
        $pdf = $alert->get_pdf();
        echo "$title [~] $content [~] $image [~] $pdf [~] $id";
        return true;
    } else if ($request == "fetch-alert-data"){
        $id =  $arguments[0];
        $alert = new Alert();
        $alert->set_id($id);
        $alert->refresh_from_db_by_id($id);
        $symbol = $alert->get_symbol();
        $low = $alert->get_low();
        $high = $alert->get_high();
        echo "$symbol [~] $low [~] $high [~] $id";
        return true;
    } else if ($request == "load-alerts"){
        displayForDashboard("alerts", "*");
        return true;
    } else if ($request == "load-subscribers"){
        displayForDashboard("subscribers", "*");
        return true;
    } else if ($request == "load-users"){
        displayForDashboard("users", "*");
        return true;
    } else if ($request == "change-user-role"){
        if ($current_user->get_type() == 'admin' ||$current_user->get_type() == 'root'){
            
            // echo $arguments[1];
            $user = new User();
            $user->set_email($arguments[0]);
            $user->set_type($arguments[1]);
            $result = $current_user->update($user);
            if ($result){
                echo "User successfully updated.";
            }
            return $result;
        }
        return false;
    } else{
        echo "Request '$request' received but not processed.";
    }
}
else{
    echo '<script>
    window.location = "./";
    </script>';
}


$ROOT =  __DIR__ ;
include_once("$ROOT/controller.php");
include_once("$ROOT/class_lib.php");
function displayForDashboard($type, $filter){
    $fetched_data = "";
    $controller =  new Controller();
    $fetched_data = $controller->get($type, $filter);

    $html = "";
    $count = 0;
    switch($type){
        case "alerts":
            // how to display alerts
                $active = array();
                $inactive = array();
                while($fetched_data && $row = $fetched_data -> fetch_array()){
                    $id = $row[0];
                    $symbol = strtoupper($row[2]);
                    $low = number_format($row[3], 2);
                    $high = number_format($row[4], 2);
                    $status = $row[5];
                    if ($status == "deleted"){
                        continue;
                    }
                    $creation_date = strtotime($row[6]);
                    $creation_date = date('d F Y h:m:s', $creation_date);
                    $modified = strtotime($row[7]);
                    $modified = date('d F Y h:m:s', $modified);
                    $pipe = ($high> 0) ? " | ": '';
                    $target = ($low > -1) ? "<small>Below <b>ZAR $low</b></small>$pipe": '';
                    $target .= ($high> 0) ? "<small>Above <b>ZAR $high</b></small>": '';
                    $user_email = $row[1];
                    $user = new User();
                    $user->get_item_from_db($user_email);
                    $user = $user->get_full_name();
                    $actions = ($status == 'active') ? "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='pauseAlert(\"$id\");'><img src='assets/images/tick_icon.png'></div>
                    <div class='small-icon' onclick='deleteAlert(\"$id\");'><img src='assets/images/cancel_icon.png'></div>
                    </div>" : "<div class='alert-actions-panel'>
                    <div class='small-icon' onclick='restoreAlert(\"$id\")'><img src='assets/images/repeat_icon.png'></div>
                    </div>";
                    $colour = ($status == 'active') ? 'secondary-bg': 'neutral-bg alternative-txt';
                    $html =  "<div class='alert-card $colour'>
                        $actions
                        <div onclick='request(\"fetch-alert-data\", \"$id\", \"populate-alert-editor\");'>
                            <h2>$symbol</h2>
                            <span>$target<span>
                            </div>
                        </div>";
                    if ($status == 'active'){
                        array_push($active, $html);
                    } else if ($status == "inactive"){
                        array_push($inactive, $html);
                    }
                    $count++;
                }
            if($count == 0){
                $html = "No alerts available.";
            }else{
                if (count($active) > 0){
                    echo "<h2>Upcoming <small>(" . count($active) . ")</small></h2>";
                }
                foreach ($active as $alert){
                    echo $alert;
                }
                if (count($inactive) > 0){
                    echo "<h2>Expired <small>(" . count($inactive) . ")</small></h2>";
                }
                foreach ($inactive as $alert){
                    echo $alert;
                }
            }
        break;
        case "subscribers":
            // // how to display subscribers 
            // $tmp = $fetched_data -> fetch_row()[0];
            // echo "<script>console.log('$tmp');</script>";
            while($fetched_data && $row = $fetched_data -> fetch_array()){
                $email = $row[0];
                $status = $row[1];
                $modified = $row[3];
                $classes = 'active-theme';
                $icon = 'tick';
                    $actions = "<div class='button unsubscribe right' onclick='unsubscribe_item(\"$email\")'>Unsubscribe</div>";
                    if($status == "unsubscribed"){
                        $classes = 'unsubscribe';
                        $icon = 'close';
                        $actions = "<div class='button subscribe right' onclick='subscribe_item(\"$email\")'>Resubscribe</div>";
                    }
                    $tmp = "
                        <details class='alert-panel'>
                            <summary class='normal-theme'>
                                $actions
                                <div class='indicator $classes shadow'><img class='small-icon' src='assets/images/icon-$icon.png'></div>
                                <b>$email</b><br>
                                <small>Modified on $modified<br><i>This user is <b>$status</b></i></small>
                            </summary>
                        </details>
                    ";
                $html .= $tmp;
                $count++;
            }
            if($count==0){
                $html = "No subscribers available.";
            }
        break;
        case "users":
            // how to display users
            while($fetched_data && $row = $fetched_data -> fetch_row()){
                $email = $row[0];
                $first_name = $row[2];
                $last_name = $row[3];
                $type = $row[4];
                $modified = $row[6];
                $actions = "";
                if (($GLOBALS["current_user"]->get_email() != $email) && ($GLOBALS["current_user"]->get_type() == "admin" || $GLOBALS["current_user"]->get_type() == "root")){
                    $actions .= "<select onchange = 'changeUserRole(\"$email\", this.value);'>";
                    $actions .="<option selected disabled hidden>Change user Role</option>";
                    if($type != "admin"){
                        $actions .= "<option value='admin'>Make admin</option>";
                    }
                    if($type != "support"){
                        $actions .= "<option value='support'>Make support</option>";
                    }
                    if($type != "regular"){
                        $actions .= "<option value='regular'>Make regular</option>";
                    } 
                    $actions .="</select>";
                }
                $who_is_this ="";
                $classes = "normal-theme";
                $icon = 'icon-man-user';
                if($GLOBALS["current_user"]->get_email() == $email || $type == 'root'){
                    $classes = "disabled-theme";
                    $icon = 'icon-system';
                }else{
                    if ($type == 'admin'){
                        $classes = "active-theme";
                        $icon = 'icon-admin-with-cogwheels';
                    }else if ($type == 'support'){
                        $classes = "inactive-theme";
                        $icon = 'icon-support-user';
                    }
                }
                $type = ucfirst($type);
                $tmp = "
                    <details class='alert-panel'>
                        <summary class='normal-theme'>
                            <div class='indicator $classes shadow'><img class='small-icon' src='assets/images/$icon.png'></div>
                            <div class='right'>$actions</div>
                            <b>$first_name $last_name</b><br><small>$type</small>
                        </summary>
                        <p>
                        <b>Email Address:</b> $email
                        <br>
                        <b>User role:</b> $type
                        <br>
                        <b>Modified:</b> $modified
                        </p>
                    </details>
                ";
                $html .= $tmp;
                $count++;
            }
            if($count==0){
                $html = "No users available.";
            }
        break;
    }
    echo $html;
}

function upload_image(){
    $target_dir = "../media/uploads/images/";
    $target_file = $target_dir . basename($_FILES['alert-image']["name"]);
    $url = "assets/media/uploads/images/"  . basename($_FILES['alert-image']["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['alert-image']["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        $target_file = false;
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES['alert-image']["tmp_name"], $target_file)) {
        return $url;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }
    return false;
}

function upload_pdf(){
    $target_dir = "../media/uploads/documents/";
    $target_file = $target_dir . basename($_FILES['alert-file']["name"]);
    $url = "assets/media/uploads/documents/"  . basename($_FILES['alert-file']["name"]);
    $uploadOk = 1;
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['alert-file']["tmp_name"]);
    if($_FILES['alert-file']['type'] == "application/pdf") {
        $uploadOk = 1;
    } else {
        echo "File is not a pdf.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        $target_file = false;
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES['alert-file']["tmp_name"], $target_file)) {
        return $url;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }
    return false;
}

?>