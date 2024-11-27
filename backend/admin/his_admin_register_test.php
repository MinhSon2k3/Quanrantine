<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if (isset($_POST['add_test'])) {


    // Xác thực Test_Type
    $test_type = $_POST['test_type'];
    if (empty($test_type)) {
        $err = "Test Type is required";
    }

    // Xác thực Test_Date
    $test_date = $_POST['test_date'];
    if (empty($test_date)) {
        $err = "Test Date is required";
    }

    // Xác thực Cycle_Threshold
    $cycle_threshold = $_POST['cycle_threshold'];
    if (!is_numeric($cycle_threshold)) {
        $err = "Cycle Threshold must be a numeric value";
    }

    // Xác thực PCR_Result
    $pcr_result = $_POST['pcr_result'];

    // Xác thực PCR_Ct_Value
    $pcr_ct_value = $_POST['pcr_ct_value'];
    if (!is_numeric($pcr_ct_value)) {
        $err = "PCR Ct Value must be a numeric value";
    }

    // Xác thực Quick_Test_Result
    $quick_test_result = $_POST['quick_test_result'];

    // Xác thực Quick_Test_Ct_Value
    $quick_test_ct_value = $_POST['quick_test_ct_value'];
    if (!is_numeric($quick_test_ct_value)) {
        $err = "Quick Test Ct Value must be a numeric value";
    } elseif ($quick_test_ct_value < 0 || $quick_test_ct_value > 100) {
        $err = "Quick Test Ct Value must be between 0 and 100";
    }

    // Xác thực SPO2
    $spo2 = $_POST['spo2'];
    if (!is_numeric($spo2)) {
        $err = "SPO2 must be a numeric value";
    }

    // SQL to insert captured values
    $query = "INSERT INTO test (Test_Type, Test_Date, Cycle_Threshold, Patient_ID, PCR_Result, PCR_Ct_Value, Quick_Test_Result, Quick_Test_Ct_Value, SPO2, Respiratory_Rate)
    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssssssss', $test_type, $test_date, $cycle_threshold, $patient_id, $pcr_result, $pcr_ct_value, $quick_test_result, $quick_test_ct_value, $spo2, $respiratory_rate);
    $stmt->execute();

    // Declare a variable which will be passed to the alert function
    if ($stmt) {
        $success = "Test Details Added";
    } else {
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
                                <h4 class="page-title">Add Test Details</h4>
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
                                    ?>
                                    <form method="post">
                                        <div class="form-row">
                                            <label for="inputTestType" class="col-form-label">Test Type</label>
                                            <input type="text" required="required" name="test_type" class="form-control" id="inputTestType" placeholder="Test Type">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputTestDate" class="col-form-label">Test Date</label>
                                                <input type="date" required="required" name="test_date" class="form-control" id="inputTestDate">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputCycleThreshold" class="col-form-label">Cycle Threshold</label>
                                                <input type="text" required="required" name="cycle_threshold" class="form-control" id="inputCycleThreshold" placeholder="Cycle Threshold">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputPCRResult" class="col-form-label">PCR Result</label>
                                                <select required="required" name="pcr_result" class="form-control" id="inputPCRResult">
                                                    <option value="0">Negative</option>
                                                    <option value="1">Positive</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPCRCtValue" class="col-form-label">PCR Ct Value</label>
                                                <input type="text" required="required" name="pcr_ct_value" class="form-control" id="inputPCRCtValue" placeholder="PCR Ct Value">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputQuickTestResult" class="col-form-label">Quick Test Result</label>
                                                <select required="required" name="quick_test_result" class="form-control" id="inputQuickTestResult">
                                                    <option value="0">Negative</option>
                                                    <option value="1">Positive</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputQuickTestCtValue" class="col-form-label">Quick Test Ct Value</label>
                                                <input type="text" required="required" name="quick_test_ct_value" class="form-control" id="inputQuickTestCtValue" placeholder="Quick Test Ct Value">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputSPO2" class="col-form-label">SPO2</label>
                                                <input type="text" required="required" name="spo2" class="form-control" id="inputSPO2" placeholder="SPO2">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputRespiratoryRate" class="col-form-label">Respiratory Rate</label>
                                                <input type="text" required="required" name="respiratory_rate" class="form-control" id="inputRespiratoryRate" placeholder="Respiratory Rate">
                                            </div>
                                        </div>

                                        <div class="form-group">
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

                                        <!-- ... -->
                                                
                                        <button type="submit" name="add_test" class="ladda-button btn btn-primary" data-style="expand-right">Add Test</button>
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