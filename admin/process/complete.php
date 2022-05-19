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
		
    }else if(!empty($_GET) && !APP_DEMO){
		
		$ID = isset($_GET['id']) ? $_GET['id'] : "";
		$type = isset($_GET['type']) ? $_GET['type'] : "";
		
		$requests = new requests($dbo);
		
		if($type === "process"){
		    
		    $requests->ProcessingRequest($ID);
		    
		}
		
		header("Location: ../rejected-requests.php");
		exit();
		
		
    }elseif(!empty($_POST) && !APP_DEMO){
        
        
		$ID = isset($_POST['id']) ? $_POST['id'] : "";
		$type = isset($_POST['type']) ? $_POST['type'] : "";
		$note = isset($_POST['note']) ? $_POST['note'] : "";
		
		$requests = new requests($dbo);
		$result = false;
		
		if($type === "complete"){
			
			$result = $requests->CompleteRequest($ID, $note);
			
		}else if($type === "process"){
			
			$result = $requests->ProcessingRequest($ID, $note);
			
		}else if($type === "reject"){
			
			$result = $requests->RejectRequest($ID, $note);
			
		}
	    
	}
	
	header("Location: ../requests.php");
	
?>