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
        $Patient_ID = $_GET['Patient_ID'];

        $ret = "SELECT p.*
           FROM patient p 
           WHERE p.Patient_ID =?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $Patient_ID);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_object()) {
            $mysqlDateTime = $row->pat_date_joined;
        ?>
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid ">

                        <!-- start page title -->
                        <div class="row mx-auto text-center">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">

                                    </div>
                                    <h4 class="page-title">Patient records: <?php echo $row->Full_Name; ?></h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                       <p>Patient Details</p>
                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                        <thead>
                            <tr>

                                <th data-toggle="true">Full_Name</th>
                                <th data-hide="phone">Gender</th>
                                <th data-hide="phone">Phone Number</th>
                                <th data-hide="phone">Symtomp_Type</th>
                                <th data-hide="phone">Comorbidity</th>
                                <th data-hide="phone">Nurse</th>
                                <th data-hide="phone">Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Patient_ID = $_GET['Patient_ID'];

                            $ret = "SELECT 
                            p.*,
                            c.comorbidity_type,
                            s.Symtomp_Type,
                            qcs.Name,
                            CASE
                                WHEN r.Normal_room = 1 THEN 'Normal Room'
                                WHEN r.Emergency_room = 1 THEN 'Emergency Room'
                                WHEN r.Recuperation_room = 1 THEN 'Recuperation Room'
                                ELSE 'Unknown Room Type'
                            END AS room_type,
                            r.Floor_ID
                        FROM 
                            patient p 
                        JOIN 
                            nurse n ON p.Nurse_ID = n.Nurse_ID
                        JOIN 
                            quarantine_camp_staff qcs ON n.Quarantine_camp_Staff_ID = qcs.Quarantine_camp_Staff_ID
                        LEFT JOIN 
                            (SELECT Patient_ID, GROUP_CONCAT(comorbidity_type) AS comorbidity_type
                             FROM comorbidity GROUP BY Patient_ID) c ON p.Patient_ID = c.Patient_ID 
                        LEFT JOIN 
                            (SELECT Patient_ID, GROUP_CONCAT(Symtomp_Type) AS Symtomp_Type 
                             FROM symtomp GROUP BY Patient_ID) s ON p.Patient_ID = s.Patient_ID 
                        LEFT JOIN 
                            Room r ON p.Room_ID = r.Room_ID
                        WHERE 
                            p.Patient_ID = ?";

                            $stmt = $mysqli->prepare($ret);
                            $stmt->bind_param('i', $Patient_ID);
                            $stmt->execute();
                            $res = $stmt->get_result();

                            while ($row = $res->fetch_object()) {

                            ?>
                                <tr>

                                    <td><?php echo $row->Full_Name; ?></td>
                                    <td><?php echo $row->Gender; ?></td>
                                    <td><?php echo $row->Phone; ?></td>
                                    <td><?php echo $row->Symtomp_Type; ?></td>
                                    <td><?php echo $row->comorbidity_type; ?></td>
                                    <td><?php echo $row->Nurse_ID; ?></td>
                                    <td><?php echo $row->room_type; ?></td>

                                </tr>
                            <?php
                            } ?>
                        </tbody>
                       
                    </table>
                   
                    <p></p>
                    <p>Test Result</p>
                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                        <thead>
                            <tr>
                                <th data-toggle="true">Full_Name</th>
                                <th data-hide="phone">Test Date</th>
                                <th data-hide="phone">Status</th>
                                <th data-hide="phone">PCR Test</th>
                                <th data-hide="phone">Quick Test</th>
                                <th data-hide="phone">SPO2</th>
                                <th data-hide="phone">Respiratory_Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Patient_ID = $_GET['Patient_ID'];
                            $ret = "SELECT test.Test_ID, patient.Full_Name, test.Test_Type, test.Patient_ID, test.Test_Date, test.Cycle_Threshold, test.PCR_Result, test.PCR_Ct_Value, test.Quick_Test_Result, test.Quick_Test_Ct_Value, test.SPO2, test.Respiratory_Rate
                            FROM test
                            JOIN patient ON test.Patient_ID = patient.Patient_ID
                            WHERE test.Patient_ID = ?";

                            $stmt = $mysqli->prepare($ret);
                            $stmt->bind_param('i', $Patient_ID);
                            $stmt->execute();
                            $res = $stmt->get_result();

                            while ($row = $res->fetch_object()) {
                                $status = ($row->SPO2 > 96 && $row->Respiratory_Rate > 20) ? 'Warning' : 'Safe';
                            ?>
                                <tr>
                                    <td><?php echo $row->Full_Name; ?></td>
                                    <td><?php echo $row->Test_Date; ?></td>
                                    <td><?php echo $status; ?></td>
                                    <td><?php echo $row->PCR_Ct_Value; ?></td>
                                    <td><?php echo $row->Quick_Test_Result; ?></td>
                                    <td><?php echo $row->SPO2; ?></td>
                                    <td><?php echo $row->Respiratory_Rate; ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                       
                </table>


                <p></p>           
                <p>Treatment Details</p>
                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                        <thead>
                            <tr>
                                <th data-toggle="true">Doctor</th>
                                <th data-hide="phone">Patient</th>
                                <th data-hide="phone">Result</th>
                                <th data-hide="phone">Date Start</th>
                                <th data-hide="phone">Date End</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Patient_ID = $_GET['Patient_ID'];
                            $ret = "SELECT d.*, t.*, p.*, qcs.*
                            FROM Treatment t
                            JOIN Doctor d ON t.Doctor_ID = d.Doctor_ID
                            JOIN Patient p ON t.Patient_ID = p.Patient_ID
                            JOIN Quarantine_camp_Staff qcs ON d.Quarantine_camp_Staff_ID = qcs.Quarantine_camp_Staff_ID
                            WHERE t.Patient_ID =?
                            ";

                            $stmt = $mysqli->prepare($ret);
                            $stmt->bind_param('i', $Patient_ID);
                            $stmt->execute();
                            $res = $stmt->get_result();

                            while ($row = $res->fetch_object()) {
                            
                            ?>
                                <tr>
                                    <td><?php echo $row->Name; ?></td>
                                    <td><?php echo $row->Full_Name; ?></td>
                                    <td><?php echo $row->Percentage; ?></td>
                                    <td><?php echo $row->Date_Start; ?></td>
                                    <td><?php echo $row->Date_End; ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                       
                </table>
                            
                <p></p>           
                <p>Medication Details</p>
                <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
                        <thead>
                            <tr>
                                <th data-toggle="true">Medication Name</th>
                                <th data-hide="phone">Medication Code </th>
                                <th data-hide="phone">Effects</th>
                                <th data-hide="phone">Price</th>
                                <th data-hide="phone">Patient</th>
                                <th data-hide="phone">Type</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Patient_ID = $_GET['Patient_ID'];
                            $ret = "SELECT p.*, t.*, m.*
                            FROM Patient p
                            JOIN Test t ON p.Patient_ID = t.Patient_ID
                            JOIN Medication m ON p.Patient_ID = m.Patient_ID AND t.Test_ID = m.Test_ID
                            WHERE p.Patient_ID =? ";

                            $stmt = $mysqli->prepare($ret);
                            $stmt->bind_param('i', $Patient_ID);
                            $stmt->execute();
                            $res = $stmt->get_result();

                            while ($row = $res->fetch_object()) {
                            
                            ?>
                                <tr>
                                    <td><?php echo $row->Medication_Name; ?></td>
                                    <td><?php echo $row->Medication_Code; ?></td>
                                    <td><?php echo $row->Effects; ?></td>
                                    <td><?php echo $row->Price; ?></td>
                                    <td><?php echo $row->Full_Name; ?></td>
                                    <td><?php echo $row->Test_Type; ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                      
                </table>

                    </div> <!-- container -->
                    
                    

                   
                </div> <!-- content -->

                <!-- Footer Start -->

                <!-- end Footer -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->

    </div>
    </div>
 <div class="rightbar-overlay">
        </div>
  
        </div>
    <?php } ?>









    <?php include('assets/inc/footer.php'); ?>
    <div class="rightbar-overlay"></div>


    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>


</html>