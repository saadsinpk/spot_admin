<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

    if (!account::isSession()) {

        header("Location: login.php");
        exit;
    }

    // otp code
    $otp_code = isset($_REQUEST['otp_code']) ? $_REQUEST['otp_code'] : 'none';
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'none';
    
    $error = false;
    $resend_email = false;
    
    if($otp_code !== 'none'){
        
        // verify
        $result = $configs->verifyOTPCode($req_user_info['id'], $otp_code);
        
        $error = $result["error"];
        $error_code = $result["error_code"];
        $error_message = $result["error_description"];
        
        if($error == false && $error_code == ERROR_SUCCESS){
            
            // email verifed successfully
            header("Location: index.php");
            exit;
            
        }
        
        
    }elseif($action == "resend"){
        
        $configs->sendRegistrationOTPEmail($req_user_info['id'], $req_user_info['fullname'], $req_user_info['email']);
        
        $resend_email = true;
    }
    
?>