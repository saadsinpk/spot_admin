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
	
	//  http://yoursite.com/postbacks/admantum.php?user_id={uid}&amount={virtual_currency}
	
    $click_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
    $timeCurrent = time();
    
    $configs = new functions($dbo);
    
    $type = "AdMantum offerwall Credit";
    
    // if it is a Web Rewards User
    $account = new account($dbo);
    $userdata = $account->getuserdata($click_id);
    
    $user_id = isset($userdata['username']) ? $userdata['username'] : "none";
    
    if($click_id === $user_id){
        
        // Web Rewards User
        $newBalance = $userdata['points'] + $amount;
        
        // Updating user Points
        $sql = "UPDATE users SET points = '$newBalance' WHERE login = '$user_id'";
        $stmt = $dbo->prepare($sql);
        $stmt->execute();
        
        // Updating user Tracker
        $sql = "INSERT INTO tracker(username, points, type, date) values ('$user_id', '$amount', '$type', '$timeCurrent')";
        $stmt = $dbo->prepare($sql);
        $stmt->execute();
        
        // All Good - Successfully Rewarded
        header("HTTP/1.1 200");
        
        echo "OK - Postback Success";
        
        
    }else{
        
        // Android App User
        $offerData = $configs->getofferStatusData($click_id);
        
        $offerClickId = isset($offerData['cid']) ? $offerData['cid'] : '';
        $offerStatus = isset($offerData['status']) ? $offerData['status'] : '';
        $offerUser = isset($offerData['user']) ? $offerData['user'] : '';
        
        if($offerClickId == $click_id && $offerStatus == 0){
            
            $user_id = $offerUser;
            $userdata = $account->getuserdata($user_id);
            
            $checkusername = isset($userdata['username']) ? $userdata['username'] : "none";
            
            if($checkusername != $user_id){
                
                // No User exists
                header("HTTP/1.1 400");
                
                api::printError(ERROR_UNKNOWN, "Account Mismatch");
                
            }else{
                
                $newBalance = $userdata['points'] + $amount;
                
                // Updating user Points
                $sql = "UPDATE users SET points = '$newBalance' WHERE login = '$user_id'";
                $stmt = $dbo->prepare($sql);
                $stmt->execute();
                
                $offerCompleted = $configs->completeofferStatusData($click_id);
                
                // Updating user Tracker
                $sql = "INSERT INTO tracker(username, points, type, date) values ('$user_id', '$amount', '$type', '$timeCurrent')";
                $stmt = $dbo->prepare($sql);
                
                if($stmt->execute()){ $configs->sendPush($userdata['gcm'], "credit", $amount, "none", "none"); }
                
                // All Good - Successfully Rewarded
                header("HTTP/1.1 200");
                
                echo "Postback Success";
            }
            
        }else if($offerStatus == 1){
            
            // Offer Rewarded Already
            header("HTTP/1.1 200");
            
            echo "OK - Postback Success";
            
        }else{
            
            // No such Offer Exists
            header("HTTP/1.1 400");
            
            api::printError(ERROR_UNKNOWN, "Unknown Offer Error");
            
        }
        
    }
    
?>