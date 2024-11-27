<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');

if (isset($_POST['update_comorbidity'])) {
    $comorbidity_id = $_POST['comorbidity_id'];
    $comorbidity_type = $_POST['comorbidity_type'];
    // $patient_id = $_POST['patient_id'];

    // Thực hiện truy vấn để cập nhật dữ liệu trong bảng comorbidity
    $query = "UPDATE Comorbidity SET Comorbidity_Type=? WHERE Comorbidity_ID=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('si', $comorbidity_type, $comorbidity_id);
    $stmt->execute();

    // Xử lý thông báo thành công hoặc thất bại
    if ($stmt->affected_rows > 0) {
        $success = "Comorbidity Updated";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>
<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">

<!--Head-->
<?php include('assets/inc/head.php'); ?>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">Update Patient</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Update Comorbidity Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>
                                    <!--Add comorbidity Form-->
                                    <form method="post">
                                        <div class="form-row">
                                            <?php
                                            function getComorbidityOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT * FROM Comorbidity";
                                                $result = $mysqli->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }
                                                return $options;
                                            }
                                            ?>
                                            <div class="form-group col-md-6">
                                                <label for="inputComorbidityType" class="col-form-label">Comorbidity Type</label>
                                                <?php
                                                $query2 = "SELECT * FROM Comorbidity WHERE Comorbidity_ID=" . $_GET['Comorbidity_ID'];
                                                if ($result = $mysqli->query($query2)) {
                                                    $Comorbidity_Type = ($row = $result->fetch_assoc()) ? $row['Comorbidity_Type'] : '';
                                                    echo '<input type="text" required="required" name="comorbidity_type" class="form-control" id="inputComorbidityType" value="' . $Comorbidity_Type . '" placeholder="Comorbidity Type">';
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPatientID" class="col-form-label">Patient ID</label>
                                                <select id="inputPatientID" required="required" name="comorbidity_id" class="form-control">
                                                    <option>Choose</option>
                                                    <?php
                                                    $ComorbidityOptions = getComorbidityOptions($mysqli);
                                                    foreach ($ComorbidityOptions as $Comorbidity) {
                                                        $query = 'SELECT Full_Name FROM Patient WHERE Patient_ID = ' . $Comorbidity['Patient_ID'];
                                                        $result = $mysqli->query($query);
                                                        $patientName = ($result && $row = $result->fetch_assoc()) ? $row['Full_Name'] : '';
                                                        $selected = ($Comorbidity['Patient_ID'] == $_GET['Patient_ID']) ? 'selected' : '';
                                                        echo "<option value='{$Comorbidity['Comorbidity_ID']}' $selected>{$Comorbidity['Comorbidity_ID']}: {$patientName}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" onclick="goBack()" class="btn btn-primary">Back</button>  
                                        <button type="submit" name="update_comorbidity" class="ladda-button btn btn-primary" data-style="expand-right">Update Comorbidity</button>
                                    </form>

                                    <!--End Patient Form-->
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->
    <script>
function goBack() {
    window.history.back();
}
</script>            

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js-->
    <script src="assets/js/pages/loading-btn.init.js"></script>

</body>

</html>