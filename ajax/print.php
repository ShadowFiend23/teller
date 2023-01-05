<?php
require '../escpos/vendor/autoload.php';
require "../includes/connection.php";
    
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
date_default_timezone_set("Asia/Manila");
session_start();

if($_SESSION["type"] == "bAdmin"){

    
    $employeeID = $_SESSION["employeeID"];
    $date       = date("Y-m-d");
    $startTime  = date("H:i:s");
    $endTime    = "00:00:00";
    $response   = array();

    $type = $_POST["tranType"];

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

            $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE date=:date AND employeeID=:employeeID AND type=:type ORDER BY id DESC");
            $sql->execute([ 
                "date"          => $date,
                "employeeID"    => $employeeID,
                "type"          => $type
            ]);
            if($row = $sql->fetch()){
                $logID = $row["id"];

                $sql = $conn->prepare("UPDATE dbo.logs SET endTime=:endTime WHERE id=:id");
                $sql->execute([
                    "endTime"   => $startTime,
                    "id"        => $logID
                ]);
            }

            $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE date=:date AND type=:type ORDER BY id DESC");
            $sql->execute([
                "date"          => $date,
                "type"          => $type
            ]);
            if($row = $sql->fetch())
                $serving = $row["serving"] + 1;
            else
                $serving = 1;

            $sql = $conn->prepare("INSERT INTO dbo.logs (employeeID,date,startTime,endTime,serving,type) VALUES (:employeeID,:date,
            :startTime,:endTime,:serving,:type)");
            $sql->execute([
                "employeeID"    => $employeeID,
                "date"          => $date,
                "startTime"     => $startTime,
                "endTime"       => $endTime,
                "serving"       => $serving,
                "type"          => $type
            ]);
            
            if($conn->commit()){
                $logo = EscposImage::load("../img/loader.png", false);
                $date = date("M. d. Y (l)");
                $connector = new WindowsPrintConnector("POS58 Printer");

                $printer = new Printer($connector);


                /* Print top logo */
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> bitImage($logo);


                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setTextSize(1, 1);
                $printer -> text("Metro Ormoc Community Multi-\nPurpose Cooperative\n\n");

                $printer -> setTextSize(1, 2);
                $printer -> text($type."\n\n");

                $printer -> setTextSize(8, 8);
                $printer -> setFont(Printer::FONT_B);
                $printer -> text($serving . "\n\n");

                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> setTextSize(1, 1);
                $printer -> text("Date: ".$date);

                $printer -> cut();
                
                /* Close printer */
                $printer -> close();

                $response["result"] = true;
                $response["type"] = str_replace(' ', '', $type);
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