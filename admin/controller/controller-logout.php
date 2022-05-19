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

        header('Location: ../../');
        exit;
    }

    if (isset($_GET['access_token'])) {

        $accessToken = (isset($_GET['access_token'])) ? ($_GET['access_token']) : '';

        if (account::getAccessToken() === $accessToken) {

            account::unsetSession();

            header('Location: ../../');
            exit;
        }
    }

    header('Location: ../../');
    
?>