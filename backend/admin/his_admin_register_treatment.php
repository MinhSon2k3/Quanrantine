<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
if (isset($_POST['add_treatment'])) {
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
        // SQL để chèn dữ liệu vào bảng treatment
        $query = "INSERT INTO treatment (Patient_ID, Doctor_ID, Date_Start, Date_End, Percentage) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iisss', $patient_id, $doctor_id, $date_start, $date_end, $percentage);
        $stmt->execute();

        if ($stmt) {
            $success = "Treatment Details Added";
        } else {
            $err = "Please Try Again Or Try Later";
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
                                        <li class="breadcrumb-item active">Add Treatment</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Update Treatment Details</h4>
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
                                    ?>

                                    <form method="post">
                                        <div class="form-row">
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
                                            <div class="form-group col-md-6">
                                                <label for="inputDoctorID" class="col-form-label">Doctor ID</label>
                                                <select required="required" name="doctor_id" class="form-control" id="inputDoctorID">
                                                    <?php
                                                        $doctorOptions = getDoctorOptions($mysqli);
                                                    foreach ($doctorOptions as $doctor) {
                                                        echo "<option value='{$doctor['Doctor_ID']}'>{$doctor['Doctor_ID']}: {$doctor['Name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputDateStart" class="col-form-label">Date Start</label>
                                                <input required="required" type="date" name="date_start" class="form-control" id="inputDateStart">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputDateEnd" class="col-form-label">Date End</label>
                                                <input required="required" type="date" name="date_end" class="form-control" id="inputDateEnd">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputPercentage" class="col-form-label">Percentage</label>
                                                <input required="required" type="number" name="percentage" class="form-control" id="inputPercentage" min="0" max="100">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_treatment" class="ladda-button btn btn-primary" data-style="expand-right">Add trreatment</button>
                                    </form>

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