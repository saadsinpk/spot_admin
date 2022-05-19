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
	 
	
    if($OFWL_SAVE && !APP_DEMO){
        
        if(strpos($type, 'custom_offerwall_') !== false){
		    
		    // CUSTOM OFFERWALL
		    $offerwall_url = $val1;
		
		}
		
    }else{
        
        // get value
        
        if(strpos($wall_data['offer_type'], 'custom_offerwall_') !== false){
            
?>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">OfferWall URL</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="https://example.com/?user={user_id}" type="text" value="<?= $wall_data['offer_url'] ?>" required=""/>
                                            <br><small style="text-transform: none;">use {user_id} in the url to replace the original user id of the system</small>
                                        </div>
                                    </div>
                                </div>
                                
<?php

        }
        
    }
    
?>