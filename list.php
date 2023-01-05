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
        <div class="container-fluid">
            <table class="table table-light table-hover border" id="tellerListTable" style="text-align: center;">
                <thead class="thead-dark">
                    <tr>
                        <th class='border border-top border-bottom' style=""></th>
                        <th class='border border-top border-bottom' style="">Employee ID</th>
                        <th class='border border-top border-bottom' style="">Full Name</th>
                        <th class='border border-top border-bottom' style="">Position</th>
                        <th class='border border-top border-bottom' style="">Delete</th>
                    </tr>
                </thead>
                <tbody id="tellerList">
                    <?php 
                        
                        $branchCode = $_SESSION["branchCode"];
                        $type       = "user";
                        $deleted    = 1;
                        $count      = 0;

                        $sql = $conn->prepare("SELECT * FROM dbo.teller WHERE type=:type AND deleted!=:deleted AND branchCode=:branchCode");
                        $sql->execute([
                            "type"          => $type,
                            "deleted"       => $deleted,
                            "branchCode"    => $branchCode
                        ]);

                        while($row = $sql->fetch()){

                            echo "
                                <tr>
                                    <td>".++$count."</td>
                                    <td>$row[employeeID]</td>
                                    <td>$row[fullname]</td>
                                    <td>$row[position]</td>
                                    <td><button class='btn btn-danger dltBtn'><i class='fa fa-trash'></i></button></td>
                                </tr>
                            
                            ";

                        }
                    
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Begin Page Content -->
        
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
<?php 
    require "includes/footer.php";
?>