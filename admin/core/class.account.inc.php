<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

class account extends db_connect
{

    private $id = 0;

    public function __construct($dbo = NULL, $accountId = 0)
    {
        
        parent::__construct($dbo);
        
        $this->setId($accountId);
    }

    public function signup($username, $fullname, $password, $email, $refererCode = 0, $profile_pic = '', $reg_type = '')
    {

        $result = array("error" => true, "error_description" => "Serious issue, contact developer");

        $helper = new helper($this->db);

        if (empty($fullname)) {

            $result = array("error" => true,
                "error_code" => ERROR_UNKNOWN,
                "error_type" => 3,
                "error_description" => "Empty user full name");

            return $result;
        }

        if (!helper::isCorrectLogin($username)) {

            $result = array("error" => true,
                "error_code" => ERROR_UNKNOWN,
                "error_type" => 0,
                "error_description" => "Incorrect Username");

            return $result;
        }

        if ($helper->isLoginExists($username)) {

            $result = array("error" => true,
                "error_code" => ERROR_LOGIN_TAKEN,
                "error_type" => 0,
                "error_description" => "Username already taken");

            return $result;
        }

        if (!helper::isCorrectEmail($email)) {

            $result = array("error" => true,
                "error_code" => ERROR_UNKNOWN,
                "error_type" => 2,
                "error_description" => "Wrong email");

            return $result;
        }

        if ($helper->isEmailExists($email)) {

            $result = array("error" => true,
                "error_code" => ERROR_EMAIL_TAKEN,
                "error_type" => 2,
                "error_description" => "Email is already registered");

            return $result;
        }

        if (!helper::isCorrectPassword($password)) {

            $result = array("error" => true,
                "error_code" => ERROR_UNKNOWN,
                "error_type" => 1,
                "error_description" => "Incorrect password");

            return $result;
        }
        
        $ip_addr = helper::ip_addr();
        
        $configs = new functions($this->db);
        
        $is_oaod_enable = $configs->getConfig('OAOD_CHECK');
        
        if ($is_oaod_enable && $helper->isIpExists($ip_addr)) {

            $result = array("error" => true,
                "error_code" => ERROR_IP_TAKEN,
                "error_type" => 4,
                "error_description" => "This Device is already registered, only one Account for one device !");

            return $result;
        }

        $salt = helper::generateSalt(3);
        $refer = helper::generateRandomString();
        $passw_hash = md5(md5($password).$salt);
        $currentTime = time();

        
        $accountState = ACCOUNT_STATE_ENABLED;
        
        $email_verified = 0;
        if($reg_type === 'Google' || $reg_type === 'Facebook'){ $email_verified = 1; }

		$query = "INSERT INTO users (last_access, last_ip_addr, gcm_regid, state, fullname, salt, passw, login, email, image, regtime, regtype, ip_addr, mobile, points, refer, refered, referer, status) VALUES ('$currentTime', '$ip_addr', NULL, '$accountState', '$fullname', '$salt', '$passw_hash', '$username', '$email', '$profile_pic', '$currentTime', '$reg_type', '$ip_addr', '', '0', '$refer', '0', '', '$email_verified')";
        
		$stmt = $this->db->prepare($query);
		
		$createuser = $stmt->execute();
        
        if ($createuser) {

            $this->setId($this->db->lastInsertId());

            $result = array("error" => false,
                            'accountId' => $this->id,
                            'username' => $username,
                            'password' => $password,
                            'error_code' => ERROR_SUCCESS,
                            'error_description' => 'SignUp Success!');
            
            // Send Registration Email with otp              
            $notify = new functions($this->db);
            $verify_email_active = $notify->getConfig('VERIFY_EMAIL_ACTIVE');
            
            
            if($email_verified == 0 && $verify_email_active == 1){
                
                $notify->sendRegistrationOTPEmail($this->id, $fullname, $email);
                
            }
            
            if($refererCode !== '0' && $refererCode !== ''){
                
                $referdata = $this->getreferer($refererCode);
                
                $rererUserName = isset($referdata['username']) ? $referdata['username'] : '0';
                
                if($rererUserName !== '0'){
                    
                    $time  = time();
                    $referReward = $notify->getConfig('REFER_REWARD');
                    $referBonusTitle = $notify->getConfig('REFERAL_BONUS_TITLE');
                    $refererBonusTitle = $notify->getConfig('REFERER_BONUS_TITLE');
                    
                    $newRefererBalance = $referdata['points'] + $referReward;
                    
                    // Updating user Points & Refer status
                    $sql = "UPDATE users SET points = '$referReward', referer = '$refererCode', refered = '1' WHERE login = '$username'";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    
                    // Updating Referer Points
                    $sql = "UPDATE users SET points = '$newRefererBalance' WHERE login = '$rererUserName'";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    
                    // Updating user Tracker
                    $sql = "INSERT INTO tracker(username, points, type, date) values ('$username', '$referReward', '$referBonusTitle', '$time')";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    
                    // Updating Referer Tracker
                    $sql = "INSERT INTO tracker(username, points, type, date) values ('$rererUserName', '$referReward', '$refererBonusTitle', '$time')";
                    $stmt = $this->db->prepare($sql);
                    
                    // Notify Referer - works only if the referer using Android Device for now
                    if($stmt->execute()){ $notify->sendPush($referdata['gcm'], "referer", $referReward, "none", "none"); }
                    
                    // Invitation Bonus Added
                    $result['error_description'] = "SignUp Success, Invitation Bonus Added !";
                    
                }else{
                    
                    // Invalid Refer Code
                    $result['error_description'] = "SignUp Success, Invitation Bonus Not Added - Invalid Referer !";
                    
                }
            }

            return $result;
        }

        return $result;
    }

