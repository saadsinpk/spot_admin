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
		
    }else if(isset($_GET['id']) && isset($_GET['action']) && !APP_DEMO){
		
		$ID = $_GET['id'];
		$status = $_GET['action'];
		
        $sql = "UPDATE payouts SET status = '$status' WHERE id = '$ID'";
        $stmt = $dbo->prepare($sql);
        
        if($stmt->execute()){
			
			header("Location: ../payouts.php");
			
		}else{
			
			header("Location: ../payouts.php");
		}
		
	}else{
		
		header("Location: ../payouts.php");
		
	}
	
	
	

?>