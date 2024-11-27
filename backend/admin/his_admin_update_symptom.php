
<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if (isset($_POST['update_symptom'])) {
    $symptom_id = $_POST['symptom_id'];
    $symptom_type = $_POST['symptom_type'];
    // $patient_id = $_POST['patient_id'];

    // Thực hiện truy vấn để cập nhật dữ liệu trong bảng symptom
    $query = "UPDATE symtomp SET Symtomp_Type=? WHERE Symtomp_ID=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('si', $symptom_type, $symptom_id);
    $stmt->execute();

    // Kiểm tra và xử lý thông báo thành công hoặc thất bại
    if ($stmt->affected_rows > 0) {
        $success = "Symptom Updated";
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
                                <h4 class="page-title">Update Symptom Details</h4>
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
                                    <!--Add Patient Form-->
                                    <form method="post">
                                        <div class="form-row">
                                            <?php
                                            function getSymtompOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT * FROM Symtomp";
                                                $result = $mysqli->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }
                                                return $options;
                                            }
                                            ?>
                                            <div class="form-group col-md-6">
                                                <label for="inputSymptomType" class="col-form-label">Symptom Type</label>
                                                <?php
                                                $query2 = "SELECT * FROM Symtomp WHERE Symtomp_ID=" . $_GET['Symtomp_ID'];
                                                if ($result = $mysqli->query($query2)) {
                                                    $Symtomp_Type = ($row = $result->fetch_assoc()) ? $row['Symtomp_Type'] : '';
                                                    echo '<input type="text" required="required" name="symptom_type" class="form-control" id="inputSymptomType" value="' . $Symtomp_Type . '" placeholder="Symptom Type">';
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPatientID" class="col-form-label">Patient ID</label>
                                                <select id="inputPatientID" required="required" name="symptom_id" class="form-control">
                                                    <option>Choose</option>
                                                    <?php
                                                    $SymtompOptions = getSymtompOptions($mysqli);
                                                    foreach ($SymtompOptions as $Symtomp) {
                                                        $query = 'SELECT Full_Name FROM Patient WHERE Patient_ID = ' . $Symtomp['Patient_ID'];
                                                        $result = $mysqli->query($query);
                                                        $patientName = ($result && $row = $result->fetch_assoc()) ? $row['Full_Name'] : '';
                                                        $selected = ($Symtomp['Patient_ID'] == $_GET['Patient_ID']) ? 'selected' : '';
                                                        echo "<option value='{$Symtomp['Symtomp_ID']}' $selected>{$Symtomp['Symtomp_ID']}: {$patientName}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" onclick="goBack()" class="btn btn-primary">Back</button>
                                        <button type="submit" name="update_symptom" class="ladda-button btn btn-primary" data-style="expand-right">Update Symptom</button>
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