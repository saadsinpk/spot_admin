<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

    include_once("../core/init.inc.php");

    if (!account::isSession()) {

        echo "Please refresh this page";
        exit;
    }
    
	$configs = new functions($dbo);
	
	// All User Data
	$req_user_info = $configs->getUserInfo(account::getUserID());
	$user_username = $req_user_info['login'];
	
	$videoscustom = new webVideos($dbo);
	$result = $videoscustom->getUnWatchedVideos($user_username);
	$videos_loaded = count($result['videos']);
	

?><!DOCTYPE html>
<?php include_once '../../dashboard/includes/vendor_comments.php'; ?>
<html lang="en">

	<!-- begin::Head -->
	<head>
	    
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
		<!--end::Fonts -->
		
		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="../../dashboard/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="../../dashboard/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles -->
		
		<link href="../assets/custom/droidoxy-custom.css" rel="stylesheet" type="text/css" />
		
	</head>
	<!-- end::Head -->
	
	<!-- begin::Body -->
	<body class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading">
	    
        <div class="col-xl-12 col-lg-12" style="padding: 0px;">
            
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__body" style="background: #f8f8fb;">
                    <div class="tab-content">
                        <div class="tab-pane active" id="custom" role="tabpanel">
                            <div class="kt-widget4">
                                
                                <?php 
                                
                                if ($videos_loaded != 0) {
                                
                                    foreach($result['videos'] as $video){?>
                                        
                                    <a href="../../dashboard/watch-video.php?id=<?php echo $video['video_id']; ?>" target="_blank">
                                        <div class="kt-portlet kt-portlet--height-fluid">
                                            <div class="kt-portlet__head kt-portlet__head--noborder">
                                            </div>
                                            <div class="kt-portlet__body">
                                                <div class="kt-widget kt-widget--user-profile-2">
                                                    <div class="kt-widget__head">
                                                        <span class="kt-media kt-media--lg kt-media--brand kt-margin-r-5 kt-margin-t-5">
                                                            <img class="kt-widget__img" src="https://img.youtube.com/vi/<?php echo helper::getYTvideoIdfromURL($video['video_url']); ?>/mqdefault.jpg" alt="">
                                                        </span>
                                                        <div class="kt-widget__info">
                                                            <a href="#" class="kt-widget__username"><?php echo $video['video_title']; ?></a>
                                                            <span class="kt-widget__desc"><?php echo $video['video_subtitle']; ?></span>
                                                        </div>
                                                    </div>
                                                    <a href="../../dashboard/watch-video.php?id=<?php echo $video['video_id']; ?>" target="_blank" class="kt-widget__footer">
                                                        <p class="btn btn-label-brand btn-lg btn-upper" style="cursor: pointer !important;"> <span class="offer-points" style=" font-size: 24px; "><?php echo $video['video_amount']; ?></span><br><span class="offer-points-title" style=" font-size: 12px; ">Points</span></p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    
                                    <?php }
                                    
                                }else{
                                    
                                    echo "<div class='no-offers-block'>No Videos at the moment, please try again</div>";
                                
                                } ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
	</body>
	<!-- end::Body -->
</html>