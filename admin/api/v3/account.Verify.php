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
    $ver = isset($json['ver']) ? $json['ver'] : '3.8';
    $pckg = isset($json['pckg']) ? $json['pckg'] : "none";
    
    $action = isset($json['name']) ? $json['name'] : "none";
    $otpaccess = isset($json['value']) ? $json['value'] : "none";

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $accessToken = helper::clearText($accessToken);
    $accessToken = helper::escapeText($accessToken);
    
    if ($clientId != CLIENT_ID) {
        
        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    $result = array("error" => true);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }
    
    $notify = new functions($dbo);
    
    $account = new account($dbo, $accountId);
    $userdata = $account->get();
    
    if($userdata['username'] == $user){
        
        if($action === 'verify'){
            
            // verify code
            
            $result = $notify->verifyOTPCode($accountId, $otpaccess);
            
        }else{
            
            // resend
            $notify->sendRegistrationOTPEmail($accountId, $userdata['fullname'], $userdata['email']);
            
            $result = array("error" => false, "error_code" => ERROR_SUCCESS);
            
        }
        
    }else{
        
         $result = array("error" => true,"error_code" => ERROR_UNKNOWN,"error_description" => 'Account Mismatch');
        
    }

    echo json_encode($result);
    exit;
}
