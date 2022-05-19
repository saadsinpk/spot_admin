<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */
	
	include_once("../admin/core/init.inc.php");
	
	//  http://yoursite.com/postbacks/cpalead.php?subid={subid}&subid2={subid2}&virtual_currency={virtual_currency}
	
    $user_id = $_REQUEST['subid2'];
    $amount = $_REQUEST['virtual_currency'];
    $amount = intval($amount); // pre-rounding the amount for safety
    
    $timeCurrent = time();
    
    $configs = new functions($dbo);
    
    $type = "CpaLead offerwall Credit";
    
    // Checking Remote Ip
    if($configs->isWhitelisted($_SERVER["REMOTE_ADDR"])){
        
        $account = new account($dbo, 0);
        $userdata = $account->getuserdata($user_id);
            
        if($userdata['username'] != $user_id){ api::printError(ERROR_UNKNOWN, "Account Mismatch"); }else{
                
            $newBalance = $userdata['points'] + $amount;
                
            // Updating user Points
            $sql = "UPDATE users SET points = '$newBalance' WHERE login = '$user_id'";
            $stmt = $dbo->prepare($sql);
            $stmt->execute();
                
            // Updating user Tracker
            $sql = "INSERT INTO tracker(username, points, type, date) values ('$user_id', '$amount', '$type', '$timeCurrent')";
            $stmt = $dbo->prepare($sql);
                
            if($stmt->execute()){ 
                
                $configs->sendPush($userdata['gcm'], "credit", $amount, "none", "none"); 
                
                echo "PostBack Success";
            }
            
        }
        
	// Unknown Ip
	}else{ api::printError(ERROR_UNKNOWN, "Unknown Error"); }

?>