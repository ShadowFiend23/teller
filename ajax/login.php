<?php

    if(isset($_POST["employeeID"]) && isset($_POST["password"])){

        require "../includes/connection.php";

        $employeeID = $_POST["employeeID"];
        $password = $_POST["password"];
        $response = array();
        $error    = 0;

       $sql = $conn->prepare("SELECT COUNT(*) as count FROM dbo.teller WHERE employeeID=:employeeID");
       $sql->execute([
           "employeeID"   => $employeeID
       ]);
       $row = $sql->fetch();

        if($row["count"] > 0 ){
            $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE employeeID=:employeeID");
            if(!$sql->execute([
                "employeeID"  => $employeeID
            ]))
                $error++;
            
            $row = $sql->fetch();
            if($error == 0){
                
                if(($row["password"] == $password) && ($row["password"] == 1)){
                    $response["result"] = true;
                    $response["changePass"] = true;
                    $response["userID"] = $row["employeeID"];

                }else if (password_verify($password, $row['password'])) {
                    $response["result"] = true;
                    $response["msg"] = "Login Successfully";
                    $response["userID"] = $row["id"];
                    $response["type"] = $row["type"];

                    
                    session_start();
                    $_SESSION["userID"] = $row["id"];
                    $_SESSION["type"] = $row["type"];
                    $_SESSION["employeeID"] = $employeeID;
                    $_SESSION["branchCode"] = $row["branchCode"];

                }else{
                    $response["result"] = false;
                    $response["msg"] = "Invalid User Or Password";
                }

            }else{
                $response["result"] = false;
                $response["msg"] = "Invalid User Or Password";
            }
        }else{
            $response["result"] = false;
            $response["msg"] = "Invalid User Or Password";
        }
        

        echo json_encode($response);
        
    }

?>