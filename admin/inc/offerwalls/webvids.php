<?php
    
    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright 2022 DroidOXY ( http://www.droidoxy.com )
	 */
	 
	 // DO NOT EDIT OR MODIFY THIS FILE
	 
	$configs = new functions($dbo);
	
    if($OFWL_SAVE && !APP_DEMO){
        
        $configs->updateConfigs($val1, 'WEB_VIDEO_CREDIT_TITLE');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">WebPanel Video Credit Title</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="WebPanel Video Credit" type="text" value="<?= $configs->getConfig('WEB_VIDEO_CREDIT_TITLE') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>