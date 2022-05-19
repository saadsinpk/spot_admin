<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

header("Content-type: application/json; charset=utf-8");
$numFunc = new functions($dbo);
if(!$numFunc->getConfig('ADMIN')){ api::printError(999, ""); }
