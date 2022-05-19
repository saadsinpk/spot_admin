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
		
    }else if(isset($_GET['type']) && isset($_GET['action']) && !APP_DEMO){
		
		$type = $_GET['type'];
		$status = $_GET['action'];
		$configs = new functions($dbo);
		
        $sql = "UPDATE offerwalls SET status = '$status' WHERE type = '$type'";
        $stmt = $dbo->prepare($sql);
        
        $result = $stmt->execute();
        
        if($type === 'checkin'){
		    $result = $configs->updateConfigs($status, 'DAILY_ACTIVE');
        }else if($type === 'admantum'){
		    $result = $configs->updateConfigs($status, 'AdMantumActive');
        }else if($type === 'adscendmedia'){
		    $result = $configs->updateConfigs($status, 'AdScendMediaActive');
        }else if($type === 'cpalead'){
		    $result = $configs->updateConfigs($status, 'CpaLeadActive');
        }else if($type === 'wannads'){
		    $result = $configs->updateConfigs($status, 'WannadsActive');
        }else if($type === 'kiwiwall'){
		    $result = $configs->updateConfigs($status, 'KiwiWallActive');
        }else if($type === 'adgem'){
		    $result = $configs->updateConfigs($status, 'AdGemActive');
        }else if($type === 'offertoro'){
		    $result = $configs->updateConfigs($status, 'OfferToroActive');
        }
        
        
        if($result){
			
			header("Location: ../offerwalls.php");
			
		}else{
			
			header("Location: ../offerwalls.php");
		}
		
	}else{
		
		header("Location: ../offerwalls.php");
		
	}
	
	
	

?>