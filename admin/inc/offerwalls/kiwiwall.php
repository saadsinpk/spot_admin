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
        
        
        $configs->updateConfigs($val1, 'KiwiWall_WallId');
        $configs->updateConfigs($val2, 'KiwiWall_APIKEY');
        $configs->updateConfigs($val3, 'KiwiWall_SECKEY');
        
        
    }else{
        
?>
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">KiwiWall Wall Id</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val1" placeholder="x7WSuXuvjGsHLevNVY4qiORPLFb7RBAS" type="text" value="<?= $configs->getConfig('KiwiWall_WallId') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">KiwiWall API Key</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val2" placeholder="2udP6T46zDewoo973hLiIg9h6Gj4jF3J" type="text" value="<?= $configs->getConfig('KiwiWall_APIKEY') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">KiwiWall Secret Key</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="val3" placeholder="rHyprpB1hgjhNu86ASSZ7VBXqed5nmI" type="text" value="<?= $configs->getConfig('KiwiWall_SECKEY') ?>" required=""/>
                                        </div>
                                    </div>
                                </div>


<?php } ?>