<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

    if (account::isSession()) {

        header("Location: index.php");
        exit;
    }
	
	$user_username = '';
	
	$error = isset($_SESSION['login_error']) ? $_SESSION['login_error']: false;
	$error_message = isset($_SESSION['login_message']) ? $_SESSION['login_message']: '';
	$_SESSION['login_error'] = false;
	$_SESSION['login_message'] = '';
	
	$configs = new functions($dbo);
	
	$APP_NAME = $configs->getConfig('APP_NAME');
	$APP_DESC = $configs->getConfig('APP_DESC');
	
	function esc_attr($attr){ return htmlspecialchars($attr, ENT_COMPAT, 'UTF-8'); }

    if (!empty($_POST)) {

        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $user_username = helper::clearText($user_username);
        $user_password = helper::clearText($user_password);

        $user_username = helper::escapeText($user_username);
        $user_password = helper::escapeText($user_password);

        if (helper::getAuthenticityToken() !== $token) {

            $error = true;
            $error_message = 'Some Error, Try Again';
        }

        if (!$error) {

            $access_data = array();
            
            $user = new account($dbo);
            
            $access_data = $user->signin($user_username, $user_password);

            if ($access_data['error'] === false){

                $clientId = 0; // Desktop version

                account::createAccessToken();

                account::setSession($access_data['accountId'], account::getAccessToken());
				
				header("Location: index.php");

            } else {

                $error = true;
                $error_message = 'Incorrect login or password.';
            }
        }
    }

    helper::newAuthenticityToken();
    
?>