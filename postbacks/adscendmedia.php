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
	
	// http://yoursite.com/postbacks/adscendmedia.php?offerid=[OID]&name=[ONM]&rate=[CUR]&sub1=[SB1]
	
	$user_id = $_REQUEST['sub1'];
	$amount = $_REQUEST['rate'];
    $timeCurrent = time();
    
    $configs = new functions($dbo);
    
    $type = "AdScendMedia offerwall Credit";
    
    // Checking Remote Ip
    if($configs->isWhitelisted($_SERVER["REMOTE_ADDR"])){
		
        if($amount < 1000){
            
            $account = new account($dbo, 1);
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
                
                if($stmt->execute()){ $configs->sendPush($userdata['gcm'], "credit", $amount, "none", "none"); }
                
                echo "success";
            
            }
            
        }else{ api::printError(ERROR_UNKNOWN, "Unknown Data Error"); }
        
	// Unknown Ip
	}else{ api::printError(ERROR_UNKNOWN, "Unknown Error"); }
	
?>