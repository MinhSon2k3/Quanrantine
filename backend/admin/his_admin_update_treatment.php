<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if (isset($_POST['update_treatment'])) {
    $treatment_id = $_POST['treatment_id'];
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];
    $percentage = $_POST['percentage'];

    // Kiểm tra điều kiện lọc
    if ($date_start >= $date_end) {
        $err = "Start date must be before end date";
    } elseif ($percentage < 0 || $percentage > 100) {
        $err = "Percentage must be between 0 and 100";
    } else {
        // SQL để cập nhật dữ liệu trong bảng treatment
        $query = "UPDATE treatment SET Patient_ID = ?, Doctor_ID = ?, Date_Start = ?, Date_End = ?, Percentage = ? WHERE Treatment_ID = ?";
        $stmt = $mysqli->prepare($query);
        
        // Sửa đổi tham số trong bind_param để phản ánh cấu trúc của bảng treatment
        $stmt->bind_param('iisssi', $patient_id, $doctor_id, $date_start, $date_end, $percentage, $treatment_id);

        $stmt->execute();

        // Kiểm tra xem có bản ghi nào được cập nhật hay không
        if ($stmt->affected_rows > 0) {
            $success = "Treatment Details Updated";
        } else {
            $err = "No records updated. Please check your input or try again later.";
        }
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
                                            function getPatientOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT * FROM patient";
                                                $result = $mysqli->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }
                                                return $options;
                                            }

                                            function getDoctorOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT doctor.Doctor_ID, quarantine_camp_staff.Name 
                                                FROM doctor
                                                JOIN quarantine_camp_staff ON doctor.Quarantine_camp_staff_ID = quarantine_camp_staff.Quarantine_camp_staff_ID";
                                                $result = $mysqli->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }
                                                return $options;
                                            }

                                            $treatmentID = $_GET['Treatment_ID'];
                                            $queryTreatment = "SELECT * FROM treatment WHERE Treatment_ID = ?";
                                            $stmtTreatment = $mysqli->prepare($queryTreatment);
                                            $stmtTreatment->bind_param('i', $treatmentID);
                                            $stmtTreatment->execute();
                                            $resultTreatment = $stmtTreatment->get_result();
                                            $rowTreatment = $resultTreatment->fetch_assoc();

                                            $patientID = $rowTreatment['Patient_ID'];
                                            $doctorID = $rowTreatment['Doctor_ID'];
                                            $dateStart = $rowTreatment['Date_Start'];
                                            $dateEnd = $rowTreatment['Date_End'];
                                            $percentage = $rowTreatment['Percentage'];
                                            ?>
                                                <input type="hidden" name="treatment_id" value="<?php echo $treatmentID; ?>">
                                            <div class="form-group col-md-6">
                                                <label for="inputPatientID" class="col-form-label">Patient ID</label>
                                                <select id="inputPatientID" required="required" name="patient_id" class="form-control">
                                                    <option>Choose</option>
                                                    <?php
                                                    $patientOptions = getPatientOptions($mysqli);
                                                    foreach ($patientOptions as $patient) {
                                                        $selected = ($patient['Patient_ID'] == $patientID) ? 'selected' : '';
                                                        echo "<option value='{$patient['Patient_ID']}' $selected>{$patient['Patient_ID']}: {$patient['Full_Name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="inputDoctorID" class="col-form-label">Doctor ID</label>
                                                <select id="inputDoctorID" required="required" name="doctor_id" class="form-control">
                                                    <option>Choose</option>
                                                    <?php
                                                    $doctorOptions = getDoctorOptions($mysqli);
                                                    foreach ($doctorOptions as $doctor) {
                                                        $selected = ($doctor['Doctor_ID'] == $doctorID) ? 'selected' : '';
                                                        echo "<option value='{$doctor['Doctor_ID']}' $selected>{$doctor['Doctor_ID']}: {$doctor['Name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputDateStart" class="col-form-label">Date Start</label>
                                                <input required="required" type="date" name="date_start" class="form-control" id="inputDateStart" value="<?php echo $dateStart; ?>">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="inputDateEnd" class="col-form-label">Date End</label>
                                                <input required="required" type="date" name="date_end" class="form-control" id="inputDateEnd" value="<?php echo $dateEnd; ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputPercentage" class="col-form-label">Percentage</label>
                                                <input required="required" type="number" name="percentage" class="form-control" id="inputPercentage" min="0" max="100" value="<?php echo $percentage; ?>">
                                            </div>
                                        </div>
                                        <button type="button" onclick="goBack()" class="btn btn-primary">Back</button>          
                                        <button type="submit" name="update_treatment" class="ladda-button btn btn-primary" data-style="expand-right">Update Treatment</button>
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