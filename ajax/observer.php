<?php 

    date_default_timezone_set("Asia/Manila");
    require "../includes/connection.php";
    
    $date       = date("Y-m-d");
    $type       = "user";
    $tellers    = array();
    $bAdmin     = "bAdmin";

    $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE type=:type ORDER BY id");
    $sql->execute([
        "type" => $type
    ]);

    while($row = $sql->fetch()){
        $employeeID = $row["employeeID"];
        $count = 0;

        $sqlS = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE date=:date AND employeeID=:employeeID ORDER BY id DESC");
        $sqlS->execute([
            "date"       => $date,
            "employeeID" => $employeeID
        ]);

        while($rowS = $sqlS->fetch()){

            $tellers[$employeeID] = $rowS["serving"];
            $count++;

        }

        if($count == 0){
            $tellers[$employeeID] = "Not Available.";
        }
    }

    echo json_encode($tellers);
?>