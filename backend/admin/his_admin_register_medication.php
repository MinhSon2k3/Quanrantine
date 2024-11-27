<!--Server side code to handle  Patient Registration-->
<?php
      session_start();
      include('assets/inc/config.php');
      include('assets/inc/checklogin.php');
      check_login();
      $aid=$_SESSION['ad_id'];
    if (isset($_POST['add_medication'])) {
        $medication_name = $_POST['medication_name'];
        $medication_code = $_POST['medication_code'];
        $price = $_POST['price'];
        $effects = $_POST['effects'];
        $patient_id = $_POST['patient_id'];
        $test_id = $_POST['test_id'];
        // SQL để chèn dữ liệu vào bảng medication
        $query = "INSERT INTO medication (Medication_Name, Medication_Code, Price, Effects, Patient_ID, Test_ID) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssdsii', $medication_name, $medication_code, $price, $effects, $patient_id, $test_id);
        $stmt->execute();
        if ($stmt) {
            $success = "Medication Details Added";
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
    <?php include('assets/inc/head.php');?>
    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include("assets/inc/nav.php");?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include("assets/inc/sidebar.php");?>
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
                                            <li class="breadcrumb-item active">Add Medication</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Medication Details</h4>
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
                                                <div class="form-group col-md-6">
                                                    <label for="inputMedicationName" class="col-form-label">Medication Name</label>
                                                    <input type="text" required="required" name="medication_name" class="form-control" id="inputMedicationName" placeholder="Medication Name">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputMedicationCode" class="col-form-label">Medication Code</label>
                                                    <input type="text" required="required" name="medication_code" class="form-control" id="inputMedicationCode" placeholder="Medication Code">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputPrice" class="col-form-label">Price</label>
                                                    <input required="required" type="text" name="price" class="form-control" id="inputPrice" placeholder="Price">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputEffects" class="col-form-label">Effects</label>
                                                    <input required="required" type="text" name="effects" class="form-control" id="inputEffects" placeholder="Effects">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputPatientID" class="col-form-label">Patient ID</label>
                                                    <select id="inputPatientID" required="required" name="patient_id" class="form-control" onchange="handlePatientSelection()">
                                                        <option>Choose</option>
                                                        <?php
                                                        // Thực hiện truy vấn để lấy dữ liệu từ bảng patient
                                                        $query = "SELECT Patient_ID, Full_Name FROM patient";
                                                        $result = $mysqli->query($query);

                                                        // Duyệt qua kết quả truy vấn và tạo các tùy chọn
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<option value='{$row['Patient_ID']}'>{$row['Patient_ID']}: {$row['Full_Name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputTestID" class="col-form-label">Test ID</label>
                                                    <select id="inputTestID" required="required" name="test_id" class="form-control">
                                                        <option>Choose</option>
                                                        <!-- AJAX will populate Test IDs dynamically -->
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" name="add_medication" class="ladda-button btn btn-primary" data-style="expand-right">Add Medication</button>
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
                <?php include('assets/inc/footer.php');?>
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
            xhr.onreadystatechange = function () {
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