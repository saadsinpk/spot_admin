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
        
        
        $configs->updateConfigs($val1, 'AdMantum_PubId');
        $configs->updateConfigs($val2, 'AdMantum_AppId');
        $configs->updateConfigs($val3, 'AdMantum_SecretKey');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">AdMantum PubId</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="217543" type="text" value="<?= $configs->getConfig('AdMantum_PubId') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">AdMantum AppId</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val2" placeholder="11969" type="text" value="<?= $configs->getConfig('AdMantum_AppId') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">AdMantum Secret Key</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val3" placeholder="adm1234567" type="text" value="<?= $configs->getConfig('AdMantum_SecretKey') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>