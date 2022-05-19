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
		
		$name = $_POST['name'];
		$subtitle = $_POST['sub'];
		$position = isset($_POST['position']) ? $_POST['position'] : 100;
		$custom_offerwall_url = isset($_POST['url']) ? $_POST['url'] : '';
		
		$type = 'custom_offerwall_'.helper::generateRandomString(6);
		
		$image_name = isset($_POST['image_name']) ? $_POST['image_name'] : $type."_image.png";
		$imageName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $image_name);
		
		$configs = new functions($dbo);
		
		require_once  "../core/class.imagehelper.php";
		
		$image = new Imagehelper\Image($_FILES);
			
		if($image["offer_image"]){
				
			$image->setSize(0, 5000000);
			$image->setMime(array('png', 'jpg','jpeg'));
			$image->setLocation("../images");
			$image->setName($imageName);
			
			$upload = $image->upload();
			
			if($upload){
				
				// image uploaded
				$imageName = $image->getName().'.'.$image->getMime();
				
			}else{
				 //echo $image->getError();
			}
		}
		
		$sql = "INSERT INTO offerwalls (name, subtitle, url, points, image, type, featured, position, status) VALUES ('$name', '$subtitle', '$custom_offerwall_url', '0', '$imageName', '$type', '1', '$position', '1')";
		$stmt = $dbo->prepare($sql);
		$stmt->execute();
		
	}
	
	header("Location: ../offerwalls.php");
	exit();
	
	
	

?>