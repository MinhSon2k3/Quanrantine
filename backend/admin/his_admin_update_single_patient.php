<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if (isset($_POST['update_patient'])) {
    // Assuming you have captured all the necessary fields from the form
    $patient_id = $_POST['patient_id'];
    $full_name = $_POST['pat_fullname'];
    $gender = $_POST['pat_gender'];
    $pat_addr = $_POST['pat_addr'];
    $pat_phone = $_POST['pat_phone'];
    $identity_number = $_POST['pat_identity_number'];
    $current_condition = $_POST['pat_current_condition'];
    $nurse_id = $_POST['pat_nurse_id'];
    $staff_id = $_POST['pat_staff_id'];
    $room_id = $_POST['pat_room_id'];
    $pat_dob = $_POST['pat_dob']; // Assuming you added the Date of Birth field
    // SQL to update values
    $query = "UPDATE patient 
              SET Full_Name=?, Gender=?, Address=?, Phone=?, Identity_Number=?, Current_Condition=?, Nurse_ID=?, Staff_ID=?, Room_ID=?, Date=?
              WHERE Patient_ID=?";

    $stmt = $mysqli->prepare($query);

    // Bind parameters
    $stmt->bind_param('ssssssssssi', $full_name, $gender, $pat_addr, $pat_phone, $identity_number, $current_condition, $nurse_id, $staff_id, $room_id, $pat_dob, $patient_id);

    // Execute the query
    $stmt->execute();

    // Close the statement and database connection
    $stmt->close();

    // Check if the query was successful
    if ($stmt) {
        $success = "Patient Details Updated";
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
        <?php
        $Identity_Number = $_GET['Identity_Number'];
        $Patient_ID = $_GET['Patient_ID'];
        $ret = "SELECT  * FROM patient WHERE Patient_ID=?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $Patient_ID);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        //$cnt=1;
        while ($row = $res->fetch_object()) {
            $mysqlDateTime = $row->pat_date_joined;
        ?>
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
                                    <h4 class="page-title">Update Patient Details</h4>
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
                                            <input type="hidden" name="patient_id" value="<?php echo $row->Patient_ID; ?>">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputFullname" class="col-form-label">Full Name</label>
                                                    <input type="text" required="required" name="pat_fullname" class="form-control" id="inputFullname" placeholder="Patient's Full Name" value="<?php echo $row->Full_Name; ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputGender" class="col-form-label">Gender</label>
                                                    <select id="inputGender" required="required" name="pat_gender" class="form-control">
                                                        <option>Choose</option>
                                                        <option <?php if ($row->Gender == 'Male') echo 'selected'; ?>>Male</option>
                                                        <option <?php if ($row->Gender == 'Female') echo 'selected'; ?>>Female</option>
                                                        <option <?php if ($row->Gender == 'Other') echo 'selected'; ?>>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputAddress" class="col-form-label">Address</label>
                                                <input required="required" type="text" class="form-control" name="pat_addr" id="inputAddress" placeholder="Patient's Address" value="<?php echo $row->Address; ?>">
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputPhone" class="col-form-label">Mobile Number</label>
                                                    <input required="required" type="text" name="pat_phone" class="form-control" id="inputPhone" value="<?php echo $row->Phone; ?>">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputIdentityNumber" class="col-form-label">Identity Number</label>
                                                    <input required="required" type="text" name="pat_identity_number" class="form-control" id="inputIdentityNumber" value="<?php echo $row->Identity_Number; ?>">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputDOB" class="col-form-label">Hospitalized Day</label>
                                                    <input type="date" required="required" name="pat_dob" class="form-control" id="inputDOB" value="<?php echo $row->Date; ?>">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputCurrentCondition" class="col-form-label">Current Condition</label>
                                                    <input required="required" type="text" name="pat_current_condition" class="form-control" id="inputCurrentCondition" value="<?php echo $row->Current_Condition; ?>">
                                                </div>
                                            </div>
                                            <?php
                                            // Function to get Nurse options
                                            function getNurseOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT Nurse_ID FROM nurse";
                                                $result = $mysqli->query($query);

                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }

                                                return $options;
                                            }

                                            // Function to get Staff options
                                            function getStaffOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT Staff_ID FROM staff";
                                                $result = $mysqli->query($query);

                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }

                                                return $options;
                                            }

                                            // Function to get Room options
                                            function getRoomOptions($mysqli)
                                            {
                                                $options = array();
                                                $query = "SELECT Room_ID FROM room";
                                                $result = $mysqli->query($query);

                                                while ($row = $result->fetch_assoc()) {
                                                    $options[] = $row;
                                                }

                                                return $options;
                                            }

                                            // Check connection
                                            if ($mysqli->connect_error) {
                                                die("Connection failed: " . $mysqli->connect_error);
                                            }
                                            ?>

                                            <!-- ... -->
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputNurseID" class="col-form-label">Nurse ID</label>
                                                    <select id="inputNurseID" required="required" name="pat_nurse_id" class="form-control">
                                                        <option>Choose</option>
                                                        <?php
                                                        // Get Nurse options
                                                        $nurseOptions = getNurseOptions($mysqli);

                                                        foreach ($nurseOptions as $nurse) {
                                                            $selected = ($nurse['Nurse_ID'] == $row->Nurse_ID) ? 'selected' : '';
                                                            echo "<option value='{$nurse['Nurse_ID']}' $selected>{$nurse['Nurse_ID']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputStaffID" class="col-form-label">Staff ID</label>
                                                    <select id="inputStaffID" required="required" name="pat_staff_id" class="form-control">
                                                        <option>Choose</option>
                                                        <?php
                                                        // Get Staff options
                                                        $staffOptions = getStaffOptions($mysqli);

                                                        foreach ($staffOptions as $staff) {
                                                            $selected = ($staff['Staff_ID'] == $row->Staff_ID) ? 'selected' : '';
                                                            echo "<option value='{$staff['Staff_ID']}' $selected>{$staff['Staff_ID']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputRoomID" class="col-form-label">Room ID</label>
                                                    <select id="inputRoomID" required="required" name="pat_room_id" class="form-control">
                                                        <option>Choose</option>
                                                        <?php
                                                        // Get Room options
                                                        $roomOptions = getRoomOptions($mysqli);

                                                        foreach ($roomOptions as $room) {
                                                            $selected = ($room['Room_ID'] == $row->Room_ID) ? 'selected' : '';
                                                            echo "<option value='{$room['Room_ID']}' $selected>{$room['Room_ID']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- ... -->
                                            <?php
                                            // Close the database connection
                                            $mysqli->close();
                                            ?>
                                    
                                            <button type="submit" name="update_patient" class="ladda-button btn btn-primary" data-style="expand-right">Update Patient</button>
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
            <?php }
        include('assets/inc/footer.php'); ?>
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