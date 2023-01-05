<?php 
    date_default_timezone_set("Asia/Manila");
    session_start();
    if($_SESSION["type"] == "user"){
        require "../includes/connection.php";

        $employeeID = $_SESSION["employeeID"];
        $time = date("H:i:s");
        $response = array();

        try {
            $conn->beginTransaction();

            $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE employeeID=:employeeID ORDER BY id DESC");
            $sql->execute([ "employeeID" => $employeeID ]);
            $row = $sql->fetch();
            $logID = $row["id"];

            $sql = $conn->prepare("UPDATE dbo.logs SET endTime=:endTime WHERE id=:id");
            $sql->execute([ 
                "endTime"   => $time,
                "id"        => $logID
            ]);

            if($conn->commit()){
                $response["result"] = true;
                $response["msg"] = "Successfully Stoped";
            }else{
                $response["result"] = false;
                $response["msg"] = "Server Error. Try Again Later.";
            }

        }catch (Exception $e){
            $conn->rollback();
            $response["result"] = false;
            $response["msg"] = "Server Error. Try Again Later.";
        }

        echo json_encode($response);
    }
?>