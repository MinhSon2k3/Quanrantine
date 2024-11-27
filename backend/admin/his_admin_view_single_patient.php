<?php
session_start();
include('assets/inc/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
?>

<!DOCTYPE html>
<html lang="en">

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

        <!--Get Details Of A Single User And Display Them Here-->
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                            <li class="breadcrumb-item active">View Patients</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Patient information: <?php echo $row->Full_Name; ?></h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="card-box text-center">
                                    <img src="assets/images/users/patient.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">


                                    <div class="text-left mt-3">

                                        <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ml-2"><?php echo $row->Full_Name; ?> </span></p>
                                        <p class="text-muted mb-2 font-13"><strong>Phone :</strong><span class="ml-2"><?php echo $row->Phone; ?></span></p>
                                        <p class="text-muted mb-2 font-13"><strong>Address :</strong> <span class="ml-2"><?php echo $row->Address; ?></span></p>

                                        <a class="ladda-button btn btn-primary" href="his_admin_register_comorbidity.php?Patient_ID=<?php echo $row->Patient_ID; ?>">+ Comorbidity</a>
                                        <a class="ladda-button btn btn-primary" href="his_admin_register_symptom.php?Patient_ID=<?php echo $row->Patient_ID; ?>">+ Symptom</a>
                                        <a class="ladda-button btn btn-primary" href="his_admin_register_test.php?Patient_ID=<?php echo $row->Patient_ID; ?>">+ Test</a>
                                        <a class="ladda-button btn btn-primary" href="his_admin_register_treatment.php?Patient_ID=<?php echo $row->Patient_ID; ?>">+ Treatment</a>
                                    </div>

                                </div> <!-- end card-box -->

                            </div> <!-- end col-->

                        <?php } ?>
                        <div class="col-lg-8 col-xl-8">
                            <div class="card-box">
                                <ul class="nav nav-pills navtab-bg nav-justified">
                                    <li class="nav-item">
                                        <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                            Test
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#timeline" data-toggle="tab" aria-expanded="true" class="nav-link">
                                            Comorbidity
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#symptom" data-toggle="tab" aria-expanded="false" class="nav-link">
                                            Symtomp
                                        </a>
                                    <li class="nav-item">
                                        <a href="#treatment" data-toggle="tab" aria-expanded="false" class="nav-link">
                                            Treatment
                                        </a>
                                    </li>

                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane show active" id="aboutme">
                                        <!-- Content for the Prescription tab -->
                                        <div class="table-responsive">
                                            <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                                <thead>
                                                    <tr>
                                                        <th data-hide="phone">Name</th>
                                                        <th data-hide="phone">Test Date</th>
                                                        <th data-hide="phone">Test Type</th>
                                                        <th data-hide="phone">Cycle Threshold</th>
                                                        <th data-hide="phone">PCR Result</th>
                                                        <th data-hide="phone">PCR Ct Value</th>
                                                        <th data-hide="phone">Quick Test Result</th>
                                                        <th data-hide="phone">Quick Test Ct Value</th>
                                                        <th data-hide="phone">SPO2</th>
                                                        <th data-hide="phone">Respiratory Rate</th>
                                                        <th data-hide="phone">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $patient_id = $_GET['Patient_ID'];

                                                    $ret = "SELECT test.Test_ID, patient.Full_Name, test.Test_Type, test.Patient_ID, test.Test_Date, test.Cycle_Threshold, test.PCR_Result, test.PCR_Ct_Value, test.Quick_Test_Result, test.Quick_Test_Ct_Value, test.SPO2, test.Respiratory_Rate
                                                FROM test
                                                JOIN patient ON test.Patient_ID = patient.Patient_ID
                                                WHERE test.Patient_ID = ?";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->bind_param('i', $patient_id);
                                                    $stmt->execute();
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;

                                                    while ($row = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $row->Full_Name ?? 'N/A'; ?></td>
                                                            <td><?php echo $row->Test_Date ?? 'N/A'; ?></td>
                                                            <td><?php echo $row->Test_Type ?? 'N/A'; ?></td>
                                                            <td><?php echo $row->Cycle_Threshold ?? 'N/A'; ?></td>
                                                            <td><?php echo ($row->PCR_Result == 0) ? 'Negative' : (($row->PCR_Result == 1) ? 'Positive' : 'N/A'); ?></td>
                                                            <td><?php echo $row->PCR_Ct_Value ?? 'N/A'; ?></td>
                                                            <td><?php echo ($row->Quick_Test_Result == 0) ? 'Negative' : (($row->Quick_Test_Result == 1) ? 'Positive' : 'N/A'); ?></td>
                                                            <td><?php echo $row->Quick_Test_Ct_Value ?? 'N/A'; ?></td>
                                                            <td><?php echo $row->SPO2 ?? 'N/A'; ?></td>
                                                            <td><?php echo $row->Respiratory_Rate ?? 'N/A'; ?></td>
                                                            <td><a href="his_admin_update_test.php?Test_ID=<?php echo $row->Test_ID; ?>&Patient_ID=<?php echo $row->Patient_ID; ?>" class="badge badge-primary"><i class="mdi mdi-pencil"></i> Update</a></td>
                                                        </tr>
                                                    <?php
                                                        $cnt = $cnt + 1;
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="14">
                                                            <div class="text-right">
                                                                <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="timeline">
                                        <!-- Content for the Vitals tab -->
                                        <div class="table-responsive">
                                            <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th data-hide="phone">Name</th>
                                                        <th data-toggle="true">Cormorbidity ID</th>
                                                        <th data-hide="phone">Comorbidity Type</th>
                                                        <th data-hide="phone">Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $patient_id = $_GET['Patient_ID'];

                                                    $ret = "SELECT c.Comorbidity_ID , c.Comorbidity_Type, patient.Patient_ID, c.Patient_ID , patient.Full_Name
                                                            FROM comorbidity c
                                                            JOIN patient ON c.Patient_ID = patient.Patient_ID
                                                            WHERE c.Patient_ID = ?";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->bind_param('i', $patient_id);
                                                    $stmt->execute();
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($row = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $row->Full_Name; ?></td>
                                                            <td><?php echo $row->Comorbidity_ID; ?></td>
                                                            <td><?php echo $row->Comorbidity_Type; ?></td>
                                                            <td><a href="his_admin_update_comorbidity.php?Comorbidity_ID=<?php echo $row->Comorbidity_ID; ?>&Patient_ID=<?php echo $row->Patient_ID; ?>" class="badge badge-primary"><i class="mdi mdi-pencil"></i> Update</a></td>
                                                        </tr>
                                                    <?php $cnt = $cnt + 1;
                                                    } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="14">
                                                            <div class="text-right">
                                                                <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="symptom">
                                        <!-- Content for the Lab Records tab -->
                                        <div class="table-responsive">
                                            <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">

                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th data-hide="phone">Name</th>
                                                        <th data-toggle="true">Symtomp ID</th>
                                                        <th data-hide="phone">Symtomp Type</th>
                                                        <th data-hide="phone">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $patient_id = $_GET['Patient_ID'];

                                                    $ret = "SELECT c.Symtomp_ID, c.Symtomp_Type, patient.Patient_ID, c.Patient_ID , patient.Full_Name
                                                            FROM symtomp c
                                                            JOIN patient ON c.Patient_ID = patient.Patient_ID
                                                            WHERE c.Patient_ID = ?";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->bind_param('i', $patient_id);
                                                    $stmt->execute();
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;
                                                    while ($row = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $row->Full_Name; ?></td>
                                                            <td><?php echo $row->Symtomp_ID; ?></td>
                                                            <td><?php echo $row->Symtomp_Type; ?></td>
                                                            <td><a href="his_admin_update_symptom.php?Symtomp_ID=<?php echo $row->Symtomp_ID; ?>&Symtomp_Type=<?php echo $row->Symtomp_Type; ?>&Patient_ID=<?php echo $row->Patient_ID; ?>" class="badge badge-primary"><i class="mdi mdi-pencil"></i> Update</a></td>
                                                        </tr>
                                                    <?php $cnt = $cnt + 1;
                                                    } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="14">
                                                            <div class="text-right">
                                                                <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="treatment">
                                        <div class="table-responsive">
                                            <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="10">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th data-hide="phone">Patient Name</th>
                                                        <th data-hide="phone">Doctor Name </th>
                                                        <th data-hide="phone">Date Start</th>
                                                        <th data-hide="phone">Date End</th>
                                                        <th data-hide="phone">Percentage</th>
                                                        <th data-hide="phone">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $patient_id = $_GET['Patient_ID'];

                                                    $ret = "SELECT treatment.Treatment_ID, patient.Patient_ID, patient.Full_Name, quarantine_camp_staff.Name AS Staff_Name, treatment.Date_Start, treatment.Date_End, treatment.Percentage FROM treatment JOIN patient ON treatment.Patient_ID = patient.Patient_ID JOIN doctor ON treatment.Doctor_ID = doctor.Doctor_ID JOIN Quarantine_camp_Staff ON doctor.Quarantine_camp_Staff_ID = Quarantine_camp_Staff.Quarantine_camp_Staff_ID WHERE treatment.Patient_ID = ?";
                                                    $stmt = $mysqli->prepare($ret);
                                                    $stmt->bind_param('i', $patient_id);
                                                    $stmt->execute();
                                                    $res = $stmt->get_result();
                                                    $cnt = 1;

                                                    while ($row = $res->fetch_object()) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><?php echo $row->Full_Name; ?></td>
                                                            <td><?php echo $row->Staff_Name; ?></td>
                                                            <td><?php echo $row->Date_Start; ?></td>
                                                            <td><?php echo $row->Date_End; ?></td>
                                                            <td><?php echo $row->Percentage; ?></td>
                                                            <td>

                                                                <a href="his_admin_update_treatment.php?Treatment_ID=<?php echo $row->Treatment_ID; ?>&Patient_ID=<?php echo $row->Patient_ID; ?>" class="badge badge-primary">
                                                                    <i class="mdi mdi-pencil"></i> Update
                                                                </a>
                                                            </td>
                                                        </tr>

                                                    <?php $cnt = $cnt + 1;
                                                    } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <td colspan="14">
                                                            <div class="text-right">
                                                                <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div>

                    </div>
                    <!-- end row-->

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

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>


</html>