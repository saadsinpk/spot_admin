<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright 2022 DroidOXY ( http://www.droidoxy.com )
	 */
	
	include_once("../core/init.inc.php");

    if (!admin::isSession()) {

        header("Location: ../index.php");
		
    }else if(!empty($_POST) && !APP_DEMO){
		
		$id = isset($_POST['id']) ? $_POST['id'] : "";
		$user = isset($_POST['user']) ? $_POST['user'] : "";
		$points_to_add = isset($_POST['points_to_add']) ? $_POST['points_to_add'] : "";
		$reason_for_adding_points = isset($_POST['reason_for_adding_points']) ? $_POST['reason_for_adding_points'] : "";
		
		$result = false;
		$timeCurrent = time();
		$_SESSION["points_added"] = 2;
		
		if($user != null){
		    
            $accountId = helper::clearInt($id);
    
            $account = new account($dbo, $accountId);
            $accountInfo = $account->get();
            $notify = new functions($dbo);
            
            if($accountInfo['username'] == $user){
                
                $newBalance = $accountInfo['points'] + $points_to_add;
                
                // Updating user Points
                $sql = "UPDATE users SET points = '$newBalance' WHERE login = '$user'";
                $stmt = $dbo->prepare($sql);
                $stmt->execute();
                
                // Updating user Tracker
                $sql = "INSERT INTO tracker(username, points, type, date) values ('$user', '$points_to_add', '$reason_for_adding_points', '$timeCurrent')";
                $stmt = $dbo->prepare($sql);
                
                if($stmt->execute()){
                    
                    $result = true;
                    $_SESSION["points_added"] = 1;
                    $notify->sendPush($accountInfo['gcm'], "credit", $points_to_add, "none", "none");
                    
                }
            }
		}
            
		if($result){
			
			header("Location: ../user-details.php?id=".$id);
			
		}else{
			
			header("Location: ../user-details.php?id=".$id);
		}
		
	}else{
		
		header("Location: ../users.php");
		
	}
?>
