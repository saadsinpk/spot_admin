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
		
		$ID = $_POST['payout_id'];
		$name = $_POST['payout_name'];
		$subtitle = $_POST['payout_sub'];
		
		$payout_image_name = isset($_POST['payout_image_name']) ? $_POST['payout_image_name'] : $ID."_payout_image.png";
		
		$payout_amount = $_POST['payout_amount'];
		$payout_points = $_POST['payout_points'];
		$payout_msg = $_POST['payout_msg'];
		
		$result = false;
		
		require_once  "../core/class.imagehelper.php";
		
		$image = new Imagehelper\Image($_FILES);
		
		if($image["payout_image"]){
				
			$image->setSize(0, 5000000);
			$image->setMime(array('png', 'jpg','jpeg'));
			$image->setLocation("../images");
			$imageName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $payout_image_name);
			$image->setName($imageName);
			
			$upload = $image->upload();
			
			if($upload){
				
				$imageName = $image->getName().'.'.$image->getMime();
				 
				 // UPDATE WITH IMAGE
				 $sql = "UPDATE payouts SET name = '$name', subtitle = '$subtitle', message='$payout_msg', amount='$payout_amount', points = '$payout_points', image = '$imageName' WHERE id = '$ID' ";
				 $stmt = $dbo->prepare($sql);
				 if($stmt->execute()){ $result = true; }
				
			}else{
			    
				 //echo $image->getError();
				 
				 $sql = "UPDATE payouts SET name = '$name', subtitle = '$subtitle', message='$payout_msg', amount='$payout_amount', points = '$payout_points' WHERE id = '$ID' ";
				 $stmt = $dbo->prepare($sql);
				 if($stmt->execute()){ $result = true; }
			}
		}
		
		
		if($result){
			
			header("Location: ../payouts.php");
			
		}else{
			
			header("Location: ../payouts.php");
		}
		
		
	}else{
		
		header("Location: ../payouts.php");
		
	}
	
	
	

?>