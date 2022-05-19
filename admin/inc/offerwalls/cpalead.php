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
        
        
        $configs->updateConfigs($val1, 'CpaLead_DirectLink');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">CpaLead Direct Link</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="https://viral782.com/list/381406" type="text" value="<?= $configs->getConfig('CpaLead_DirectLink') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>