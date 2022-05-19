<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

     // Install Handler
	if (!file_exists('includes/user.inc.php')) {
		
		echo '<html lang="en"><head><title>Pocket - Rewards Application</title></head>';
		echo '<body style="color: #525252; sans-serif; font-size: 16px; -webkit-font-smoothing: antialiased; margin: 0">';
		echo '<div style="text-align: center;font-size: 56px; font-weight: 200; margin: 100px 0;">';
		echo '<p style="font-size: 56px; font-weight: 200;">Pocket - Rewards Application</p><br>';
		echo '<p style="font-size: 20px; margin-bottom: 10%; line-height: 1.5;">User Dashboard Not Found <br><br>The Admin Panel you\'ve installed works for all Web, Android & IOS Versions.<br><br>But to use Web Rewards App, please Purchase and Upload User Dashboard from Web Rewards (This is optional).</p>';
		
		echo '<a href="https://www.droidoxy.com/item/web-rewards-app-pocket/" target="_blank" rel="nofollow" style="padding: 20px 20px; text-decoration: none; font-size: 14px; text-transform: uppercase;color: #fff; background: #1880c9;transition: all 0.3s;">Purchase User Dashboard</a>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://www.droidoxy.com/support/" target="_blank" rel="nofollow" style="padding: 20px 20px; text-decoration: none; font-size: 14px; text-transform: uppercase;color: #fff; background: #1880c9;transition: all 0.3s;">Need Help/Support ?</a>';
		
		echo '<p style="font-size: 14px;margin-top: 8%;">&copy; <a href="http://www.droidoxy.com/" target="_blank" style="text-decoration: none;color: inherit;">DroidOXY</a>. All Rights Reserved.</p>';
		echo '</div>';
		echo '</body>';
		exit; 
		
	}
	
?>