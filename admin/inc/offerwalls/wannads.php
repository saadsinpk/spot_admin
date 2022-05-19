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
        
        
        $configs->updateConfigs($val1, 'WannadsApiKey');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Wannads API Key</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="5de64c1be7d46893158082" type="text" value="<?= $configs->getConfig('WannadsApiKey') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>