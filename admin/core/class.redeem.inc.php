<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

class redeem extends db_connect
{

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    public function getSinglePayout($id = 0)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM payouts WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();
                
                $status = "Disabled";
                
                if($row['status'] == 1){ $status = "Active"; }

                $result = array("payout_id" => $row['id'],
                                "payout_title" => $row['name'],
                                "payout_subtitle" => $row['subtitle'],
                                "payout_message" => $row['message'],
                                "payout_amount" => $row['amount'],
                                "payout_pointsRequired" => $row['points'],
                                "payout_thumbnail" => $row['image'],
                                "payout_status" => $status);
            }
        }

        return $result;
    }

    public function getPayouts($requestId = 0)
    {
        if ($requestId == 0) {

            $requestId = 20;
            $requestId++;
        }

        $requests = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "payouts" => array());

        $stmt = $this->db->prepare("SELECT id FROM payouts WHERE id < (:requestId) ORDER BY id");
        $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $requestInfo = $this->getSinglePayout($row['id']);

                array_push($requests['payouts'], $requestInfo);

                unset($requestInfo);
            }
        }

        return $requests;
    }
    
}
