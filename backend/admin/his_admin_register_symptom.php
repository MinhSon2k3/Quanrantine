<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
if (isset($_POST['add_symptom'])) {
    $symptom_type = $_POST['symptom_type'];
    $patient_id = $_POST['patient_id'];

    // Thực hiện truy vấn để thêm dữ liệu vào bảng symptom
    $query = "INSERT INTO symtomp (Symtomp_Type, Patient_ID) VALUES (?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('si', $symptom_type, $patient_id);
    $stmt->execute();

    // Xử lý thông báo thành công hoặc thất bại
    if ($stmt) {
        $success = "Symptom Added";
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
                                        <li class="breadcrumb-item active">Add Patient</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Symptom Details</h4>
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
                                    <?php
                                    function getPatientOptions($mysqli)
                                    {
                                        $options = array();
                                        $query = "SELECT Patient_ID, Full_Name FROM patient";
                                        $result = $mysqli->query($query);

                                        while ($row = $result->fetch_assoc()) {
                                            $options[] = $row;
                                        }

                                        return $options;
                                    }

                                    // Sử dụng hàm để lấy danh sách các patient_id và Full_name


                                    // Hiển thị dropdown cho patient_id và Full_name
                                    ?>
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputSymptomType" class="col-form-label">Symptom Type</label>
                                                <input type="text" required="required" name="symptom_type" class="form-control" id="inputSymptomType" placeholder="Symptom Type">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPatientID" class="col-form-label">Patient ID</label>
                                                <select required="required" name="patient_id" class="form-control" id="inputPatientID">
                                                    <?php
                                                    $patientOptions = getPatientOptions($mysqli);
                                                    $selectedPatientID = $_GET['Patient_ID'];

                                                    foreach ($patientOptions as $patient) {
                                                        $selected = ($selectedPatientID == $patient['Patient_ID']) ? 'selected' : '';
                                                        echo "<option value='{$patient['Patient_ID']}' $selected>{$patient['Patient_ID']}: {$patient['Full_Name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" name="add_symptom" class="ladda-button btn btn-primary" data-style="expand-right">Add Symptom</button>
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