<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */
	 
	include_once("../core/init.inc.php");
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $video_id = isset($_REQUEST['video_id']) ? $_REQUEST['video_id'] : 0;
        $accountId = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
        
        if($video_id < 1 || $accountId < 1){
            
            api::printError(420, "Invalid Video or Try again after some time");
        }
        
        $notify = new functions($dbo);
        
        $account = new account($dbo, $accountId);
        $userData = $account->get();
        
        $videos = new webVideos($dbo);
        $videoData = $videos->getSingleVideo($video_id);
        
        
        $timeCurrent = time();
        
        if($videoData['error']){
            
            api::printError(420, "Invalid Video or Try again after some time");
        }
        
        $userName = $userData['username'];
        
        $videoWatchData = $videos->getStatus($video_id, $userName);
        $videoWatchStatus = isset($videoWatchData['status']) ? $videoWatchData['status'] : '404';
        
        
        if($videoWatchStatus == 1){
            
            api::printError(420, "Video Reward Taken Already");
            
        }else if($videoWatchStatus == 404){
            
            $video_amount = $videoData['video_amount'];
            
            // Saving New Video watch
            $add_watchedvideo = $videos->addVideoWatch($userName, $video_id, $video_amount);
        
            if($add_watchedvideo){
                
                // $videoCreditTitle = "Video Reward - ".$videoData['video_id'];
                $videoCreditTitle = $notify->getConfig('WEB_VIDEO_CREDIT_TITLE');
                
                // Updating user Points 
                $notify->updateUserBalance($accountId, $video_amount);
                
                // Updating user Tracker
                $add_credit_tn = $notify->addCreditTransaction($userName, $video_amount, $videoCreditTitle);
                
                if($add_credit_tn){ $notify->sendPush($userData['gcm'], "credit", $video_amount, "none", "none"); }
                
                $result = array("error" => false, "error_code" => ERROR_SUCCESS, "error_description" => "Video Reward Credited Successfully");
                echo json_encode($result);
                exit;
                
            }else{
                
                api::printError(ERROR_UNKNOWN, "Server Error");
                
            }
            
        }
        
        
    }else{
        
        // File Access Directly
        api::printError(101, "Invalid Client Id");
    }
    
?>