    public function signin($username, $password)
    {
        $access_data = array('error' => true);

        $username = helper::clearText($username);
        $password = helper::clearText($password);
        
        if (helper::isCorrectEmail($username)) {
            
            $type = "email";
            
        }else{
            $type = "login";
            
        }
        
        $sql = "SELECT salt FROM users WHERE ".$type." = (:username) LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();
            $passw_hash = md5(md5($password).$row['salt']);
            
            $sql = "SELECT id, state FROM users WHERE ".$type." = (:username) AND passw = (:password) LIMIT 1";
            $stmt2 = $this->db->prepare($sql);
            $stmt2->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt2->bindParam(":password", $passw_hash, PDO::PARAM_STR);
            $stmt2->execute();

            if ($stmt2->rowCount() > 0) {

                $row2 = $stmt2->fetch();

                $access_data = array("error" => false,
                                     "error_code" => ERROR_SUCCESS,
                                     "accountId" => $row2['id']);
            }
        }

        return $access_data;
    }

    public function logout($accountId, $accessToken)
    {
        $auth = new auth($this->db);
        $auth->remove($accountId, $accessToken);
    }

    public function newPassword($password)
    {
        $newSalt = helper::generateSalt(3);
        $newHash = md5(md5($password).$newSalt);

        $stmt = $this->db->prepare("UPDATE users SET passw = (:newHash), salt = (:newSalt) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":newHash", $newHash, PDO::PARAM_STR);
        $stmt->bindParam(":newSalt", $newSalt, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function changePassword($password)
    {
        $result = array("error" => false,
                            'error_code' => ERROR_UNKNOWN,
                            "error_type" => 1,
                            'error_description' => 'There was an issue changing password, please try again.');
                          
        if (!helper::isCorrectPassword($password)) {

            $result = array("error" => true,
                "error_code" => ERROR_UNKNOWN,
                "error_type" => 1,
                "error_description" => "Invalid password Length or Format, please choose different password.");

            return $result;
        }
        
        $newSalt = helper::generateSalt(3);
        $newHash = md5(md5($password).$newSalt);

        $stmt = $this->db->prepare("UPDATE users SET passw = (:newHash), salt = (:newSalt) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":newHash", $newHash, PDO::PARAM_STR);
        $stmt->bindParam(":newSalt", $newSalt, PDO::PARAM_STR);
        
        if($stmt->execute()){
            
            $result = array("error" => false,
                            'error_code' => ERROR_SUCCESS,
                            'error_description' => 'Your Password has been Changed Successfully.');
        }
        
        return $result;
    }
    
    public function updateAccount($fullname, $email, $mobile, $newEmail){
        
        $result = array("error" => false,
                            'error_code' => ERROR_UNKNOWN,
                            "error_type" => 1,
                            'error_description' => 'There was an issue updating your profile, please try again.');
                            
        $helper = new helper($this->db);
        
        if (empty($fullname)) {

            $result = array("error" => true,
                "error_code" => ERROR_UNKNOWN,
                "error_type" => 3,
                "error_description" => "Empty user full name");

            return $result;
        }
        
        // INcase new Email
        if($newEmail){
            
            if (!helper::isCorrectEmail($email)) {
    
                $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN,
                    "error_type" => 2,
                    "error_description" => "Wrong email format, choose a different email address.");
    
                return $result;
            }
    
            if ($helper->isEmailExists($email)) {
    
                $result = array("error" => true,
                    "error_code" => ERROR_EMAIL_TAKEN,
                    "error_type" => 2,
                    "error_description" => "Email is already registered with us, choose a different email address");
    
                return $result;
            }
            
        }
    

        $stmt = $this->db->prepare("UPDATE users SET fullname = (:name), email = (:email), mobile = (:mobile) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $fullname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":mobile", $mobile, PDO::PARAM_STR);
        
        if($stmt->execute()){
            
            $result = array("error" => false,
                            'error_code' => ERROR_SUCCESS,
                            'error_description' => 'Your Profile has been Updated Successfully.');
        }
        
        return $result;
        
    }

    public function restorePointCreate($email, $clientId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $restorePointInfo = $this->restorePointInfo();

        if ($restorePointInfo['error'] === false) {

            return $restorePointInfo;
        }

        $currentTime = time();	// Current time

        $u_agent = helper::u_agent();
        $ip_addr = helper::ip_addr();

        $hash = md5(uniqid(rand(), true));

        $stmt = $this->db->prepare("INSERT INTO restore_data (accountId, hash, email, clientId, createAt, u_agent, ip_addr) value (:accountId, :hash, :email, :clientId, :createAt, :u_agent, :ip_addr)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":hash", $hash, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":clientId", $clientId, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS,
                            'accountId' => $this->id,
                            'hash' => $hash,
                            'email' => $email);
        }

        return $result;
    }

    public function restorePointInfo()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM restore_data WHERE accountId = (:accountId) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS,
                            'accountId' => $row['accountId'],
                            'hash' => $row['hash'],
                            'email' => $row['email']);
        }

        return $result;
    }

    public function restorePointRemove()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $removeAt = time();

        $stmt = $this->db->prepare("UPDATE restore_data SET removeAt = (:removeAt) WHERE accountId = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $removeAt, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function setState($accountState)
    {

        $stmt = $this->db->prepare("UPDATE users SET state = (:accountState) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":accountState", $accountState, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function remAccount($accountId)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getState()
    {
        $stmt = $this->db->prepare("SELECT state FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['state'];
        }

        return 0;
    }

    public function get()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "last_access" => $row['last_access'],
                                "last_ip_addr" => $row['last_ip_addr'],
                                "gcm" => $row['gcm_regid'],
                                "state" => $row['state'],
                                "fullname" => stripcslashes($row['fullname']),
                                "username" => $row['login'],
                                "email" => $row['email'],
                                "regtime" => $row['regtime'],
                                "ip_addr" => $row['ip_addr'],
                                "mobile" => $row['mobile'],
                                "points" => $row['points'],
                                "refer" => $row['refer'],
                                "refered" => $row['refered'],
                                "status" => $row['status']);
            }
        }

        return $result;
    }

    public function getreferer($refererCode)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE refer = (:refercode) LIMIT 1");
        $stmt->bindParam(":refercode", $refererCode, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "last_access" => $row['last_access'],
                                "last_ip_addr" => $row['last_ip_addr'],
                                "gcm" => $row['gcm_regid'],
                                "state" => $row['state'],
                                "fullname" => stripcslashes($row['fullname']),
                                "username" => $row['login'],
                                "email" => $row['email'],
                                "regtime" => $row['regtime'],
                                "ip_addr" => $row['ip_addr'],
                                "mobile" => $row['mobile'],
                                "points" => $row['points'],
                                "refer" => $row['refer'],
                                "refered" => $row['refered'],
                                "status" => $row['status']);
                                
                                
            }
        }

        return $result;
    }

    public function getOldRefersData($username)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM referers WHERE username = (:username) LIMIT 1");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("id" => $row['id'],
                                "username" => $row['username'],
                                "referer" => $row['referer'],
                                "points" => $row['points'],
                                "type" => $row['type'],
                                "date" => $row['date']);
            }
        }

        return $result;
    }
    
    public function getuserdata($username)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE login = (:username) LIMIT 1");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "last_access" => $row['last_access'],
                                "last_ip_addr" => $row['last_ip_addr'],
                                "gcm" => $row['gcm_regid'],
                                "state" => $row['state'],
                                "fullname" => stripcslashes($row['fullname']),
                                "username" => $row['login'],
                                "email" => $row['email'],
                                "regtime" => $row['regtime'],
                                "ip_addr" => $row['ip_addr'],
                                "mobile" => $row['mobile'],
                                "points" => $row['points'],
                                "refer" => $row['refer'],
                                "refered" => $row['refered'],
                                "status" => $row['status']);
                                
            }
        }

        return $result;
    }

    public function getConfigs($fcm = 0)
    {
        
        $conf = array();
        $config = new functions($this->db);
        
        $stmt = $this->db->prepare("SELECT * FROM configuration WHERE api_status = 1");

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                while($row = $stmt->fetch()) {
                    
                    if(strlen($row['config_value']) == 1){
                        
                        if($row['config_value'] == 1){
                            
                            $conf[$row['config_name']] = true;
                            
                        }else if($row['config_value'] == 0){
                            
                            $conf[$row['config_name']] = false;
                            
                        }else{
                            
                            $conf[$row['config_name']] = $row['config_value'];
                            
                        }
                        
                    }else{
                        
                        $conf[$row['config_name']] = $row['config_value'];
                        
                    }
                    
                    
                }
            }
        }
        
        
        $config->updateAnalyticsSessions();
        
        $ipaddr = $_SERVER['REMOTE_ADDR'];
        $time = time();
        
        if ($fcm == 0) {
            
            $stmt = $this->db->prepare("UPDATE users SET last_access = (:time),last_ip_addr = (:ipaddr),gcm_regid = (:fcm) WHERE id = (:id)");
            $stmt->bindParam(":fcm", $fcm, PDO::PARAM_STR);
            $stmt->bindParam(":ipaddr", $ipaddr, PDO::PARAM_STR);
            $stmt->bindParam(":time", $time, PDO::PARAM_STR);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();

        }else{
        
            $stmt = $this->db->prepare("UPDATE users SET last_access = (:time),last_ip_addr = (:ipaddr) WHERE id = (:id)");
            $stmt->bindParam(":ipaddr", $ipaddr, PDO::PARAM_STR);
            $stmt->bindParam(":time", $time, PDO::PARAM_STR);
            $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            $stmt->execute();
            
        }
        
        return $conf;
    }

    public function getConfig()
    {
        $result = array("error" => true,"error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM configuration WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();
				//$row['config_name'] => $row['config_value']
                $result = array("config_name" => $row['config_name'],"config_value" => $row['config_value']);
            }
        }

        return $result;
    }

    public function setId($accountId)
    {
        $this->id = $accountId;
    }

    public function getId()
    {
        return $this->id;
    }
    
    static function isSession()
    {
        if (isset($_SESSION) && isset($_SESSION['user_id'])) {

            return true;

        } else {

            return false;
        }
    }

    static function setSession($user_id, $access_token)
    {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_access_token'] = $access_token;
    }

    static function unsetSession()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_access_token']);
    }

    static function getUserID()
    {
        if (isset($_SESSION) && isset($_SESSION['user_id'])) {

            return $_SESSION['user_id'];

        } else {

            return "undefined";
        }
    }

    static function getAccessToken()
    {
        if (isset($_SESSION) && isset($_SESSION['user_access_token'])) {

            return $_SESSION['user_access_token'];

        } else {

            return "undefined";
        }
    }

    static function createAccessToken()
    {
        $access_token = md5(uniqid(rand(), true));

        if (isset($_SESSION)) {

            $_SESSION['user_access_token'] = $access_token;
        }
    }
}

