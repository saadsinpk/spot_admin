<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */
	 
	 include_once("../admin/core/init.inc.php");
	 
	 $_SESSION["refererCode"] = isset($_REQUEST['refer']) ? $_REQUEST['refer']: '';
	 
	 
	 header("Location: ../dashboard/register.php");
	 exit;

?>