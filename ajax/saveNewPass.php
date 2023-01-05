<?php 

    if(isset($_POST["newPass"]) && isset ($_POST["userID"])){

        require "../includes/connection.php";

        $password = password_hash($_POST["newPass"], PASSWORD_DEFAULT);
        $userID = $_POST["userID"];
        $response = array();
        $error = 0;
        $sql = $conn->prepare("UPDATE dbo.teller SET password=:password WHERE employeeID=:userID");
        if(!$sql->execute([
            "password"  => $password,
            "userID"        => $userID
        ]))
            $error++;

        $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE employeeID=:userID");
        if(!$sql->execute([
            "userID"    => $userID
        ]))
            $error++;


        $row = $sql->fetch();

        if($error == 0){
            $response["result"] = true;
            $response["msg"]    = "Successfully Registered New Password";
            
            $response["type"] = $row["type"];
            $response["userID"] = $row["id"];

            session_start();
            $_SESSION["userID"] = $row["id"];
            $_SESSION["type"] = $row["type"];
            $_SESSION["employeeID"] = $userID;
            $_SESSION["branchCode"] = $row["branchCode"];

            if($row["type"] == "bAdmin")
                $response["link"] = "./index.php";
            else if($row["type"] == "user"){
                $response["link"] = "./user.php";
            }
                

        }else{
            $response["result"] = false;
            $response["msg"]   = "Server Error. Try Again Later";
        }

        echo json_encode($response);

    }

?>