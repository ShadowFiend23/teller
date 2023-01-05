<?php 

        require "../includes/connection.php";

        session_start();
        $type       = "user";
        $branchCode = $_SESSION["branchCode"];

        $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE type=:type AND branchCode=:branchCode");
        $sql->execute([
            "type"          => $type,
            "branchCode"    => $branchCode
        ]);
        
        while($row = $sql->fetch()){
            $fullName = ucwords($row["fullname"]);
            $position = ucwords($row["position"]);
            echo "  <div class='row'>
                        <div class='col-md-6'>
                            <h6 class='text-center'>$fullName ($position)</h6>
                        </div>
                        <div class='col-md-6'>
                            <h6 class='text-center'>$row[status]</h6>
                        </div>
                    </div>
                <hr>
            ";

        }
    ?>