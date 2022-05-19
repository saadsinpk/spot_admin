<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */
	 
	 require_once ("admin/controller/controller.php");
	 
	 $appName = $configs->getConfig('APP_NAME');
	 $appDescription = $configs->getConfig('APP_DESC');
	 $appPackagename = $configs->getConfig('PACKAGE_NAME');
	 
?>
<!-- Google Play URl : https://play.google.com/store/apps/details?id=<?php echo $appPackagename; ?>-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $appName; ?> - <?php echo $appDescription; ?></title>
    <meta name="google-site-verification" content="" />
    <meta name='yandex-verification' content='' />
    <meta name="msvalidate.01" content="" />
    <meta charset="utf-8">
    <meta name="description" content="">
    <link href="assets/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
</head>
<body style="color: #525252; font-family: 'Source Sans Pro', sans-serif; font-size: 16px; -webkit-font-smoothing: antialiased; margin: 0">

    <div style="text-align: center;">
        <p style="font-size: 56px; margin: 120px 0 0 0;"><?php echo $appName; ?></p>
        <br>
         <p style="font-size: 24px; font-weight: 200; margin: 0 0 140px 0"><?php echo $appDescription; ?></p>
        <div style="margin-top: 50px;">
            <a style="text-decoration: none;" target="_blank" href="https://www.droidoxy.com/item/android-rewards-app-pocket/" rel="nofollow">
                <img src="admin/assets/images/googleplay.png">
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a style="text-decoration: none;" target="_blank" href="https://www.droidoxy.com/item/ios-rewards-app-pocket/" rel="nofollow">
                <img src="admin/assets/images/appstore.png">
            </a>
        </div>
        <div style="margin-top: 100px; font-size: 22px;">
            <a style="color: #3A6DA5; text-decoration: none;" href="dashboard/">Go to User Login --></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a style="color: #3A6DA5; text-decoration: none;" href="admin/">Go to Admin Login --></a>
        </div>
    </div>
</body>
</html>

