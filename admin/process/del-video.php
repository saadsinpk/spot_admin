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
		
    }else if(isset($_GET['vid']) && !APP_DEMO){
		
		$ID = $_GET['vid'];
		
		$sql = "DELETE FROM videos WHERE id = '$ID'";
        $stmt = $dbo->prepare($sql);
		
		if($stmt->execute()){
			
			header("Location: ../videos.php");
			
		}else{
			
			header("Location: ../videos.php");
		}
		
	}else{
		
		header("Location: ../videos.php");
		
	}
	
	
	

?>