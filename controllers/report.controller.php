<?php
require_once './model/report.model.php';


 class ReportController{

    public function report(){

        $report = new Report();
        $verified_user_id = new login_user();
        $uid = $verified_user_id->isLoggedIn();
        $repStatusCall = "";

        $data = json_decode(file_get_contents("php://input"));
        
        $report->user_id = $data->user_id;
        $report->buyer_id = $data->buyer_id;
        $report->food_id = $data->food_id;
        
        
        $reportStatus = array("Troll", "Inappropriate", "Not food", "Spam");

        if($data->status_code == 0){
            $repStatusCall = $reportStatus[0];
        }else if($data->status_code == 1){
            $repStatusCall = $reportStatus[1];
        }else if($data->status_code == 2){
            $repStatusCall = $reportStatus[2];
        }else if($data->status_code == 3){
            $repStatusCall = $reportStatus[3];
        }

        $report->reportFood($repStatusCall);
        
    }
}