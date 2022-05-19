<?php

    /*!
     * POCKET v3.8
     *
     * http://www.droidoxy.com
     * support@droidoxy.com
     *
     * Copyright DroidOXY ( http://www.droidoxy.com )
     */

    include_once("../api.inc.php");

     if(isset($_POST['device'])) {
         $offer = $dbo->prepare("SELECT * FROM offer WHERE device = '".$_POST['device']."' ORDER BY payout");
     } elseif(isset($_POST['category'])) {
         $offer = $dbo->prepare("SELECT * FROM offer WHERE categories = '".$_POST['category']."' ORDER BY payout");
     } elseif(isset($_POST['device']) AND isset($_POST['category'])) {
         $offer = $dbo->prepare("SELECT * FROM offer WHERE device = '".$_POST['device']."' AND categories = '".$_POST['category']."' ORDER BY payout");
     } else {
         $offer = $dbo->prepare("SELECT * FROM offer ORDER BY payout");
     }
     $offer->execute();
     $row = $offer->fetchAll(PDO::FETCH_ASSOC);
     $offerwalls_loaded = count($row);

    if ($offerwalls_loaded < 1) {
        
        api::printError(ERROR_UNKNOWN, "Server Not Responding");
        
    }

     echo json_encode($row);

    exit;