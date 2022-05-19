<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */


	if(!empty($_POST) && isset($_POST['installsbt'])){
	
		$database_host	= isset($_POST['host'])?$_POST['host']:"";
		$database_name	= isset($_POST['dbname'])?$_POST['dbname']:"";
		$database_username  = isset($_POST['dbuser'])?$_POST['dbuser']:"";
		$database_password  = isset($_POST['dbpass'])?$_POST['dbpass']:"";
		
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$uname = $_POST['uname'];
		$upass = $_POST['upass'];
		
		$fullname = $fname . " " . $lname;
		
		try {
			$db = new PDO('mysql:host=' . $database_host . ';dbname=' . $database_name . ';charset=utf8', $database_username, $database_password);
			// set the PDO error mode to exception
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connection = 1;
		}
			catch(PDOException $e)
		{
			$connection = 0;
		}
			
		// connection was successful
		if($connection == '1' && $database_name != "") {
			
			$config_file = file_get_contents('../admin/core/config.php');
			$config_file = str_replace("_DB_HOST_", $database_host, $config_file);
			$config_file = str_replace("_DB_NAME_", $database_name, $config_file);
			$config_file = str_replace("_DB_USER_", $database_username, $config_file);
			$config_file = str_replace("_DB_PASSWORD_", $database_password, $config_file);
			$config_file = str_replace("_INSTALL_STATUS_", "SUCCESS", $config_file);
			
			$f = @fopen("../admin/core/config.php", "w+");
			
			if (@fwrite($f, $config_file) > 0){
				
				$sql = file_get_contents('pocket_db.sql');
				
				try {
					$db->exec($sql);
				}
					catch (PDOException $e)
				{
					//Cannot process sql file
					header("Location: summary.php?error=3");
				}
				
				function generateSalt() {
                    $key = '';
                    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
                    $counter = strlen($pattern)-1;
            
                    for ($i=0; $i<3; $i++) {
            
                        $key .= $pattern{rand(0,$counter)};
                    }
            
                    return $key;
                }
				
				// Create Admin
				$salt = generateSalt();
				$passw_hash = md5(md5($upass).$salt);
				$currentTime = time();
				
				$query = "INSERT INTO admins SET username = :username, salt = :salt, password = :password, fullname = :fullname, createAt = :createAt";
				$stmt = $db->prepare($query);
				$createadmin = $stmt->execute(array(':username' => $uname, ':salt' => $salt, ':password' => $passw_hash,  ':fullname' => $fullname, ':createAt' => $currentTime));
        
			}else{
				
				//Cannot edit config.php file
				header("Location: summary.php?error=2");
			}

			@fclose($f);
			header("Location: summary.php?success=1");
			
		}else{
			//Can't connect to database
			header("Location: summary.php?error=1");
		}
		
	}else{
		
		// Not Posted the data
		header("Location: index.php");
		exit;
		
	}

?>