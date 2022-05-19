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
	
	//  http://yoursite.com/postbacks/kiwiwall.php
	
    $user_id = $_REQUEST['sub_id'];
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : 0;
    
    $amount = $_REQUEST['amount'];
    $amount = intval($amount); // pre-rounding the amount for safety
    
    $timeCurrent = time();
    
    $configs = new functions($dbo);
    
    $type = "KiwiWall offerwall Credit";
    
    // Checking Remote Ip
    if($configs->isWhitelisted($_SERVER["REMOTE_ADDR"])){
        
        if($status == 1){
            
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
                    
                    echo "1";
                }
                
            }
            
        }else{ echo "1"; }
        
	// Unknown Ip
	}else{ api::printError(ERROR_UNKNOWN, "Unknown Error"); }

?>