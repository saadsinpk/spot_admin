<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

    include_once("../api.inc.php");

    $videos = new webVideos($dbo);
    $result = $videos->getAllVideos();
    
    
    echo json_encode($result);
    exit;