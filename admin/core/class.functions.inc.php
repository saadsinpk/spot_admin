<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

class functions extends db_connect
{
    private $requestFrom = 0;

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }
    
    public function getUserInfo($userId) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':id' => $userId));
        $dbarray = $stmt->fetch();
        /* Error occurred, return given name by default */
        //$result = count($dbarray);
        if (!$dbarray) {
            return NULL;
        }
        /* Return result array */
        return $dbarray;
    }
    
    public function getUserInfoByValue($field, $value) {
        $query = "SELECT * FROM users WHERE ".$field." = :value";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':value' => $value));
        $dbarray = $stmt->fetch();
        
        
        if (!$dbarray) {
            return NULL;
        }
        /* Return result array */
        return $dbarray;
    }
    
    public function updateUserBalance($accountId, $points)
    {
		$result = false;
		
		$sql = "UPDATE users SET points = points + '$points' WHERE id = '$accountId' LIMIT 1";
		$stmt = $this->db->prepare($sql);
		
        if($stmt->execute()){
            $result = true;
        }
		
        return $result;
    }
    
    public function addCreditTransaction($user, $points, $type)
    {
		$result = false;
		
        $time = time();
        
        $sql = "INSERT INTO tracker(username, points, type, date) values ('$user', '$points', '$type', '$time')";
        $stmt = $this->db->prepare($sql);
        
        if($stmt->execute()){
            $result = true;
        }
        
		
        return $result;
    }
    
    public function updateUserAccess($userId) {
		$result = false;
		
        $ipaddr = $_SERVER['REMOTE_ADDR'];
        $time = time();
        
        $stmt = $this->db->prepare("UPDATE users SET last_access = (:time),last_ip_addr = (:ipaddr) WHERE id = (:id)");
        $stmt->bindParam(":ipaddr", $ipaddr, PDO::PARAM_STR);
        $stmt->bindParam(":time", $time, PDO::PARAM_STR);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $result = $stmt->execute();
		
        return $result;
    }
    
    public function getUserReferredMembers($refererCode)
    {
		$stmt = $this->db->prepare("SELECT count(*) FROM users where referer = :referer");
        $stmt->execute(array(':referer' => $refererCode));

        return $number_of_rows = $stmt->fetchColumn();
    }
    
    public function getUserReferIncome($username)
    {
        $type = $this->getConfig('REFERER_BONUS_TITLE');
		$stmt = $this->db->prepare("SELECT SUM(points) FROM tracker where username = :username AND type = :type");
        $stmt->execute(array(':username' => $username, ':type' => $type));
        
        $actual_referIncome = $stmt->fetchColumn();
        
        $userIncomeFromReferredMembers = 0;
        if( $actual_referIncome > 1){ $userIncomeFromReferredMembers = $actual_referIncome; };

        return $userIncomeFromReferredMembers;
    }

    public function getUserRedeemedPoints($username)
    {
		$stmt = $this->db->prepare("SELECT SUM(points_used) FROM Requests where username = :username");
        $stmt->execute(array(':username' => $username));
        $fromRequests = $stmt->fetchColumn();
        
		$stmt = $this->db->prepare("SELECT SUM(points_used) FROM Completed where username = :username");
        $stmt->execute(array(':username' => $username));
        $fromCompleted = $stmt->fetchColumn();
        
        $userRedeemedPoints = 0;
        $actual_redeemedpoints = $fromRequests+$fromCompleted;
        if( $actual_redeemedpoints > 1){ $userRedeemedPoints = $actual_redeemedpoints; };
        
        return $userRedeemedPoints;
    }

    public function getRequestsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM Requests");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }
    
    public function mask_payoutTo($payoutTo) {
        
        $result = "du******@**il.com";
        
        if (filter_var($payoutTo, FILTER_VALIDATE_EMAIL)) {
            
            $mail_segments = explode("@", $payoutTo);
            $mail_segments[0] = substr($payoutTo, 0, 2).str_repeat("*", strlen($mail_segments[0])-2);
            
            $result =  implode("@", $mail_segments);
           
        }else{
            
            //return substr($payoutTo, 0, -4) . "****";
            $len = strlen($payoutTo);
            
            $result = substr($payoutTo, 0, 2).str_repeat('*', $len - 4).substr($payoutTo, $len - 2, 2);
            
        }
        
        return $result;
    }

    private function getMaxRequestsId()
    {
        $stmt = $this->db->prepare("SELECT MAX(rid) FROM Requests");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function calcPercent($amount,$type)
    {
		$percent = 0;
		
        if($amount < 100 && $type == "week"){
			
			$percent = $amount/1;
			
		}else if($amount < 200 && $type == "week"){
			
			$percent = $amount/2;
			
		}else if($amount < 500 && $type == "week"){
			
			$percent = $amount/5;
			
		}else if($amount < 800 && $type == "week"){
			
			$percent = $amount/8;
			
		}else if($amount < 1000){
			
			$percent = $amount/10;
			
		}else if($amount < 1500){
			
			$percent = $amount/15;
			
		}else if($amount < 2000){
			
			$percent = $amount/20;
			
		}else if($amount < 3000){
			
			$percent = $amount/30;
			
		}else if($amount < 4000){
			
			$percent = $amount/40;
			
		}else if($amount < 5000){
			
			$percent = $amount/50;
			
		}else if($amount < 7500){
			
			$percent = $amount/75;
			
		}else{
			
			$percent = $amount/100;
			
		}

        return $percent;
    }

    public function getTotalUsers()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getNewUsers()
    {
        $today = strtotime(date("d-m-Y", time()));
		$stmt = $this->db->prepare("SELECT count(*) FROM users where regtime >= :today");
        $stmt->execute(array(':today' => $today));

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getOldUsers()
    {
		$time = time();
        $oldtime = $time - 24 * 3600;
		$today = strtotime(date("d-m-Y", $time));
        $yesterday = strtotime(date("d-m-Y", $oldtime));
		$stmt = $this->db->prepare("SELECT count(*) FROM users where regtime BETWEEN :yesterday AND :today");
        $stmt->execute(array(':yesterday' => $yesterday, ':today' => $today));
		$number_of_rows = $stmt->fetchColumn();
		
		if($number_of_rows < 1){
			$number_of_rows = 1;
		}

        return $number_of_rows;
    }

    public function getTodayActiveusers()
    {
        $today = strtotime(date("d-m-Y", time()));
		$stmt = $this->db->prepare("SELECT count(*) FROM users where last_access >= :today");
        $stmt->execute(array(':today' => $today));

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getTotalTodayPoints()
    {
        $today = strtotime(date("d-m-Y", time()));
		$type = "Daily Checkin Credit";
		$stmt = $this->db->prepare("SELECT SUM(points) FROM tracker where (date >= :today AND type != :type)");
        $stmt->execute(array(':today' => $today, ':type' => $type));
        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getTotalYesterdayPoints()
    {
		$time = time();
        $oldtime = $time - 24 * 3600;
		$today = strtotime(date("d-m-Y", $time));
        $yesterday = strtotime(date("d-m-Y", $oldtime));
        $type = "Daily Checkin Credit";
		$stmt = $this->db->prepare("SELECT SUM(points) FROM tracker where (date BETWEEN :yesterday AND :today) AND type != :type)");
        $stmt->execute(array(':yesterday' => $yesterday, ':today' => $today, ':type' => $type));
		$number_of_rows = $stmt->fetchColumn();
		
		if($number_of_rows < 1){
			$number_of_rows = 1;
		}

        return $number_of_rows;
    }

    public function getTotalAllTimePoints()
    {
		$type = "Daily Checkin Credit";
		$stmt = $this->db->prepare("SELECT SUM(points) FROM tracker where type != :type");
        $stmt->execute(array(':type' => $type));
        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getTotalMonthPoints()
    {
		$type = "Daily Checkin Credit";
		$time = strtotime("01-" .date("m-Y", time()));
		$month = strtotime(date("Y-m-d", $time));
		$stmt = $this->db->prepare("SELECT SUM(points) FROM tracker where (date >= :month AND type != :type)");
        $stmt->execute(array(':month' => $month, ':type' => $type));
        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getTotalWeekPoints()
    {
		$type = "Daily Checkin Credit";
		$day = date('w');
		$week = strtotime(date('Y-m-d', strtotime('-'.$day.' days')));
		$stmt = $this->db->prepare("SELECT SUM(points) FROM tracker where (date >= :week AND type != :type)");
        $stmt->execute(array(':week' => $week, ':type' => $type));
        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getCurrentTotalPoints() {
        $sql = "SELECT SUM(points) FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $row = $stmt->fetchColumn();
    }

    public function getCompletedRequests()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM Completed");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }
	
    public function getPendingRequests()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM Requests where status = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getProcessingRequests()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM Requests where status = 2");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getRejectedRequests()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM Requests where status = 3");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getConfig($value) {
        $sql = "SELECT config_value FROM configuration WHERE config_name = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':value' => $value));
        return $row = $stmt->fetchColumn();
    }

    public function getAdminUserName() {
        $sql = "SELECT username FROM admins WHERE id = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $row = $stmt->fetchColumn();
    }
    
    public function updateAnalyticsSessions() {
		$result = false;
        $today = date("Y-m-d", time());
        $sql = "SELECT * FROM analytics WHERE date = :today LIMIT 1";
        $stmt = $this->db->prepare($sql);
		$stmt->execute(array(':today' => $today));
		$number_of_rows = $stmt->fetchColumn();
		
		if ($number_of_rows > 0) {

				$sql = "UPDATE analytics SET sessions = sessions+1 WHERE date = :today";
				$stmt = $this->db->prepare($sql);
				$result = $stmt->execute(array(':today' => $today));
            
			
        }else{
			$sql = "INSERT INTO analytics (date,sessions,requests,completed) value (:today, 1,0,0)";
			$stmt = $this->db->prepare($sql);
			$result = $stmt->execute(array(':today' => $today));
		}
		
        return $result;
    }

    public function isWhitelisted($ip)
    {
        $stmt = $this->db->prepare("SELECT id FROM whitelists WHERE ip_addr = (:ip) LIMIT 1");
        $stmt->bindParam(":ip", $ip, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                return true;
            }
        }

        return false;
    }
    

    function updateConfigs($value, $configname) {
        $sql = "UPDATE configuration SET config_value = :value WHERE config_name = :configname";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':value' => $value, ':configname' => $configname));
    }

    function updateAdnetworksIds($configname, $value) {
        $sql = "UPDATE adnetworks_ids SET config_value = :value WHERE config_name = :configname";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':value' => $value, ':configname' => $configname));
    }
    
    public function completeofferStatusData($clickId){
        
        $stmt = $this->db->prepare("UPDATE offer_status SET status = '1' WHERE cid = (:clickId) LIMIT 1");
        $stmt->bindParam(":clickId", $clickId, PDO::PARAM_INT);
        
        return $stmt->execute();
        
    }

    public function getofferStatusData($clickId)
    {
        
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM offer_status WHERE cid = (:clickId) LIMIT 1");
        $stmt->bindParam(":clickId", $clickId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "cid" => $row['cid'],
                                "user" => $row['user'],
                                "of_id" => $row['of_id'],
                                "of_title" => $row['of_title'],
                                "of_amount" => $row['of_amount'],
                                "of_url" => $row['of_url'],
                                "partner" => $row['partner'],
                                "date" => $row['date'],
                                "status" => $row['status']);
            }
        }

        return $result;
    }
    
    public function verifyOTPCode($accountId, $otpaccess)
    {
        
        $result = array("error" => true, "error_code" => 400, "error_description" => "unable to check security code");
        
        $current_time = time();
        
        $sql = "SELECT * FROM otpcodes WHERE otpcodes.code = '$otpaccess' ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute()) {
            
            if ($stmt->rowCount() > 0) {
                
                $row = $stmt->fetch();
                
                $otp_status = $row['status'];
                $otp_expire = $row['expire'];
                $otp_accountId = $row['accountId'];
                
                if($otp_accountId == $accountId && $otp_status == 0 && $otp_expire >= $current_time){
                    
                    // Update otp code status
                    $sql = "UPDATE otpcodes SET status = '1' WHERE otpcodes.code = '$otpaccess' ORDER BY id DESC LIMIT 1";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    
                    // Update user status
                    $sql = "UPDATE users SET status = '1' WHERE users.id = '$accountId' ORDER BY id DESC LIMIT 1";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    
                    $result = array("error" => false, "error_code" => ERROR_SUCCESS, "error_description" => "Security code verified successfully");
                    
                    
                }else if($otp_accountId != $accountId){
                    
                    // Invalid code - using another user code here
                    $result = array("error" => true, "error_code" => 404, "error_description" => "Invalid Security code");
                    
                    
                }else{
                    
                    // Expired otp code
                    $result = array("error" => true, "error_code" => 410, "error_description" => "Security code expired");
                    
                    
                }
                
                
            }else{
                
                // Invalid code
                $result = array("error" => true, "error_code" => 404, "error_description" => "Invalid Security code");
                
                
            }
        }
        
        return $result;
        
    }
    
    public function sendPasswordResetEmail($email, $clientId)
    {
        
        $result = array("error" =>  true, "error_code" => 404, "error_message" => "Inavlid smtp settings");
        
        $helper = new helper($this->db);
        
        if (helper::isCorrectEmail($email)) {
            
            $userInfo = $this->getUserInfoByValue('email', $email);
            
            $accountId = isset($userInfo['id']) ? $userInfo['id'] : 0;
            
            if ($accountId != 0) {
                
                if ($userInfo['state'] != ACCOUNT_STATE_BLOCKED) {
                    
                    $account = new account($this->db, $accountId);
                    $restorePointInfo = $account->restorePointCreate($email, $clientId);
                    $hash = $restorePointInfo['hash'];
                    
                    $emails = new emails($this->db);
                    $email_result = $emails->sendPasswordResetEmail($userInfo['fullname'], $userInfo['email'], $hash);
                    
                    $result['error'] = $email_result['error'];
                    $result['error_code'] = $email_result['error_code'];
                    $result['error_message'] = $email_result['error_message'];
                    
                }else{
                    
                    $result['error'] = true;
                    $result['error_code'] = 420;
                    $result['error_message'] = 'Account is either disabled or Blocked.';
                }
                
            }else{
                
                $result['error'] = true;
                $result['error_code'] = 404;
                $result['error_message'] = 'No Account is associated with the provided email address.';
            }
            
        }else{
            
            $result['error'] = true;
            $result['error_code'] = ERROR_UNKNOWN;
            $result['error_message'] = 'Incorrect email address.';
        }
        
        return $result;
        
    }
    
    public function sendRegistrationOTPEmail($accountId, $fullname, $email){
        
        $result = array("error" =>  true, "error_code" => 404, "error_message" => "Invalid smtp settings");
        
        $length = 6;    // OTP Length
        
        $email_otp = '';
        $pattern = '123456789123456789123456789123456789';
        $counter = strlen($pattern) - 1;

        for ($i = 0; $i < $length; $i++){
            $email_otp .= $pattern{rand(0, $counter)};
        }
        
        $code_expiry = time() + 1800; // 1800 secs = 30 mins
        
        $sql = "INSERT INTO otpcodes(accountId,code,expire,status) VALUES ('$accountId', '$email_otp','$code_expiry',0)";
        $stmt = $this->db->prepare($sql);
        
        if($stmt->execute()){
            
            $emails = new emails($this->db);
            $email_result = $emails->sendRegistrationOTPEmail($fullname, $email, $email_otp);
            
            $result['error'] = $email_result['error'];
            $result['error_code'] = $email_result['error_code'];
            $result['error_message'] = $email_result['error_message'];
            
            
        }else{
            error_log("EMAILS::UNABLE TO SEND EMAIL OTP TO ACCOUNT ID : ".$accountId." AND EMAIL : ".$email);
        }
        
        return $result;
        
    }

    public function sendPush($fcm_id, $title, $message, $image, $type) {
		
		$GOOGLE_API_KEY = $this->getConfig("FIREBASE_API_KEY");
		
		$fields = array(
        	'to'		=> $fcm_id ,
		'priority'	=> "high",
		'data'		=> array("title" =>$title, "message" =>$message, "image"=> $image, "type"=> $type),
        );
		
        $headers = array('https://fcm.googleapis.com/fcm/send','Content-Type: application/json','Authorization: key='.$GOOGLE_API_KEY);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Problem occurred: ' . curl_error($ch));
        }
		
        curl_close($ch);
         //echo $result;
		 //exit;
		
        return $result;
    }
	
	public function getDailyCheckinTimeLeft($userName){
	    
	    $timeLeft = -1;
	    
	    $checkinBonusTitle = $this->getConfig('CHECKIN_BONUS_TITLE');
	    
	    $sql = "SELECT * FROM tracker WHERE username = '$userName' AND type = '$checkinBonusTitle' ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            
            $row = $stmt->fetch();
            
            // "1539068949"
            $timeData = $row['date'];
            
            $timeCurrent = time();
            
            $timeCalculated = $timeData + 24 * 3600;
            
            $difference = $timeCalculated - $timeCurrent;
            
            if($timeCalculated > $timeCurrent){
                
                //api::printError(410, $diff);
                
                $timeLeft = $difference;
            }
            
        }
        
        return $timeLeft;
	    
	}

}

