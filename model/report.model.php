<?php
require_once './config/DB.php';
require_once './controllers/login_user.controller.php';

class Report
{
    public $conn;
    public $user_id;
    public $buyer_id;
    public $food_id;
    
    public function __construct()
    {
        $this->conn = new DB();
    }
    
    public function reportUser(){

    }

    public function reportFood($reportStatus){
        $statement = $this->conn->query("INSERT INTO report(user_id, buyer_id, food_id, report_status)
        VALUES(:uid, :bid, :fid, :rStatus);");
        $statement->bindParam(':uid', $this->user_id);
        $statement->bindParam(':bid', $this->buyer_id);
        $statement->bindParam(':fid', $this->food_id);
        $statement->bindParam(':rStatus', $reportStatus);
        $statement->execute();

    }
}
