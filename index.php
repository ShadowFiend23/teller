<?php 

    require "session/sessionBranchAdmin.php";
    require "includes/header.php";
    require "includes/sidebarBranchAdmin.php";
    require "includes/connection.php";

    $sql = $conn->prepare("SELECT TOP 1 * FROM dbo.logs ORDER BY date DESC");
    $sql->execute();
    $row = $sql->fetch();

    $lastDate = date("Y-m-d",strtotime($row["date"]));
    $currentDate = date("Y-m-d");
    $employeeID     = $_SESSION["employeeID"];

    $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE employeeID=:employeeID");
    $sql->execute([
        "employeeID"    => $employeeID
    ]);
    $row = $sql->fetch();


    if($lastDate !== $currentDate && $row["status"] === "Signed Off"){
        $status         = "Signed Off";
        $sql = $conn->prepare("UPDATE dbo.teller SET status=:status");
        $sql->execute([
            "status"        => $status
        ]);
        $bankingDate = date("M. d, Y",strtotime($lastDate));
    }

    $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE employeeID=:employeeID");
    $sql->execute([
        "employeeID"    => $employeeID
    ]);
    $row = $sql->fetch();
    if($row["status"] === "Signed On"){
        $disabledSignOn = "disabled='disabled'";
        $bankingDate = date("M. d, Y");
    }else{
        $disabledSignOn = "";
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
                        <label>Banking Date:<span id="bankingDate"> <?php echo $bankingDate;  ?></span></label>
                    </div>
                    <div class="col-md-6">
                        <label>Current Date: <?php echo date("M. d, Y"); ?></label>
                    </div>
                </div>
                <button class="btn btn-primary" <?php echo $disabledSignOn; ?> id='bAdminSignOn'>Sign On</button>
                <div class="col-md-5">
                    <a href='./view.php' class="btn btn-success float-right" target="_blank">View Live <i class='fa fa-eye'></i></a>
                </div>
            </div>
            <hr>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <h4 class='text-center'>Teller</h4>
                </div>
                <div class="col-md-6">
                    <h4 class='text-center'>Status</h4>
                </div>
            </div>
            <hr>
            <br>
            <div id="tellerStatus" class='col-md-12'>

            </div>
        </div>
        <!-- Begin Page Content -->
        
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
<?php 
    require "includes/footer.php";
?>