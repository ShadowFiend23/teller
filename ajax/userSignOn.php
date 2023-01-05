<?php 

    session_start();
    if($_SESSION["type"] == "user"){
        require "../includes/connection.php";
		
        $employeeID = $_SESSION["employeeID"];
        $status     = "Signed On";
        $response   = array();
        $user       = "bAdmin";

        $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE type=:user");
        $sql->execute([ "user" => $user ]);
        $row = $sql->fetch();

        if($row["status"] === "Signed Off"){

            $response["result"] = false;
            $response["msg"] = "Cashier Or Admin Has Not Signed On.";

        }else{
                
            $sql = $conn->prepare("SELECT status FROM dbo.teller WHERE employeeID=:employeeID");
            $sql->execute([ "employeeID" => $employeeID ]);
            $row = $sql->fetch();

            if($row["status"] === "Signed On"){

                $response["result"] = false;
                $response["msg"] = "User Already Signed On.";

            }else{

                $sql = $conn->prepare("UPDATE dbo.teller SET status=:status WHERE employeeID=:employeeID");
                if($sql->execute([
                    "status"        => $status,
                    "employeeID"    => $employeeID
                ])){
                    $response["result"] = true;
                    $response["msg"] = "Successfully Signed On.";
                }else{
                    $response["result"] = false;
                    $response["msg"] = "Server Error. Try Again Later.";
                }
            }
        }
        echo json_encode($response);
	}

?>