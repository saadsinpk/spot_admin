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
        
        $configs->updateConfigs($points, 'DAILY_REWARD');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3"><?= $wall_data['offer_title'] ?> Reward</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="points" placeholder="25" type="text" value="<?= $wall_data['offer_points'] ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>