<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

    

?>
    <!--Begin:: AdBlock Notice -->
    <div id="adblock_layout" class="alert alert-danger" role="alert" style="display: none;" >
        <div class="alert-icon"><i class="flaticon-warning kt-font-white"></i></div>
        <div class="alert-text">
            <h4 class="alert-heading"><?php echo esc_attr($configs->getConfig("NOTICE_ADBLOCK_TITLE")); ?></h4>
            <p><?php echo esc_attr($configs->getConfig("NOTICE_ADBLOCK")); ?></p>
        </div>
    </div>
    <!--End:: AdBlock Notice -->
    
    <script src="../admin/assets/custom/adblock.js"></script>