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
	
	// http://yoururl.com/postbacks/offerdaddy.php?user_id={userid}&amount={amount}&tx_id={transaction_id}&offer_title={offer_name}
	
    $user_id = $_REQUEST['user_id'];
    $amount = $_REQUEST['amount'];
    
    $timeCurrent = time();
    
    $configs = new functions($dbo);
    
    $type = "OfferDaddy offerwall Credit";
    
        
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
                
            echo "1";
            
        }
        
		

?>