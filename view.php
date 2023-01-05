<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tellering</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body{
            background-color: rgb(247, 202, 24);
            overflow: hidden;
        }
        img{
            width:150px;
        
        }
        h3{
            text-align:center;
            color:white;
        }

        #viewPage h3{
            font-size: 70px;
            font-weight: bolder;
            color: blue;
            padding:0;
            margin: 0;
        }

        #viewPage h5{
            font-size: 50px;
            font-weight: bold;
            margin: 10px 0;
            border: 2px solid maroon;
            background-color: #00008B;
            color:white;
        }
        
        .grow{
            transition: all .2s ease-in-out;
        }

        .grow.active{
            animation: scaleImg .5s;
            animation-direction: alternate;
            animation-iteration-count: 4;
        }
        @keyframes scaleImg {
            from {
                transform: scale(1);
                background-color: #00008B;
            }
            to {
                transform: scale(1.5);
                background-color: red;
            }
        }
    </style>
</head>
<body>
        <audio id="tellerNotif"  src="audio/tellerNotif.mp3">
         </audio>
    <div class="container-fluid" id="viewPage">
        <div class='row justify-content-center'>
            <div class="">
                <img src="img/loader.jpg" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Teller</h3>
            </div>
            <div class="col-md-6">
                <h3>Now Serving</h3>
            </div>
        </div>
        <hr>
        <div class="row">
            <?php 

                    date_default_timezone_set("Asia/Manila");
                    require "includes/connection.php";

                    session_start();
                    $employeeID = $_SESSION["employeeID"];
                    $date = date("Y-m-d");
                    $type = "user";
                    $tellers = array();
                    
                    $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE type=:type ORDER BY id");
                    $sql->execute([
                        "type" => $type
                    ]);

                    while($row = $sql->fetch()){
                        $employeeID = $row["employeeID"];
                        if($row["status"] === "Signed On"){
                            $count = 0;
                            $sqlS = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE employeeID=:employeeID AND date=:date ORDER BY id DESC");
                            $sqlS->execute([
                                "employeeID"    => $employeeID,
                                "date"          => $date
                            ]);

                            while($rowS = $sqlS->fetch()){

                                $status = $rowS["serving"];

                                $count++;
                            }

                            if($count === 0)
                                $status = "Not Available.";

                        }else{
                            $status = "Not Available.";
                        }

                        echo "
                            <div class='col-md-6'>
                                <h5 class='text-center'>$row[position]</h5>
                            </div>
                            <div class='col-md-6'>
                                <h5 class='text-center tellerStatus grow' id='$employeeID'>$status</h5>
                            </div>
                        ";
                    }
                
            ?>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="./js/howler.js"></script>
    <script src="js/view.js?v=<?php echo rand(); ?>"></script>
</body>
</html>