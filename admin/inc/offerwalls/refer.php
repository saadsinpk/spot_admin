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
        
        $configs->updateConfigs($points, 'REFER_REWARD');
        
        $configs->updateConfigs($val1, 'REFERER_BONUS_TITLE');
        $configs->updateConfigs($val2, 'REFERAL_BONUS_TITLE');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Refer & Earn Reward</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="points" placeholder="25" type="text" value="<?= $wall_data['offer_points'] ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Referer Credit Title<br><small>Users who refer other users to register</small></label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="Referral Bonus" type="text" value="<?= $configs->getConfig('REFERER_BONUS_TITLE') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Referral Credit Title<br><small>Users who get Invited to register</small></label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val2" placeholder="Invitation Bonus" type="text" value="<?= $configs->getConfig('REFERAL_BONUS_TITLE') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>