<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

class webVideos extends db_connect
{

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }
    
    
    public function getSingleVideo($videoId){

        $result = array("error" => true, "error_code" => ERROR_ACCOUNT_ID);
        
        $stmt = $this->db->prepare("SELECT * FROM videos WHERE id = (:videoId) ORDER BY id DESC LIMIT 1");
        $stmt->bindParam(':videoId', $videoId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            
            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();
                
                $status = "Disabled";
                
                if($row['status'] == 1){ $status = "Active"; }
                
                
                $result = array("error" => false,
                                "video_id" => $row['id'],
                                "video_title" => $row['title'],
                                "video_url" => $row['video_url'],
                                "video_thumbnail" => $row['image'],
                                "video_subtitle" => $row['descp'],
                                "video_instructions" => $row['inst'],
                                "video_countries" => unserialize($row['countries']),
                                "video_amount" => $row['points'],
                                "video_duration" => $row['watch_time'],
                                "video_open_link" => $row['open_link'],
                                "video_added" => $row['added'],
                                "video_status" => $status);
            }
            
            
        }

        return $result;
    }

    public function getAllVideos($requestId = 0)
    {
        
        if ($requestId == 0) {

            $requestId = 2000;
        }
    
        $requests = array("error" => false, "error_code" => ERROR_SUCCESS, "videos" => array());

        $stmt = $this->db->prepare("SELECT id FROM videos WHERE id < (:requestId) ORDER BY id DESC");
        $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $requestInfo = $this->getSingleVideo($row['id']);

                array_push($requests['videos'], $requestInfo);

                unset($requestInfo);
            }
        }

        return $requests;
    }

    public function getStatus($videoid,$user)
    {
        
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM video_status WHERE username = '$user' AND videoid = '$videoid' ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();
                
                $result = array("id" => $row['id'],
                                "user" => $row['username'],
                                "video_id" => $row['videoid'],
                                "points" => $row['points'],
                                "viewed" => $row['viewed'],
                                "status" => $row['status']);
            
        }
        
        return $result;
    }
    
    public function getWatchedVideos($user)
    {
        
        $result = array("000","00");
        
        $stmt = $this->db->prepare("SELECT videoid,status FROM video_status WHERE username = (:username) ORDER BY id DESC");
        $stmt->bindParam(':username', $user, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            
            while ($row = $stmt->fetch()) {
                
                array_push($result, $row['videoid']);
                
            }
            
        }
        
        return $result;
        
    }
    
    public function getUnWatchedVideos($user)
    {
        
        $result = array("error" => false, "error_code" => ERROR_SUCCESS, "videos" => array());
        
        $all_videos = $this->getAllVideos(0);
        $watched_videos = $this->getWatchedVideos($user);
        
        foreach($all_videos['videos'] as $video){
            
            if(!in_array($video['video_id'], $watched_videos)){
                
                array_push($result['videos'], $video);
                
            }
            
        }
        
        
        return $result;
        
    }
    
    public function addVideoWatch($user, $videoId, $points)
    {
        $result = false;
		
        $time = time();
        
        $sql = "INSERT INTO video_status(username, videoid, points, viewed, status) values ('$user', '$videoId', '$points', '$time', '1')";
        $stmt = $this->db->prepare($sql);
        
        if($stmt->execute()){
            $result = true;
        }
        
		
        return $result;
    }
    
}
