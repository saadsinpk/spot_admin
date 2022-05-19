<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

/**
 * The SPL __autoload() method is one of the Magic Methods supplied in PHP. It is used to autoload
 * classes so that you do not need to 'include' them in your scripts.
 */
function autoload($class) {
	
    if (file_exists(dirname(__FILE__) . "/class." . $class . ".inc.php")) {
        require (dirname(__FILE__) . "/class." . $class . ".inc.php");
    } else {
        exit('{"error":true,"error_code":0,"error_description":"Error License Authorization"}');
    }
}

// spl_autoload_register
spl_autoload_register("autoload");
