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
        
        
        $configs->updateConfigs($val1, 'AdScendMedia_PubId');
        $configs->updateConfigs($val2, 'AdScendMedia_AdwallId');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">AdscendMedia Pub Id</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="107461" type="text" value="<?= $configs->getConfig('AdScendMedia_PubId') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">AdscendMedia Ad Wall Id</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val2" placeholder="7351" type="text" value="<?= $configs->getConfig('AdScendMedia_AdwallId') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>