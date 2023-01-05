<?php 

    require "session/sessionUser.php";
    require "includes/header.php";
    require "includes/sidebarUser.php";
    require "includes/connection.php";

    $employeeID = $_SESSION["employeeID"];

    $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE employeeID=:employeeID");
    $sql->execute([
        "employeeID" => $employeeID
    ]);
    $row = $sql->fetch();

    $status = $row["status"];
    $disabled = $status === "Signed On" ? "disabled='disabled'" : "";
    $nxtStop = $status === "Signed On" ? "" : "disabled='disabled'";

    $bAdmin = "bAdmin";
    $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE type=:bAdmin");
    $sql->execute([
        "bAdmin"    => $bAdmin
    ]);
    $row = $sql->fetch();

    if($row["status"] === "Signed On"){
        $bankingDate = date("M. d, Y");
        $curDate = date("Y-m-d");

        $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs WHERE employeeID=:employeeID AND date=:date ORDER BY id DESC");
        $sql->execute([
            "employeeID"    => $employeeID,
            "date"          => $curDate
        ]);

        if($row = $sql->fetch()){

            $serving = $row["serving"];

        }else{
            
            $serving = 0;

        }

    }else{
        $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs ORDER BY id DESC");
        $sql->execute();
        $row = $sql->fetch();
        $bankingDate = date("M. d, Y",strtotime($row["date"]));
        $serving = 0;
    }
?>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <?php 
            require "includes/topbar.php";
        ?>
        <!-- End of Topbar -->
        <div class="container-fluid">
            <div class="row">
                <div class="row col-md-6">
                    <div class="col-md-6">
                        <label>Banking Date: <?php echo $bankingDate; ?></label>
                    </div>
                    <div class="col-md-6">
                        <label>Current Date: <?php echo date("M. d, Y"); ?></label>
                    </div>
                </div>
                <button class="btn btn-primary" id="userSignOn" <?php echo $disabled; ?>>Sign On</button>
            </div>
            <input type='hidden' id='userStatus' value="<?php echo $status; ?>" />
            <h5 id="userCurServing"><?php echo $serving; ?></h5>
            <div class="row">
                <div class="col-md-6 row justify-content-center">
                    <button class="btn btn-danger" id="stopServing" <?php echo $nxtStop; ?>><i class="fa fa-stop"></i>Stop</button>
                </div>
                <div class="col-md-6 row justify-content-center">
                    <button class="btn btn-success" id="nextServing" <?php echo $nxtStop; ?>><i class="fa fa-arrow-right"></i>Next</button>
                </div>
            </div>
        </div>
        <!-- Begin Page Content -->
        
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
<?php 
    require "includes/footer.php";
?>