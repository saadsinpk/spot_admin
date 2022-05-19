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
		
		$payout_name = $_POST['payout_name'];
		$payout_sub = $_POST['payout_sub'];
		$payout_image_name = $_POST['payout_image_name'];
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
			
			if(!empty($payout_image_name)){
				$imageName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $payout_image_name);
				$image->setName($imageName);
			}
			
			$upload = $image->upload();
			
			if($upload){
				
				$urlfinal = $image->getName().'.'.$image->getMime();
				
				$sql = "INSERT INTO payouts (name, subtitle, message, amount, points, image, status) VALUES ('$payout_name','$payout_sub','$payout_msg','$payout_amount','$payout_points','$urlfinal',1)";
				$stmt = $dbo->prepare($sql);
				if($stmt->execute()){ $result = true; }
				
			}else{
				
				// echo $image->getError();
				
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