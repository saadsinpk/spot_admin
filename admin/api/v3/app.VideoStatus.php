<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

include_once("../api.inc.php");

if (!empty($_POST)) {
    
    $data = $_POST['data'];
    
    $json = json_decode($data, true);

    $clientId = isset($json['clientId']) ? $json['clientId'] : 0;

    $accountId = isset($json['accountId']) ? $json['accountId'] : '';
    $accessToken = isset($json['accessToken']) ? $json['accessToken'] : '';
    
    $user = isset($json['user']) ? $json['user'] : '11';
    $video_id = isset($json['name']) ? $json['name'] : '0';
    $video_amount = isset($json['value']) ? $json['value'] : '0';
    
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    
    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);
    
    $accessToken = helper::clearText($accessToken);
    $accessToken = helper::escapeText($accessToken);
    
    $result = array("error" => true);
    $auth = new auth($dbo);
    
    if($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
        
    }else if(!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $account = new account($dbo, $accountId);
    $userdata = $account->get();
    $videos = new webVideos($dbo);
    $notify = new functions($dbo);
    $videodata = $videos->getStatus($video_id,$user);
    $date = time();
    
    $videoStatus = isset($videodata['status']) ? $videodata['status'] : '404';
    
    if($userdata['username'] != $user){
        
        api::printError(ERROR_UNKNOWN, "Account Mismatch");
    
    }else if($videoStatus == 1){
        
        api::printError(420, "Video Watched Already");
        
    }else if($videoStatus == 404){
        
        // Saving New Video watch
        $add_watchedvideo = $videos->addVideoWatch($user, $video_id, $video_amount);
        
        if($add_watchedvideo){
            
            $videoCreditTitle = $notify->getConfig('WEB_VIDEO_CREDIT_TITLE');
            
            // Updating user Points 
            $notify->updateUserBalance($accountId, $video_amount);
            
            // Updating user Tracker
            $add_transaction = $notify->addCreditTransaction($user, $video_amount, $videoCreditTitle);
            
            if($add_transaction){ $notify->sendPush($userdata['gcm'], "credit", $video_amount, "none", "none"); }
            
            $result = array("error" => false, "error_code" => ERROR_SUCCESS, "error_description" => "Video Reward Credited Successfully");
            
        }else{
            
            api::printError(ERROR_UNKNOWN, "Server Error");
            
        }
        
    }else{
        
        api::printError(ERROR_UNKNOWN, "UNKNOWN Status Error");
    }
    
    echo json_encode($result);
    exit;
}
