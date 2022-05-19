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
        
        $configs->updateConfigs($val1, 'OfferDaddy_AppId');
        $configs->updateConfigs($val2, 'OfferDaddy_AppKey');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">OfferDaddy AppId</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="78336" type="text" value="<?= $configs->getConfig('OfferDaddy_AppId') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">OfferDaddy AppKey</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val2" placeholder="4e9498d423b6b7772f2ce73158b8981c" type="text" value="<?= $configs->getConfig('OfferDaddy_AppKey') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>