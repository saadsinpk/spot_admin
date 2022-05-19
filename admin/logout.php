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

    if (!admin::isSession()) {

        header('Location: ../');
    }

    if (isset($_GET['access_token'])) {

        $accessToken = (isset($_GET['access_token'])) ? ($_GET['access_token']) : '';

        if (admin::getAccessToken() === $accessToken) {

            admin::unsetSession();

            header('Location: ../');
            exit;
        }
    }

    header('Location: ../');