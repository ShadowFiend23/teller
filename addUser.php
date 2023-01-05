<?php 

    require "session/sessionBranchAdmin.php";
    require "includes/header.php";
    require "includes/sidebarBranchAdmin.php";
    
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
            <h3>Add User</h3>
            <hr>
            <form id="addTellerForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Employee ID</label>
                            <input class='form-control' type="text" name="employeeID"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Full Name</label>
                            <input class='form-control' type="text" name="fullName"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Section</label>
                            <select class="form-control" name='section'>
                                <option value='Teller'>Teller</option>
                                <option value='New Accounts'>New Accounts</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" style='visibility:hidden;'>Submit</label>
                            <input type='submit' class="btn btn-primary form-control">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Begin Page Content -->
        
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
<?php 
    require "includes/footer.php";
?>