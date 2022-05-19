<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright 2022 DroidOXY ( http://www.droidoxy.com )
	 */

	$pagename = 'offerwalls';
	$container = 'offerwalls';
	
	include_once("core/init.inc.php");
	$valid = false;

    if (!admin::isSession()) {

        header("Location: index.php");
        
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="ie=edge" http-equiv="x-ua-compatible" />
	<?php include_once 'inc/title.php'; ?>

    <!--Preloader-CSS-->
    <link rel="stylesheet" href="./assets/plugins/preloader/preloader.css" />

    <!--bootstrap-4-->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />

    <!--Custom Scroll-->
    <link rel="stylesheet" href="./assets/plugins/customScroll/jquery.mCustomScrollbar.min.css" />
    <!--Font Icons-->
    <link rel="stylesheet" href="./assets/icons/simple-line/css/simple-line-icons.css" />
    <link rel="stylesheet" href="./assets/icons/dripicons/dripicons.css" />
    <link rel="stylesheet" href="./assets/icons/ionicons/css/ionicons.min.css" />
    <link rel="stylesheet" href="./assets/icons/eightyshades/eightyshades.css" />
    <link rel="stylesheet" href="./assets/icons/fontawesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="./assets/icons/foundation/foundation-icons.css" />
    <link rel="stylesheet" href="./assets/icons/metrize/metrize.css" />
    <link rel="stylesheet" href="./assets/icons/typicons/typicons.min.css" />
    <link rel="stylesheet" href="./assets/icons/weathericons/css/weather-icons.min.css" />

    <!--Date-range-->
    <link rel="stylesheet" href="./assets/plugins/date-range/daterangepicker.css" />
    <!--Drop-Zone-->
    <link rel="stylesheet" href="./assets/plugins/dropzone/dropzone.css" />
    <!--Full Calendar-->
    <link rel="stylesheet" href="./assets/plugins/full-calendar/fullcalendar.min.css" />
    <!--Normalize Css-->
    <link rel="stylesheet" href="./assets/css/normalize.css" />
    <!--Main Css-->
    <link rel="stylesheet" href="./assets/css/main.css" />
    <!--Custom Css-->
    <link rel="stylesheet" href="./assets/css/custom.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<?php include_once 'inc/preloader.php'; ?>

<?php include_once 'inc/navigation.php'; ?>

<!--Page Container-->
<section class="page-container">
    <div class="page-content-wrapper">
        <!--Header Fixed-->
		<?php include_once 'inc/header-fixed.php'; ?>

        <!--Main Content-->
        <div class="content sm-gutter">
            <div class="container-fluid padding-25 sm-padding-10">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <h4>Custom Offerwall Details</h4>
                        </div>
                    </div>
					<?php if(APP_DEMO) { include_once 'inc/demo-notice.php'; } ?>
					
					<!-- START MAIN CONTENT HERE -->
					
                    <div class="col-md-12">
                        <div class="block form-block mb-4">
                            <div class="block-heading">
                                <h5>Add Custom Offerwall</h5>
                            </div>

                            <form action="process/add-offerwall.php" method="post" enctype="multipart/form-data" class="horizontal-form" />
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Position</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="position" type="number" placeholder="25" value="" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Title</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="name" type="text" placeholder="Custom Offerwall" value="" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Sub Title</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="sub" type="text" placeholder="Install Apps to Earn Points" value="" required=""/>
                                        </div>
                                    </div>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">Image</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input id="image_name" class="form-control" type="text" name="image_name" value="" placeholder="Choose Image" style="background: #e9ecef; " autocomplete="off" disabled/>
												<span class="input-group-addon text-dark"><label for="file-upload" class="custom-file-upload"><i class="ion-ios-folder"></i><span>Change Image</span></label>
													<input id="file-upload" onchange="readURL(this);" name="offer_image" accept="image/png, image/jpeg, image/jpg" type="file"/>
												</span>
											</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-row">
                                        <label class="col-md-3">OfferWall URL</label>
                                        <div class="col-md-9">
                                            <input class="form-control" name="url" placeholder="https://example.com/?user={user_id}" type="text" value="" required=""/>
                                            <br><small style="text-transform: none;">use {user_id} in the url to replace the original user id of the system</small>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <button class="btn btn-primary mr-3 pull-right" type="submit">Save Settings</button>
								<br><br>

                            </form>
                        </div>
                    </div>
	
					
					<!-- END MAIN CONTENT HERE -->
					<?php include_once 'inc/support.php'; ?>
					
                </div>
            </div>
        </div>
    </div>
	
	<?php include_once 'inc/footer-fixed.php'; ?>

</section>

<!--Jquery-->
<script type="text/javascript" src="./assets/js/jquery-3.2.1.min.js"></script>
<!--Bootstrap Js-->
<script type="text/javascript" src="./assets/js/popper.min.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
<!--Modernizr Js-->
<script type="text/javascript" src="./assets/js/modernizr.custom.js"></script>

<!--Morphin Search JS-->
<script type="text/javascript" src="./assets/plugins/morphin-search/classie.js"></script>
<script type="text/javascript" src="./assets/plugins/morphin-search/morphin-search.js"></script>
<!--Morphin Search JS-->
<script type="text/javascript" src="./assets/plugins/preloader/pathLoader.js"></script>
<script type="text/javascript" src="./assets/plugins/preloader/preloader-main.js"></script>

<!--Chart js-->
<script type="text/javascript" src="./assets/plugins/charts/Chart.min.js"></script>

<!--Sparkline Chart Js-->
<script type="text/javascript" src="./assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="./assets/plugins/sparkline/jquery.charts-sparkline.js"></script>

<!--Custom Scroll-->
<script type="text/javascript" src="./assets/plugins/customScroll/jquery.mCustomScrollbar.min.js"></script>
<!--Sortable Js-->
<script type="text/javascript" src="./assets/plugins/sortable2/sortable.min.js"></script>
<!--DropZone Js-->
<script type="text/javascript" src="./assets/plugins/dropzone/dropzone.js"></script>
<!--Date Range JS-->
<script type="text/javascript" src="./assets/plugins/date-range/moment.min.js"></script>
<script type="text/javascript" src="./assets/plugins/date-range/daterangepicker.js"></script>
<!--CK Editor JS-->
<script type="text/javascript" src="./assets/plugins/ckEditor/ckeditor.js"></script>
<!--Data-Table JS-->
<script type="text/javascript" src="./assets/plugins/data-tables/datatables.min.js"></script>
<!--Editable JS-->
<script type="text/javascript" src="./assets/plugins/editable/editable.js"></script>
<!--Full Calendar JS-->
<script type="text/javascript" src="./assets/plugins/full-calendar/fullcalendar.min.js"></script>

<!--- Main JS -->
<script src="./assets/js/main.js"></script>

<script type="text/javascript">

function readURL(input) {
	
	if (input.files && input.files[0]) {
		
		var reader = new FileReader();
		
		reader.readAsDataURL(input.files[0]);
		$('#image_name').val(input.files[0].name);
		$('#image_name').prop('disabled', false);
	}
}

</script>

</body>
</html>