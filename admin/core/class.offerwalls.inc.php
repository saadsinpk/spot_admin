<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

class offerwalls extends db_connect
{

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    public function getSingleOfferWall($id)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM offerwalls WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();
                
                $featured = false;
                $status = "Disabled";
                
                if($row['featured'] == 1){ $featured = true; }
                if($row['status'] == 1){ $status = "Active"; }
                
                $result = array("offer_id" => $row['id'],
                                "offer_title" => $row['name'],
                                "offer_subtitle" => $row['subtitle'],
                                "offer_url" => $row['url'],
                                "offer_type" => $row['type'],
                                "offer_points" => $row['points'],
                                "offer_featured" => $featured,
                                "offer_thumbnail" => $row['image'],
                                "offer_position" => $row['position'],
                                "offer_status" => $status);
            }
        }

        return $result;
    }

    public function getOfferwalls($requestId = 0)
    {
        if ($requestId == 0) {

            $requestId = 600;
            $requestId++;
        }

        $requests = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "offerwalls" => array());

        $stmt = $this->db->prepare("SELECT id FROM offerwalls WHERE id < (:requestId) ORDER BY position ASC");
        $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $requestInfo = $this->getSingleOfferWall($row['id']);

                array_push($requests['offerwalls'], $requestInfo);

                unset($requestInfo);
            }
        }

        return $requests;
    }
    
}
