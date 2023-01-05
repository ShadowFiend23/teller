<?php 

    if(isset($_POST["employeeID"]) && isset($_POST["fullName"]) && isset($_POST["section"])){

        require "../includes/connection.php";
        session_start();

        $branchCode = $_SESSION["branchCode"];
        $employeeID = trim($_POST["employeeID"]);
        $fullName   = trim($_POST["fullName"]);
        $section    = trim($_POST["section"]);
        $password   = 1;
        $status     = "Signed Off";
        $response   = array();
        $type       = "user";

        if(empty($employeeID)){
            $response["result"] = false;
            $response["msg"]    = "Please Add An Employee ID.";
        }else if(empty($fullName)){
            $response["result"] = false;
            $response["msg"]    = "Please Add The User's Full Name.";
        }else if($section !== 'Teller' && $section !== 'New Accounts'){
            $response["result"] = false;
            $response['msg']    = "Invalid Section.";
        }else{
            $like = $section."%";
            $sql = $conn->prepare("SELECT COUNT(*) as count FROM dbo.teller WHERE branchCode=:branchCode AND position LIKE :like");
            $sql->execute([
                "branchCode"    => $branchCode,
                "like"      => $like
            ]);
            $row = $sql->fetch();
            $position = $section . " " . ($row["count"] + 1);

            $sql = $conn->prepare("INSERT INTO dbo.teller (employeeID,fullName,password,status,type,branchCode,position)
            VALUES (:employeeID,:fullName,:password,:status,:type,:branchCode,:position)");
            if($sql->execute([
                "employeeID"    => $employeeID,
                "fullName"      => $fullName,
                "password"      => $password,
                "status"        => $status,
                "type"          => $type,
                "branchCode"    => $branchCode,
                "position"      => $position
            ])){
                
                $response["result"] = true;
                $response["msg"]    = "Successfully Saved New User.";

            }else{

                $response["result"] = false;
                $response["msg"]    = "Server Error. Try Again Later.";

            }

        }

        echo json_encode($response);
    }

?>