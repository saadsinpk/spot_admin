<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

class helper extends db_connect
{

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
    }

    public function isEmailExists($user_email)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = (:email) LIMIT 1");
        $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                return true;
            }
        }

        return false;
    }
    
    public function isIpExists($user_ip)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE ip_addr = (:ip_addr) LIMIT 1");
        $stmt->bindParam(':ip_addr', $user_ip, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                return true;
            }
        }

        return false;
    }

    public function isLoginExists($username)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE login = (:username) LIMIT 1");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                return true;
            }
        }

        return false;
    }

    static function isCorrectFullname($fullname)
    {
        if (strlen($fullname) > 0) {

            return true;
        }

        return false;
    }

    static function isCorrectLogin($username)
    {
        if (preg_match("/^([a-zA-Z]{4,24})?([a-zA-Z][a-zA-Z0-9_]{4,24})$/i", $username)) {

            return true;
        }

        return false;
    }

    static function isCorrectPassword($password)
    {

        if (preg_match('/^[a-z0-9_]{6,20}$/i', $password)) {

            return true;
        }

        return false;
    }

    static function isCorrectEmail($email)
    {
        if (preg_match('/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i', $email)) {

            return true;
        }

        return false;
    }

    static function clearText($text)
    {
        $text = trim($text);
        $text = strip_tags($text);
        $text = htmlspecialchars($text);

        return $text;
    }

    static  function escapeText($text)
    {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $text = $mysqli->real_escape_string($text);

        return $text;
    }

    static function clearInt($value)
    {
        $value = intval($value);

        if ($value < 0) {

            $value = 0;
        }

        return $value;
    }

    static function ip_addr()
    {
        (string) $ip_addr = 'undefined';

        if (isset($_SERVER['REMOTE_ADDR'])) $ip_addr = $_SERVER['REMOTE_ADDR'];

        return $ip_addr;
    }

    static function u_agent()
    {
        (string) $u_agent = 'undefined';

        if (isset($_SERVER['HTTP_USER_AGENT'])) $u_agent = $_SERVER['HTTP_USER_AGENT'];

        return $u_agent;
    }

    static function generateId($n = 2)
    {
        $key = '';
        $pattern = '123456789';
        $counter = strlen($pattern) - 1;

        for ($i = 0; $i < $n; $i++) {

            $key .= $pattern{rand(0, $counter)};
        }

        return $key;
    }

    static function generateHash($n = 7)
    {
        $key = '';
        $pattern = '123456789abcdefg';
        $counter = strlen($pattern) - 1;

        for ($i = 0; $i < $n; $i++) {

            $key .= $pattern{rand(0, $counter)};
        }

        return $key;
    }

    static function generateSalt($n = 3)
    {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
        $counter = strlen($pattern)-1;

        for ($i=0; $i<$n; $i++) {

            $key .= $pattern{rand(0,$counter)};
        }

        return $key;
    }

    static function generateRandomString($length = 6)
    {
        
        return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    
    }
    
    static function getYTvideoIdfromURL($YTURL)
    {
        
        $video_id = explode("?v=", $YTURL); // For videos like http://www.youtube.com/watch?v=...
        
        if (empty($video_id[1]))
        $video_id = explode("/v/", $YTURL); // For videos like http://www.youtube.com/watch/v/..
        
        $video_id = explode("&", $video_id[1]); // Deleting any other params
        
        $video_id = $video_id[0];
        
        return $video_id;
        
    }

    static function newAuthenticityToken()
    {

        $authenticity_token = md5(uniqid(rand(), true));

        if (isset($_SESSION)) {

            $_SESSION['authenticity_token'] = $authenticity_token;
        }
    }
    
    static function goHome()
    {
        
        header("Location: index.php");
        exit;
        
    }

    static function getAuthenticityToken()
    {
        if (isset($_SESSION) && isset($_SESSION['authenticity_token'])) {

            return $_SESSION['authenticity_token'];

        } else {

            return NULL;
        }
    }

    public function getAdminFullName($id) {
		
		$sql = "SELECT fullname FROM admins WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(':id' => $id));
		return $row = $stmt->fetchColumn();   
    }

    public function getRestorePoint($hash)
    {
        $hash = helper::clearText($hash);
        $hash = helper::escapeText($hash);

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM restore_data WHERE hash = (:hash) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":hash", $hash, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            $result = array('error'=> false,
                            'error_code' => ERROR_SUCCESS,
                            'accountId' => $row['accountId'],
                            'hash' => $row['hash'],
                            'email' => $row['email']);
        }

        return $result;
    }
}

