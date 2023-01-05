<?php 

    require "session/sessionBranchAdmin.php";
    require "includes/header.php";
    require "includes/sidebarBranchAdmin.php";
    require "includes/connection.php";

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
        
        <div class="container-fluid" id="operator">
            <br><br>
            <div class="row">
                <div class="col-md-4">
                    <button class="btn btn-secondary w-100 toPrint" data-type="Daily Transactions" style="height: 200px;">Daily Transactions</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100 toPrint" data-type="Leyeco" style="height: 200px;">Leyeco</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-success w-100 toPrint" data-type="New Accounts" style="height:200px;">New Accounts</button>
                </div>
            </div>
            <br>
            <h1 class="text-center" style="font-size: 50px; font-weight:bolder;">Tally</h1>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <label class="text-center w-100">Daily Transactions</label>
                    <p class="text-center" id="DailyTransactions">0</p>
                </div>
                <div class="col-md-4">
                    <label class="text-center w-100">Leyeco</label>
                    <p class="text-center" id="Leyeco">0</p>
                </div>
                <div class="col-md-4">
                    <label class="text-center w-100">New Accounts</label>
                    <p class="text-center" id="NewAccounts">0</p>
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