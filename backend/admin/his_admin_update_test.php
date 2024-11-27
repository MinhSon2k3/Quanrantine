<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];


if (isset($_POST['update_test'])) {
    if (isset($_POST['update_test'])) {
        $Test_Type = $_POST['Test_Type'];
    if (empty($Test_Type)) {
        $err = "Test Type is required";
    }

    // Validate Test_Date
    $Test_Date = $_POST['Test_Date'];
    if (empty($Test_Date)) {
        $err = "Test Date is required";
    }

    // Validate Cycle_Threshold
    $Cycle_Threshold = $_POST['Cycle_Threshold'];
    if (!is_numeric($Cycle_Threshold)) {
        $err = "Cycle Threshold must be a numeric value";
    }

    // Validate PCR_Result
    $PCR_Result = $_POST['PCR_Result'];


    // Validate PCR_Ct_Value
    $PCR_Ct_Value = $_POST['PCR_Ct_Value'];
    if (!is_numeric($PCR_Ct_Value)) {
        $err = "PCR Ct Value must be a numeric value";
    }

    // Validate Quick_Test_Result
    $Quick_Test_Result = $_POST['Quick_Test_Result'];

    // Validate Quick_Test_Ct_Value
    $Quick_Test_Ct_Value = $_POST['Quick_Test_Ct_Value'];
    if (!is_numeric($Quick_Test_Ct_Value)) {
        $err = "Quick Test Ct Value must be a numeric value";
    } elseif ($Quick_Test_Ct_Value < 0 || $Quick_Test_Ct_Value > 100) {
        $err = "Quick Test Ct Value must be between 0 and 100";
    }

    // Validate SPO2
    $SPO2 = $_POST['SPO2'];
    if (!is_numeric($SPO2)) {
        $err = "SPO2 must be a numeric value";
    }

    // Validate Respiratory_Rate
    $Respiratory_Rate = $_POST['Respiratory_Rate'];
    if (!is_numeric($Respiratory_Rate)) {
        $err = "Respiratory Rate must be a numeric value";
    }
    
        // Validate and sanitize input data (Example: you can use mysqli_real_escape_string or other validation methods)
        // ...
    
        $query = "UPDATE Test 
                  SET Test_Type=?, Test_Date=?, Cycle_Threshold=?, 
                      PCR_Result=?, PCR_Ct_Value=?, Quick_Test_Result=?, 
                      Quick_Test_Ct_Value=?, SPO2=?, Respiratory_Rate=?
                  WHERE Test_ID=?";
        $stmt = $mysqli->prepare($query);
    
        // Check for errors in preparing statement
        if ($stmt === false) {
            die("Error in preparing statement: " . $mysqli->error);
        }
    
        // Bind parameters
        $rc = $stmt->bind_param(
            'sssssssssi',
            $Test_Type,
            $Test_Date,
            $Cycle_Threshold,
            $PCR_Result,
            $PCR_Ct_Value,
            $Quick_Test_Result,
            $Quick_Test_Ct_Value,
            $SPO2,
            $Respiratory_Rate,
            $Test_ID
        );
    
        // Check for errors in binding parameters
        if ($rc === false) {
            die("Error in binding parameters: " . $stmt->error);
        }
    
        // Execute statement
        $stmt->execute();
    
        // Check for errors in executing statement
        if ($stmt->affected_rows > 0) {
            $success = "Test Updated";
        } else if ($stmt->affected_rows === 0) {
           
        } else {
            $err = "Error in executing statement: " . $stmt->error;
        }
    
        // Close statement
        $stmt->close();
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
                                <h4 class="page-title">Update Test Details</h4>
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
                                        <?php
                                      if (isset($_GET['Test_ID'])) {
                                        $Test_ID = $_GET['Test_ID'];
                                    
                                        $query = "SELECT * FROM Test WHERE Test_ID=" . $Test_ID;
                                        if ($result = $mysqli->query($query)) {
                                            $Test2 = $result->fetch_assoc();
                                        } 
                                            }
                                            
                                            function getTestOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT * FROM Test";
                                                $result = $mysqli->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }
                                                return $options;
                                            }
                                            ?>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="Patient_ID" class="col-form-label">Patient ID</label>
                                                <select id="Patient_ID" required="required" name="Patient_ID" class="form-control">
                                                    <option>Choose</option>
                                                    <?php
                                                    $TestOptions = getTestOptions($mysqli);
                                                    foreach ($TestOptions as $Test) {
                                                        $query = 'SELECT Full_Name FROM Patient WHERE Patient_ID = ' . $Test['Patient_ID'];
                                                        $result = $mysqli->query($query);
                                                        $patientName = ($result && $row = $result->fetch_assoc()) ? $row['Full_Name'] : '';
                                                        $selected = ($Test['Patient_ID'] == $Test2['Patient_ID']) ? 'selected' : '';
                                                        echo "<option value='{$Test['Patient_ID']}' $selected>{$Test['Patient_ID']}: {$patientName}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="test_id" class="col-form-label">Test ID</label>
                                                <select id="test_id" required="required" name="test_id" class="form-control">
                                                    <option>Choose</option>
                                                    <?php
                                                    $TestOptions = getTestOptions($mysqli);
                                                    foreach ($TestOptions as $Test) {
                                                        $selected = ($Test['Test_ID'] == $Test2['Test_ID']) ? 'selected' : '';
                                                        echo "<option value='{$Test['Test_ID']}' $selected>{$Test['Test_ID']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputTestType" class="col-form-label">Test Type</label>
                                                <input type="text" required="required" name="Test_Type" class="form-control" id="inputTestType" value="<?php echo $Test2['Test_Type'] ?>" placeholder="Patient's Test Type">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputTestDate" class="col-form-label">Test Date</label>
                                                <input required="required" type="date" name="Test_Date" class="form-control" id="inputTestDate" value="<?php echo $Test2['Test_Date'] ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputCycleThreshold" class="col-form-label">Cycle Threshold</label>
                                                <input required="required" type="text" class="form-control" name="Cycle_Threshold" id="inputCycleThreshold" value="<?php echo $Test2['Cycle_Threshold'] ?>" placeholder="Patient's Cycle Threshold">
                                            </div> 
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputQuickTestResult" class="col-form-label">PCR_Result</label>
                                                <select required="required" class="form-control" name="PCR_Result" id="inputQuickTestResult">
                                                    <option value="0" <?php echo ($Test2['PCR_Result'] == '0') ? 'selected' : ''; ?>>Negative</option>
                                                    <option value="1" <?php echo ($Test2['PCR_Result'] == '1') ? 'selected' : ''; ?>>Positive</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPCRCtValue" class="col-form-label">PCR Ct Value</label>
                                                <input required="required" type="text" class="form-control" name="PCR_Ct_Value" id="inputPCRCtValue" value="<?php echo $Test2['PCR_Ct_Value'] ?>" placeholder="Patient's PCR Ct Value">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                        <div class="form-group col-md-6">
                                                <label for="inputQuickTestResult" class="col-form-label">Quick Test Result</label>
                                                <select required="required" class="form-control" name="Quick_Test_Result" id="inputQuickTestResult">
                                                    <option value="0" <?php echo ($Test2['Quick_Test_Result'] == '0') ? 'selected' : ''; ?>>Negative</option>
                                                    <option value="1" <?php echo ($Test2['Quick_Test_Result'] == '1') ? 'selected' : ''; ?>>Positive</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputQuickTestCtValue" class="col-form-label">Quick Test Ct Value</label>
                                                <input required="required" type="text" class="form-control" name="Quick_Test_Ct_Value" id="inputQuickTestCtValue" value="<?php echo $Test2['Quick_Test_Ct_Value'] ?>" placeholder="Patient's Quick Test Ct Value">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputSPO2" class="col-form-label">SPO2</label>
                                                <input required="required" type="text" class="form-control" name="SPO2" id="inputSPO2" value="<?php echo $Test2['SPO2'] ?>" placeholder="Patient's SPO2">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputRespiratoryRate" class="col-form-label">Respiratory Rate</label>
                                                <input required="required" type="text" class="form-control" name="Respiratory_Rate" id="inputRespiratoryRate" value="<?php echo $Test2['Respiratory_Rate'] ?>" placeholder="Patient's Respiratory Rate">
                                            </div>
                                        </div>



                                        <?php
                                        // Close the database connection
                                        $mysqli->close();
                                        ?>
                                            <button type="button" onclick="goBack()" class="btn btn-primary">Back</button>
                                        <button type="submit" name="update_test" class="ladda-button btn btn-primary" data-style="expand-right">Add Patient</button>
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
    <script>
function goBack() {
    window.history.back();
}
</script>    
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