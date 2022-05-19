<?php

    /*!
	 * POCKET v3.8
	 *
	 * http://www.droidoxy.com
	 * support@droidoxy.com
	 *
	 * Copyright DroidOXY ( http://www.droidoxy.com )
	 */

class completed extends db_connect
{

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    public function getRequestsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM Completed");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxRequestsId()
    {
        $stmt = $this->db->prepare("SELECT MAX(rid) FROM Completed");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getSingleRequest($id)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM Completed WHERE rid = (:id) LIMIT 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "rid" => $row['rid'],
                                "request_from" => $row['request_from'],
                                "dev_name" => $row['dev_name'],
                                "dev_man" => $row['dev_man'],
                                "gift_name" => stripcslashes($row['gift_name']),
                                "req_amount" => $row['req_amount'],
                                "points_used" => $row['points_used'],
                                "date" => $row['date'],
                                "status" => $row['status'],
                                "username" => $row['username']);
            }
        }

        return $result;
    }

    public function getRequests($requestId = 0)
    {
        if ($requestId == 0) {

            $requestId = $this->getMaxRequestsId();
            $requestId++;
        }

        $requests = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "requestId" => $requestId,
                        "requests" => array());

        $stmt = $this->db->prepare("SELECT rid FROM Completed WHERE rid < (:requestId) ORDER BY rid");
        $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $requestInfo = $this->getSingleRequest($row['rid']);

                array_push($requests['requests'], $requestInfo);

                $requests['requestId'] = $requestInfo['rid'];

                unset($requestInfo);
            }
        }

        return $requests;
    }

    public function getuserRequests($username)
    {
        
        $requests = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "requests" => array());

        $stmt = $this->db->prepare("SELECT rid FROM Completed WHERE username = (:username) ORDER BY rid");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $requestInfo = $this->getSingleRequest($row['rid']);

                array_push($requests['requests'], $requestInfo);

                unset($requestInfo);
            }
        }

        return $requests;
    }
	
	

}

