<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

    include_once("../core/init.inc.php");

    if (!account::isSession()) {

        // User Not Logged in
        $result = array('error' => true, 'error_code' => 101, 'error_description' => "Invalid Client Id");
        echo json_encode($result);
        exit;
        
    }else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
	$configs = new functions($dbo);
	
	// All User Data
	$req_user_info = $configs->getUserInfo(account::getUserID());

    require_once ("Mobile_Detect.php");
    require_once ("detect.php");
    
    $id = isset($_REQUEST['orderId']) ? $_REQUEST['orderId'] : '0';
    $payoutTo = isset($_REQUEST['pos']) ? $_REQUEST['pos'] : 'Data Not Posted due to server issue - contact Developer';
	
	$dev_name = Detect::deviceType() .' - '.Detect::os();
	$dev_man = Detect::browser();
	
    $GetPdata = new redeem($dbo);
	$payoutData = $GetPdata->getSinglePayout($id);
	
	$payoutPoints = $payoutData['payout_pointsRequired'];
	
	$userPoints = $req_user_info['points'];
	$username = $req_user_info['login'];
	
	if(isset($payoutData['error_code'])){
	    
	    // Invalid Payout Id - Hence, Malicious Activity
	    $result = array('error' => true, 'error_code' => 420, 'error_description' => "Malicious Activity");
	    
	// User Has the Required Points ?	
	}else if($userPoints < $payoutPoints){
	    
	    // No Enough Points	
	    $result = array('error' => true, 'error_code' => 210, 'error_description' => "No Enough Points");
	
	// User Has the Required Points ?
	}else if($userPoints >= $payoutPoints){
		
		//Yes, Has the Points
		
		//start::  Do Redeem
		
		$newBalance = $userPoints - $payoutPoints;
        
        $time  = date("Y-m-d", time());
        
        $sql = "UPDATE users SET points = '$newBalance' WHERE login = '$username'";
        $stmt = $dbo->prepare($sql);
        
        if($stmt->execute()){
            
            $payout_title = $payoutData['payout_title'];
            $payout_amount = $payoutData['payout_amount'];
            
            $sql = "INSERT INTO Requests(request_from, dev_name, dev_man, gift_name, req_amount, points_used, date, status, username, note) VALUES ('$payoutTo', '$dev_name', '$dev_man', '$payout_title', '$payout_amount', '$payoutPoints', '$time', 0, '$username', '')";
            
            $stmt = $dbo->prepare($sql);
            
            if($stmt->execute()){
                
                $result = array('error' => true, 'error_code' => 100, 'error_description' => "Redeem Request Receievd");
                
                // Send Notification Here
                // -- No such notification method integrated in this Script yet :(
                
            }else{
                
                /**
                 * @ INCASE OF DATABASE ERROR ( IT HAPPENS ONLY ON RARE CASES LIKE SLOW INTERNET CONNECTION OR SERVER FAILURE )
                 * 
                 * Incase of Points debited From User but Request Not Added because of database Connectivity issue.
                 * It'll be saved in Error_Log File - Process it Manually or return the Points to the user Manually incase user contacts the App Admin.
                 * 
                 * Also, Please Check the Error_Log Files Regurarly for any issues.. 
                 * 
                 * if found any serious error/issue, please contact the developers AKA DROIDOXY Suport Team Immediately.
                 * 
                 * SUPPORT FORUM : http://www.droidoxy.com/support/
                 * 
                 * The Demo LOG : [16-Nov-2019 08:44:11] Points Debited but Redeem request not added to Database, the Redeem is from the user : testuser the Points Used are : 1000 request came from device : Computer - Mac OS X (Puma) - Chrome 78.0.3904.97 the Redeem was for : $1 USD Paypal Requested to : test@usertesting.com on the Date of : 2019-11-16
                 * 
                 * 
                **/
                
                error_log("Points Debited but Redeem request not added to Database, the Redeem is from the user : ".$username." the Points Used are : ".$payoutPoints." request came from device : ".$dev_name." - ".$dev_man." the Redeem was for : " .$payout_amount ." " . $payout_title . " Requested to : " . $payoutTo . " on the Date of : " .$time );
                
                // Server Error
                $result = array('error' => true, 'error_code' => 911, 'error_description' => "Db Error - Points Debited");
            }
            
        }else{
            
            // Server Error
            $result = array('error' => true, 'error_code' => 104, 'error_description' => "Db Error - Points Not Debited");
        }
		
		//end:: Do Redeem
		
	}else{
	    
	    // Unknown erorr - This shoud error not be shown
	    $result = array('error' => true, 'error_code' => 108, 'error_description' => "Contact Developer - This shoud error not be shown");
	    
	}
	
	echo json_encode($result);
	
}else{
    
    // File Access Directly
    $result = array('error' => true, 'error_code' => 101, 'error_description' => "Invalid Client Id");
    
    echo json_encode($result);
}

?>