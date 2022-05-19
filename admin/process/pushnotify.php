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
		
    }else if(!empty($_POST)){
		
		$fcm_id = isset($_POST['fcm']) ? $_POST['fcm'] : '0';
		$title = $_POST['title'];
		$message = $_POST['msg'];
		$image = $_POST['img'];
		$type = "none";
		
		$notify = new functions($dbo);
		
		$result = $notify->sendPush($fcm_id, $title, $message, $image, $type);
		
		if($result){
			
			header("Location: ../push.php");
			
		}else{
			
			header("Location: ../push.php");
		}
		
	}else{
		
		header("Location: ../index.php");
		
	}
	
	
	

?>