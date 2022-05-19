<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright 2022 DroidOXY ( http://www.droidoxy.com )
	 */
	
	include_once("../core/init.inc.php"); // init
	require_once  "../core/class.imagehelper.php"; // img helper

    if (!admin::isSession()) {

        header("Location: ../index.php");
        exit;
		
    }else if(!empty($_POST) && !APP_DEMO){
		
		$ID = $_POST['id'];
		$name = $_POST['name'];
		$subtitle = $_POST['sub'];
		$position = isset($_POST['position']) ? $_POST['position'] : 100;
		
		$image_name = isset($_POST['image_name']) ? $_POST['image_name'] : $ID."_offerwall_image.png";
		$type = $_POST['type'];
		$points = isset($_POST['points']) ? $_POST['points'] : 0;
		$val1 = isset($_POST['val1']) ? $_POST['val1'] : "0000";
		$val2 = isset($_POST['val2']) ? $_POST['val2'] : "0000";
		$val3 = isset($_POST['val3']) ? $_POST['val3'] : "0000";
		
		$offerwall_url = '';
		$OFWL_SAVE = true;
		$inc_offerwall = "../inc/offerwalls/custom_offerwall.php";
		
		// process offerwall Ids here
		$inc_file = "../inc/offerwalls/".$type.".php";
        if(file_exists($inc_file)){ $inc_offerwall = $inc_file; }
		
		include($inc_offerwall);
		
		
		$image = new Imagehelper\Image($_FILES);
			
		if($image["offer_image"]){
				
			$image->setSize(0, 5000000);
			$image->setMime(array('png', 'jpg','jpeg'));
			$image->setLocation("../images");
			
				$imageName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $image_name);
				$image->setName($imageName);
			
			$upload = $image->upload();
			
			if($upload){
				
				// update with image
				
				$urlfinal = $image->getName().'.'.$image->getMime();
    		    
    		    $sql = "UPDATE offerwalls SET name = '$name', subtitle = '$subtitle', url='$offerwall_url', position='$position', points = '$points', image = '$urlfinal' WHERE id = '$ID' ";
    			$stmt = $dbo->prepare($sql);
    			$stmt->execute();
				
			}else{
				
				 //echo $image->getError();
				 
				 // update without image
		    
    		    $sql = "UPDATE offerwalls SET name = '$name', subtitle = '$subtitle', url='$offerwall_url', position='$position', points = '$points' WHERE id = '$ID' ";
    			$stmt = $dbo->prepare($sql);
    			$stmt->execute();
				
			}
		}
		
		header("Location: ../offerwalls.php");
		exit;
		
		
	}else{
		
		header("Location: ../offerwalls.php");
		exit;
		
	}
	
	
	

?>