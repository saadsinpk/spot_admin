<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

include_once("../api.inc.php");

if (!empty($_POST)) {
    
    $data = $_POST['data'];
    
    $json = json_decode($data, true);

    $clientId = isset($json['clientId']) ? $json['clientId'] : 0;

    $accountId = isset($json['accountId']) ? $json['accountId'] : '';
    $accessToken = isset($json['accessToken']) ? $json['accessToken'] : '';
    
    $user = isset($json['user']) ? $json['user'] : '11';
    $amount = isset($json['name']) ? $json['name'] : '0';
    $type = isset($json['value']) ? $json['value'] : '0';
    
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    
    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);
    
    $accessToken = helper::clearText($accessToken);
    $accessToken = helper::escapeText($accessToken);
    
    $result = array("error" => true);
    $auth = new auth($dbo);
    
    if($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
        
    }else if(!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $account = new account($dbo, $accountId);
    $userdata = $account->get();
    $notify = new functions($dbo);
    $date = time();
    
    if($userdata['username'] != $user){
        
        api::printError(ERROR_UNKNOWN, "Account Mismatch");
    
    }else{
        
         $newBalance = $userdata['points'] + $amount;
         
         // Updating user Points 
         $sql = "UPDATE users SET points = '$newBalance' WHERE login = '$user'";
         $stmt = $dbo->prepare($sql);
         $stmt->execute();
         
         // Updating user Tracker
         $sql = "INSERT INTO tracker(username, points, type, date) values ('$user', '$amount', '$type', '$date')";
         $stmt = $dbo->prepare($sql);
         
         if($stmt->execute()){ $notify->sendPush($userdata['gcm'], "credit", $amount, "none", "none"); }
            
        $result = array("error" => false, "error_code" => ERROR_SUCCESS, "error_description" => "Amount Credited");
        
    }

    echo json_encode($result);
    exit;
}
