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
		
		$video_title = $_POST['video_title'];
		$video_sub = $_POST['video_sub'];
		$video_ins = $_POST['video_ins'];
		$video_url = $_POST['video_url'];
		$video_amount = $_POST['video_amount'];
		$video_dur = $_POST['video_dur'];
		
		
		$video_title = helper::clearText($video_title);
		$video_title = helper::escapeText($video_title);
		
		$video_sub = helper::clearText($video_sub);
		$video_sub = helper::escapeText($video_sub);
		
		$video_ins = helper::clearText($video_ins);
		$video_ins = helper::escapeText($video_ins);
		
		$cuurent_time = time();
		
		
		$sql = "INSERT INTO videos (title, video_url, image, descp, inst, countries, points, watch_time, open_link, added, status) VALUES ('$video_title','$video_url','none','$video_sub','$video_ins','','$video_amount', '$video_dur', 'none', '$cuurent_time', '1')";
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