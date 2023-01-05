<?php 
    date_default_timezone_set("Asia/Manila");
    session_start();
    if($_SESSION["type"] == "user"){
        require "../includes/connection.php";

        
        $employeeID = $_SESSION["employeeID"];
        $date       = date("Y-m-d");
        $startTime  = date("H:i:s");
        $endTime    = "00:00:00";
        $response   = array();


        $sql = $conn->prepare("SELECT COUNT(*) as count FROM dbo.logs WHERE date=:date");
        $sql->execute([
            "date"  => $date
        ]);
        $row = $sql->fetch();

        if($row["count"] === 0){
            $response["result"] = false;
            $response["msg"] = "Admin or Cashier has not signed on.";
        }else{
            try {
                $conn->beginTransaction();

                $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE date=:date AND employeeID=:employeeID ORDER BY id DESC");
                $sql->execute([ 
                    "date"          => $date,
                    "employeeID"    => $employeeID
                ]);
                if($row = $sql->fetch()){
                    $logID = $row["id"];

                    $sql = $conn->prepare("UPDATE dbo.logs SET endTime=:endTime WHERE id=:id");
                    $sql->execute([
                        "endTime"   => $startTime,
                        "id"        => $logID
                    ]);
                }

                $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE date=:date ORDER BY id DESC");
                $sql->execute([
                    "date"          => $date
                ]);
                $row = $sql->fetch();
                $serving = $row["serving"] + 1;
                
                
                if($conn->commit()){
                    $response["result"] = true;
                    $response["serving"] = $serving;
                }
            }catch (Exception $e){
                $conn->rollback();
                $response["result"] = false;
                $response["msg"] = "Server Error. Try Again Later.";
            }
        }

        
        echo json_encode($response);
    }

?>