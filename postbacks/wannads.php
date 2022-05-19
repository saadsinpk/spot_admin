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
	
	// http://yoururl.com/postbacks/wannads.php

    $user_id = isset($_GET['subId']) ? $_GET['subId'] : "null";
    $amount = isset($_GET['reward']) ? $_GET['reward'] : 0;
    $amount = intval($amount); // just pre-rounding the amount for safety
    
    // IMP Parameter
    $status = isset($_GET['status']) ? $_GET['status'] : 0;
    
    if($status != 1){
        
        // No Need to credit user Here
        echo "OK";
        exit;
    }
    
    $timeCurrent = time();
    
    $configs = new functions($dbo);
    
    $type = "Wannads offerwall Credit";
        
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
                
            if($stmt->execute()){ 
                
                $configs->sendPush($userdata['gcm'], "credit", $amount, "none", "none"); 
                
                echo "OK";
            }
            
        }
        

?>