<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright 2022 DroidOXY ( http://www.droidoxy.com )
	 */

    include_once("core/init.inc.php");

	if (admin::isSession()) {

		header("Location: admin.php");
		
	}else{
	    
		header("Location: login.php");
	}
	
	
	?>