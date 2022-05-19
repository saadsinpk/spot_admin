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

    $payouts = new redeem($dbo);
    $result = $payouts->getPayouts();

    echo json_encode($result);
    exit;