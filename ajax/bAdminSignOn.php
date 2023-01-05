<?php 

    date_default_timezone_set("Asia/Manila");
    session_start();
    if($_SESSION["type"] == "bAdmin"){
        require "../includes/connection.php";

        $employeeID = $_SESSION["employeeID"];
        $status = "Signed On";
        $response = array();
        
        $sql = $conn->prepare("UPDATE dbo.teller SET status=:status WHERE employeeID=:employeeID");
        if($sql->execute([
            "status"    => $status,
            "employeeID"    => $employeeID
        ])){
            $response["result"] = true;
            $response["msg"] = "Successfully Signed On.";
            $response["bankingDate"] = " ". date("M. d, Y");
        }else{
            $response["result"] = false;
            $response["msg"] = "Server Error. Try Again Later.";
        }
        
        echo json_encode($response);
    }
?>