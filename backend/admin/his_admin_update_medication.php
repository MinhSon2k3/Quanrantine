<!--Server side code to handle  Patient Registration-->
<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['update_medication'])) {
    $medication_name = $_POST['medication_name'];
    $medication_code = $_POST['medication_code'];
    $medication_id = $_POST['medication_id'];
    $price = $_POST['price'];
    $effects = $_POST['effects'];
    // $patient_id = $_POST['patient_id'];
    $test_id = $_POST['test_id'];
    // SQL để chèn dữ liệu vào bảng medication
    $query = "UPDATE Medication SET Medication_Name=?, Medication_Code=?, Price=?, Effects=?, Test_ID=?  WHERE Medication_ID=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssdsii', $medication_name, $medication_code, $price, $effects, $test_id, $medication_id);
    $stmt->execute();
    if ($stmt) {
        $success = "Medication Details Updated";
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Medication</a></li>
                                        <li class="breadcrumb-item active">Update Medication</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Update Medication Details</h4>
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
                                        $query = "SELECT * FROM Medication WHERE Medication_ID=" . $_GET['Medication_ID'];
                                        if ($result = $mysqli->query($query)) {
                                            $medication = $result->fetch_assoc();
                                        }
                                        ?>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputMedicationName" class="col-form-label">Medication Name</label>
                                                <input type="text" required="required" name="medication_name" class="form-control" id="inputMedicationName" value="<?php echo $medication['Medication_Name'] ?>" placeholder="Medication Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputMedicationCode" class="col-form-label">Medication Code</label>
                                                <input type="text" required="required" name="medication_code" class="form-control" id="inputMedicationCode" value="<?php echo $medication['Medication_Code'] ?>" placeholder="Medication Code">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputPrice" class="col-form-label">Price</label>
                                                <input required="required" type="text" name="price" class="form-control" id="inputPrice" value="<?php echo $medication['Price'] ?>" placeholder="Price">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputEffects" class="col-form-label">Effects</label>
                                                <input required="required" type="text" name="effects" class="form-control" id="inputEffects" value="<?php echo $medication['Effects'] ?>" placeholder="Effects">
                                            </div>
                                        </div>
                                        <?php
                                        function getMedicationOptions($mysqli)
                                        {
                                            $options = array();
                                            $query = "SELECT * FROM Medication";
                                            $result = $mysqli->query($query);
                                            while ($row = $result->fetch_assoc()) {
                                                $options[] = $row;
                                            }
                                            return $options;
                                        }
                                        ?>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputPatientID" class="col-form-label">Patient ID</label>
                                                <select id="inputPatientID" required="required" name="medication_id" class="form-control" onchange="handlePatientSelection()">
                                                    <option>Choose</option>
                                                    <?php
                                                    // Thực hiện truy vấn để lấy dữ liệu từ bảng patient
                                                    $MedicationOptions = getMedicationOptions($mysqli);
                                                    // Duyệt qua kết quả truy vấn và tạo các tùy chọn
                                                    foreach ($MedicationOptions as $Medication) {
                                                        $query = 'SELECT * FROM Patient WHERE Patient_ID = ' . $Medication['Patient_ID'];
                                                        $result = $mysqli->query($query);
                                                        $patientName = ($result && $row = $result->fetch_assoc()) ? $row['Full_Name'] : '';
                                                        $selected = ($Medication['Medication_ID'] == $_GET['Medication_ID']) ? 'selected' : '';
                                                        echo "<option value='{$Medication['Medication_ID']}' $selected>{$Medication['Patient_ID']}: {$patientName}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputTestID" class="col-form-label">Test ID</label>
                                                <select id="inputTestID" required="required" name="test_id" class="form-control">
                                                    <option>Choose</option>
                                                    <?php
                                                    // Thực hiện truy vấn để lấy dữ liệu từ bảng patient
                                                    $MedicationOptions = getMedicationOptions($mysqli);
                                                    // Duyệt qua kết quả truy vấn và tạo các tùy chọn
                                                    foreach ($MedicationOptions as $Medication) {
                                                        $result = $mysqli->query($query);
                                                        $selected = ($Medication['Medication_ID'] == $_GET['Medication_ID']) ? 'selected' : '';
                                                        echo "<option value='{$Medication['Test_ID']}' $selected>{$Medication['Test_ID']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" name="update_medication" class="ladda-button btn btn-primary" data-style="expand-right">Update Medication</button>
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
    <script>
        function handlePatientSelection() {
            // Get the selected option element
            var selectedOption = document.getElementById("inputPatientID");

            // Get the selected Patient_ID
            var selectedPatientID = selectedOption.value;

            // Make an AJAX request
            var xhr = new XMLHttpRequest();

            // Define the AJAX request parameters
            xhr.open("GET", "assets/inc/FindTestID.php?Patient_ID=" + selectedPatientID, true);

            // Set up the callback function to handle the response
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Process the response here (update the Test ID dropdown or handle as needed)
                    var responseData = xhr.responseText;
                    // Update the Test ID dropdown or perform other actions based on the response
                    updateTestIdDropdown(responseData);
                }
            };

            // Send the AJAX request
            xhr.send();
        }

        function updateTestIdDropdown(responseData) {
            // Assuming responseData is a JSON-encoded array of Test IDs
            var testIdDropdown = document.getElementById("inputTestID");
            var testIds = JSON.parse(responseData);

            // Clear existing options
            testIdDropdown.innerHTML = '<option>Choose</option>';

            // Populate the Test ID dropdown with new options
            for (var i = 0; i < testIds.length; i++) {
                var option = document.createElement("option");
                option.value = testIds[i];
                option.text = testIds[i];
                testIdDropdown.add(option);
            }
        }
    </script>

    <!-- App js-->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js-->
    <script src="assets/js/pages/loading-btn.init.js"></script>

</body>

</html>